<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        // Barang Masuk bulan ini
        $totalBarangMasuk = BarangMasuk::whereMonth('tanggal', $now->month)
            ->whereYear('tanggal', $now->year)
            ->count();

        // Barang Keluar bulan ini (yang disetujui)
        $totalBarangKeluar = Pengajuan::where('status', 'Disetujui')
            ->whereMonth('tanggal_proses', $now->month)
            ->whereYear('tanggal_proses', $now->year)
            ->count();

        // Total transaksi
        $totalTransaksi = $totalBarangMasuk + $totalBarangKeluar;

        return view('pages.laporan.index', compact(
            'totalBarangMasuk',
            'totalBarangKeluar',
            'totalTransaksi'
        ));
    }

    public function generate(Request $request)
    {
        $jenis = $request->jenis_laporan;
        $dari  = $request->tanggal_dari;
        $sampai = $request->tanggal_sampai;

        if (!$jenis) {
            if ($request->ajax()) {
                return response('<div class="text-center text-amber-600 py-6 font-medium">
                    <i class="fas fa-exclamation-triangle mr-2"></i> Pilih jenis laporan terlebih dahulu!
                </div>', 200);
            }
            // Gunakan back() agar lebih aman daripada menulis nama route manual
            return back()->with('error', 'Pilih jenis laporan terlebih dahulu!');
        }

        // =============================
        // SEMUA TRANSAKSI
        // =============================
        if ($jenis === 'semua') {

            $barangMasuk = BarangMasuk::select(
                    'tanggal',
                    'barang_id',
                    'jumlah',
                    DB::raw("'Masuk' as jenis"),
                    DB::raw("'-' as keterangan")
                )
                ->when($dari && $sampai, function ($q) use ($dari, $sampai) {
                    $q->whereBetween('tanggal', [$dari, $sampai]);
                });

            $barangKeluar = Pengajuan::select(
                    'tanggal_proses as tanggal',
                    'barang_id',
                    'jumlah',
                    DB::raw("'Keluar' as jenis"),
                    'keperluan'
                )
                ->where('status', 'Disetujui')
                ->when($dari && $sampai, function ($q) use ($dari, $sampai) {
                    $q->whereBetween('tanggal_proses', [$dari, $sampai]);
                });

            $laporan = $barangMasuk
                ->unionAll($barangKeluar)
                ->orderBy('tanggal', 'desc')
                ->get();

            // load relasi barang
            $laporan->load('barang');

            // ================= PDF =================
            if ($request->submit_type === 'pdf') {
                $pdf = Pdf::loadView('pages.laporan.pdf.semua_transaksi', compact(
                    'laporan', 'dari', 'sampai'
                ));
                return $pdf->stream('laporan-semua-transaksi.pdf');
            }

            if ($request->ajax()) {
                return view('pages.laporan._table', compact(
                    'laporan', 'jenis', 'dari', 'sampai'
                ));
            }

            // ================= VIEW =================
            return view('pages.laporan.index', compact(
                'laporan', 'jenis', 'dari', 'sampai')
            );
        }

        // =============================
        // LAPORAN BARANG MASUK
        // =============================
        if ($jenis === 'barang_masuk') {

            $laporan = BarangMasuk::with('barang')
                ->when($dari && $sampai, function ($q) use ($dari, $sampai) {
                    $q->whereBetween('tanggal', [$dari, $sampai]);
                })
                ->orderBy('tanggal', 'desc')
                ->get();

            // EXPORT PDF
            if ($request->submit_type === 'pdf') {
                $pdf = Pdf::loadView('pages.laporan.pdf.barang_masuk', compact(
                    'laporan', 'dari', 'sampai'
                ));
                return $pdf->stream('laporan-barang-masuk.pdf');
            }

            if ($request->ajax()) {
                return view('pages.laporan._table', compact(
                    'laporan', 'jenis', 'dari', 'sampai'
                ));
            }

            // VIEW
            return view('pages.laporan.index', compact(
                'laporan', 'jenis', 'dari', 'sampai')
            );
        }

        // =============================
        // LAPORAN BARANG KELUAR
        // =============================
        if ($jenis === 'barang_keluar') {

            $laporan = Pengajuan::with(['barang', 'user'])
                ->where('status', 'Disetujui')
                ->when($dari && $sampai, function ($q) use ($dari, $sampai) {
                    $q->whereBetween('tanggal_proses', [$dari, $sampai]);
                })
                ->orderBy('tanggal_proses', 'desc')
                ->get();

            // EXPORT PDF
            if ($request->submit_type === 'pdf') {
                $pdf = Pdf::loadView(
                    'pages.laporan.pdf.barang_keluar',
                    compact('laporan', 'dari', 'sampai')
                );
                return $pdf->stream('laporan-barang-keluar.pdf');
            }

            if ($request->ajax()) {
                return view('pages.laporan._table', compact(
                    'laporan', 'jenis', 'dari', 'sampai'
                ));
            }

            // VIEW
            return view('pages.laporan.index', compact(
                'laporan', 'jenis', 'dari', 'sampai')
            );
        }

        // =============================
        // LAPORAN STOK BARANG
        // =============================
        if ($jenis === 'stok_barang') {

            $laporan = Barang::with('kategori')->get()->map(function ($barang) use ($dari, $sampai) {

                // BARANG MASUK DALAM PERIODE
                $masuk = BarangMasuk::where('barang_id', $barang->id)
                    ->when($dari && $sampai, function ($q) use ($dari, $sampai) {
                        $q->whereBetween('tanggal', [$dari, $sampai]);
                    })
                    ->sum('jumlah');

                // BARANG KELUAR DALAM PERIODE
                $keluar = Pengajuan::where('barang_id', $barang->id)
                    ->where('status', 'Disetujui')
                    ->when($dari && $sampai, function ($q) use ($dari, $sampai) {
                        $q->whereBetween('tanggal_proses', [$dari, $sampai]);
                    })
                    ->sum('jumlah');

                // STOK AKHIR = DATA REAL DI TABEL BARANGS
                $stokAkhir = $barang->jumlah;

                // STOK AWAL DITURUNKAN
                $stokAwal = $stokAkhir - $masuk + $keluar;

                return (object) [
                    'barang'     => $barang,
                    'stok_awal'  => $stokAwal,
                    'masuk'      => $masuk,
                    'keluar'     => $keluar,
                    'stok_akhir' => $stokAkhir,
                ];
            });

            // EXPORT PDF
            if ($request->submit_type === 'pdf') {
                $pdf = Pdf::loadView(
                    'pages.laporan.pdf.stok_barang',
                    compact('laporan', 'dari', 'sampai')
                );
                return $pdf->stream('laporan-stok-barang.pdf');
            }

            if ($request->ajax()) {
                return view('pages.laporan._table', compact(
                    'laporan', 'jenis', 'dari', 'sampai'
                ));
            }

            return view('pages.laporan.index', compact(
                'laporan', 'jenis', 'dari', 'sampai'
            ));
        }

        if ($request->ajax()) {
            return response('<div class="text-center text-red-600 py-6 font-medium">
                Jenis laporan tidak valid.
            </div>', 200);
        }

        return redirect()->route('pages.laporan.index')
            ->with('error', 'Jenis laporan belum didukung.');
    }
}
