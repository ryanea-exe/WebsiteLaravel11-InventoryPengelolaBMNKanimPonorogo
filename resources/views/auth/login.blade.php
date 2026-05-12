<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ $namaAplikasi ?? 'Singobarong' }}</title>

    <!-- Favicon -->
    @php
        $setting = \App\Models\Setting::first();
        $faviconPath = ($setting && $setting->logo)
            ? asset('storage/logo/'.$setting->logo)
            : asset('images/logo-ditjen-imigrasi.png');
    @endphp
    <link rel="icon" type="image/png" href="{{ $faviconPath }}">

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- FONT POPPINS -->
    <!-- <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet"> -->
    <!-- FONT ABRIL FATFACE & LATO -->
    <!-- <link href="https://fonts.googleapis.com/css2?family=Abril+Fatface&family=Lato:wght@300;400;700&display=swap" rel="stylesheet"> -->
    <!-- FONT GEIST -->
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@100..900&family=Geist+Mono:wght@100..900&display=swap" rel="stylesheet">

    <style>
        body {
            /* font-family: 'Poppins', sans-serif; */
            /* font-family: 'Abril Fatface', serif; */ /* font-family: 'Lato', sans-serif; */
            font-family: 'Geist', sans-serif; /* font-family: 'Geist Mono', monospace; */
        }
        .fade-in {
            animation: fadeIn 1s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .soft-shadow {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1), 0 4px 10px rgba(0, 0, 0, 0.05);
        }
        .input-focus {
            transition: all 0.3s ease;
        }
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* ================================
        GLOBAL PAGE TRANSITION EFFECT
        ================================ */
        html {
            scroll-behavior: smooth;
        }
        /* Fade in body */
        @keyframes fadeInBody {
            from {
                opacity: 0;
                transform: translateY(8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Smooth content appearance */
        main {
            animation: fadeInContent 0.5s ease-out;
        }
        @keyframes fadeInContent {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Hover animation for cards, buttons, tables */
        button, a, {
            transition: all 0.2s ease-in-out;
        }

        /* Button hover */
        button:hover {
            transform: translateY(-1px);
        }

        /* Subtle scale hover */
        .hover-scale:hover {
            transform: scale(1.02);
        }

        /* Smooth fade utility */
        .fade-enter {
            opacity: 0;
            transform: translateY(10px);
        }
        .fade-enter-active {
            opacity: 1;
            transform: translateY(0);
            transition: all 0.4s ease;
        }
    </style>
</head>

@php
    $setting = \App\Models\Setting::first();
    $logoPath = $setting && $setting->logo
        ? asset('storage/logo/'.$setting->logo)
        : asset('images/logo-ditjen-imigrasi.png');
    $namaAplikasi = $setting->nama_aplikasi ?? 'Singobarong';
    $subnamaAplikasi = $setting->subnama_aplikasi ?? 'Kantor Imigrasi Ponorogo';
@endphp

<body class="min-h-screen flex flex-col md:flex-row bg-gradient-to-br from-blue-50 via-white to-blue-100 relative overflow-hidden">
    <!-- Decorative Blur Background -->
    <div class="absolute -top-32 -left-32 w-96 h-96 bg-blue-300 rounded-full opacity-30 blur-3xl"></div>
    <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-indigo-300 rounded-full opacity-30 blur-3xl"></div>
        <!-- LOGO -->
        <div onclick="openInfoModal()" class="absolute top-6 left-6 flex items-center gap-3 z-10 fade-in cursor-pointer group">
            <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center soft-shadow ring-2 ring-blue-100">
                <img src="{{ $logoPath }}"
                    alt="Logo Imigrasi"
                    class="w-10 h-10 object-contain">
            </div>
            <div>
                <h1 class="text-xl font-bold text-blue-800 tracking-wide uppercase group-hover:text-blue-600 transition">
                    {{ $namaAplikasi }}.
                </h1>
                <p class="max-w-[65%] text-xs text-blue-600 group-hover:text-blue-500 transition">
                    {{ $subnamaAplikasi }}
                </p>
            </div>
        </div>

        <!-- FORM -->
        <div class="flex flex-col justify-center w-full md:w-1/2 px-8 py-20 fade-in">
            <div class="max-w-md w-full mx-auto my-auto" style="padding-top:50px;">
                <h2 class="text-3xl font-semibold text-blue-800 mt-16 md:mt-0">
                    Login
                </h2>
                <p class="text-gray-500 mb-6">
                    Silakan login menggunakan akun Anda.
                </p>

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

                <form method="POST" action="{{ route('login.process') }}">
                    @csrf
                    <!-- EMAIL -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-blue-700 mb-1">
                            E-mail
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-blue-200
                                    focus:border-blue-500 input-focus transition outline-none text-gray-800 bg-white/50
                                    @error('email') border-red-500 @enderror""
                                placeholder="Masukkan e-mail" required>
                        </div>

                        @error('email')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- PASSWORD -->
                    <div class="mb-8">
                        <label class="block text-sm font-medium text-blue-700 mb-1">
                            Password
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" id="password" name="password"
                                class="w-full pl-10 pr-12 py-2.5 rounded-xl border border-blue-200
                                    focus:border-blue-500 input-focus transition outline-none text-gray-800 bg-white/50
                                    @error('password') border-red-500 @enderror"
                                placeholder="Masukkan password" required>
                            <button type="button" onclick="togglePassword()"
                                    class="absolute inset-y-0 right-0 mr-3 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none">
                                <i id="eyeIcon" class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>
                    <!-- Remember -->
                    <div class="flex items-center justify-between mb-2">
                        <label class="flex items-center text-sm text-gray-700">
                            <input type="checkbox" name="remember"
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded">
                            <span class="ml-2">Ingat saya</span>
                        </label>
                        <a href="javascript:void(0)" onclick="openForgotModal()"
                            class="text-sm text-blue-600 hover:underline">
                            Lupa password?
                        </a>
                    </div>
                    <!-- BUTTON -->
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-md font-semibold py-2.5 rounded-xl
                            soft-shadow hover:shadow-xl transition transform">
                        <i class="fas fa-sign-in-alt mr-3"></i>Login
                    </button>
                </form>

                <div class="mt-8 text-center text-xs text-gray-400">
                    © {{ date('Y') }}
                    <span class="font-semibold text-gray-600">
                        Kantor Imigrasi Kelas II Non TPI Ponorogo
                    </span>.
                    Seluruh hak cipta dilindungi.
                </div>
            </div>
        </div>

        <!-- IMAGE -->
        <div class="hidden md:block w-full md:w-1/2 relative">
            @php
                $setting = \App\Models\Setting::first();
                $loginBg = ($setting && $setting->login_bg)
                    ? asset('storage/login-bg/'.$setting->login_bg)
                    : asset('images/login-bg.jpg');
            @endphp
            <img src="{{ $loginBg }}"
                alt="Inventaris Barang di Kantor Imigrasi"
                class="h-full w-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-tr from-blue-900/40 via-blue-800/20 to-transparent"></div>
        </div>
    </div>
    </div>

    <!-- MODAL INFO APLIKASI -->
    <div id="infoModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50">
        <div id="modalBox" class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 relative transition-all duration-300 ease-out opacity-0 scale-95">
            <!-- Header -->
            <div class="mb-4 flex items-center justify-between flex-shrink-0">
                <h2 class="text-xl font-semibold text-blue-800">
                    Tentang Aplikasi
                </h2>
                <button onclick="closeInfoModal()"
                        class="text-gray-500 hover:text-gray-800">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Content -->
            <p class="text-gray-600 leading-relaxed text-justify whitespace">
                {{ $setting->login_opening_text ?? '-' }}
            </p>

            <!-- Footer Button -->
            <div class="text-right">
                <button onclick="closeInfoModal()"
                        class="px-3 py-1.5 border rounded-lg text-gray-700 font-medium text-sm hover:bg-gray-100 transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL LUPA PASSWORD -->
    <div id="forgotModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50">
        <div id="forgotModalBox" class="bg-white rounded-2xl shadow-2xl max-w-sm w-full p-6 relative transition-all duration-300 ease-out opacity-0 scale-95">
            <!-- Header -->
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-xl font-semibold text-blue-800">
                    Lupa Password?
                </h2>
                <button onclick="closeForgotModal()"
                        class="text-gray-500 hover:text-gray-800">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Content -->
            <p class="text-gray-600 leading-relaxed text-center text-lg font-medium">
                Hubungi Pegawai Umum!
            </p>
        </div>
    </div>

    <script>
        // toogle password
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }

        function openInfoModal() {
            const modal = document.getElementById('infoModal');
            const box = document.getElementById('modalBox');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // delay sedikit supaya transition aktif
            setTimeout(() => {
                box.classList.remove('opacity-0', 'scale-95');
                box.classList.add('opacity-100', 'scale-100');
            }, 10);
        }

        function closeInfoModal() {
            const modal = document.getElementById('infoModal');
            const box = document.getElementById('modalBox');

            // animasi keluar
            box.classList.remove('opacity-100', 'scale-100');
            box.classList.add('opacity-0', 'scale-95');

            // tunggu animasi selesai baru hidden
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300); // sesuai duration-300
        }

        // modal lupa password
        function openForgotModal() {
            const modal = document.getElementById('forgotModal');
            const box = document.getElementById('forgotModalBox');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            setTimeout(() => {
                box.classList.remove('opacity-0', 'scale-95');
                box.classList.add('opacity-100', 'scale-100');
            }, 10);
        }

        function closeForgotModal() {
            const modal = document.getElementById('forgotModal');
            const box = document.getElementById('forgotModalBox');

            box.classList.remove('opacity-100', 'scale-100');
            box.classList.add('opacity-0', 'scale-95');

            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
        }

        // Close when click outside modal
        document.addEventListener('click', function (event) {
            const modal = document.getElementById('infoModal');
            if (event.target === modal) {
                closeInfoModal();
            }
        });
        document.addEventListener('click', function (event) {
            const forgotModal = document.getElementById('forgotModal');
            if (event.target === forgotModal) {
                closeForgotModal();
            }
        });

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
</body>
</html>