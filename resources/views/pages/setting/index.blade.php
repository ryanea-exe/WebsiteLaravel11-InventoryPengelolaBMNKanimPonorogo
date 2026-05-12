@extends('layouts.app')

@section('title', 'Pengaturan')

@section('page-title', 'Pengaturan')
@section('breadcrumb')
    <p class="text-gray-800 cursor-pointer">
        Pengaturan
    </p>
@endsection

@section('content')

<div class="space-y-4">
<div x-data="{ tab: '{{ request('tab','ui') }}' }" class="bg-white rounded-lg shadow-sm">
    <!-- TAB BUTTON -->
    <div class="border-b border-gray-200">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
            <!-- TAB UI -->
            <li class="mr-1">
                <button 
                    @click="tab='ui'"
                    :class="tab=='ui' 
                        ? 'border-blue-500 text-blue-600' 
                        : 'border-transparent text-gray-800 hover:text-blue-600 hover:border-blue-500'"
                    class="inline-block p-3 border-b-2">
                    <i class="fas fa-cog mr-1"></i> Pengaturan UI
                </button>
            </li>
            <!-- TAB PENANDATANGAN -->
            <li class="mr-1">
                <button 
                    @click="tab='penandatangan'"
                    :class="tab=='penandatangan' 
                        ? 'border-blue-500 text-blue-600' 
                        : 'border-transparent text-gray-800 hover:text-blue-600 hover:border-blue-500'"
                    class="inline-block p-3 border-b-2">
                    <i class="fas fa-user-tie mr-1"></i> Penandatangan
                </button>
            </li>
        </ul>
    </div>

    <div x-show="tab=='ui'" class="x-transition.opacity.duration.200ms">
        <form action="{{ route('setting.update') }}" method="POST" enctype="multipart/form-data"
            class="bg-white rounded-lg shadow-sm mb-4">
            @csrf

            <!-- Header -->
            <div class="px-4 py-4 border-b mb-4">
                <h1 class="text-xl font-bold text-gray-800">
                    <i class="fas fa-cog text-xl mr-3"></i>Pengaturan UI
                </h1>
            </div>

            <!-- Scrollable Field -->
            <div class="px-4 pb-2 mb-2 overflow-y-auto flex-1">
                <!-- ALERT -->
                @if(session('success_ui'))
                <div id="alert-message"
                    class="mb-2 p-2 rounded-lg bg-green-100 text-green-800 border border-green-300">
                    <strong>Sukses!</strong> {{ session('success_ui') }}
                </div>
                @endif
                @if(session('error_ui'))
                <div id="alert-message"
                    class="mb-2 p-2 rounded-lg bg-red-100 text-red-800 border border-red-300">
                    <strong>Gagal!</strong> {{ session('error_ui') }}
                </div>
                @endif
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-start">
                    <div class="md:col-span-2">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- ================= NAMA APLIKASI ================= -->
                            <div>
                                <h1 class="text-lg font-bold mb-2 text-gray-800">
                                    <i class="fas fa-rocket mr-1"></i> Nama Aplikasi
                                </h1>
                                <input type="text" name="nama_aplikasi"
                                    value="{{ $setting->nama_aplikasi ?? '' }}"
                                    class="w-full border rounded-lg px-4 py-2 mt-1">
                                <p class="text-sm text-gray-500 mt-2">
                                    Nama aplikasi akan tampil di sidebar / halaman login.
                                </p>
                            </div>
                            <!-- ================= NAMA APLIKASI 2 ================= -->
                            <div>
                                <h1 class="text-lg font-bold mb-2 text-gray-800">
                                    <i class="fas fa-rocket mr-1"></i> Nama Aplikasi 2
                                </h1>
                                <input type="text" name="nama_aplikasi2"
                                    value="{{ $setting->nama_aplikasi2 ?? '' }}"
                                    class="w-full border rounded-lg px-4 py-2 mt-1">
                            </div>
                            <!-- ================= SUBNAMA APLIKASI ================= -->
                            <div>
                                <h1 class="text-lg font-bold mb-2 text-gray-800">
                                    <i class="fas fa-gem mr-1"></i> Subnama Aplikasi
                                </h1>
                                <textarea name="subnama_aplikasi" rows="3"
                                    class="w-full border rounded-lg p-3">{{ $setting->subnama_aplikasi }}</textarea>
                                <!-- <input type="text" name="subnama_aplikasi"
                                    value="{{ $setting->subnama_aplikasi ?? '' }}"
                                    class="w-full border rounded-lg px-4 py-2 mt-1"> -->
                            </div>
                            <!-- ================= FORMAT CETAK ================= -->
                            <div>
                                <h1 class="text-lg font-bold mb-2 text-gray-800">
                                    <i class="fas fa-file mr-1"></i> Format Cetak (Nodin/BAST)
                                </h1>
                                <select name="format_cetak"
                                    class="w-full border rounded-lg px-4 py-2">
                                    <option value="pdf" {{ ($setting->format_cetak ?? 'pdf') == 'pdf' ? 'selected' : '' }}>
                                        PDF
                                    </option>
                                    <option value="docx" {{ ($setting->format_cetak ?? '') == 'docx' ? 'selected' : '' }}>
                                        DOCX (Word)
                                    </option>
                                </select>
                                <p class="text-sm text-gray-500 mt-2">
                                    Pilih format file saat mencetak dokumen pengajuan.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- ================= LOGO SYSTEM ================= -->
                    <div>
                        <h1 class="text-lg font-bold mb-2 text-gray-800">
                            <i class="fas fa-microchip mr-1"></i> Logo Sistem
                        </h1>
                        <input type="file" name="logo" accept="image/*"
                            class="w-full border rounded-lg px-4 py-2">
                        <p class="text-sm text-gray-500 mt-2">
                            Upload logo baru max. 2MB (PNG/JPG/SVG).
                        </p>
                        @if($setting->logo)
                            <div class="mt-3">
                                <p class="text-xs text-gray-500 mb-1">Preview:</p>
                                <img src="{{ asset('storage/logo/'.$setting->logo) }}"
                                    class="h-24 object-contain border rounded p-2">
                            </div>
                        @endif
                    </div>

                    <!-- ================= LOGIN BACKGROUND ================= -->
                    <div>
                        <h1 class="text-lg font-bold mb-2 text-gray-800">
                            <i class="fas fa-image mr-1"></i> Background Halaman Login
                        </h1>
                        <input type="file" name="login_bg" accept="image/*"
                            class="w-full border rounded-lg px-4 py-2">
                        <p class="text-sm text-gray-500 mt-2">
                            Upload background login max. 4MB (PNG/JPG).
                        </p>
                        @if($setting->login_bg)
                            <div class="mt-3">
                                <p class="text-xs text-gray-500 mb-1">Preview:</p>
                                <img src="{{ asset('storage/login-bg/'.$setting->login_bg) }}"
                                    class="h-32 object-cover border rounded">
                            </div>
                        @endif
                    </div>

                    <!-- ================= WARNA SIDEBAR ================= -->
                    <div>
                        <h1 class="text-lg font-bold mb-3 text-gray-800">
                            <i class="fas fa-palette mr-1"></i> Palet Warna Sidebar
                        </h1>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <!-- Background -->
                            <div class="flex flex-col">
                                <label class="text-sm font-medium text-gray-700 mb-1">
                                    Background
                                </label>
                                <input type="color" name="sidebar_color"
                                    value="{{ $setting->sidebar_color ?? '#1e3a8a' }}"
                                    class="w-full h-12 border rounded-lg cursor-pointer">
                            </div>
                            <!-- Text -->
                            <div class="flex flex-col">
                                <label class="text-sm font-medium text-gray-700 mb-1">
                                    Text
                                </label>
                                <input type="color" name="sidebar_text_color"
                                    value="{{ $setting->sidebar_text_color ?? '#ffffff' }}"
                                    class="w-full h-12 border rounded-lg cursor-pointer">
                            </div>
                            <!-- Hover -->
                            <div class="flex flex-col">
                                <label class="text-sm font-medium text-gray-700 mb-1">
                                    Hover
                                </label>
                                <input type="color" name="sidebar_hover_color"
                                    value="{{ $setting->sidebar_hover_color ?? '#1d4ed8' }}"
                                    class="w-full h-12 border rounded-lg cursor-pointer">
                            </div>
                        </div>

                        <p class="text-sm text-gray-500 mt-3">
                            Pilih warna utama, warna teks, dan warna hover sidebar sistem.
                        </p>
                    </div>
                    
                    <!-- ================= TEKS LOGIN ================= -->
                    <div>
                        <h1 class="text-lg font-bold mb-2 text-gray-800">
                            <i class="fas fa-hand-holding-heart mr-1"></i> Teks Pembuka Halaman Login
                        </h1>
                        <textarea name="login_opening_text" rows="4"
                            class="w-full border rounded-lg p-3">{{ $setting->login_opening_text }}</textarea>
                        <p class="text-sm text-gray-500">
                            Teks ini akan muncul di halaman login sistem.
                        </p>
                    </div>
                </div>

                <!-- CARD 2 - TEKS LOGIN -->
                <!--
                <h1 class="text-lg font-bold mt-6 mb-2 text-gray-800">
                    <i class="fas fa-hand-holding-heart mr-1"></i> Teks Pembuka Halaman Login
                </h1>
                <textarea name="login_opening_text" rows="4"
                    class="w-full border rounded-lg p-3">{{ $setting->login_opening_text }}</textarea>
                <p class="text-sm text-gray-500">
                    Teks ini akan muncul di halaman login sistem.
                </p>
                -->
            </div>

            <!-- Footer -->
            <div class="flex justify-end gap-2 px-4 py-4 border-t border-gray-200">
                <button type="reset"
                    class="px-3 py-1.5 text-sm font-normal border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100">
                    Reset
                </button>
                <button type="submit"
                    class="px-3 py-1.5 text-sm font-normal rounded-lg bg-green-600 text-white hover:bg-green-700">
                    <i class="fas fa-save mr-1"></i> Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>

    <div x-show="tab=='penandatangan'" class="x-transition.opacity.duration.200ms">
        <form action="{{ route('setting2.update') }}" method="POST"
            class="bg-white rounded-lg shadow-sm">
            @csrf

            <div class="px-4 py-4 border-b mb-4">
                <h1 class="text-xl font-bold text-gray-800">
                    <i class="fas fa-user-tie mr-2"></i>Pengaturan Penandatangan
                </h1>
            </div>

            <div class="px-4 pb-2 mb-4">
                <!-- ALERT -->
                @if(session('success_penandatangan'))
                <div id="alert-message"
                    class="mb-2 p-2 rounded-lg bg-green-100 text-green-800 border border-green-300">
                    <strong>Sukses!</strong> {{ session('success_penandatangan') }}
                </div>
                @endif
                @if(session('error_penandatangan'))
                <div id="alert-message"
                    class="mb-2 p-2 rounded-lg bg-red-100 text-red-800 border border-red-300">
                    <strong>Gagal!</strong> {{ session('error_penandatangan') }}
                </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Kasubbag TU -->
                    <div>
                        <h1 class="text-lg font-bold mb-2 text-gray-800">
                            <i class="fas fa-user mr-1"></i> Kasubbag TU
                        </h1>
                        <label class="text-sm text-gray-600">Nama</label>
                        <input type="text"
                            name="nama_kasubbag_tu"
                            value="{{ $setting2->nama_kasubbag_tu ?? '' }}"
                            class="w-full border rounded-lg px-4 py-2 mb-3">
                        <label class="text-sm text-gray-600">NIP</label>
                        <input type="text"
                            name="nip_kasubbag_tu"
                            value="{{ $setting2->nip_kasubbag_tu ?? '' }}"
                            class="w-full border rounded-lg px-4 py-2">
                    </div>
                    <!-- Kaur Umum TU -->
                    <div>
                        <h1 class="text-lg font-bold mb-2 text-gray-800">
                            <i class="fas fa-user mr-1"></i> Kaur Umum TU
                        </h1>
                        <label class="text-sm text-gray-600">Nama</label>
                        <input type="text"
                            name="nama_kaurumum_tu"
                            value="{{ $setting2->nama_kaurumum_tu ?? '' }}"
                            class="w-full border rounded-lg px-4 py-2 mb-3">
                        <label class="text-sm text-gray-600">NIP</label>
                        <input type="text"
                            name="nip_kaurumum_tu"
                            value="{{ $setting2->nip_kaurumum_tu ?? '' }}"
                            class="w-full border rounded-lg px-4 py-2">
                    </div>
                    <!-- Staff BMN TU -->
                    <div>
                        <h1 class="text-lg font-bold mb-2 text-gray-800">
                            <i class="fas fa-user mr-1"></i> Staff BMN TU
                        </h1>
                        <label class="text-sm text-gray-600">Nama</label>
                        <input type="text"
                            name="nama_staffbmn_tu"
                            value="{{ $setting2->nama_staffbmn_tu ?? '' }}"
                            class="w-full border rounded-lg px-4 py-2 mb-3">
                        <label class="text-sm text-gray-600">NIP</label>
                        <input type="text"
                            name="nip_staffbmn_tu"
                            value="{{ $setting2->nip_staffbmn_tu ?? '' }}"
                            class="w-full border rounded-lg px-4 py-2">
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-2 px-4 py-4 border-t border-gray-200">
                <button type="reset"
                    class="px-3 py-1.5 text-sm font-normal border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100">
                    Reset
                </button>
                <button type="submit"
                    class="px-3 py-1.5 text-sm rounded-lg bg-green-600 text-white hover:bg-green-700">
                    <i class="fas fa-save mr-1"></i> Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
</div>

<script>
    // color picker
    const picker = document.getElementById('colorPicker');
    const preview = document.getElementById('colorPreview');

    if (picker) {
        picker.addEventListener('input', function () {
            preview.style.backgroundColor = this.value;
        });
    }

    // auto hide alert
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
