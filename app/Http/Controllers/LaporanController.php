<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Barang;
use App\Models\BarangBMN;
use App\Models\BarangMasuk;
use App\Models\Pengajuan;
use App\Models\PengajuanDetail;
use App\Models\PengajuanBMN;
use App\Models\PengajuanBMNDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;

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
        /*
        $totalBarangKeluar = PengajuanDetail::where('status', 'Disetujui')
            ->whereHas('pengajuan', function ($q) use ($now) {
                $q->whereIn('status', ['Disetujui', 'Disetujui Sebagian'])
                    ->whereMonth('tanggal_proses', $now->month)
                    ->whereYear('tanggal_proses', $now->year);
            })
            ->count();
        */
        $totalBarangKeluar = PengajuanDetail::where('status', 'Disetujui')
            ->whereHas('pengajuan', function ($q) use ($now) {
                $q->whereIn('status', ['Disetujui', 'Disetujui Sebagian'])
                    ->whereMonth('tanggal_proses', $now->month)
                    ->whereYear('tanggal_proses', $now->year);
            })
            ->distinct('pengajuan_id')
            ->count('pengajuan_id');

        // Total transaksi
        $totalTransaksi = $totalBarangMasuk + $totalBarangKeluar;

        $totalTransaksiBMN = PengajuanBMNDetail::where('status', 'Disetujui')
            ->whereHas('pengajuanBMN', function ($q) use ($now) {
                $q->whereIn('status', ['Disetujui', 'Disetujui Sebagian'])
                    ->whereMonth('tanggal_proses', $now->month)
                    ->whereYear('tanggal_proses', $now->year);
            })
            ->distinct('pengajuan_id')
            ->count('pengajuan_id');

        return view('pages.laporan.index', compact(
            'totalBarangMasuk',
            'totalBarangKeluar',
            'totalTransaksi',
            'totalTransaksiBMN'
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
                    <i class="fas fa-exclamation-triangle mr-2"></i> Laporan belum ditampilkan, Pilih jenis laporan terlebih dahulu!
                </div>', 200);
            }
            // Gunakan back() agar lebih aman daripada menulis nama route manual
            return back()->with('error', 'Laporan belum ditampilkan, Pilih jenis laporan terlebih dahulu!');
        }

        // =============================
        // SEMUA TRANSAKSI (BMN)
        // =============================
        if ($jenis === 'semua_bmn') {
            $laporan = PengajuanBMNDetail::with(['barang', 'pengajuanBMN.user'])
                ->where('pengajuan_bmn_detail.status', 'Disetujui')
                ->whereHas('pengajuanBMN', function ($q) use ($dari, $sampai) {
                    $q->whereIn('status', ['Disetujui', 'Disetujui Sebagian']);

                    if ($dari && $sampai) {
                        $q->whereDate('tanggal_proses', '>=', $dari)
                        ->whereDate('tanggal_proses', '<=', $sampai);
                    }
                })
                ->join('pengajuan_bmn', 'pengajuan_bmn_detail.pengajuan_id', '=', 'pengajuan_bmn.id')
                ->orderBy('pengajuan_bmn.tanggal_proses', 'desc')
                ->select('pengajuan_bmn_detail.*')
                ->get();

            // EXPORT PDF
            if ($request->submit_type === 'pdf') {
                $pdf = Pdf::loadView(
                    'pages.laporan.pdf.semua_transaksi_bmn',
                    compact('laporan', 'dari', 'sampai')
                );
                return $pdf->stream('laporan-semua-transaksi-bmn.pdf');
            }

            // EXPORT EXCEL
            if ($request->submit_type === 'excel') {
                return Excel::download(
                    new LaporanExport(
                        'pages.laporan.pdf.barang_keluar',
                        compact('laporan','dari','sampai')
                    ),
                    'laporan-barang-keluar.xlsx'
                );
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
        // SEMUA TRANSAKSI (PERSEDIAAN)
        // =============================
        if ($jenis === 'semua') {
            $barangMasuk = BarangMasuk::with('details.barang')
                ->when($dari && $sampai, function ($q) use ($dari, $sampai) {
                    $q->whereBetween('tanggal', [
                        Carbon::parse($dari)->startOfDay(),
                        Carbon::parse($sampai)->endOfDay()
                    ]);
                })
                ->latest()
                ->get()
                ->map(function ($item) {
                    return (object)[
                        'id'         => $item->id,
                        'tanggal'    => $item->tanggal,
                        'jenis'      => 'Masuk',
                        'details'    => $item->details, // ⬅️ tetap array
                        'keterangan' => $item->keterangan
                    ];
                });

            $barangKeluar = PengajuanDetail::with(['barang', 'pengajuan'])
                ->where('status', 'Disetujui')
                ->whereHas('pengajuan', function ($q) use ($dari, $sampai) {
                    $q->whereIn('status', ['Disetujui', 'Disetujui Sebagian']);

                    if ($dari && $sampai) {
                        $q->whereDate('tanggal_proses', '>=', $dari)
                        ->whereDate('tanggal_proses', '<=', $sampai);
                    }
                })
                ->get()
                ->map(function ($item) {
                    return (object)[
                        'id'            => $item->id,
                        'pengajuan_id'  => $item->pengajuan_id,   // ✅ TAMBAHKAN INI
                        'tanggal'       => $item->pengajuan->tanggal_proses,
                        'jenis'         => 'Keluar',
                        'barang'        => $item->barang,
                        'jumlah'        => $item->jumlah_disetujui, // ✔ pakai jumlah disetujui
                        'keterangan'    => $item->pengajuan->keperluan ?? '-'
                    ];
                });

            $laporan = $barangMasuk
                ->merge($barangKeluar)
                ->sortByDesc('tanggal')
                ->values();

            // ================= PDF =================
            if ($request->submit_type === 'pdf') {
                $pdf = Pdf::loadView('pages.laporan.pdf.semua_transaksi', compact(
                    'laporan', 'dari', 'sampai'
                ));
                return $pdf->stream('laporan-semua-transaksi-persediaan.pdf');
            }

            if ($request->ajax()) {
                return view('pages.laporan._table', compact(
                    'laporan', 'jenis', 'dari', 'sampai'
                ));
            }

            // ================= EXCEL =================
            if ($request->submit_type === 'excel') {
                return Excel::download(
                    new LaporanExport(
                        'pages.laporan.pdf.semua_transaksi',
                        compact('laporan','dari','sampai')
                    ),
                    'laporan-semua-transaksi.xlsx'
                );
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
            $laporan = BarangMasuk::with('details.barang')
                ->when($dari && $sampai, function ($q) use ($dari, $sampai) {
                    $q->whereBetween('tanggal', [
                        Carbon::parse($dari)->startOfDay(),
                        Carbon::parse($sampai)->endOfDay()
                    ]);
                })
                ->latest()
                ->get()
                ->map(function ($item) {
                    return (object)[
                        'id'         => $item->id,
                        'tanggal'    => $item->tanggal,
                        'details'    => $item->details, // ⬅️ simpan semua item
                        'keterangan' => $item->keterangan
                    ];
                });

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

            // EXPORT EXCEL
            if ($request->submit_type === 'excel') {
                return Excel::download(
                    new LaporanExport(
                        'pages.laporan.pdf.barang_masuk',
                        compact('laporan','dari','sampai')
                    ),
                    'laporan-barang-masuk.xlsx'
                );
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
            $laporan = PengajuanDetail::with(['barang', 'pengajuan.user'])
                ->where('pengajuan_details.status', 'Disetujui')
                ->whereHas('pengajuan', function ($q) use ($dari, $sampai) {
                    $q->whereIn('status', ['Disetujui', 'Disetujui Sebagian']);

                    if ($dari && $sampai) {
                        $q->whereDate('tanggal_proses', '>=', $dari)
                        ->whereDate('tanggal_proses', '<=', $sampai);
                    }
                })
                ->join('pengajuans', 'pengajuan_details.pengajuan_id', '=', 'pengajuans.id')
                ->orderBy('pengajuans.tanggal_proses', 'desc')
                ->select('pengajuan_details.*')
                ->get();

            // EXPORT PDF
            if ($request->submit_type === 'pdf') {
                $pdf = Pdf::loadView(
                    'pages.laporan.pdf.barang_keluar',
                    compact('laporan', 'dari', 'sampai')
                );
                return $pdf->stream('laporan-barang-keluar.pdf');
            }

            // EXPORT EXCEL
            if ($request->submit_type === 'excel') {
                return Excel::download(
                    new LaporanExport(
                        'pages.laporan.pdf.barang_keluar',
                        compact('laporan','dari','sampai')
                    ),
                    'laporan-barang-keluar.xlsx'
                );
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
            $barangList = Barang::with('kategori')->get();

            $laporan = $barangList->map(function ($barang) use ($dari, $sampai) {
                // BARANG MASUK DALAM PERIODE
                $masuk = DB::table('barang_masuk_details')
                    ->join('barang_masuk', 'barang_masuk.id', '=', 'barang_masuk_details.barang_masuk_id')
                    ->where('barang_masuk_details.barang_id', $barang->id)
                    ->when($dari && $sampai, function ($q) use ($dari, $sampai) {
                        $q->whereBetween('barang_masuk.tanggal', [$dari, $sampai]);
                    })
                    ->sum('barang_masuk_details.jumlah');

                // BARANG KELUAR DALAM PERIODE
                $keluar = PengajuanDetail::where('barang_id', $barang->id)
                    ->where('status', 'Disetujui')
                    ->whereHas('pengajuan', function ($q) use ($dari, $sampai) {
                        $q->whereIn('status', ['Disetujui', 'Disetujui Sebagian']);

                        if ($dari && $sampai) {
                            $q->whereDate('tanggal_proses', '>=', $dari)
                            ->whereDate('tanggal_proses', '<=', $sampai);
                        }
                    })
                    ->sum('jumlah_disetujui');

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

            // EXPORT EXCEL
            if ($request->submit_type === 'excel') {
                return Excel::download(
                    new LaporanExport(
                        'pages.laporan.pdf.stok_barang',
                        compact('laporan','dari','sampai')
                    ),
                    'laporan-stok-barang.xlsx'
                );
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
