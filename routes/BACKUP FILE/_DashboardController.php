<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\Pengajuan;
use App\Models\Kategori;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total stok barang
        $totalBarang = Barang::sum('jumlah');

        // MENGUBAH: Total variasi barang berdasarkan kode_barang
        $jumlahBarangUnik = Barang::count('kode_barang');

        // Total count kategori
        $jumlahKategori = Kategori::count();

        // =========================
        // BARANG MASUK (bulan ini)
        // =========================
        $barangMasuk = BarangMasuk::whereMonth('tanggal', now()->month)
            ->sum('jumlah');

        // =========================
        // BARANG KELUAR (pengajuan disetujui, bulan ini)
        // =========================
        $barangKeluar = Pengajuan::where('status', 'Disetujui')
            ->whereMonth('tanggal_proses', now()->month)
            ->sum('jumlah');

        // =========================
        // TRANSAKSI TERAKHIR (UNION)
        // =========================
        $user = auth()->user();

        if ($user->role === 'Administrator') {
            // ================= ADMIN =================
            $transaksiMasuk = BarangMasuk::select(
                    'tanggal',
                    'barang_id',
                    'jumlah',
                    DB::raw("'Masuk' as jenis")
                );

            $transaksiKeluar = Pengajuan::select(
                    'tanggal_proses as tanggal',
                    'barang_id',
                    'jumlah',
                    DB::raw("'Keluar' as jenis")
                )
                ->where('status', 'Disetujui');

            $transaksiTerakhir = $transaksiMasuk
                ->unionAll($transaksiKeluar)
                ->orderBy('tanggal', 'desc')
                ->limit(10)
                ->get();

        } else {
            // ================= STAFF =================
            $transaksiTerakhir = Pengajuan::select(
                    'tanggal_pengajuan as tanggal',
                    'barang_id',
                    'jumlah',
                    DB::raw("'Keluar' as jenis")
                )
                ->where('status', 'Disetujui')
                ->where('user_id', $user->id)
                ->orderBy('tanggal', 'desc')
                ->limit(10)
                ->get();
        }

        // Load relasi barang
        $transaksiTerakhir->load('barang');

        // =========================
        // Statistik kategori
        // =========================
        $kategoriStat = Barang::selectRaw('kategori_id, SUM(jumlah) as total')
            ->groupBy('kategori_id')
            ->with('kategori')
            ->get();

        return view('pages.dashboard', compact(
            'totalBarang',
            'barangMasuk',
            'barangKeluar',
            'transaksiTerakhir',
            'kategoriStat',
            'jumlahKategori',
            'jumlahBarangUnik' // Kirim variabel baru ke view
        ));
    }
}
