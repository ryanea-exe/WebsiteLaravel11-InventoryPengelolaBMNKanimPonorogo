@extends('layouts.app')

@section('title', 'Riwayat Catatan')

@section('page-title', 'Riwayat Catatan')
@section('breadcrumb')
    <p class="text-gray-800 cursor-pointer">
        Riwayat Catatan
    </p>
@endsection

@section('content')

<div class="space-y-4">
    <div class="bg-white rounded-lg shadow-sm p-4">
        <!-- HEADER -->
        <div class="flex justify-between mb-4">
            <h1 class="text-xl font-bold text-gray-800">
                <i class="fas fa-inbox mr-3"></i>Riwayat Catatan Staff
            </h1>
        </div>

        <!-- ALERT -->
        @if(session('success'))
        <div id="alert-message"
            class="mb-2 p-2 rounded-lg bg-green-100 text-green-800 border border-green-300">
            <strong>Sukses!</strong> {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div id="alert-message"
            class="mb-2 p-2 rounded-lg bg-red-100 text-red-800 border border-red-300">
            <strong>Gagal!</strong> {{ session('error') }}
        </div>
        @endif

        <!-- TABLE -->
        <div class="overflow-x-auto">
            <table id="dataTable" class="strip text-gray-700 w-full row-border">
                <thead class="bg-gray-200 border-b border-gray-300">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase w-8">No</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nama User</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Catatan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-300">
                    @foreach($catatan as $index => $item)
                    <tr class="hover:bg-gray-100">
                        <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-800">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-800">
                            {{ $item->tanggal ? $item->tanggal->format('d M Y, H:i') : '-' }}
                        </td>
                        <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-800">
                            {{ $item->user->name ?? '-' }}
                        </td>
                        <td class="px-6 py-2 text-sm text-gray-800">
                            {{ $item->catatan }}
                        </td>
                        <td class="px-6 py-2 text-sm">
                            <span class="text-gray-400 italic">-</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- AUTO HIDE ALERT --}}
<script>
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
