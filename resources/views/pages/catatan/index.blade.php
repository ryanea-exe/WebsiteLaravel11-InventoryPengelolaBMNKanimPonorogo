@extends('layouts.app')

@section('title', 'Kirim Catatan')

@section('page-title', 'Kirim Catatan')
@section('breadcrumb')
    <p class="text-gray-800 cursor-pointer">
        Kirim Catatan
    </p>
@endsection

@section('content')

@php 
    \Carbon\Carbon::setLocale('id'); 
@endphp

<div class="space-y-4">
    <form action="{{ route('catatan.store') }}" method="POST"
        class="bg-white rounded-lg shadow-sm">
        @csrf

        <!-- HEADER -->
        <div class="px-4 py-4 border-b mb-4">
            <h1 class="text-xl font-bold text-gray-800">
                <i class="fas fa-sticky-note mr-3"></i>Form Kirim Catatan ke Admin
            </h1>
        </div>

        <!-- BODY -->
        <div class="px-4 py-2">
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

            <!-- TANGGAL -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Tanggal <span class="text-red-500">*</span>
                </label>
                <input type="text" value="{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}"
                    class="w-full border rounded-lg px-4 py-2 bg-gray-100 cursor-not-allowed"
                    disabled>
                <input type="hidden" name="tanggal" value="{{ now() }}">
            </div>
            <!-- CATATAN -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Isi Catatan <span class="text-red-500">*</span>
                </label>
                <textarea name="catatan" rows="4"
                    class="w-full border rounded-lg px-4 py-2"
                    placeholder="Tulis catatan untuk admin..."
                    required></textarea>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="flex justify-end gap-2 px-4 py-4 border-t border-gray-200">
            <button type="reset"
                class="px-3 py-1.5 font-normal text-sm border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100">
                Reset
            </button>
            <button type="submit"
                class="px-3 py-1.5 font-normal text-sm rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                <i class="fas fa-paper-plane mr-1"></i> Kirim Catatan
            </button>
        </div>
    </form>
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
