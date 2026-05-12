<?php

namespace App\Providers;

use App\Models\Pengajuan;
use App\Models\PengajuanBMN;
use App\Models\Kendaraan;
use App\Models\PengajuanPemeliharaan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (!auth()->check()) return;

            $user = auth()->user();

            // ================= ADMIN =================
            if ($user->role === 'Administrator') {
                $notifPersediaan = Pengajuan::with(['user', 'details.barang'])
                    ->where('status', 'Diajukan')
                    ->latest()
                    ->take(10)
                    ->get()
                    ->map(function ($item) {
                        $item->tipe = 'persediaan';
                        return $item;
                    });

                $notifBMN = PengajuanBMN::with(['user', 'details.barang'])
                    ->where('status', 'Diajukan')
                    ->latest()
                    ->take(10)
                    ->get()
                    ->map(function ($item) {
                        $item->tipe = 'bmn';
                        return $item;
                    });

                $notifications = $notifPersediaan
                    ->merge($notifBMN)
                    ->sortByDesc('created_at')
                    ->take(10);

            }

            // ================= STAFF =================
            else {
                $notifPersediaan = Pengajuan::with(['details.barang'])
                    ->where('user_id', $user->id)
                    ->whereIn('status', ['Disetujui', 'Sebagian Disetujui', 'Ditolak'])
                    ->whereNull('read_at')
                    ->latest('updated_at')
                    ->take(10)
                    ->get()
                    ->map(function ($item) {
                        $item->tipe = 'persediaan';
                        return $item;
                    });

                $notifBMN = PengajuanBMN::with(['details.barang'])
                    ->where('user_id', $user->id)
                    ->whereIn('status', ['Disetujui', 'Sebagian Disetujui', 'Ditolak'])
                    ->whereNull('read_at')
                    ->latest('updated_at')
                    ->take(10)
                    ->get()
                    ->map(function ($item) {
                        $item->tipe = 'bmn';
                        return $item;
                    });

                $notifications = $notifPersediaan
                    ->merge($notifBMN)
                    ->sortByDesc('updated_at')
                    ->take(10);
            }

            $view->with('notifications', $notifications);

            // ================= NOTIF KENDARAAN =================
            $vehicleNotifications = collect();

            $kendaraan = Kendaraan::all();

            // ================= NOTIF PENGAJUAN PEMELIHARAAN =================
            if ($user->role === 'Administrator') {
                $notifPemeliharaan = PengajuanPemeliharaan::with(['user','kendaraan'])
                    ->where('status', 'Diajukan')
                    ->latest()
                    ->take(10)
                    ->get();
            } else {
                $user = auth()->user();
                $notifPemeliharaan = PengajuanPemeliharaan::with(['user','kendaraan'])
                    /* ->where('user_id', $user->id) */
                    ->whereHas('user', function ($q) use ($user) {
                        $q->where('seksi_id', $user->seksi_id); // ✅ berdasarkan seksi
                    })
                    ->whereIn('status', ['Disetujui', 'Ditolak'])
                    ->whereNull('read_at')
                    ->latest('updated_at')
                    ->take(10)
                    ->get();
            }

            // format ke vehicleNotifications
            foreach ($notifPemeliharaan as $item) {
                $namaKendaraan = $item->kendaraan->nama_kendaraan ?? '-';
                $plat = $item->kendaraan->nomor_polisi ?? '-';
                $seksi = $item->kendaraan->seksi->seksi_singkat ?? '-';

                if ($user->role === 'Administrator') {
                    $pesan = "Pengajuan pemeliharaan {$namaKendaraan} <strong>({$plat})</strong> diajukan Seksi {$seksi}";
                } else {
                    $pesan = "Pengajuan pemeliharaan {$namaKendaraan} <strong>({$plat})</strong> {$item->status}";
                }

                $vehicleNotifications->push((object)[
                    'id'      => $item->id, // 🔥 WAJIB
                    'jenis'   => 'pemeliharaan',
                    'pesan'   => $pesan,
                    'tanggal' => $item->created_at ?? $item->updated_at,
                ]);
            }

            foreach ($kendaraan as $k) {
                // ================= PAJAK =================
                if ($user->role === 'Administrator') {
                    if ($k->tanggal_pajak_berkala) {
                        $today = Carbon::today();

                        // set ke tahun sekarang
                        $jatuhTempo = Carbon::parse($k->tanggal_pajak_berkala)
                            ->year($today->year);

                        $sudahBayar = $k->riwayatPajak->isNotEmpty();

                        if ($sudahBayar) {
                            // kalau sudah bayar → loncat ke tahun depan
                            $target = $jatuhTempo->copy()->addYear();
                            $selisih = $today->diffInDays($target);
                        } else {
                            if ($jatuhTempo->lt($today)) {
                                $selisih = -$jatuhTempo->diffInDays($today);
                            } else {
                                $selisih = $today->diffInDays($jatuhTempo);
                            }
                        }

                        // ================= FILTER NOTIF =================
                        if (!$sudahBayar && $selisih <= 7) {
                            if ($selisih > 0) {
                                $pesan = "Pajak {$k->nama_kendaraan} <strong>({$k->nomor_polisi})</strong>: {$selisih} hari lagi";
                            } elseif ($selisih == 0) {
                                $pesan = "Pajak {$k->nama_kendaraan} <strong>({$k->nomor_polisi})</strong>: hari ini";
                            } else {
                                $pesan = "Pajak {$k->nama_kendaraan} <strong>({$k->nomor_polisi})</strong>: terlambat " . abs($selisih) . " hari";
                            }

                            $vehicleNotifications->push((object)[
                                'id'      => null, // 🔥 penting
                                'jenis'   => 'pajak',
                                'pesan'   => $pesan,
                                'tanggal' => $jatuhTempo,
                            ]);
                        }
                    }
                }

                // ================= SERVIS =================
                if ($user->role === 'Staff') {
                    if ($k->riwayatServis->isNotEmpty() && $k->rentang_waktu_servis) {
                        $last = Carbon::parse($k->riwayatServis->first()->tanggal_servis);
                        $next = $last->copy()->addMonths($k->rentang_waktu_servis);

                        $today = Carbon::today();
                        $selisih = (int) $today->diffInDays($next, false);

                        if ($selisih <= 7) {
                            if ($selisih > 0) {
                                $pesan = "Estimasi servis {$k->nama_kendaraan} <strong>({$k->nomor_polisi})</strong>: {$selisih} hari lagi";
                            } elseif ($selisih == 0) {
                                $pesan = "Estimasi servis {$k->nama_kendaraan} <strong>({$k->nomor_polisi})</strong>: hari ini";
                            } else {
                                $pesan = "Estimasi servis {$k->nama_kendaraan} <strong>({$k->nomor_polisi})</strong>: terlambat " . abs($selisih) . " hari";
                            }

                            $vehicleNotifications->push((object)[
                                'id'      => null, // 🔥 penting
                                'jenis'   => 'servis',
                                'pesan'   => $pesan,
                                'tanggal' => $next,
                            ]);
                        }
                    }
                }
            }

            // SORT terbaru
            $vehicleNotifications = $vehicleNotifications
                ->sortByDesc(function ($item) {
                    // PRIORITAS JENIS
                    $priorityMap = [
                        'pemeliharaan' => 3,
                        'pajak' => 2,
                        'servis' => 2, // pajak & servis setara
                    ];

                    $priority = $priorityMap[$item->jenis] ?? 0;

                    // PEMELIHARAAN → TERBARU DI ATAS
                    if ($item->jenis === 'pemeliharaan') {
                        return $priority * 10000000000 + strtotime($item->tanggal);
                    }

                    // PAJAK & SERVIS → PALING LAMA DI ATAS
                    $today = \Carbon\Carbon::today();
                    $tanggal = \Carbon\Carbon::parse($item->tanggal);

                    // selisih negatif = terlambat → harus paling atas
                    $selisih = (int) $today->diffInDays($tanggal, false);

                    // balik logika → makin kecil (negatif besar) makin tinggi
                    return $priority * 10000000000 - $selisih;
                })
                ->take(10)
                ->values();

            $view->with('vehicleNotifications', $vehicleNotifications);
        });
    }
}
