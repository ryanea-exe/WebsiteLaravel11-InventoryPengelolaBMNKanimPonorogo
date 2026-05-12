@php
    $setting = \App\Models\Setting::first();
    $namaAplikasi = $setting->nama_aplikasi ?? 'Singobarong';
    $namaAplikasi2 = $setting->nama_aplikasi2 ?? 'Pemeliharaan';
@endphp

<header class="bg-white shadow-md border-b border-gray-200 sticky top-0 z-40 border-b">
    <div class="flex items-center justify-between px-3 sm:px-4 md:px-6 py-3 md:py-4 gap-2">
        <!-- LEFT -->
        <!--
        <div class="flex items-center">
            <button onclick="toggleSidebar()" class="md:hidden text-gray-600 hover:text-gray-900">
                <i class="fas fa-bars text-xl"></i>
            </button>
            <h1 class="text-xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h1>
        </div>
        -->
        <div class="flex items-center space-x-2">
            <!-- Toggle Sidebar -->
            <button onclick="toggleSidebar()"
                class="md:hidden text-gray-600 hover:text-gray-900 mr-1">
                <i class="fas fa-bars text-lg"></i>
            </button>
            <!-- Breadcrumb -->
            <nav class="flex items-center text-sm text-gray-500">
                <!-- DASHBOARD (SELALU ADA) -->
                <a href="{{ route('dashboard.index') }}" class="hover:text-blue-600">
                    Dashboard
                </a>
                <!-- CEK JIKA BUKAN DASHBOARD -->
                @hasSection('breadcrumb')
                    <span class="mx-1">/</span>
                    @yield('breadcrumb')
                @endif
            </nav>
        </div>

        <!-- RIGHT -->
        <div class="flex items-center space-x-4">
            <!-- NOTIFIKASI SINGOBARONG -->
            <div class="relative dropdown">
                <button onclick="toggleDropdown('notifDropdown')"
                    class="relative text-gray-600 hover:text-gray-900 focus:outline-none"
                    aria-label="Notifikasi">
                    <i class="fas fa-bell text-xl"></i>

                    {{-- BADGE JUMLAH NOTIFIKASI --}}
                    @if(isset($notifications) && $notifications->count() > 0)
                        <span
                            class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-semibold rounded-full min-w-[18px] h-[18px] px-1
                                flex items-center justify-center">
                            {{ $notifications->count() }}
                        </span>
                    @endif
                </button>

                {{-- DROPDOWN NOTIFIKASI --}}
                <div id="notifDropdown"
                    class="dropdown-menu absolute right-0 sm:right-0 left-1/2 sm:left-auto -translate-x-1/2 sm:translate-x-0
                        mt-3 w-[92vw] max-w-[340px] sm:w-80
                        bg-white rounded-xl shadow-lg border border-gray-200 z-50 opacity-0
                        scale-95 translate-y-2 pointer-events-none transition-all duration-200">

                    {{-- HEADER --}}
                    <div class="px-4 py-3 border-b border-gray-100">
                        <h3 class="font-semibold text-gray-800">Notifikasi {{ $namaAplikasi }}</h3>
                        @auth
                            <p class="text-xs text-gray-500">
                                {{ auth()->user()->role === 'Administrator'
                                    ? 'Pengajuan barang dari user'
                                    : 'Status pengajuan Anda'
                                }}
                            </p>
                        @endauth
                    </div>

                    {{-- ISI --}}
                    <div class="max-h-72 overflow-y-auto divide-y">
                        @isset($notifications)
                            @forelse($notifications as $notif)

                                {{-- ADMIN --}}
                                @if(auth()->user()->role === 'Administrator')
                                    <a href="{{ $notif->tipe === 'bmn'
                                        ? route('pengajuan-bmn.show_admin', $notif->id)
                                        : route('pengajuan.show_admin', $notif->id) }}"
                                        class="block px-4 py-3 hover:bg-gray-100 transition">
                                        <p class="text-sm font-semibold text-gray-800">
                                            {{ $notif->tipe === 'bmn'
                                                ? 'Pengajuan Baru Barang BMN'
                                                : 'Pengajuan Baru Barang Persediaan' }}
                                        </p>
                                        <p class="text-sm text-gray-600 break-words">
                                            {{ $notif->user->name }} —
                                            <span class="font-medium">
                                                @php
                                                    $firstDetail = $notif->details->first();
                                                @endphp

                                                {{ $firstDetail?->barang?->nama_barang ?? '-' }}
                                                @if($notif->details->count() > 1)
                                                    dan {{ $notif->details->count() - 1 }} barang lainnya
                                                @endif
                                            </span>
                                        </p>
                                        <p class="text-xs text-gray-400 mt-1">
                                            {{ $notif->created_at->diffForHumans() }}
                                        </p>
                                    </a>

                                {{-- STAFF --}}
                                @else
                                    <a href="{{ $notif->tipe === 'bmn'
                                        ? route('pengajuan-bmn.show_user', $notif->id)
                                        : route('pengajuan.show_user', $notif->id) }}"
                                        class="block px-4 py-3 hover:bg-gray-100 transition flex justify-between items-start">
                                        <div>
                                            <p class="text-sm font-semibold
                                                {{ $notif->status === 'Disetujui'
                                                    ? 'text-green-700'
                                                    : 'text-red-700' }}">
                                                
                                                {{ $notif->tipe === 'bmn'
                                                    ? 'Pengajuan Barang BMN ' . $notif->status
                                                    : 'Pengajuan Barang Persediaan ' . $notif->status }}
                                            </p>
                                            <p class="text-sm text-gray-600 break-words">
                                                @php
                                                    $firstDetail = $notif->details->first();
                                                @endphp

                                                {{ $firstDetail?->barang?->nama_barang ?? '-' }}
                                                @if($notif->details->count() > 1)
                                                    dan {{ $notif->details->count() - 1 }} barang lainnya
                                                @endif
                                            </p>
                                            <p class="text-xs text-gray-400 mt-1">
                                                {{ $notif->updated_at->diffForHumans() }}
                                            </p>
                                        </div>

                                        {{-- 🔵 PENANDA BELUM DIBACA --}}
                                        @if(is_null($notif->read_at))
                                            <span class="mt-1 ml-2 inline-block w-2 h-2 bg-blue-500 rounded-full"></span>
                                        @endif
                                    </a>
                                @endif

                            @empty
                                <div class="px-4 py-6 text-center text-sm text-gray-500">
                                    Tidak ada notifikasi
                                </div>
                            @endforelse
                        @endisset

                    </div>
                </div>
            </div>

            <!-- NOTIFIKASI PEMELIHARAAN / KENDARAAN -->
            <div class="relative dropdown">
                <button onclick="toggleDropdown('vehicleNotifDropdown')"
                    class="relative text-gray-600 hover:text-gray-900 focus:outline-none"
                    aria-label="Notifikasi Kendaraan">
                    <i class="fas fa-car text-xl"></i>

                    {{-- BADGE JUMLAH NOTIFIKASI --}}
                    @if(isset($vehicleNotifications) && $vehicleNotifications->count() > 0)
                        <span
                            class="absolute -top-1 -right-1 bg-orange-500 text-white text-[10px] font-semibold rounded-full min-w-[18px] h-[18px] px-1
                                flex items-center justify-center">
                            {{ $vehicleNotifications->count() }}
                        </span>
                    @endif
                </button>

                {{-- DROPDOWN --}}
                <div id="vehicleNotifDropdown"
                    class="dropdown-menu absolute right-0 sm:right-0 left-1/2 sm:left-auto -translate-x-1/2 sm:translate-x-0
                        mt-3 w-[92vw] max-w-[340px] sm:w-80
                        bg-white rounded-xl shadow-lg border border-gray-200 z-50
                        opacity-0 scale-95 translate-y-2 pointer-events-none transition-all duration-200">
                    {{-- HEADER --}}
                    <div class="px-4 py-3 border-b border-gray-100">
                        <h3 class="font-semibold text-gray-800">Notifikasi {{ $namaAplikasi2 }}</h3>
                        <p class="text-xs text-gray-500">
                            Pajak mendekati jatuh tempo & pengajuan pemeliharaan
                        </p>
                    </div>

                    {{-- ISI --}}
                    <div class="max-h-72 overflow-y-auto divide-y">
                        @forelse($vehicleNotifications as $notif)
                            <div class="px-4 py-3 hover:bg-gray-100 transition">
                                @if($notif->id)
                                    <a href="{{ auth()->user()->role === 'Administrator'
                                        ? route('pemeliharaan.show_admin', $notif->id)
                                        : route('pemeliharaan.show_user', $notif->id) }}"
                                        class="block hover:bg-gray-100 transition">
                                @endif
                                    <p class="text-sm font-semibold
                                        {{ $notif->jenis === 'pajak' ? 'text-red-600' : 'text-yellow-600' }}">
                                        {{ ucfirst($notif->jenis) }}
                                    </p>
                                    <p class="text-sm text-gray-700 break-words">
                                        {!! $notif->pesan !!}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ \Carbon\Carbon::parse($notif->tanggal)->translatedFormat('d M Y') }}
                                    </p>
                                @if($notif->id)
                                    </a>
                                @endif
                            </div>
                        @empty
                            <div class="px-4 py-6 text-center text-sm text-gray-500">
                                Tidak ada notifikasi kendaraan
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- PROFILE DROPDOWN -->
            <div class="relative dropdown">
                <button onclick="toggleDropdown('profileDropdown')" class="flex items-center space-x-3 focus:outline-none">
                    <span class="text-sm font-medium text-gray-700 hidden lg:block">
                        Hallo, {{ auth()->user()->name }}
                    </span>
                    <img src="{{ auth()->user()->photo_url }}"
                        class="w-9 h-9 rounded-full object-cover border"
                        alt="Foto Profil">
                    <i id="profileChevron" class="fas fa-chevron-down text-xs text-gray-500 transition-transform duration-300"></i>
                </button>
                <div id="profileDropdown" class="dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg
                                            border border-gray-200 z-50 opacity-0 scale-95 translate-y-2 pointer-events-none">
                    <button onclick="window.location='{{ route('profile.edit') }}'"
                        class="w-full flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-user-edit mr-3 text-blue-600"></i> Edit Profil
                    </button>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full text-left flex items-center px-4 py-3 text-sm text-red-600 hover:bg-red-50">
                            <i class="fas fa-sign-out-alt mr-3"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
