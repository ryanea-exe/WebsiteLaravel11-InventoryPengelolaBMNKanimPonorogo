<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanPemeliharaan;
use App\Models\PengajuanPemeliharaanDetail;
use App\Models\Kendaraan;
use App\Models\RiwayatServis;
use App\Models\Setting;
use App\Models\Setting2;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;

class PengajuanPemeliharaanController extends Controller
{
    /**
     * Form pengajuan
     */
    public function index()
    {
        $user = auth()->user();

        $kendaraans = Kendaraan::where('seksi_id', $user->seksi_id)
            ->select('id','nama_kendaraan','nomor_polisi','tahun')
            ->orderBy('nama_kendaraan')
            ->get();

        return view('pages.pemeliharaan.index', compact('kendaraans'));
    }

    /**
     * Simpan pengajuan
     */
    public function store(Request $request)
    {
        $request->validate([
            'kendaraan_id' => 'required|exists:kendaraan,id',
            'keperluan' => 'required|array',
            'keperluan.*' => 'required|string',
            'estimasi_biaya' => 'required|array',
            'estimasi_biaya.*' => 'required|integer'
        ]);

        try {
            DB::transaction(function () use ($request) {
                $pengajuan = PengajuanPemeliharaan::create([
                    'user_id'           => auth()->id(),
                    'kendaraan_id'      => $request->kendaraan_id,
                    'tanggal_pengajuan' => now(),
                    'status'            => 'Diajukan'
                ]);

                foreach ($request->keperluan as $index => $k) {
                    \App\Models\PengajuanPemeliharaanDetail::create([
                        'pengajuan_id'  => $pengajuan->id,
                        'keperluan'     => $k,
                        'estimasi_biaya'=> $request->estimasi_biaya[$index] ?? 0
                    ]);
                }
            });

            if ($request->ajax()) {
                session()->flash('success','Pengajuan pemeliharaan kendaraan berhasil diajukan');
                return response()->json(['success'=>true]);
            }

            return redirect()->route('pemeliharaan.index')
                ->with('success','Pengajuan pemeliharaan kendaraan berhasil diajukan');

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

    // RIWAYAT ADMIN
    public function riwayat_admin(Request $request)
    {
        $query = PengajuanPemeliharaan::with(['user','kendaraan','details']);

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

        return view('pages.pemeliharaan.riwayat_admin', compact('pengajuans'));
    }

    public function approve($id)
    {
        $pengajuan = PengajuanPemeliharaan::findOrFail($id);

        $pengajuan->update([
            'status' => 'Disetujui',
            'tanggal_proses' => now(),
            'keterangan_proses' => 'Pengajuan disetujui',
            'read_at' => null // ⬅️ penting!
        ]);

        return redirect()->back()->with('success', 'Pengajuan berhasil disetujui');
    }

    public function reject($id)
    {
        $pengajuan = PengajuanPemeliharaan::findOrFail($id);

        $pengajuan->update([
            'status' => 'Ditolak',
            'tanggal_proses' => now(),
            'keterangan_proses' => 'Pengajuan ditolak',
            'read_at' => null // ⬅️ penting!
        ]);

        return redirect()->back()->with('success', 'Pengajuan berhasil ditolak');
    }

    // RIWAYAT USER (HANYA DATA MILIK USER LOGIN)
    public function riwayat_user(Request $request)
    {
        $user = auth()->user();
        $query = PengajuanPemeliharaan::with(['user','kendaraan','details'])
            /* ->where('user_id', auth()->id()); */
            ->whereHas('user', function ($q) use ($user) {
                $q->where('seksi_id', $user->seksi_id); // ✅ berdasarkan seksi
            });

        $pengajuans = $query
            ->orderBy('tanggal_pengajuan', 'desc')
            ->get();

        return view('pages.pemeliharaan.riwayat_user', compact('pengajuans'));
    }

    public function detail($id)
    {
        $query = PengajuanPemeliharaan::with(['user','kendaraan','details'])
            ->where('id', $id);

        if (auth()->user()->role === 'Staff') {
            /* $query->where('user_id', auth()->id()); */
            $query->whereHas('user', function ($q) {
                $q->where('seksi_id', auth()->user()->seksi_id); // ✅ berdasarkan seksi
            });
        }

        return response()->json($query->firstOrFail());
    }

    public function cancel($id)
    {
        $user = auth()->user();
        $pengajuan = PengajuanPemeliharaan::where('id', $id)
            /* ->where('user_id', auth()->id()) */
            ->whereHas('user', function ($q) use ($user) {
                $q->where('seksi_id', $user->seksi_id); // ✅ berdasarkan seksi
            })
            ->firstOrFail();

        // hanya boleh dibatalkan jika masih diajukan
        if ($pengajuan->status !== 'Diajukan') {
            return back()->with('error', 'Pengajuan pemeliharaan sudah diproses dan tidak bisa dibatalkan.');
        }

        $pengajuan->delete();

        return back()->with('success', 'Pengajuan pemeliharaan berhasil dibatalkan.');
    }

    public function destroy($id)
    {
        PengajuanPemeliharaan::findOrFail($id)->delete();
        return redirect()->route('pemeliharaan.riwayat_admin')
            ->with('success', 'Permintaan berhasil dihapus');
    }

    public function show_admin($id)
    {
        $pengajuan = PengajuanPemeliharaan::with(['user','kendaraan','details'])
            ->findOrFail($id);

        return view('pages.pemeliharaan.show_admin', compact('pengajuan'));
    }

    public function show_user($id)
    {
        $user = auth()->user();
        $pengajuan = PengajuanPemeliharaan::with(['user','kendaraan','details'])
            ->where('id', $id)
            ->whereHas('user', function ($q) use ($user) {
                $q->where('seksi_id', $user->seksi_id);
            })
            ->firstOrFail();

        // tandai notif sudah dibaca
        if (is_null($pengajuan->read_at)) {
            $pengajuan->update([
                'read_at' => now()
            ]);
        }

        return view('pages.pemeliharaan.show_user', compact('pengajuan'));
    }

    // cetak nodin
    public function nodin($id)
    {
        $pengajuan = PengajuanPemeliharaan::with(['user','kendaraan','details'])->findOrFail($id);
        $settings2 = Setting2::first();
        $setting   = Setting::first();
        $servis = $pengajuan->kendaraan->riwayatServis()
            ->orderBy('tanggal_servis', 'desc')
            ->first();
        $servisTerakhir = $servis 
            ? \Carbon\Carbon::parse($servis->tanggal_servis)
            : null;

        // ================= DOCX =================
        if ($setting && $setting->format_cetak === 'docx') {
            Settings::setTempDir(storage_path('app/temp'));

            $template = new TemplateProcessor(
                storage_path('app/template/nodin_pemeliharaan_template.docx')
            );

            // ===== FORMAT TANGGAL =====
            $tanggal = \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)
                ->translatedFormat('d F Y');
            $tanggalServis = $servisTerakhir 
                ? $servisTerakhir->translatedFormat('d F Y') 
                : '-';

            // ===== SET HEADER =====
            $template->setValue('tanggal', $tanggal);
            $template->setValue('tanggal_servis', $tanggalServis);
            $template->setValue('seksi', $pengajuan->user->seksi->seksi ?? '-');
            $template->setValue('nama_kepala', $pengajuan->user->seksi->nama_kepala ?? '-');
            $template->setValue('nip_kepala', $pengajuan->user->seksi->nip_kepala ?? '-');

            $template->setValue('jenis_kendaraan', $pengajuan->kendaraan->jenis_kendaraan);
            $template->setValue('nomor_polisi', $pengajuan->kendaraan->nomor_polisi);
            $template->setValue('nama_kendaraan', $pengajuan->kendaraan->nama_kendaraan);
            $template->setValue('tahun', $pengajuan->kendaraan->tahun);

            // ===== DETAIL (CLONE ROW) =====
            $details = $pengajuan->details;
            $template->cloneRow('keperluan', $details->count());

            foreach ($details as $i => $detail) {
                $index = $i + 1;

                $template->setValue("keperluan#$index", $detail->keperluan);
                $template->setValue(
                    "biaya#$index",
                    'Rp. ' . number_format($detail->estimasi_biaya, 0, ',', '.')
                );
            }

            // ===== TOTAL =====
            $total = $details->sum('estimasi_biaya');
            $template->setValue('total', 'Rp. ' . number_format($total, 0, ',', '.'));

            // ===== DOWNLOAD =====
            $fileName = 'nota-dinas-pemeliharaan.docx';
            $tempFile = tempnam(sys_get_temp_dir(), 'word');

            $template->saveAs($tempFile);

            return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
        }

        // ================= PDF =================
        $pdf = Pdf::loadView('pages.pemeliharaan.pdf.nodin', [
            'pengajuan' => $pengajuan,
            'settings2' => $settings2,
            'servisTerakhir' => $servisTerakhir // ✅ tambahkan ini
        ])->setPaper('A4', 'portrait');

        return $pdf->stream('nota-dinas-pemeliharaan.pdf');
    }
}
