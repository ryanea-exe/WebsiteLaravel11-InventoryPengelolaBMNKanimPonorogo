@extends('layouts.app')

@section('title', 'Detail Pengajuan Saya')

@section('page-title', 'Detail Pengajuan Persediaan Saya')
@section('breadcrumb')
    <p class="text-gray-800 cursor-pointer">
        Detail Pengajuan Persediaan Saya
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
                Detail Pengajuan Barang Persediaan - {{ $pengajuan->kode_pengajuan }}
            </h2>
        </div>

        <span>Status:
            <span class="px-4 py-1.5 text-sm rounded-full font-semibold
                @if($pengajuan->status === 'Disetujui')
                    bg-green-100 text-green-700
                @elseif($pengajuan->status === 'Disetujui Sebagian')
                    bg-blue-100 text-blue-700
                @elseif($pengajuan->status === 'Ditolak')
                    bg-red-100 text-red-700
                @else
                    bg-yellow-100 text-yellow-700
                @endif">
                {{ $pengajuan->status }}
            </span>
        </span>
    </div>

    <!-- INFO GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 px-4">
        <div>
            <p class="text-sm text-gray-500 mb-1">Tanggal Pengajuan</p>
            <p class="font-medium text-gray-800">
                {{ \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->translatedFormat('d F Y') }}
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
        <div>
            <p class="text-sm text-gray-500 mb-1">Keperluan</p>
            <p class="bg-gray-50 rounded-lg p-3 text-gray-800 text-sm">
                {{ $pengajuan->keperluan }}
            </p>
        </div>
        <div>
            <p class="text-sm text-gray-500 mb-1">Tanggal Proses</p>
            <p class="font-medium text-gray-800">
                {{ $pengajuan->tanggal_proses 
                    ? \Carbon\Carbon::parse($pengajuan->tanggal_proses)->translatedFormat('d F Y')
                    : '-' }}
            </p>
        </div>
        <div>
            <p class="text-sm text-gray-500 mb-1">Keterangan Proses</p>
            <p class="bg-gray-50 rounded-lg p-3 text-gray-800 text-sm">
                {{ $pengajuan->keterangan_proses ?? '-'}}
            </p>
        </div>
    </div>

    <!-- BARANG -->
    <div>
        <p class="text-sm text-gray-500 px-4 mb-4">Barang</p>
    </div>

    <!-- TABEL BARANG -->
    <div class="overflow-x-auto px-4 mb-4" style="margin-top:-4px">
        <table class="w-full text-gray-700 border border-gray-300">
            <thead class="bg-gray-200 border-b border-gray-300">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Kode Barang</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Nama Barang</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Jumlah Permintaan</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Jumlah Disetujui</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-300">
            @foreach($pengajuan->details as $detail)
                @php
                    $stok = $detail->barang->jumlah ?? 0;
                    $jumlah = $detail->jumlah;
                    $stokKurang = $stok < $jumlah;
                @endphp

                <tr class="{{ $stokKurang ? 'bg-red-50' : '' }}">
                    <td class="px-4 py-2 text-sm">
                        {{ $detail->barang->kode_barang }}
                    </td>
                    <td class="px-4 py-2 text-sm">
                        {{ $detail->barang->nama_barang }}
                    </td>
                    <td class="px-4 py-2 text-sm">
                        {{ $jumlah }} {{ $detail->barang->satuan ?? '' }}
                    </td>
                    <!-- STATUS -->
                    <td class="px-4 py-2 text-sm">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            @if($detail->status === 'Disetujui')
                                bg-green-100 text-green-700
                            @elseif($detail->status === 'Ditolak')
                                bg-red-100 text-red-700
                            @else
                                bg-yellow-100 text-yellow-700
                            @endif">
                            {{ $detail->status ?? 'Diajukan' }}
                        </span>
                    </td>
                    <td class="px-4 py-2 text-sm">
                        {{ $detail->jumlah_disetujui }} {{ $detail->barang->satuan ?? '' }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <!-- FOOTER -->
    <div class="px-4 py-4 flex justify-end gap-2 border-t border-gray-200">
        <button onclick="window.location='{{ route('pengajuan.riwayat_user') }}'"
            class="px-3 py-1.5 border rounded-lg text-gray-700 hover:bg-gray-100 text-sm font-normal">
            Kembali
        </button>
    </div>
</div>

@endsection