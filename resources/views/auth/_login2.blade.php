<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Inventaris BMN</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-blue-900 via-blue-800 to-blue-900 h-screen overflow-hidden">
    <div class="h-full flex flex-col">

        <!-- HEADER -->
        <div class="pt-10 text-center px-4 shrink-0">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-full shadow-lg mb-3">
                <i class="fas fa-passport text-blue-900 text-3xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-white">Sistem Inventaris BMN</h1>
            <p class="text-blue-200 text-sm">Kantor Imigrasi Kelas II Non TPI Ponorogo</p>
        </div>

        <!-- LOGIN CARD -->
        <div class="flex-1 flex items-center justify-center px-4">
            <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 text-center">
                    Masuk ke Sistem
                </h2>

                <!-- FORM (ISI FORM TETAP PAKAI PUNYA KAMU) -->
                @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span>{{ session('error') }}</span>
                </div>
                @endif

                @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
                @endif

                <form action="{{ route('login.process') }}" method="POST">
                    @csrf
                    
                    <!-- Email -->
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email / Username</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input type="text" id="email" name="email" value="{{ old('email') }}" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror" placeholder="Masukkan email atau username" autofocus>
                        </div>
                        @error('email')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-6">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input type="password" id="password" name="password" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror" placeholder="Masukkan password">
                        </div>
                        @error('password')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Ingat saya</span>
                        </label>
                        <a href="#" class="text-sm text-blue-600 hover:text-blue-800">Lupa password?</a>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-sign-in-alt mr-2"></i> Masuk
                    </button>
                </form>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="pb-6 text-center text-blue-200 text-xs shrink-0">
            <p>&copy; 2026 Kantor Imigrasi Kelas II Non TPI Ponorogo</p>
            <p>Kementerian Imigrasi dan Pemasyarakatan</p>
        </div>

    </div>
</body>
</html>
