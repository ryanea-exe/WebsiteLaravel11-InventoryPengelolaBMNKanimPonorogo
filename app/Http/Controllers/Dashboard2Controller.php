<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\RiwayatPajak;
use App\Models\RiwayatServis;
use App\Models\PengajuanPemeliharaan;
use App\Models\PengajuanPemeliharaanDetail;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Dashboard2Controller extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $namaAplikasi2 = Setting::first()->nama_aplikasi2;

        if ($user->role === 'Administrator') {
            $kendaraan = Kendaraan::with(['riwayatPajak'])
                ->get();
        } else {
            $kendaraan = Kendaraan::with(['seksi'])
                ->where('seksi_id', $user->seksi_id)
                ->get();
        }

        $today = Carbon::today();

        $pajakBelumDibayar = 0;
        $pajakSudahDibayar = 0;

        foreach ($kendaraan as $k) {
            if (!$k->tanggal_pajak_berkala) continue;

            $jatuhTempo = Carbon::parse($k->tanggal_pajak_berkala)->year($today->year);

            $sudahBayar = $k->riwayatPajak->isNotEmpty();

            if ($sudahBayar) {
                $pajakSudahDibayar++;
            } else {
                $pajakBelumDibayar++;
            }
        }

        // =========================
        // RIWAYAT TERBARU
        // =========================
        $riwayatServisTerakhir = RiwayatServis::with('kendaraan')
            ->latest()
            ->take(5)
            ->get();

        if ($user->role === 'Administrator') {
            $riwayatPengajuanTerakhir = PengajuanPemeliharaan::with(['kendaraan', 'user'])
                ->latest('tanggal_pengajuan')
                ->take(5)
                ->get();
        } else {
            $riwayatPengajuanTerakhir = PengajuanPemeliharaan::with(['kendaraan', 'user'])
                ->whereHas('user', function ($q) use ($user) {
                    $q->where('seksi_id', $user->seksi_id); // ✅ berdasarkan seksi
                })
                ->latest('tanggal_pengajuan')
                ->take(5)
                ->get();
        }

        $kendaraanPrioritasPajak = Kendaraan::with('riwayatPajak')->get()
            ->map(function ($k) {
                if (!$k->tanggal_pajak_berkala) {
                    $k->selisih = 9999;
                    return $k;
                }

                $today = Carbon::today();
                $jatuhTempo = Carbon::parse($k->tanggal_pajak_berkala)->year($today->year);
                $sudahBayar = $k->riwayatPajak->isNotEmpty();

                if ($sudahBayar) {
                    $target = $jatuhTempo->copy()->addYear();
                    $selisih = $today->diffInDays($target);
                } else {
                    if ($jatuhTempo->lt($today)) {
                        $selisih = -$jatuhTempo->diffInDays($today);
                    } else {
                        $selisih = $today->diffInDays($jatuhTempo);
                    }
                }

                $k->selisih = $selisih;
                return $k;
            })
            ->sortBy('selisih') // 🔥 paling kecil = paling prioritas
            ->take(5)
            ->values();

            if ($user->role === 'Administrator') {
                $servisCount = RiwayatServis::whereMonth('tanggal_servis', now()->month)
                    ->count();
            } else {
                $servisCount = RiwayatServis::whereYear('tanggal_servis', now()->year)
                    ->whereHas('kendaraan', function ($q) use ($user) {
                        $q->where('seksi_id', $user->seksi_id);
                    })
                    ->count();
            }

        // RETURN VIEW
        return view('pages.dashboard2', compact('namaAplikasi2'), [
            'totalKendaraan' => $kendaraan->count(),
            'servisCount' => $servisCount,
            'pengajuanBulanIni' => PengajuanPemeliharaan::whereMonth('tanggal_pengajuan', now()->month)->count(),
            'totalPajak' => RiwayatPajak::count(),

            // 🔥 hasil dari logic yang sama seperti blade
            'pajakBelumDibayar' => $pajakBelumDibayar,
            'pajakSudahDibayar' => $pajakSudahDibayar,
            'riwayatServisTerakhir' => $riwayatServisTerakhir,
            'riwayatPengajuanTerakhir' => $riwayatPengajuanTerakhir,
            'kendaraanPrioritasPajak' => $kendaraanPrioritasPajak,
        ]);
    }
}
