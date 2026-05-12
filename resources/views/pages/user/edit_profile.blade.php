@extends('layouts.app')

@section('title', 'Edit Profil Saya')

@section('page-title', 'Edit Profil Saya')
@section('breadcrumb')
    <p class="text-gray-800 cursor-pointer">
        Edit Profil Saya
    </p>
@endsection

@section('content')

<div class="space-y-4">
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data"
        class="bg-white rounded-lg shadow-sm">
        @csrf

        <!-- Header -->
        <div class="px-4 py-4 border-b mb-4">
            <h1 class="text-xl font-bold text-gray-800">
                <i class="fas fa-user-edit text-xl mr-3"></i>Edit Profil - {{ $user->name }}
            </h1>
        </div>

        <!-- Scrollable Field -->
        <div class="px-4 pt-2 pb-6 overflow-y-auto flex-1">
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

            {{-- INPUT HIDDEN (WAJIB UNTUK AUTO UPLOAD FOTO) --}}
            <input type="hidden" name="name" value="{{ $user->name }}">
            <input type="hidden" name="email" value="{{ $user->email }}">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- FOTO PROFIL --}}
                <div class="flex flex-col items-center">
                    <div class="relative">
                        <img id="preview-photo" src="{{ $user->photo_url }}?v={{ time() }}"
                            class="w-36 h-36 rounded-full object-cover border"
                            alt="Foto Profil">
                        {{-- BUTTON PENSIL --}}
                        <label for="photo"
                            class="absolute bottom-1 right-1 bg-blue-600 hover:bg-blue-700 text-white w-9 h-9 flex items-center justify-center rounded-full cursor-pointer shadow">
                            <i class="fas fa-pencil-alt text-sm"></i>
                        </label>
                        {{-- INPUT FILE --}}
                        <input type="file" id="photo" name="photo" accept="image/*" 
                            class="hidden"
                            onchange="autoSubmitPhoto(this)">
                    </div>
                    @error('photo')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- FORM DATA --}}
                <div class="md:col-span-2 space-y-4">
                    {{-- NAMA --}}
                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-700">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                            class="w-full border rounded-lg px-4 py-2">
                        @error('name')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                    {{-- EMAIL --}}
                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-700">Email</label>
                        @if(auth()->user()->email === "superadmin@system.com")
                            <input type="email" name="email" value="{{ $user->email }}"
                                class="w-full border rounded-lg px-4 py-2">
                        @else
                            <input type="email" name="email" value="{{ $user->email }}" readonly
                                class="w-full border rounded-lg px-4 py-2 bg-gray-100 cursor-not-allowed">
                        @endif
                    </div>

                    <hr class="my-4">
                    <p class="text-sm text-gray-500">
                        Kosongkan password jika tidak ingin mengubah.
                    </p>

                    {{-- PASSWORD LAMA --}}
                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-700">Password Lama</label>
                        <input type="password" name="current_password"
                            class="w-full border rounded-lg px-4 py-2">
                        @error('current_password')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                    {{-- PASSWORD BARU --}}
                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-700">Password Baru</label>
                        <input type="password" name="password"
                            class="w-full border rounded-lg px-4 py-2">
                        @error('password')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                    {{-- KONFIRMASI PASSWORD --}}
                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-700">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation"
                            class="w-full border rounded-lg px-4 py-2">
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-2 px-4 py-4 border-t border-gray-200"> 
            <button type="reset"
                class="px-3 py-1.5 font-normal text-sm border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100">
                Reset
            </button>
            <button type="submit"
                class="px-3 py-1.5 font-normal text-sm bg-green-600 hover:bg-green-700 text-white rounded-lg">
                <i class="fas fa-save mr-1"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script>
    function autoSubmitPhoto(input) {
        if (!input.files || !input.files[0]) return;

        const reader = new FileReader();

        reader.onload = function (e) {
            document.getElementById('preview-photo').src = e.target.result;
        };

        reader.readAsDataURL(input.files[0]);

        // submit otomatis
        setTimeout(() => {
            input.form.submit();
        }, 300);
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
