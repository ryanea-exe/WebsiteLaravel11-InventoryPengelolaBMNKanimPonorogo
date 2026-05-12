<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout - {{ $namaAplikasi ?? 'Singobarong' }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gradient-to-br from-blue-50 via-white to-blue-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <!-- Logout Success Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Header with Icon -->
            <div class="bg-gradient-to-br from-blue-600 to-blue-700 p-8 text-center">
                <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4 animate-bounce">
                    <i class="fas fa-check-circle text-white text-4xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-white">Logout Berhasil</h1>
                <p class="text-blue-100 mt-2">Anda telah keluar dari sistem</p>
            </div>

            <!-- Content -->
            <div class="p-8 text-center">
                <div class="mb-6">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-sign-out-alt text-green-600 text-2xl"></i>
                    </div>
                    <p class="text-gray-600 leading-relaxed">
                        Terima kasih telah menggunakan Sistem Inventaris Barang Kantor Imigrasi Ponorogo. 
                        Sesi Anda telah berakhir dengan aman.
                    </p>
                </div>

                <!-- Security Info -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-shield-alt text-blue-600 mt-1"></i>
                        <div class="text-left">
                            <h3 class="text-sm font-semibold text-gray-800 mb-1">Keamanan Sistem</h3>
                            <p class="text-xs text-gray-600">
                                Data Anda telah tersimpan dengan aman. Pastikan untuk logout setiap kali selesai menggunakan sistem, terutama di komputer publik.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-3">
                    <a href="{{ route('login') }}" class="block w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-6 rounded-lg transition duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-sign-in-alt mr-2"></i> Login Kembali
                    </a>
                    <a href="{{ url('/') }}" class="block w-full bg-white hover:bg-gray-50 text-gray-700 font-semibold py-3 px-6 rounded-lg transition duration-200 border-2 border-gray-300">
                        <i class="fas fa-home mr-2"></i> Ke Halaman Utama
                    </a>
                </div>

                <!-- Session Info -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="flex items-center justify-center text-xs text-gray-500 space-x-4">
                        <div class="flex items-center">
                            <i class="fas fa-clock mr-1"></i>
                            <span>Logout: {{ now()->format('d/m/Y H:i') }}</span>
                        </div>
                        <span>•</span>
                        <div class="flex items-center">
                            <i class="fas fa-laptop mr-1"></i>
                            <span>IP: {{ request()->ip() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6">
            <div class="flex items-center justify-center space-x-2 text-gray-600 mb-2">
                <img src="/placeholder.svg?height=32&width=32" alt="Logo" class="h-8 w-8">
                <span class="font-semibold">Kantor Imigrasi Ponorogo</span>
            </div>
            <p class="text-xs text-gray-500">
                Sistem Inventaris Barang
            </p>
            <p class="text-xs text-gray-400 mt-2">
                © {{ date('Y') }} Kementerian Hukum dan HAM RI
            </p>
        </div>
    </div>

    <style>
        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }
        .animate-bounce {
            animation: bounce 2s infinite;
        }
    </style>
</body>
</html>
