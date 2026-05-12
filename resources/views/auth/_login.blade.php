<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Inventaris BMN</title>

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
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-blue-900 via-blue-800 to-blue-900 flex items-center justify-center px-4">
    <!-- CARD CONTAINER -->
    <div class="w-full max-w-5xl bg-white rounded-2xl shadow-2xl overflow-hidden grid grid-cols-1 md:grid-cols-2">

        <!-- ================= LEFT INFO ================= -->
        <div class="relative p-10 flex flex-col justify-between text-white"
            style="background-image: url('{{ asset('images/login-bg.jpg') }}');
                    background-size: cover;
                    background-position: center;">

            <!-- OVERLAY BIRU TRANSPARAN -->
            <div class="absolute inset-0 bg-blue-900/75"></div>

            <!-- CONTENT -->
            <div class="relative z-10">
                <div class="flex items-center mb-6">
                    <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center shadow mr-4 backdrop-blur">
                        @php
                            $setting = \App\Models\Setting::first();
                            $logoPath = $setting && $setting->logo
                                ? asset('storage/logo/'.$setting->logo)
                                : asset('images/logo-ditjen-imigrasi.png');
                        @endphp

                        <img src="{{ $logoPath }}" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <h1 class="text-xl font-bold">
                            Sistem Inventaris BMN
                        </h1>
                        <p class="text-sm text-blue-200">
                            Kantor Imigrasi Kelas II Non TPI Ponorogo
                        </p>
                    </div>
                </div>

                @php
                    $setting = \App\Models\Setting::first();
                @endphp
                <p class="text-blue-100 leading-relaxed">
                    {{ $setting->login_opening_text ?? '' }}
                </p>
            </div>

            <!-- FOOTER -->
            <div class="relative z-10 text-xs text-blue-200 mt-8">
                <p>&copy; 2026 Kantor Imigrasi Kelas II Non TPI Ponorogo</p>
                <p>Kementerian Imigrasi dan Pemasyarakatan</p>
            </div>
        </div>

        <!-- ================= RIGHT FORM ================= -->
        <div class="p-10">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">
                Masuk ke Sistem
            </h2>
            <p class="text-sm text-gray-500 mb-6">
                Silakan login menggunakan akun Anda
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

            <!-- FORM -->
            <form action="{{ route('login.process') }}" method="POST">
                @csrf

                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">E-mail</label>

                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="fas fa-user"></i>
                        </span>
                        <input type="text" name="email" value="{{ old('email') }}"
                            class="w-full pl-10 pr-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500
                            @error('email') border-red-500 @enderror"
                            placeholder="Masukkan e-mail">
                    </div>
                    @error('email')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>

                    <div class="relative">
                        <!-- ICON LOCK -->
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <!-- INPUT -->
                        <input type="password" id="password" name="password"
                            class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg
                                    focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                    @error('password') border-red-500 @enderror"
                            placeholder="Masukkan password">
                        <!-- TOGGLE EYE -->
                        <button type="button"
                                onclick="togglePassword()"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none">
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
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center text-sm text-gray-700">
                        <input type="checkbox" name="remember"
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded">
                        <span class="ml-2">Ingat saya</span>
                    </label>
                    <a href="#" class="text-sm text-blue-600 hover:underline">
                        Lupa password?
                    </a>
                </div>

                <!-- Button -->
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg
                    transition shadow-md hover:shadow-lg">
                    <i class="fas fa-sign-in-alt mr-3"></i>Masuk
                </button>
            </form>
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
