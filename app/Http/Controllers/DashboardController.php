<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangBMN;
use App\Models\BarangMasuk;
use App\Models\BarangMasukDetail;
use App\Models\Pengajuan;
use App\Models\PengajuanBMN;
use App\Models\PengajuanDetail;
use App\Models\PengajuanBMNDetail;
use App\Models\Kategori;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $namaAplikasi = Setting::first()->nama_aplikasi;
        
        // =========================
        // TOTAL STOK BARANG
        // =========================
        $totalBarangPersediaan = Barang::sum('jumlah');
        $totalBarangBMN = BarangBMN::sum('jumlah');

        $jumlahBarangUnik = Barang::count('kode_barang');

        $jumlahKategori = Kategori::count();

        // =========================
        // BARANG MASUK (bulan ini)
        // =========================
        $barangMasuk = BarangMasukDetail::whereHas('barang_masuk', function ($q) {
                $q->whereMonth('tanggal', now()->month);
            })
            ->sum('jumlah');

        // =========================
        // BARANG KELUAR (DETAIL YANG DISETUJUI, bulan ini)
        // =========================
        $barangKeluar = PengajuanDetail::where('status', 'Disetujui')
            ->whereHas('pengajuan', function ($q) {
                $q->whereMonth('tanggal_pengajuan', now()->month);
            })
            ->sum('jumlah_disetujui');

        // =========================
        // STATISTIK PERMINTAAN (bulan ini)
        // =========================
        $permintaanSemua = Pengajuan::whereMonth('tanggal_pengajuan', now()->month)
            ->count();
        $permintaanDiajukan = Pengajuan::where('status', 'Diajukan')
            ->whereMonth('tanggal_pengajuan', now()->month)
            ->count();
        $permintaanDisetujui = Pengajuan::where('status', 'Disetujui')
            ->whereMonth('tanggal_pengajuan', now()->month)
            ->count();
        $permintaanSebagian = Pengajuan::where('status', 'Disetujui Sebagian')
            ->whereMonth('tanggal_pengajuan', now()->month)
            ->count();
        $permintaanDitolak = Pengajuan::where('status', 'Ditolak')
            ->whereMonth('tanggal_pengajuan', now()->month)
            ->count();

        $permintaanBMNSemua = PengajuanBMN::whereMonth('tanggal_pengajuan', now()->month)
            ->count();
        $permintaanBMNDiajukan = PengajuanBMN::where('status', 'Diajukan')
            ->whereMonth('tanggal_pengajuan', now()->month)
            ->count();
        $permintaanBMNDisetujui = PengajuanBMN::where('status', 'Disetujui')
            ->whereMonth('tanggal_pengajuan', now()->month)
            ->count();
        $permintaanBMNSebagian = PengajuanBMN::where('status', 'Disetujui Sebagian')
            ->whereMonth('tanggal_pengajuan', now()->month)
            ->count();
        $permintaanBMNDitolak = PengajuanBMN::where('status', 'Ditolak')
            ->whereMonth('tanggal_pengajuan', now()->month)
            ->count();

        // =========================
        // TRANSAKSI TERAKHIR
        // =========================
        $user = auth()->user();

        if ($user->role === 'Administrator') {
            // ================= MASUK PERSEDIAAN =================
            $transaksiMasuk = BarangMasuk::with('details.barang')
                ->latest()
                ->limit(10)
                ->get()
                ->map(function ($item) {
                    return (object)[
                        'tanggal'  => $item->tanggal,
                        'jenis'    => 'Masuk',
                        'kategori' => 'Persediaan',
                        'details'  => $item->details
                    ];
                });

            // ================= KELUAR PERSEDIAAN (GROUP PER PENGAJUAN) =================
            $transaksiKeluar = Pengajuan::with(['details.barang'])
                ->whereHas('details', function ($q) {
                    $q->where('status', 'Disetujui');
                })
                ->orderBy('tanggal_proses', 'desc')
                ->get()
                ->map(function ($pengajuan) {
                    return (object)[
                        'tanggal' => $pengajuan->tanggal_proses,
                        'jenis'   => 'Keluar',
                        'kategori' => 'Persediaan',
                        'details' => $pengajuan->details
                                        ->where('status', 'Disetujui')
                    ];
                });
            
            // // ================= KELUAR BMN (GROUP PER PENGAJUAN) =================
            $transaksiKeluarBMN = \App\Models\PengajuanBMN::with(['details.barang'])
                ->whereHas('details', function ($q) {
                    $q->where('status', 'Disetujui');
                })
                ->orderBy('tanggal_proses', 'desc')
                ->get()
                ->map(function ($pengajuan) {
                    return (object)[
                        'tanggal'  => $pengajuan->tanggal_proses,
                        'jenis'    => 'Keluar',
                        'kategori' => 'BMN',
                        'details'  => $pengajuan->details
                                        ->where('status', 'Disetujui')
                    ];
                });

            // Gabungkan
            $transaksiTerakhir = collect();

            foreach ($transaksiMasuk as $m) {
                $transaksiTerakhir->push($m);
            }

            foreach ($transaksiKeluar as $k) {
                $transaksiTerakhir->push($k);
            }

            foreach ($transaksiKeluarBMN as $k) {
                $transaksiTerakhir->push($k);
            }

            // Urutkan ulang berdasarkan tanggal
            $transaksiTerakhir = $transaksiTerakhir
                ->sortByDesc(function ($item) {
                    return \Carbon\Carbon::parse($item->tanggal);
                })
                ->take(5)
                ->values();

        } else {
            $transaksiTerakhir = Pengajuan::with(['details.barang'])
                ->where('user_id', $user->id)
                /* ->whereHas('user', function ($q) use ($user) {
                    $q->where('seksi_id', $user->seksi_id); // ✅ berdasarkan seksi
                }) */
                ->whereHas('details', function ($q) {
                    $q->where('status', 'Disetujui');
                })
                ->latest()
                ->take(10)
                ->get()
                ->map(function ($pengajuan) {
                    return (object)[
                        'tanggal'  => $pengajuan->tanggal_proses ?? $pengajuan->created_at,
                        'jenis'    => 'Keluar',
                        'kategori' => 'Persediaan', // ✅ TAMBAHKAN INI
                        'details'  => $pengajuan->details
                                        ->where('status', 'Disetujui')
                    ];
                });

            $transaksiBMN = \App\Models\PengajuanBMN::with(['details.barang'])
                ->where('user_id', $user->id)
                /* ->whereHas('user', function ($q) use ($user) {
                    $q->where('seksi_id', $user->seksi_id); // ✅ berdasarkan seksi
                }) */
                ->whereHas('details', function ($q) {
                    $q->where('status', 'Disetujui');
                })
                ->latest()
                ->take(10)
                ->get()
                ->map(function ($pengajuan) {
                    return (object)[
                        'tanggal'  => $pengajuan->tanggal_proses ?? $pengajuan->created_at,
                        'jenis'    => 'Keluar',
                        'kategori' => 'BMN',
                        'details'  => $pengajuan->details
                                        ->where('status', 'Disetujui')
                    ];
                });

            $transaksiTerakhir = $transaksiTerakhir
                ->merge($transaksiBMN)
                ->sortByDesc(function ($item) {
                    return \Carbon\Carbon::parse($item->tanggal);
                })
                ->take(10)
                ->values();
        }

        // =========================
        // Statistik kategori
        // =========================
        // Ambil dari barang
        $persediaan = Barang::select('kategori_id', DB::raw('SUM(jumlah) as total'))
            ->groupBy('kategori_id');
        // Ambil dari BMN
        $bmn = BarangBMN::select('kategori_id', DB::raw('SUM(jumlah) as total'))
            ->groupBy('kategori_id');
        // Union
        $gabungan = $persediaan->unionAll($bmn);
        // FINAL (JOIN ke tabel kategori)
        $kategoriStat = DB::table(DB::raw("({$gabungan->toSql()}) as combined"))
            ->mergeBindings($gabungan->getQuery())
            ->leftJoin('kategori', 'kategori.id', '=', 'combined.kategori_id')
            ->select(
                'combined.kategori_id',
                DB::raw('COALESCE(kategori.nama, "Kategori tidak ditemukan") as nama'),
                DB::raw('SUM(total) as total')
            )
            ->groupBy('combined.kategori_id', 'kategori.nama')
            ->orderByDesc('total') // 🔥 TAMBAHKAN INI
            ->get();

        // return view
        return view('pages.dashboard', compact(
            'namaAplikasi',
            'totalBarangPersediaan',
            'totalBarangBMN',
            'barangMasuk',
            'barangKeluar',
            'transaksiTerakhir',
            'kategoriStat',
            'jumlahKategori',
            'jumlahBarangUnik',
            'permintaanSemua',
            'permintaanDiajukan',
            'permintaanDisetujui',
            'permintaanSebagian',
            'permintaanDitolak',
            'permintaanBMNSemua',
            'permintaanBMNDiajukan',
            'permintaanBMNDisetujui',
            'permintaanBMNSebagian',
            'permintaanBMNDitolak'
        ));
    }
}
