<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Pengajuan;
use App\Models\Setting;
use App\Models\Setting2;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;

class PengajuanController extends Controller
{
    /**
     * Menampilkan halaman form pengajuan barang
     */
    public function index()
    {
        $barangs = Barang::select('id','kode_barang','nama_barang','jumlah')
            ->orderBy('kode_barang')
            ->get();

        return view('pages.pengajuan.index', compact('barangs'));
    }

    /**
     * Menyimpan data pengajuan barang
     */
    public function store(Request $request)
    {
        $request->validate([
            'barang_id'   => 'required|array|min:1',
            'barang_id.*' => 'exists:barangs,id',
            'jumlah'      => 'required|array|min:1',
            'jumlah.*'    => 'integer|min:1',
            'keperluan'   => 'required|string'
        ]);

        try {
            DB::transaction(function () use ($request) {
                $pengajuan = Pengajuan::create([
                    'user_id' => auth()->id(),
                    'tanggal_pengajuan' => now(),
                    'keperluan' => $request->keperluan,
                    'status' => 'Diajukan'
                ]);

                foreach ($request->barang_id as $index => $barangId) {
                    $barang = Barang::findOrFail($barangId);
                    $jumlah = $request->jumlah[$index];

                    if ($barang->jumlah < $jumlah) {
                        throw new \Exception("Stok {$barang->nama_barang} tidak mencukupi");
                    }

                    $pengajuan->details()->create([
                        'barang_id' => $barangId,
                        'jumlah' => $jumlah,
                        'status' => 'Diajukan'
                    ]);
                }
            });

            // jika AJAX
            if ($request->ajax()) {
                session()->flash('success','Pengajuan berhasil diajukan');
                return response()->json(['success'=>true]);
            }

            return redirect()->route('pengajuan.index')
                ->with('success','Pengajuan berhasil diajukan');

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success'=>false,
                    'message'=>$e->getMessage()
                ],422);
            }

            return back()->with('error',$e->getMessage());
        }
    }

    // RIWAYAT
    public function riwayat()
    {
        $user = auth()->user();

        if ($user->role === 'Administrator') {
            return redirect()->route('pengajuan.riwayat_admin');
        }

        if ($user->role === 'Staff') {
            return redirect()->route('pengajuan.riwayat_user');
        }

        abort(403, 'Role tidak dikenali');
    }

    // RIWAYAT ADMIN
    public function riwayat_admin(Request $request)
    {
        $query = Pengajuan::with(['details.barang', 'user']);

        if (auth()->user()->role === 'Staff') {
            $query->where('user_id', auth()->id());
        }

        // 🏷 Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        //  Filter Tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_pengajuan', $request->tanggal);
        }

        $pengajuans = $query
            ->orderBy('tanggal_pengajuan', 'desc')
            ->get();

        return view('pages.pengajuan.riwayat_admin', compact('pengajuans'));
    }

    // RIWAYAT USER (HANYA DATA MILIK USER LOGIN)
    public function riwayat_user(Request $request)
    {
        $query = Pengajuan::with(['details.barang', 'user'])
            ->where('user_id', auth()->id());

        $pengajuans = $query
            ->orderBy('tanggal_pengajuan', 'desc')
            ->get();

        return view('pages.pengajuan.riwayat_user', compact('pengajuans'));
    }

    // UPDATE STATUS (ADMIN)
    public function updateDetailStatus(Request $request, $id)
    {
        $request->validate([
            'details' => 'required|array',
            'details.*.id' => 'required|exists:pengajuan_details,id',
            'details.*.status' => 'required|in:Disetujui,Ditolak'
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $pengajuan = Pengajuan::with(['details.barang'])
                    ->lockForUpdate()
                    ->findOrFail($id);

                if ($pengajuan->status !== 'Diajukan') {
                    throw new \Exception('Pengajuan sudah diproses.');
                }

                $totalDisetujui = 0;
                $totalDitolak   = 0;

                foreach ($request->details as $item) {
                    $detail = $pengajuan->details()->where('id', $item['id'])->first();

                    if (!$detail) {
                        throw new \Exception('Detail tidak ditemukan.');
                    }

                    $jumlahDisetujui = $item['jumlah_disetujui'] ?? 0;

                    if ($item['status'] === 'Disetujui') {
                        if ($jumlahDisetujui <= 0) {
                            throw new \Exception("Jumlah disetujui harus diisi dan tidak boleh 0.");
                        }
                        if ($jumlahDisetujui > $detail->jumlah) {
                            throw new \Exception("Jumlah disetujui tidak boleh lebih dari jumlah permintaan.");
                        }
                        if ($detail->barang->jumlah < $jumlahDisetujui) {
                            throw new \Exception("Stok {$detail->barang->nama_barang} tidak mencukupi.");
                        }

                        $detail->barang->decrement('jumlah', $jumlahDisetujui);

                        $totalDisetujui++;
                    } 
                    else {
                        $jumlahDisetujui = 0;
                        $totalDitolak++;
                    }

                    $detail->update([
                        'status' => $item['status'],
                        'jumlah_disetujui' => $jumlahDisetujui
                    ]);
                }

                if ($totalDisetujui > 0 && $totalDitolak > 0) {
                    $statusAkhir = 'Disetujui Sebagian';
                } elseif ($totalDisetujui > 0) {
                    $statusAkhir = 'Disetujui';
                } else {
                    $statusAkhir = 'Ditolak';
                }

                $pengajuan->update([
                    'status' => $statusAkhir,
                    'tanggal_proses' => now(),
                    'keterangan_proses' => $request->keterangan_proses
                ]);
            });

            session()->flash('success', 'Permintaan barang berhasil diproses.');
            return response()->json([
                'success' => true,
                'message' => 'Permintaan barang berhasil diproses.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function destroy($id)
    {
        Pengajuan::findOrFail($id)->delete();
        return redirect()->route('pengajuan.riwayat_admin')
            ->with('success', 'Permintaan berhasil dihapus');
    }

    public function cancel($id)
    {
        $pengajuan = Pengajuan::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // hanya boleh dibatalkan jika masih diajukan
        if ($pengajuan->status !== 'Diajukan') {
            return back()->with('error', 'Pengajuan sudah diproses dan tidak bisa dibatalkan.');
        }

        $pengajuan->delete();

        return back()->with('success', 'Pengajuan berhasil dibatalkan.');
    }

    public function show_admin($id)
    {
        $pengajuan = Pengajuan::with(['details.barang','user'])->findOrFail($id);
        return view('pages.pengajuan.show_admin', compact('pengajuan'));
    }

    public function show_user($id)
    {
        $pengajuan = Pengajuan::with(['details.barang','user'])
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // tandai notif sudah dibaca
        if (is_null($pengajuan->read_at)) {
            $pengajuan->update([
                'read_at' => now()
            ]);
        }

        return view('pages.pengajuan.show_user', compact('pengajuan'));
    }

    /*
    public function read($id)
    {
        $pengajuan = Pengajuan::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $pengajuan->update([
            'read_at' => now()
        ]);

        return redirect()->route('pengajuan.riwayat_user');
    }
    */

    // modal show data
    public function detail($id)
    {
        $pengajuan = Pengajuan::with(['details.barang', 'user'])
            ->findOrFail($id);

        // 🔐 Jika staff hanya boleh lihat milik sendiri
        if (auth()->user()->role === 'Staff' &&
            $pengajuan->user_id !== auth()->id()) {
            abort(403);
        }

        return response()->json($pengajuan);
    }

    // cetak nodin / formulir pengajuan
    public function nodin($id)
    {
        $pengajuan = Pengajuan::with(['details.barang','user'])->findOrFail($id);
        $settings2 = Setting2::first();
        $setting   = Setting::first();

        // ================= DOCX =================
        if ($setting && $setting->format_cetak === 'docx') {
            Settings::setTempDir(storage_path('app/temp'));

            $template = new TemplateProcessor(
                storage_path('app/template/nodin_persediaan_template.docx')
            );

            // ===== FORMAT TANGGAL =====
            $tanggal = \Carbon\Carbon::parse($pengajuan->tanggal_proses)
                ->translatedFormat('d F Y');

            // ===== SET VALUE =====
            $template->setValue('tanggal', $tanggal);
            $template->setValue('nama', $pengajuan->user->name);

            // ===== CLONE ROW (TABEL BARANG) =====
            $template->cloneRow('no', count($pengajuan->details));

            foreach ($pengajuan->details as $i => $detail) {
                $index = $i + 1;

                $template->setValue("no#$index", $index);
                $template->setValue("nama_barang#$index", $detail->barang->nama_barang);
                $template->setValue("jumlah#$index", $detail->jumlah);
                $template->setValue("satuan#$index", $detail->barang->satuan ?? '-');
            }

            // ===== DOWNLOAD =====
            $fileName = 'formulir-pengajuan-persediaan.docx';
            $tempFile = tempnam(sys_get_temp_dir(), 'word');

            $template->saveAs($tempFile);

            return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
        }

        // ================= PDF =================
        $pdf = Pdf::loadView('pages.pengajuan.pdf.nodin', [
            'pengajuan' => $pengajuan,
            'settings2' => $settings2
        ])->setPaper('A4', 'portrait');

        return $pdf->stream('formulir-pengajuan-persediaan.pdf');
    }

    // cetak bast / tanda terima
    public function bast($id)
    {
        $pengajuan = Pengajuan::with(['details.barang','user'])->findOrFail($id);
        $settings2 = Setting2::first();
        $setting   = Setting::first();

        if (!in_array($pengajuan->status, ['Disetujui', 'Disetujui Sebagian'])) {
            abort(403, 'BAST hanya untuk pengajuan yang disetujui');
        }

        // ================= DOCX =================
        if ($setting && $setting->format_cetak === 'docx') {
            Settings::setTempDir(storage_path('app/temp'));

            $template = new TemplateProcessor(
                storage_path('app/template/bast_persediaan_template.docx')
            );

            // ===== FORMAT TANGGAL =====
            $tanggal = \Carbon\Carbon::parse($pengajuan->tanggal_proses)->translatedFormat('d F Y');

            // ===== FILTER DATA DISETUJUI SAJA =====
            $details = $pengajuan->details->where('status', 'Disetujui')->values();

            // ===== SET VALUE =====
            $template->setValue('tanggal', $tanggal);
            $template->setValue('nama_penerima', $pengajuan->user->name);
            $template->setValue('seksi', $pengajuan->user->seksi->seksi_singkat ?? '-');

            $template->setValue('nama_penyerah', $settings2->nama_staffbmn_tu ?? '-');
            $template->setValue('nip_penyerah', $settings2->nip_staffbmn_tu ?? '-');

            $template->setValue('nama_kaur', $settings2->nama_kaurumum_tu ?? '-');
            $template->setValue('nip_kaur', $settings2->nip_kaurumum_tu ?? '-');

            // ===== CLONE ROW =====
            $template->cloneRow('no', count($details));

            foreach ($details as $i => $detail) {
                $index = $i + 1;

                $template->setValue("no#$index", $index);
                $template->setValue("nama_barang#$index", $detail->barang->nama_barang);
                $template->setValue("jumlah#$index", $detail->jumlah_disetujui);
                $template->setValue("satuan#$index", $detail->barang->satuan ?? '-');
                $template->setValue("kondisi#$index", 'Baik');
            }

            // ===== DOWNLOAD =====
            $fileName = 'tanda-terima-persediaan.docx';
            $tempFile = tempnam(sys_get_temp_dir(), 'word');

            $template->saveAs($tempFile);

            return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
        }

        // ================= PDF =================
        $pdf = Pdf::loadView('pages.pengajuan.pdf.bast', [
            'pengajuan' => $pengajuan,
            'settings2' => $settings2
        ])->setPaper('A4', 'portrait');

        return $pdf->stream('tanda-terima-persediaan.pdf');
    }
}
