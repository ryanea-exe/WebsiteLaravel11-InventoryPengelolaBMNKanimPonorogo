@extends('layouts.app')

@section('title', 'Detail Pengajuan')

@section('page-title', 'Detail Pengajuan Pemeliharaan')
@section('breadcrumb')
    <p class="text-gray-800 cursor-pointer">
        Detail Pengajuan Pemeliharaan
    </p>
@endsection

@section('content')

@php
    \Carbon\Carbon::setLocale('id');
@endphp

<div class="bg-white rounded-lg shadow-sm space-y-4">
    <!-- HEADER -->
    <div class="flex items-center justify-between border-b px-4 py-4">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">
                Detail Pengajuan Pemeliharaan Kendaraan - {{ $pengajuan->kode_pengajuan }}
            </h2>
        </div>
        <span>Status:
            <span class="px-4 py-1.5 text-sm rounded-full font-semibold
                @if($pengajuan->status === 'Disetujui')
                    bg-green-100 text-green-700
                @elseif($pengajuan->status === 'Ditolak')
                    bg-red-100 text-red-700
                @else
                    bg-yellow-100 text-yellow-700
                @endif">
                {{ $pengajuan->status }}
            </span>
        </span>
    </div>

    <!-- ALERT -->
    @if(session('success'))
    <div class="px-4">
        <div id="alert-message"
            class="mb-2 p-2 rounded-lg bg-green-100 text-green-800 border border-green-300">
            <strong>Sukses!</strong> {{ session('success') }}
        </div>
    </div>
    @endif
    @if(session('error'))
    <div class="px-4">
        <div id="alert-message"
            class="mb-2 p-2 rounded-lg bg-red-100 text-red-800 border border-red-300">
            <strong>Gagal!</strong> {{ session('error') }}
        </div>
    </div>
    @endif

    <!-- INFO GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 px-4">
        <div>
            <p class="text-sm text-gray-500 mb-1">Tanggal Pengajuan</p>
            <p class="font-medium text-gray-800">
                {{ \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->translatedFormat('d F Y, H:i') }}
            </p>
        </div>
        <div>
            <p class="text-sm text-gray-500 mb-1">Kode Pengajuan</p>
            <p class="font-semibold text-blue-600">
                {{ $pengajuan->kode_pengajuan }}
            </p>
        </div>
        <div>
            <p class="text-sm text-gray-500 mb-1">Peminta</p>
            <p class="font-medium text-gray-800">
                {{ $pengajuan->user->name }}
            </p>
        </div>
        @if($pengajuan->status !== 'Diajukan')
        <div>
            <p class="text-sm text-gray-500 mb-1">Tanggal Proses</p>
            <p class="font-medium text-gray-800">
                {{ \Carbon\Carbon::parse($pengajuan->tanggal_proses)->translatedFormat('d F Y, H:i') }}
            </p>
        </div>
        <div>
            <p class="text-sm text-gray-500 mb-1">Keterangan Proses</p>
            <p class="bg-gray-50 rounded-lg p-3 text-gray-800 text-sm">
                {{ $pengajuan->keterangan_proses ?: '-' }}
            </p>
        </div>
        @endif
    </div>

    <!-- FORM UPDATE -->
    <div>
        <p class="text-sm text-gray-500 px-4 mb-4">Kendaraan</p>
    </div>

    <!-- TABEL KENDARAAN -->
    <div class="overflow-x-auto px-4 mb-4" style="margin-top:-4px">
        <table class="w-full text-gray-700 border border-gray-300">
            <thead class="bg-gray-200 border-b border-gray-300">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Nomor Polisi</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Nama Kendaraan</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Tahun</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-300">
                <tr>
                    <td class="px-4 py-2 text-sm">{{ $pengajuan->kendaraan->nomor_polisi }}</td>
                    <td class="px-4 py-2 text-sm">{{ $pengajuan->kendaraan->nama_kendaraan }}</td>
                    <td class="px-4 py-2 text-sm">{{ $pengajuan->kendaraan->tahun }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="px-4">
        <p class="text-sm text-gray-500 mb-2">Keperluan & Estimasi Biaya</p>
        <div class="bg-gray-50 rounded-lg p-4 text-sm text-gray-800 font-mono space-y-1">
            @php $total = 0; @endphp

            @foreach($pengajuan->details as $i => $d)
                @php $total += $d->estimasi_biaya; @endphp
                <div class="grid grid-cols-[30px_1fr_20px_120px]">
                    <div>{{ $i+1 }}.</div>
                    <div>{{ $d->keperluan }}</div>
                    <div class="text-center">:</div>
                    <div class="text-right">
                        Rp. {{ number_format($d->estimasi_biaya,0,',','.') }}
                    </div>
                </div>
            @endforeach

            <div class="border-t border-gray-300 my-2"></div>
            <div class="grid grid-cols-12 font-semibold">
                <div class="col-span-9 text-right pr-2">Total</div>
                <div class="col-span-3 text-right">
                    Rp. {{ number_format($total,0,',','.') }}
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="px-4 py-4 border-t flex justify-end items-center">
        <button onclick="window.location='{{ route('pemeliharaan.riwayat_user') }}'"
            class="px-3 py-1.5 border rounded-lg text-gray-700 hover:bg-gray-100 text-sm">
            Kembali
        </button>
    </div>
</div>

<script>
    function approvePengajuan() {
        if (!currentPengajuanId) return;

        document.getElementById('confirmTitle').innerText = 'Setujui Pengajuan';
        document.getElementById('confirmMessage').innerText = 'Apakah Anda yakin ingin menyetujui pengajuan ini?';

        const form = document.getElementById('confirmForm');
        form.action = `/pemeliharaan/${currentPengajuanId}/approve`;

        openModal('confirmModal');
    }

    function rejectPengajuan() {
        if (!currentPengajuanId) return;

        document.getElementById('confirmTitle').innerText = 'Tolak Pengajuan';
        document.getElementById('confirmMessage').innerText = 'Apakah Anda yakin ingin menolak pengajuan ini?';

        const form = document.getElementById('confirmForm');
        form.action = `/pemeliharaan/${currentPengajuanId}/reject`;

        openModal('confirmModal');
    }

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
