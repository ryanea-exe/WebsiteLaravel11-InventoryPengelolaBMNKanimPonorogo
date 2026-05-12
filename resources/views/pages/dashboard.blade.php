@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

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
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
        <div class="bg-gradient-to-br from-blue-800 via-blue-500 to-blue-600 text-white rounded-lg shadow-sm p-6 border-l-4 border-white/30">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Barang Persediaan</p>
                    <h3 class="text-3xl font-bold">{{ $totalBarangPersediaan }}</h3>
                    <p class="text-green-200 text-xs mt-1">
                        <i class="fas fa-arrow-up"></i> 12% dari bulan lalu
                    </p>
                </div>
                <button onclick="window.location='{{ route('barang.index') }}'"
                    class="w-14 h-14 bg-white/20 backdrop-blur rounded-full flex items-center justify-center hover:bg-white/30 transition duration-200">
                    <i class="fas fa-box text-white text-2xl"></i>
                </button>
            </div>
        </div>
        <div class="bg-gradient-to-br from-blue-800 via-blue-500 to-blue-600 text-white rounded-lg shadow-sm p-6 border-l-4 border-white/30">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Barang BMN yang Tersedia</p>
                    <h3 class="text-3xl font-bold">{{ $totalBarangBMN }}</h3>
                    <p class="text-green-200 text-xs mt-1">
                        <i class="fas fa-arrow-up"></i> 12% dari bulan lalu
                    </p>
                </div>
                <button onclick="window.location='{{ route('barang-bmn.index') }}'"
                    class="w-14 h-14 bg-white/20 backdrop-blur rounded-full flex items-center justify-center hover:bg-white/30 transition duration-200">
                    <i class="fas fa-archive text-white text-2xl"></i>
                </button>
            </div>
        </div>
        <!--
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-purple-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Jenis Barang (SKU)</p>
                    <h3 class="text-3xl font-bold">{{ $jumlahBarangUnik }}</h3>
                    <p class="text-gray-500 text-xs mt-1">
                        Total variasi barang
                    </p>
                </div>
                <div class="w-14 h-14 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-boxes text-purple-600 text-2xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Barang Masuk</p>
                    <h3 class="text-3xl font-bold">{{ $barangMasuk }}</h3>
                    <p class="text-gray-500 text-xs mt-1">Bulan ini</p>
                </div>
                <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-arrow-down text-green-600 text-2xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-orange-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Barang Keluar</p>
                    <h3 class="text-3xl font-bold">{{ $barangKeluar }}</h3>
                    <p class="text-gray-500 text-xs mt-1">Bulan ini</p>
                </div>
                <div class="w-14 h-14 bg-orange-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-arrow-up text-orange-600 text-2xl"></i>
                </div>
            </div>
        </div>
        -->
    </div>

    @if(auth()->user()->role === 'Administrator')
    <div class="bg-gradient-to-r from-blue-800 via-blue-700 to-blue-400 text-white rounded-lg shadow-sm p-6 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 flex-shrink-0 bg-white/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-file-signature text-white text-2xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold mb-1">
                    Ingin melihat riwayat pengajuan barang yang masuk?
                </h2>
                <p class="text-blue-100 text-sm">
                    Silakan lihat riwayat pengajuan barang persediaan atau barang BMN yang masuk<br>
                    dengan cara klik tombol pada sebelah kanan.
                </p>
            </div>
        </div>

        <div class="flex gap-3">
            <button onclick="window.location='{{ route('pengajuan.riwayat_admin') }}'"
                class="px-4 py-2 bg-white text-blue-600 rounded-lg font-medium hover:bg-blue-100 transition">
                <i class="fas fa-box mr-1"></i> Persediaan
            </button>
            <button onclick="window.location='{{ route('pengajuan-bmn.riwayat_admin') }}'"
                class="px-4 py-2 bg-white text-blue-600 rounded-lg font-medium hover:bg-blue-100 transition">
                <i class="fas fa-archive mr-1"></i> BMN
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Semua Permintaan -->
        <!--
        <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Semua Pengajuan Persediaan</p>
                    <h3 class="text-3xl font-bold">{{ $permintaanSemua }}</h3>
                    <p class="text-gray-500 text-xs mt-1">Bulan ini</p>
                </div>
                <div class="w-12 h-12 flex-shrink-0 aspect-square bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-clipboard-list text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>
        -->
        <!-- Permintaan Diajukan -->
        <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Pengajuan Persediaan Diajukan</p>
                    <h3 class="text-3xl font-bold">{{ $permintaanDiajukan }}</h3>
                    <p class="text-gray-500 text-xs mt-1">Bulan ini</p>
                </div>
                <button onclick="window.location='{{ route('pengajuan.riwayat_admin') }}'"
                    class="w-12 h-12 flex-shrink-0 aspect-square bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-paper-plane text-yellow-600 text-xl"></i>
                </button>
            </div>
        </div>
        <!-- Permintaan Disetujui -->
        <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Pengajuan Persediaan Disetujui</p>
                    <h3 class="text-3xl font-bold">{{ $permintaanDisetujui }}</h3>
                    <p class="text-gray-500 text-xs mt-1">Bulan ini</p>
                </div>
                <button onclick="window.location='{{ route('pengajuan.riwayat_admin') }}'"
                    class="w-12 h-12 flex-shrink-0 aspect-square bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </button>
            </div>
        </div>
        <!-- Permintaan Disetujui Sebagian -->
        <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Pengajuan Persediaan Disetujui Sebagian</p>
                    <h3 class="text-3xl font-bold">{{ $permintaanSebagian }}</h3>
                    <p class="text-gray-500 text-xs mt-1">Bulan ini</p>
                </div>
                <button onclick="window.location='{{ route('pengajuan.riwayat_admin') }}'"
                    class="w-12 h-12 flex-shrink-0 aspect-square bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-tasks text-purple-600 text-xl"></i>
                </button>
            </div>
        </div>
        <!-- Permintaan Ditolak -->
        <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Pengajuan Persediaan Ditolak</p>
                    <h3 class="text-3xl font-bold">{{ $permintaanDitolak }}</h3>
                    <p class="text-gray-500 text-xs mt-1">Bulan ini</p>
                </div>
                <button onclick="window.location='{{ route('pengajuan.riwayat_admin') }}'"
                class="w-12 h-12 flex-shrink-0 aspect-square bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Semua Permintaan -->
        <!--
        <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Semua Permintaan BMN</p>
                    <h3 class="text-3xl font-bold">{{ $permintaanBMNSemua }}</h3>
                    <p class="text-gray-500 text-xs mt-1">Bulan ini</p>
                </div>
                <div class="w-12 h-12 flex-shrink-0 aspect-square bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-clipboard-list text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
        -->
        <!-- Permintaan Diajukan -->
        <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Pengajuan BMN Diajukan</p>
                    <h3 class="text-3xl font-bold">{{ $permintaanBMNDiajukan }}</h3>
                    <p class="text-gray-500 text-xs mt-1">Bulan ini</p>
                </div>
                <button onclick="window.location='{{ route('pengajuan-bmn.riwayat_admin') }}'"
                    class="w-12 h-12 flex-shrink-0 aspect-square bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-paper-plane text-yellow-600 text-xl"></i>
                </button>
            </div>
        </div>
        <!-- Permintaan Disetujui -->
        <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Pengajuan BMN Disetujui</p>
                    <h3 class="text-3xl font-bold">{{ $permintaanBMNDisetujui }}</h3>
                    <p class="text-gray-500 text-xs mt-1">Bulan ini</p>
                </div>
                <button onclick="window.location='{{ route('pengajuan-bmn.riwayat_admin') }}'"
                    class="w-12 h-12 flex-shrink-0 aspect-square bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </button>
            </div>
        </div>
        <!-- Permintaan Disetujui Sebagian -->
        <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Pengajuan BMN Disetujui Sebagian</p>
                    <h3 class="text-3xl font-bold">{{ $permintaanBMNSebagian }}</h3>
                    <p class="text-gray-500 text-xs mt-1">Bulan ini</p>
                </div>
                <button onclick="window.location='{{ route('pengajuan-bmn.riwayat_admin') }}'"
                    class="w-12 h-12 flex-shrink-0 aspect-square bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-tasks text-purple-600 text-xl"></i>
                </button>
            </div>
        </div>
        <!-- Permintaan Ditolak -->
        <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Pengajuan BMN Ditolak</p>
                    <h3 class="text-3xl font-bold">{{ $permintaanDitolak }}</h3>
                    <p class="text-gray-500 text-xs mt-1">Bulan ini</p>
                </div>
                <button onclick="window.location='{{ route('pengajuan-bmn.riwayat_admin') }}'"
                    class="w-12 h-12 flex-shrink-0 aspect-square bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </button>
            </div>
        </div>
    </div>
    @endif

    @if(auth()->user()->role === 'Staff')
    <div class="bg-gradient-to-r from-blue-800 via-blue-700 to-blue-400 text-white rounded-lg shadow-sm p-6 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 flex-shrink-0 bg-white/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-file-signature text-white text-2xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold mb-1">
                    Ingin mengajukan permintaan barang?
                </h2>
                <p class="text-blue-100 text-sm">
                    Silakan ajukan permintaan barang persediaan atau barang BMN sesuai kebutuhan Anda<br>
                    dengan cara klik tombol pada sebelah kanan.
                </p>
            </div>
        </div>

        <div class="flex gap-3">
            <button onclick="window.location='{{ route('pengajuan.index') }}'"
                class="px-4 py-2 bg-white text-blue-600 rounded-lg font-medium hover:bg-blue-100 transition">
                <i class="fas fa-box mr-1"></i> Persediaan
            </button>
            <button onclick="window.location='{{ route('pengajuan-bmn.index') }}'"
                class="px-4 py-2 bg-white text-blue-600 rounded-lg font-medium hover:bg-blue-100 transition">
                <i class="fas fa-archive mr-1"></i> BMN
            </button>
        </div>
    </div>
    @endif

    <!-- Recent Transactions -->
    <div class="bg-white rounded-lg shadow-sm p-4">
        @if(auth()->user()->role === 'Administrator')
        <h3 class="text-lg font-bold mb-4 text-gray-800">
            <i class="fas fa-history mr-2"></i>Transaksi Terakhir
        </h3>
        @else
        <h3 class="text-lg font-bold mb-4 text-gray-800">
            <i class="fas fa-history mr-2"></i>Transaksi Terakhir Saya
        </h3>
        @endif
        <div class="overflow-x-auto" style="box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);">
            <table class="w-full rounded-lg overflow-hidden">
                <thead class="bg-gray-200 border-b border-gray-300">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider w-8">No</th>
                        <th class="px-2 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider w-60">Tanggal</th>
                        <th class="px-2 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Jenis Transaksi</th>
                        <th class="px-2 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kategori</th>
                        <th class="px-2 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kode Barang</th>
                        <th class="px-2 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama Barang</th>
                        <th class="px-2 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-300 border-b border-gray-300">
                    @forelse($transaksiTerakhir as $trx)
                    <tr class="hover:bg-gray-100">
                        <td class="px-4 py-2 text-sm text-gray-700">
                            {{ $loop->iteration }}
                        </td>
                        <td class="px-2 py-2 text-sm text-gray-700">
                            {{ \Carbon\Carbon::parse($trx->tanggal)->format('d M Y, H:i') }}
                        </td>
                        <td class="px-2 py-2">
                            @if($trx->jenis === 'Masuk')
                                <span class="px-3 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">
                                    <i class="fas fa-arrow-down text-[10px]"></i> Masuk
                                </span>
                            @else
                                <span class="px-3 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">
                                    <i class="fas fa-arrow-up text-[10px]"></i> Keluar
                                </span>
                            @endif
                        </td>
                        <td class="px-2 py-2 text-sm">
                            @if($trx->kategori === 'Persediaan')
                                <span class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded">
                                    Persediaan
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs bg-purple-100 text-purple-700 rounded">
                                    BMN
                                </span>
                            @endif
                        </td>
                        <td class="px-2 py-2 text-sm text-gray-700">
                            @foreach($trx->details as $detail)
                                <div>{{ $detail->barang->kode_barang ?? '-' }}</div>
                            @endforeach
                        </td>
                        <td class="px-2 py-2 text-sm text-gray-700">
                            @foreach($trx->details as $detail)
                            <div>
                                @if($trx->kategori === 'BMN')
                                    {{ $detail->barang->nama_barang ?? '-' }} ({{ $detail->barang->merk_type }})
                                @else
                                    {{ $detail->barang->nama_barang ?? '-' }}
                                @endif
                            </div>
                            @endforeach
                        </td>
                        <td class="px-2 py-2 text-sm text-gray-700">
                            @if($trx->jenis === 'Masuk')
                                @foreach($trx->details as $detail)
                                <div class="text-green-600 font-semibold">
                                    {{ $detail->jumlah }} {{ $detail->barang->satuan ?? '' }}
                                </div>
                            @endforeach
                            @else
                                @foreach($trx->details as $detail)
                                <div class="text-red-600 font-semibold">
                                    {{ $detail->jumlah_disetujui }} {{ $detail->barang->satuan ?? '' }}
                                </div>
                                @endforeach
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-2 py-4 text-center text-gray-700 text-sm italic">
                            Belum ada riwayat transaksi
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Chart Section -->
    @if(auth()->user()->role === 'Administrator')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-sm p-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-th-large mr-2"></i>Kategori Barang
                </h3>
                <a href="{{ route('kategori.index') }}"
                    class="text-sm text-blue-600 hover:text-blue-800 hover:underline font-medium transition">
                    Lihat Semua →
                </a>
            </div>

            @php
                $totalKategoriBarang = $kategoriStat->sum('total');
                $colors = ['bg-blue-600', 'bg-green-600', 'bg-orange-600', 'bg-purple-600', 'bg-red-600'];
            @endphp

            <div class="space-y-3">
                @foreach($kategoriStat as $index => $item)
                    @php
                        $percentage = $totalKategoriBarang > 0
                            ? round(($item->total / $totalKategoriBarang) * 100)
                            : 0;
                    @endphp

                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-700 font-medium">
                                {{ $item->nama ?? 'Kategori tidak ditemukan' }}
                            </span>
                            <span class="text-gray-600">
                                {{ $item->total }} item
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="{{ $colors[$index % count($colors)] }} h-2 rounded-full"
                                style="width: {{ $percentage }}%">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

<script>
    // waktu pesan alert
    setTimeout(function () {
        const alert = document.getElementById('alert-message');
        if (alert) {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }
    }, 5000); // 5 detik
</script>

@endsection
