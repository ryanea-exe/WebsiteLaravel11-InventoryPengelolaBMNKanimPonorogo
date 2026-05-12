<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangBMN;
use App\Models\PengajuanBMN;
use App\Models\Setting;
use App\Models\Setting2;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;

class PengajuanBMNController extends Controller
{
    /**
     * Menampilkan halaman form pengajuan barang
     */
    public function index()
    {
        $barangs = BarangBMN::select('id','kode_barang','nama_barang','merk_type','jumlah')
            ->orderBy('kode_barang')
            ->get();

        return view('pages.pengajuan-bmn.index', compact('barangs'));
    }

    /**
     * Menyimpan data pengajuan barang
     */
    public function store(Request $request)
    {
        $request->validate([
            'barang_id'   => 'required|array|min:1',
            'barang_id.*' => 'exists:barang_bmn,id',
            'jumlah'      => 'required|array|min:1',
            'jumlah.*'    => 'integer|min:1',
            'keperluan'   => 'required|string'
        ]);

        try {
            DB::transaction(function () use ($request) {
                $pengajuan = PengajuanBMN::create([
                    'user_id' => auth()->id(),
                    'tanggal_pengajuan' => now(),
                    'keperluan' => $request->keperluan,
                    'status' => 'Diajukan'
                ]);

                foreach ($request->barang_id as $index => $barangId) {
                    $barang = BarangBMN::findOrFail($barangId);
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

            return redirect()->route('pengajuan-bmn.index')
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
            return redirect()->route('pengajuan-bmn.riwayat_admin');
        }

        if ($user->role === 'Staff') {
            return redirect()->route('pengajuan-bmn.riwayat_user');
        }

        abort(403, 'Role tidak dikenali');
    }

    // RIWAYAT ADMIN
    public function riwayat_admin(Request $request)
    {
        $query = PengajuanBMN::with(['details.barang', 'user']);

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

        return view('pages.pengajuan-bmn.riwayat_admin', compact('pengajuans'));
    }

    // RIWAYAT USER (HANYA DATA MILIK USER LOGIN)
    public function riwayat_user(Request $request)
    {
        $query = PengajuanBMN::with(['details.barang', 'user'])
            ->where('user_id', auth()->id());

        $pengajuans = $query
            ->orderBy('tanggal_pengajuan', 'desc')
            ->get();

        return view('pages.pengajuan-bmn.riwayat_user', compact('pengajuans'));
    }

    // UPDATE STATUS (ADMIN)
    public function updateDetailStatus(Request $request, $id)
    {
        $request->validate([
            'details' => 'required|array',
            'details.*.id' => 'required|exists:pengajuan_bmn_detail,id',
            'details.*.status' => 'required|in:Disetujui,Ditolak'
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $pengajuan = PengajuanBMN::with(['details.barang'])
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
        PengajuanBMN::findOrFail($id)->delete();
        return redirect()->route('pengajuan-bmn.riwayat_admin')
            ->with('success', 'Permintaan berhasil dihapus');
    }

    public function cancel($id)
    {
        $pengajuan = PengajuanBMN::where('id', $id)
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
        $pengajuan = PengajuanBMN::with(['details.barang','user'])->findOrFail($id);
        return view('pages.pengajuan-bmn.show_admin', compact('pengajuan'));
    }

    public function show_user($id)
    {
        $pengajuan = PengajuanBMN::with(['details.barang','user'])
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // tandai notif sudah dibaca
        if (is_null($pengajuan->read_at)) {
            $pengajuan->update([
                'read_at' => now()
            ]);
        }

        return view('pages.pengajuan-bmn.show_user', compact('pengajuan'));
    }

    /*
    public function read($id)
    {
        $pengajuan = PengajuanBMN::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $pengajuan->update([
            'read_at' => now()
        ]);

        return redirect()->route('pengajuan-bmn.riwayat_user');
    }
    */

    // modal show data
    public function detail($id)
    {
        $pengajuan = PengajuanBMN::with(['details.barang', 'user'])
            ->findOrFail($id);

        // 🔐 Jika staff hanya boleh lihat milik sendiri
        if (auth()->user()->role === 'Staff' &&
            $pengajuan->user_id !== auth()->id()) {
            abort(403);
        }

        return response()->json($pengajuan);
    }

    // cetak nodin
    public function nodin($id)
    {
        $pengajuan = PengajuanBMN::with(['details.barang','user'])->findOrFail($id);
        $settings2 = Setting2::first();
        $setting   = Setting::first();

        // ================= DOCX =================
        if ($setting && $setting->format_cetak === 'docx') {
            Settings::setTempDir(storage_path('app/temp'));

            $template = new TemplateProcessor(
                storage_path('app/template/nodin_bmn_template.docx')
            );

            // ===== FORMAT TANGGAL =====
            $tanggal = \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)
                ->translatedFormat('d F Y');

            // ===== SET VALUE =====
            $template->setValue('tanggal', $tanggal);
            $template->setValue('seksi', $pengajuan->user->seksi->seksi ?? '-');
            $template->setValue('nama_kepala', $pengajuan->user->seksi->nama_kepala ?? '-');
            $template->setValue('nip_kepala', $pengajuan->user->seksi->nip_kepala ?? '-');

            // ===== CLONE ROW =====
            $template->cloneRow('no', count($pengajuan->details));

            foreach ($pengajuan->details as $i => $detail) {
                $index = $i + 1;

                $template->setValue("no#$index", $index);
                $template->setValue(
                    "nama_barang#$index",
                    $detail->barang->nama_barang . ' (' . $detail->barang->merk_type . ')'
                );
                $template->setValue(
                    "jumlah#$index",
                    $detail->jumlah . ' ' . ($detail->barang->satuan ?? '-')
                );
            }

            // ===== DOWNLOAD =====
            $fileName = 'nota-dinas-bmn.docx';
            $tempFile = tempnam(sys_get_temp_dir(), 'word');

            $template->saveAs($tempFile);

            return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
        }

        // ================= PDF =================
        $pdf = Pdf::loadView('pages.pengajuan-bmn.pdf.nodin', [
            'pengajuan' => $pengajuan,
            'settings2' => $settings2
        ])->setPaper('A4', 'portrait');

        return $pdf->stream('nota-dinas-bmn.pdf');
    }

    // cetak bast
    public function bast($id)
    {
        $pengajuan = PengajuanBMN::with(['details.barang','user'])->findOrFail($id);
        $settings2 = Setting2::first();
        $setting   = Setting::first(); // ⬅️ TAMBAHAN

        if (!in_array($pengajuan->status, ['Disetujui', 'Disetujui Sebagian'])) {
            abort(403, 'BAST hanya untuk pengajuan yang disetujui');
        }

        // ================= DOCX =================
        if ($setting && $setting->format_cetak === 'docx') {
            Settings::setTempDir(storage_path('app/temp'));

            $template = new TemplateProcessor(
                storage_path('app/template/bast_bmn_template.docx')
            );

            $tanggal = \Carbon\Carbon::parse($pengajuan->tanggal_proses);

            // fungsi terbilang
            function terbilang($angka) {
                $angka = abs($angka);
                $huruf = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];

                if ($angka < 12) return $huruf[$angka];
                elseif ($angka < 20) return terbilang($angka - 10) . " Belas";
                elseif ($angka < 100) return terbilang($angka / 10) . " Puluh " . terbilang($angka % 10);
                elseif ($angka < 200) return "Seratus " . terbilang($angka - 100);
                elseif ($angka < 1000) return terbilang($angka / 100) . " Ratus " . terbilang($angka % 100);
                elseif ($angka < 2000) return "Seribu " . terbilang($angka - 1000);
                elseif ($angka < 1000000) return terbilang($angka / 1000) . " Ribu " . terbilang($angka % 1000);

                return $angka;
            }

            // ===== SET HEADER DATA =====
            $template->setValue('hari', $tanggal->translatedFormat('l'));
            $template->setValue('tgl_terbilang', terbilang($tanggal->day));
            $template->setValue('bulan', $tanggal->translatedFormat('F'));
            $template->setValue('tahun_terbilang', terbilang($tanggal->year));
            $template->setValue('tanggal', $tanggal->format('d-m-Y'));

            // ===== PIHAK =====
            $template->setValue('nama_pihak1', $settings2->nama_kaurumum_tu ?? '-');
            $template->setValue('nip_pihak1', $settings2->nip_kaurumum_tu ?? '-');

            $template->setValue('nama_pihak2', $pengajuan->user->seksi->nama_kepala ?? '-');
            $template->setValue('nip_pihak2', $pengajuan->user->seksi->nip_kepala ?? '-');
            $template->setValue('seksi', $pengajuan->user->seksi->seksi ?? '-');

            $template->setValue('nama_kasubbag', $settings2->nama_kasubbag_tu ?? '-');
            $template->setValue('nip_kasubbag', $settings2->nip_kasubbag_tu ?? '-');

            // ===== FILTER DATA =====
            $details = $pengajuan->details->where('status','Disetujui')->values();

            $template->cloneRow('no', count($details));

            foreach ($details as $i => $detail) {
                $index = $i + 1;

                $template->setValue("no#$index", $index);
                $template->setValue("nama_barang#$index", $detail->barang->nama_barang . ' (' . $detail->barang->merk_type . ')');
                $template->setValue("jumlah#$index", $detail->jumlah . ' ' . ($detail->barang->satuan ?? '-'));
                $template->setValue("nup#$index", '');
                $template->setValue("kondisi#$index", 'Baik');
            }

            $fileName = 'berita-acara-serah-terima-bmn.docx';
            $tempFile = tempnam(sys_get_temp_dir(), 'word');

            $template->saveAs($tempFile);

            return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
        }

        // ================= PDF =================
        $pdf = Pdf::loadView('pages.pengajuan-bmn.pdf.bast', [
            'pengajuan' => $pengajuan,
            'settings2' => $settings2
        ])->setPaper('A4', 'portrait');

        return $pdf->stream('berita-acara-serah-terima-bmn.pdf');
    }
}
