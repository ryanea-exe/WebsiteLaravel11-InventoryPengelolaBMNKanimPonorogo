@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

@php 
    \Carbon\Carbon::setLocale('id'); 
@endphp

<div class="space-y-4">
    <!-- ALERT -->
    @if(session('success'))
    <div id="alert-message"
        class="mb-4 p-2 rounded-lg bg-green-100 text-green-800 border border-green-300">
        <strong>Sukses!</strong> {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div id="alert-message"
        class="mb-4 p-2 rounded-lg bg-red-100 text-red-800 border border-red-300">
        <strong>Gagal!</strong> {{ session('error') }}
    </div>
    @endif

    <!-- Statistics Cards -->
    @if(auth()->user()->role === 'Administrator')
    <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-4 gap-6">
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
    @endif
        <!-- 1. TOTAL KENDARAAN (BIRU) -->
        <div class="bg-gradient-to-br from-blue-800 via-blue-500 to-blue-600 text-white rounded-lg shadow-sm p-6 border-l-4 border-white/30">
            <div class="flex items-center justify-between">
                <div>
                    @if(auth()->user()->role === 'Administrator')
                    <p class="text-blue-100 text-sm font-medium">Total Kendaraan</p>
                    @else
                    <p class="text-blue-100 text-sm font-medium">Total Kendaraan Seksi Saya</p>
                    @endif
                    <h3 class="text-3xl font-bold">{{ $totalKendaraan }}</h3>
                    <p class="text-blue-200 text-xs mt-1">
                        <i class="fas fa-car"></i> Data kendaraan aktif
                    </p>
                </div>
                <button onclick="window.location='{{ route('pemeliharaan.kendaraan.index') }}'"
                    class="w-14 h-14 aspect-square bg-white/20 backdrop-blur rounded-full flex items-center justify-center hover:bg-white/30 transition duration-200">
                    <i class="fas fa-car text-white text-2xl"></i>
                </button>
            </div>
        </div>
        @if(auth()->user()->role === 'Administrator')
        <!-- 2. PAJAK SUDAH DIBAYAR (HIJAU) -->
        <div class="bg-gradient-to-br from-green-800 via-green-500 to-green-600 text-white rounded-lg shadow-sm p-6 border-l-4 border-white/30">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Pajak Sudah Dibayar</p>
                    <h3 class="text-3xl font-bold">{{ $pajakSudahDibayar }}</h3>
                    <p class="text-green-200 text-xs mt-1">
                        <i class="fas fa-check-circle"></i> Kendaraan aman
                    </p>
                </div>
                <button onclick="window.location='{{ route('pemeliharaan.riwayat_pajak.index') }}'"
                    class="w-14 h-14 aspect-square bg-white/20 backdrop-blur rounded-full flex items-center justify-center hover:bg-white/30 transition duration-200">
                    <i class="fas fa-check text-white text-2xl"></i>
                </button>
            </div>
        </div>
        <!-- 3. PAJAK BELUM DIBAYAR (MERAH) -->
        <div class="bg-gradient-to-br from-red-800 via-red-500 to-red-600 text-white rounded-lg shadow-sm p-6 border-l-4 border-white/30">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm font-medium">Pajak Belum Dibayar</p>
                    <h3 class="text-3xl font-bold">{{ $pajakBelumDibayar }}</h3>
                    <p class="text-red-200 text-xs mt-1">
                        <i class="fas fa-exclamation-triangle"></i> Perlu segera dibayar
                    </p>
                </div>
                <button onclick="window.location='{{ route('pemeliharaan.riwayat_pajak.index') }}'"
                    class="w-14 h-14 aspect-square bg-white/20 backdrop-blur rounded-full flex items-center justify-center hover:bg-white/30 transition duration-200">
                    <i class="fas fa-exclamation-circle text-white text-2xl"></i>
                </button>
            </div>
        </div>
        @endif
        <!-- 4. SERVIS BULAN INI (KUNING) -->
        <div class="bg-gradient-to-br from-yellow-700 via-yellow-500 to-yellow-600 text-white rounded-lg shadow-sm p-6 border-l-4 border-white/30">
            <div class="flex items-center justify-between">
                <div>
                    @if(auth()->user()->role === 'Administrator')
                        <p class="text-yellow-100 text-sm font-medium">Servis Bulan Ini</p>
                        <h3 class="text-3xl font-bold">{{ $servisCount }}</h3>
                        <p class="text-yellow-200 text-xs mt-1">
                            <i class="fas fa-calendar-alt"></i> Bulan {{ \Carbon\Carbon::now()->translatedFormat('F') }}
                        </p>
                    @else
                        <p class="text-yellow-100 text-sm font-medium">Servis Kendaraan Tahun Ini</p>
                        <h3 class="text-3xl font-bold">{{ $servisCount }}</h3>
                        <p class="text-yellow-200 text-xs mt-1">
                            <i class="fas fa-calendar-alt"></i> Tahun {{ \Carbon\Carbon::now()->year }}
                        </p>
                    @endif
                </div>
                <button onclick="window.location='{{ route('pemeliharaan.riwayat_servis.index') }}'"
                    class="w-14 h-14 aspect-square bg-white/20 backdrop-blur rounded-full flex items-center justify-center hover:bg-white/30 transition duration-200">
                    <i class="fas fa-tools text-white text-2xl"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- CARD UTAMA -->
    @if(auth()->user()->role === 'Administrator')
    <div class="bg-gradient-to-r from-blue-800 via-blue-700 to-blue-400 text-white rounded-lg shadow-sm p-6 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 flex-shrink-0 bg-white/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-tools text-white text-2xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold mb-1">
                    Ingin melihat data kendaraan & riwayat pengajuan pemeliharaan yang masuk?
                </h2>
                <p class="text-blue-100 text-sm">
                    Silakan lihat data kendaraan dan riwayat pengajuan pemeliharaan kendaraan yang masuk
                    dengan cara klik tombol pada sebelah kanan.
                </p>
            </div>
        </div>
        <div class="flex gap-3 flex-nowrap">
            <button onclick="window.location='{{ route('pemeliharaan.kendaraan.index') }}'"
                class="px-4 py-2 bg-white text-blue-600 rounded-lg font-medium hover:bg-blue-100 transition whitespace-nowrap flex items-center gap-2">
                <i class="fas fa-car"></i>
                <span>Data Kendaraan</span>
            </button>
            <button onclick="window.location='{{ route('pemeliharaan.riwayat_admin') }}'"
                class="px-4 py-2 bg-white text-blue-600 rounded-lg font-medium hover:bg-blue-100 transition whitespace-nowrap flex items-center gap-2">
                <i class="fas fa-history"></i>
                <span>Pengajuan Pemeliharaan</span>
            </button>
        </div>
    </div>
    @else
    <div class="bg-gradient-to-r from-blue-800 via-blue-700 to-blue-400 text-white rounded-lg shadow-sm p-6 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 flex-shrink-0 bg-white/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-file-signature text-white text-2xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold mb-1">
                    Ingin mengajukan pemeliharaan kendaraan seksi anda?
                </h2>
                <p class="text-blue-100 text-sm">
                    Silakan ajukan pemeliharaan kendaraan sesuai kebutuhan anda 
                    dengan cara klik tombol pada sebelah kanan.
                </p>
            </div>
        </div>
        <div class="flex gap-3 flex-nowrap">
            <button onclick="window.location='{{ route('pemeliharaan.index') }}'"
                class="px-4 py-2 bg-white text-blue-600 rounded-lg font-medium hover:bg-blue-100 transition whitespace-nowrap flex items-center gap-2">
                <i class="fas fa-file-signature"></i>
                <span>Pengajuan Pemeliharaan</span>
            </button>
        </div>
    </div>
    @endif

    <!-- TABEL -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- ================= TABEL 1 ================= --}}
        <div class="bg-white rounded-lg shadow-sm p-4">
            @if(auth()->user()->role === 'Administrator')
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-file-alt mr-2"></i>Riwayat Pengajuan Terakhir
                </h3>
                <a href="{{ route('pemeliharaan.riwayat_admin') }}"
                    class="text-xs text-blue-600 hover:text-blue-800 hover:underline font-medium transition">
                    Lihat Semua →
                </a>
            </div>
            @else
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-file-alt mr-2"></i>Riwayat Pengajuan Seksi Saya Terakhir
                </h3>
                <a href="{{ route('pemeliharaan.riwayat_user') }}"
                    class="text-xs text-blue-600 hover:text-blue-800 hover:underline font-medium transition">
                    Lihat Semua →
                </a>
            </div>
            @endif
            <div class="overflow-x-auto" style="box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);">
                <table class="w-full rounded-lg overflow-hidden text-sm">
                    <thead class="bg-gray-200 border-b border-gray-300">
                        <tr>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tanggal</th>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kendaraan</th>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-300 border-b border-gray-300">
                        @forelse($riwayatPengajuanTerakhir as $item)
                        <tr class="hover:bg-gray-100">
                            <td class="px-2 py-1.5 text-xs text-gray-700">{{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d F Y') }}</td>
                            <td class="px-2 py-1.5 text-xs text-gray-700">{{ $item->kendaraan->nama_kendaraan ?? '-' }} <b>({{ $item->kendaraan->nomor_polisi ?? '-' }})</b></td>
                            <td class="px-2 py-1.5 text-xs text-gray-700">{{ ucfirst($item->status) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-2 py-3.5 text-center text-gray-700 text-sm italic">Belum ada data pengajuan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ================= TABEL 2 ================= --}}
        @if(auth()->user()->role === 'Administrator')
        <div class="bg-white rounded-lg shadow-sm p-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Pajak Terdekat / Terlambat
                </h3>
                <a href="{{ route('pemeliharaan.kendaraan.index') }}"
                    class="text-xs text-blue-600 hover:text-blue-800 hover:underline font-medium transition">
                    Lihat Semua →
                </a>
            </div>
            <div class="overflow-x-auto" style="box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);">
                <table class="w-full rounded-lg overflow-hidden text-sm">
                    <thead class="bg-gray-200 border-b border-gray-300">
                        <tr>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kendaraan</th>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Pajak</th>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-300 border-b border-gray-300">
                        @forelse($kendaraanPrioritasPajak as $k)
                        <tr class="hover:bg-gray-100">
                            <td class="px-2 py-1.5 text-xs text-gray-700">{{ $k->nama_kendaraan }} <b>({{ $k->nomor_polisi }})</b></td>
                            <td class="px-2 py-1.5 text-xs text-gray-700">{{ \Carbon\Carbon::parse($k->tanggal_pajak_berkala)->translatedFormat('d F') }}</td>
                            <td class="px-2 py-1.5 text-xs text-gray-700
                                @if($k->selisih < 0) text-red-600
                                @elseif($k->selisih <= 7) text-orange-500
                                @else text-gray-500
                                @endif
                            ">
                                @if($k->selisih > 0)
                                    {{ $k->selisih }} hari lagi
                                @elseif($k->selisih == 0)
                                    Hari ini
                                @else
                                    Terlambat {{ abs($k->selisih) }} hari
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-2 py-3.5 text-center text-gray-700 text-sm italic">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
    // Auto hide alert
    setTimeout(function () {
        const alert = document.getElementById('alert-message');
        if (alert) {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }
    }, 5000);
</script>

@endsection
