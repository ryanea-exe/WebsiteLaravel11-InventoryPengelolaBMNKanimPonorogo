@php
    use Carbon\Carbon;
    $role = Auth::user()->role ?? null;
@endphp

@php
    $setting = \App\Models\Setting::first();

    $logoPath = $setting->logo 
        ? asset('storage/logo/'.$setting->logo) 
        : asset('images/logo-ditjen-imigrasi.png');
    $namaAplikasi = $setting->nama_aplikasi ?? 'Singobarong';
    $namaAplikasi2 = $setting->nama_aplikasi2 ?? 'Pemeliharaan';
    $sidebarColor = $setting->sidebar_color ?? '#1e3a8a';
    $sidebarTextColor = $setting->sidebar_text_color ?? '#ffffff';
    $sidebarHoverColor = $setting->sidebar_hover_color ?? '#1d4ed8';
@endphp

<style>
    /* Scrollbar untuk sidebar */
    #sidebar nav::-webkit-scrollbar {
        width: 6px;
    }
    #sidebar nav::-webkit-scrollbar-track {
        background: transparent; /* hilangkan putih */
    }
    #sidebar nav::-webkit-scrollbar-thumb {
        background-color: rgba(255, 255, 255, 0.3); /* warna thumb */
        border-radius: 10px;
    }
    #sidebar nav::-webkit-scrollbar-thumb:hover {
        background-color: rgba(255, 255, 255, 0.5);
    }

    /* Firefox */
    #sidebar nav {
        scrollbar-width: thin;
        scrollbar-color: rgba(255,255,255,0.3) transparent;
    }
</style>

<aside id="sidebar"
    class="w-64 sm:w-72 md:w-64 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out fixed md:static top-0 left-0 h-screen flex flex-col z-30 shadow-xl md:shadow-none"
    style="background: linear-gradient(to bottom, {{ $sidebarColor }} 0%, {{ $sidebarColor }} 40%, color-mix(in srgb, {{ $sidebarColor }} 70%, white) 100%);
           color: {{ $sidebarTextColor }};">

    <!-- HEADER -->
    <div class="px-3 sm:px-4 py-4 border-b" style="border-color: {{ $sidebarHoverColor }};">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 rounded-lg flex items-center justify-center p-1">
                <img src="{{ $logoPath }}"
                    alt="Logo Ditjen Imigrasi"
                    class="w-full h-full object-contain">
            </div>
            <div>
                <h2 class="font-bold text-md uppercase" style="color: {{ $sidebarTextColor }};">{{ $namaAplikasi }}</h2>
                <p class="text-xs" style="color: {{ $sidebarHoverColor }};">Kantor Imigrasi Ponorogo</p>
            </div>
        </div>
    </div>

    <!-- MENU -->
    <nav class="mt-3 md:mt-4 px-2 space-y-0.5 flex-1 text-sm overflow-y-auto scrollbar-thin">
        {{-- ================= ADMINISTRATOR ================= --}}
        @if ($role === 'Administrator')
            {{-- ================= APLIKASI 1 ================= --}}
            <div class="px-4 pt-2 pb-0.5 text-xs font-semibold uppercase tracking-wide opacity-70">
                Aplikasi {{ $namaAplikasi }}
            </div>

            <a href="{{ route('dashboard.index') }}"
                class="flex items-center space-x-3 px-4 py-0.5 rounded-lg transition"
                style="background-color: {{ request()->routeIs('dashboard.*') ? $sidebarHoverColor : 'transparent' }};"
                onmouseover="this.style.backgroundColor='{{ $sidebarHoverColor }}'"
                onmouseout="this.style.backgroundColor='{{ request()->routeIs('dashboard.*') ? $sidebarHoverColor : 'transparent' }}'">
                <i class="fas fa-home w-5 text-sm"></i>
                <span>Dashboard</span>
            </a>
            <!--
            <a href="{{ route('dashboard.index') }}"
                class="flex items-center space-x-3 px-4 py-0.5 rounded-lg transition
                {{ request()->routeIs('dashboard.*') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
                <i class="fas fa-home w-5 text-sm"></i>
                <span>Dashboard</span>
            </a>
            -->

            {{-- MASTER BARANG PERSEDIAAN DROPDOWN --}}
            @php
                $isMasterPersediaanActive = request()->routeIs('barang.*') ||
                                            request()->routeIs('barang-masuk.*') ||
                                            request()->routeIs('barang-keluar.*');
            @endphp
            <div>
                <a href="javascript:void(0)"
                    onclick="toggleMasterBarangPersediaan()"
                    class="w-full flex items-center justify-between px-4 py-0.5 rounded-lg transition cursor-pointer"
                    style="background-color: {{ $isMasterPersediaanActive ? $sidebarHoverColor : 'transparent' }};
                        color: {{ $sidebarTextColor }};"
                    onmouseover="this.style.backgroundColor='{{ $sidebarHoverColor }}'"
                    onmouseout="this.style.backgroundColor='{{ $isMasterPersediaanActive ? $sidebarHoverColor : 'transparent' }}'">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-boxes w-5 text-sm"></i>
                        <span>Master Barang Persediaan</span>
                    </div>
                    <i id="master-persediaan-arrow"
                        class="fas fa-chevron-down text-xs transition-transform duration-300 {{ $isMasterPersediaanActive ? 'rotate-180' : '' }}"></i>
                </a>
                <!--
                <a href="javascript:void(0)" 
                    onclick="toggleMasterBarang()"
                    class="w-full flex items-center justify-between px-4 py-0.5 rounded-lg transition cursor-pointer 
                    {{ $isMasterPersediaanActive ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-boxes w-5 text-sm"></i>
                        <span>Master Barang</span>
                    </div>
                    <i id="master-arrow"
                        class="fas fa-chevron-down text-xs transition-transform duration-300 {{ $isMasterPersediaanActive ? 'rotate-180' : '' }}"></i>
                </a>
                -->
                {{-- SUB MENU --}}
                <div id="master-persediaan-menu"
                    class="ml-8 space-y-0.5 overflow-hidden transition-all duration-300 ease-in-out {{ $isMasterPersediaanActive ? 'max-h-96 opacity-100' : 'max-h-0 opacity-0' }}">
                    <a href="{{ route('barang.index') }}"
                        class="flex items-center space-x-3 mt-0.5 px-4 py-0.5 rounded-lg transition"
                        style="background-color: {{ request()->routeIs('barang.*') ? $sidebarHoverColor : 'transparent' }};"
                        onmouseover="this.style.backgroundColor='{{ $sidebarHoverColor }}'"
                        onmouseout="this.style.backgroundColor='{{ request()->routeIs('barang.*') ? $sidebarHoverColor : 'transparent' }}'">
                        <i class="fas fa-box w-5 text-sm"></i>
                        <span>Data Barang</span>
                    </a>
                    <a href="{{ route('barang-masuk.index') }}"
                        class="flex items-center space-x-3 px-4 py-0.5 rounded-lg transition"
                        style="background-color: {{ request()->routeIs('barang-masuk.*') ? $sidebarHoverColor : 'transparent' }};"
                        onmouseover="this.style.backgroundColor='{{ $sidebarHoverColor }}'"
                        onmouseout="this.style.backgroundColor='{{ request()->routeIs('barang-masuk.*') ? $sidebarHoverColor : 'transparent' }}'">
                        <i class="fas fa-file-import w-5 text-sm"></i>
                        <span>Barang Masuk</span>
                    </a>
                    <a href="{{ route('barang-keluar.index') }}"
                        class="flex items-center space-x-3 px-4 py-0.5 rounded-lg transition"
                        style="background-color: {{ request()->routeIs('barang-keluar.*') ? $sidebarHoverColor : 'transparent' }};"
                        onmouseover="this.style.backgroundColor='{{ $sidebarHoverColor }}'"
                        onmouseout="this.style.backgroundColor='{{ request()->routeIs('barang-keluar.*') ? $sidebarHoverColor : 'transparent' }}'">
                        <i class="fas fa-file-export w-5 text-sm"></i>
                        <span>Barang Keluar</span>
                    </a>
                </div>
            </div>

            {{-- MASTER BARANG BMN DROPDOWN --}}
            @php
                $isMasterBMNActive = request()->routeIs('barang-bmn*') ||
                                     request()->routeIs('barang-keluar-bmn*');
            @endphp
            <div>
                <a href="javascript:void(0)"
                    onclick="toggleMasterBarangBMN()"
                    class="w-full flex items-center justify-between px-4 py-0.5 rounded-lg transition cursor-pointer"
                    style="background-color: {{ $isMasterBMNActive ? $sidebarHoverColor : 'transparent' }};
                        color: {{ $sidebarTextColor }};"
                    onmouseover="this.style.backgroundColor='{{ $sidebarHoverColor }}'"
                    onmouseout="this.style.backgroundColor='{{ $isMasterBMNActive ? $sidebarHoverColor : 'transparent' }}'">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-boxes w-5 text-sm"></i>
                        <span>Master Barang BMN</span>
                    </div>
                    <i id="master-bmn-arrow"
                        class="fas fa-chevron-down text-xs transition-transform duration-300 {{ $isMasterBMNActive ? 'rotate-180' : '' }}"></i>
                </a>
                {{-- SUB MENU --}}
                <div id="master-bmn-menu"
                    class="ml-8 space-y-0.5 overflow-hidden transition-all duration-300 ease-in-out {{ $isMasterBMNActive ? 'max-h-96 opacity-100' : 'max-h-0 opacity-0' }}">
                    <a href="{{ route('barang-bmn.index') }}"
                        class="flex items-center space-x-3 mt-0.5 px-4 py-0.5 rounded-lg transition"
                        style="background-color: {{ request()->routeIs('barang-bmn.index') ? $sidebarHoverColor : 'transparent' }};"
                        onmouseover="this.style.backgroundColor='{{ $sidebarHoverColor }}'"
                        onmouseout="this.style.backgroundColor='{{ request()->routeIs('barang-bmn.index') ? $sidebarHoverColor : 'transparent' }}'">
                        <i class="fas fa-archive w-5 text-sm"></i>
                        <span>Data Barang</span>
                    </a>
                    <a href="{{ route('barang-keluar-bmn.index') }}"
                        class="flex items-center space-x-3 px-4 py-0.5 rounded-lg transition"
                        style="background-color: {{ request()->routeIs('barang-keluar-bmn.*') ? $sidebarHoverColor : 'transparent' }};"
                        onmouseover="this.style.backgroundColor='{{ $sidebarHoverColor }}'"
                        onmouseout="this.style.backgroundColor='{{ request()->routeIs('barang-keluar-bmn.*') ? $sidebarHoverColor : 'transparent' }}'">
                        <i class="fas fa-file-export w-5 text-sm"></i>
                        <span>Barang Keluar</span>
                    </a>
                </div>
            </div>

            {{-- RIWAYAT PENGAJUAN BARANG DROPDOWN --}}
            @php
                $isRiwayatPengajuanActive = request()->routeIs('pengajuan.riwayat_admin*') ||
                                            request()->routeIs('pengajuan-bmn.riwayat_admin*');;
            @endphp
            <div>
                <a href="javascript:void(0)"
                    onclick="toggleRiwayatPengajuanBarang()"
                    class="w-full flex items-center justify-between px-4 py-0.5 rounded-lg transition cursor-pointer"
                    style="background-color: {{ $isRiwayatPengajuanActive ? $sidebarHoverColor : 'transparent' }};
                        color: {{ $sidebarTextColor }};"
                    onmouseover="this.style.backgroundColor='{{ $sidebarHoverColor }}'"
                    onmouseout="this.style.backgroundColor='{{ $isRiwayatPengajuanActive ? $sidebarHoverColor : 'transparent' }}'">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-history w-5 text-sm"></i>
                        <span>Riwayat Pengajuan</span>
                    </div>
                    <i id="riwayat-pengajuan-arrow"
                        class="fas fa-chevron-down text-xs transition-transform duration-300 {{ $isRiwayatPengajuanActive ? 'rotate-180' : '' }}"></i>
                </a>
                {{-- SUB MENU --}}
                <div id="riwayat-pengajuan-menu"
                    class="ml-8 space-y-0.5 overflow-hidden transition-all duration-300 ease-in-out {{ $isRiwayatPengajuanActive ? 'max-h-96 opacity-100' : 'max-h-0 opacity-0' }}">
                    <a href="{{ route('pengajuan.riwayat_admin') }}"
                        class="flex items-center space-x-3 mt-0.5 px-4 py-0.5 rounded-lg transition"
                        style="background-color: {{ request()->routeIs('pengajuan.riwayat_admin') ? $sidebarHoverColor : 'transparent' }};"
                        onmouseover="this.style.backgroundColor='{{ $sidebarHoverColor }}'"
                        onmouseout="this.style.backgroundColor='{{ request()->routeIs('pengajuan.riwayat_admin') ? $sidebarHoverColor : 'transparent' }}'">
                        <i class="fas fa-history w-5 text-sm"></i>
                        <span>Barang Persediaan</span>
                    </a>
                    <a href="{{ route('pengajuan-bmn.riwayat_admin') }}"
                        class="flex items-center space-x-3 mt-0.5 px-4 py-0.5 rounded-lg transition"
                        style="background-color: {{ request()->routeIs('pengajuan-bmn.riwayat_admin') ? $sidebarHoverColor : 'transparent' }};"
                        onmouseover="this.style.backgroundColor='{{ $sidebarHoverColor }}'"
                        onmouseout="this.style.backgroundColor='{{ request()->routeIs('pengajuan-bmn.riwayat_admin') ? $sidebarHoverColor : 'transparent' }}'">
                        <i class="fas fa-history w-5 text-sm"></i>
                        <span>Barang BMN</span>
                    </a>
                </div>
            </div>

            {{-- MENU LAIN --}}
            <a href="{{ route('catatan.riwayat_admin') }}"
                class="flex items-center space-x-3 px-4 py-0.5 rounded-lg transition"
                style="background-color: {{ request()->routeIs('catatan.riwayat_admin') ? $sidebarHoverColor : 'transparent' }};"
                onmouseover="this.style.backgroundColor='{{ $sidebarHoverColor }}'"
                onmouseout="this.style.backgroundColor='{{ request()->routeIs('catatan.riwayat_admin') ? $sidebarHoverColor : 'transparent' }}'">
                <i class="fas fa-inbox w-5 text-sm"></i>
                <span>Catatan Masuk</span>
            </a> 
            <a href="{{ route('laporan.index') }}"
                class="flex items-center space-x-3 px-4 py-0.5 rounded-lg transition"
                style="background-color: {{ request()->routeIs('laporan.index') ? $sidebarHoverColor : 'transparent' }};"
                onmouseover="this.style.backgroundColor='{{ $sidebarHoverColor }}'"
                onmouseout="this.style.backgroundColor='{{ request()->routeIs('laporan.index') ? $sidebarHoverColor : 'transparent' }}'">
                <i class="fas fa-file-alt w-5 text-sm"></i>
                <span>Laporan</span>
            </a>

            {{-- ================= APLIKASI 2 ================= --}}
            <div class="px-4 pt-4 pb-0.5 text-xs font-semibold uppercase tracking-wide opacity-70">
                Aplikasi {{ $namaAplikasi2 }}
            </div>

            <a href="{{ route('dashboard2.index') }}"
                class="flex items-center space-x-3 px-4 py-0.5 rounded-lg transition"
                style="background-color: {{ request()->routeIs('dashboard2.index') ? $sidebarHoverColor : 'transparent' }};"
                onmouseover="this.style.backgroundColor='{{ $sidebarHoverColor }}'"
                onmouseout="this.style.backgroundColor='{{ request()->routeIs('dashboard2.index') ? $sidebarHoverColor : 'transparent' }}'">
                <i class="fas fa-home w-5 text-sm"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('pemeliharaan.kendaraan.index') }}"
                class="flex items-center space-x-3 px-4 py-0.5 rounded-lg transition"
                style="background-color: {{ request()->routeIs('pemeliharaan.kendaraan.index') ? $sidebarHoverColor : 'transparent' }};"
                onmouseover="this.style.backgroundColor='{{ $sidebarHoverColor }}'"
                onmouseout="this.style.backgroundColor='{{ request()->routeIs('pemeliharaan.kendaraan.index') ? $sidebarHoverColor : 'transparent' }}'">
                <i class="fas fa-car w-5 text-sm"></i>
                <span>Data Kendaraan</span>
            </a>
            <a href="{{ route('pemeliharaan.input_pajak.index') }}"
                class="flex items-center space-x-3 px-4 py-0.5 rounded-lg transition"
                style="background-color: {{ request()->routeIs('pemeliharaan.input_pajak.index') ? $sidebarHoverColor : 'transparent' }};"
                onmouseover="this.style.backgroundColor='{{ $sidebarHoverColor }}'"
                onmouseout="this.style.backgroundColor='{{ request()->routeIs('pemeliharaan.input_pajak.index') ? $sidebarHoverColor : 'transparent' }}'">
                <!-- <i class="fas fa-tools w-5 text-sm"></i> -->
                 <i class="fas fa-file-invoice w-5 text-sm"></i>
                <span>Input Pajak</span>
            </a>
            <a href="{{ route('pemeliharaan.input_servis.index') }}"
                class="flex items-center space-x-3 px-4 py-0.5 rounded-lg transition"
                style="background-color: {{ request()->routeIs('pemeliharaan.input_servis.index') ? $sidebarHoverColor : 'transparent' }};"
                onmouseover="this.style.backgroundColor='{{ $sidebarHoverColor }}'"
                onmouseout="this.style.backgroundColor='{{ request()->routeIs('pemeliharaan.input_servis.index') ? $sidebarHoverColor : 'transparent' }}'">
                <!-- <i class="fas fa-tools w-5 text-sm"></i> -->
                 <i class="fas fa-file-invoice w-5 text-sm"></i>
                <span>Input Servis</span>
            </a>

            {{-- Master Riwayat Pemeliharaan Dropdown --}}
            @php
                $isPemeliharaanActive = request()->routeIs('pemeliharaan.riwayat_admin*') ||
                                        request()->routeIs('pemeliharaan.riwayat_pajak.index*') ||
                                        request()->routeIs('pemeliharaan.riwayat_servis.index*');
            @endphp
            <div>
                <a href="javascript:void(0)"
                    onclick="toggleMasterPemeliharaan()"
                    class="w-full flex items-center justify-between px-4 py-0.5 rounded-lg transition cursor-pointer"
                    style="background-color: {{ $isPemeliharaanActive ? $sidebarHoverColor : 'transparent' }};
                        color: {{ $sidebarTextColor }};"
                    onmouseover="this.style.backgroundColor='{{ $sidebarHoverColor }}'"
                    onmouseout="this.style.backgroundColor='{{ $isPemeliharaanActive ? $sidebarHoverColor : 'transparent' }}'">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-history w-5 text-sm"></i>
                        <span>Riwayat Pemeliharaan</span>
                    </div>
                    <i id="master-riwayat-pemeliharaan-arrow"
                        class="fas fa-chevron-down text-xs transition-transform duration-300"></i>
                </a>
                {{-- SUB MENU --}}
                <div id="master-riwayat-pemeliharaan-menu"
                    class="ml-8 space-y-0.5 overflow-hidden transition-all duration-300 ease-in-out {{ $isPemeliharaanActive ? 'max-h-96 opacity-100' : 'max-h-0 opacity-0' }}">
                    <a href="{{ route('pemeliharaan.riwayat_admin') }}"
                        class="flex items-center space-x-3 px-4 mt-0.5 py-0.5 rounded-lg transition"
                        style="background-color: {{ request()->routeIs('pemeliharaan.riwayat_admin') ? $sidebarHoverColor : 'transparent' }};"
                        onmouseover="this.style.backgroundColor='{{ $sidebarHoverColor }}'"
                        onmouseout="this.style.backgroundColor='{{ request()->routeIs('pemeliharaan.riwayat_admin') ? $sidebarHoverColor : 'transparent' }}'">
                        <i class="fas fa-history w-5 text-sm"></i>
                        <span>Pengajuan Pemeliharaan</span>
                    </a>
                    <a href="{{ route('pemeliharaan.riwayat_pajak.index') }}"
                        class="flex items-center space-x-3 px-4 py-0.5 rounded-lg transition"
                        style="background-color: {{ request()->routeIs('pemeliharaan.riwayat_pajak.index') ? $sidebarHoverColor : 'transparent' }};"
                        onmouseover="this.style.backgroundColor='{{ $sidebarHoverColor }}'"
                        onmouseout="this.style.backgroundColor='{{ request()->routeIs('pemeliharaan.riwayat_pajak.index') ? $sidebarHoverColor : 'transparent' }}'">
                        <i class="fas fa-file-invoice w-5 text-sm"></i>
                        <span>Riwayat Pajak</span>
                    </a>
                    <a href="{{ route('pemeliharaan.riwayat_servis.index') }}"
                        class="flex items-center space-x-3 px-4 py-0.5 rounded-lg transition"
                        style="background-color: {{ request()->routeIs('pemeliharaan.riwayat_servis.index') ? $sidebarHoverColor : 'transparent' }};"
                        onmouseover="this.style.backgroundColor='{{ $sidebarHoverColor }}'"
                        onmouseout="this.style.backgroundColor='{{ request()->routeIs('pemeliharaan.riwayat_servis.index') ? $sidebarHoverColor : 'transparent' }}'">
                        <i class="fas fa-wrench w-5 text-sm"></i>
                        <span>Riwayat Servis</span>
                    </a>
                </div>
            </div>

            {{-- ================= LAINNYA ================= --}}
            <div class="px-4 pt-4 pb-0.5 text-xs font-semibold uppercase tracking-wide opacity-70">
                LAINNYA
            </div>
            <a href="{{ route('user.index') }}"
                class="flex items-center space-x-3 px-4 py-0.5 rounded-lg transition"
                style="background-color: {{ request()->routeIs('user.index') ? $sidebarHoverColor : 'transparent' }};"
                onmouseover="this.style.backgroundColor='{{ $sidebarHoverColor }}'"
                onmouseout="this.style.backgroundColor='{{ request()->routeIs('user.index') ? $sidebarHoverColor : 'transparent' }}'">
                <i class="fas fa-users w-5 text-sm"></i>
                <span>Manajemen User</span>
            </a>
            <a href="{{ route('setting.index') }}"
                class="flex items-center space-x-3 px-4 py-0.5 rounded-lg transition"
                style="background-color: {{ request()->routeIs('setting.index') ? $sidebarHoverColor : 'transparent' }};"
                onmouseover="this.style.backgroundColor='{{ $sidebarHoverColor }}'"
                onmouseout="this.style.backgroundColor='{{ request()->routeIs('setting.index') ? $sidebarHoverColor : 'transparent' }}'">
                <i class="fas fa-gear w-5 text-sm"></i>
                <span>Pengaturan</span>
            </a>

        {{-- ================= STAFF ================= --}}
        @elseif ($role === 'Staff')
            {{-- ================= APLIKASI 1 ================= --}}
            <div class="px-4 pt-2 pb-0.5 text-xs font-semibold uppercase tracking-wide opacity-70">
                Aplikasi {{ $namaAplikasi }}
            </div>

            <a href="{{ route('dashboard.index') }}"
                class="flex items-center space-x-3 px-4 py-0.5 rounded-lg transition"
                style="background-color: {{ request()->routeIs('dashboard.*') ? $sidebarHoverColor : 'transparent' }};"
                onmouseover="this.style.backgroundColor='{{ $sidebarHoverColor }}'"
                onmouseout="this.style.backgroundColor='{{ request()->routeIs('dashboard.*') ? $sidebarHoverColor : 'transparent' }}'">
                <i class="fas fa-home w-5 text-sm"></i>
                <span>Dashboard</span>
            </a>
            <!--
            <a href="{{ route('dashboard.index') }}"
                class="flex items-center space-x-3 px-4 py-0.5 rounded-lg transition
                {{ request()->routeIs('dashboard.*') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
                <i class="fas fa-home w-5 text-sm"></i>
                <span>Dashboard</span>
            </a>
            -->

            {{-- MASTER BARANG DROPDOWN --}}
            @php
                $isMasterBarangActive = request()->routeIs('barang.*') ||
                                        request()->routeIs('barang-bmn.*');
            @endphp
            <div>
                <a href="javascript:void(0)"
                    onclick="toggleMasterBarang()"
                    class="w-full flex items-center justify-between px-4 py-0.5 rounded-lg transition cursor-pointer"
                    style="background-color: {{ $isMasterBarangActive ? $sidebarHoverColor : 'transparent' }};
                        color: {{ $sidebarTextColor }};"
                    onmouseover="this.style.backgroundColor='{{ $sidebarHoverColor }}'"
                    onmouseout="this.style.backgroundColor='{{ $isMasterBarangActive ? $sidebarHoverColor : 'transparent' }}'">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-boxes w-5 text-sm"></i>
                        <span>Master Barang</span>
                    </div>
                    <i id="master-barang-arrow"
                        class="fas fa-chevron-down text-xs transition-transform duration-300 {{ $isMasterBarangActive ? 'rotate-180' : '' }}"></i>
                </a>
                {{-- SUB MENU --}}
                <div id="master-barang-menu"
                    class="ml-8 space-y-0.5 overflow-hidden transition-all duration-300 ease-in-out {{ $isMasterBarangActive ? 'max-h-96 opacity-100' : 'max-h-0 opacity-0' }}">
                    <a href="{{ route('barang.index') }}"
                        class="flex items-center space-x-3 mt-0.5 px-4 py-0.5 rounded-lg transition"
                        style="background-color: {{ request()->routeIs('barang.index') ? $sidebarHoverColor : 'transparent' }};"
                        onmouseover="this.style.backgroundColor='{{ $sidebarHoverColor }}'"
                        onmouseout="this.style.backgroundColor='{{ request()->routeIs('barang.index') ? $sidebarHoverColor : 'transparent' }}'">
                        <i class="fas fa-box w-5 text-sm"></i>
                        <span>Barang Persediaan</span>
                    </a>
                    <a href="{{ route('barang-bmn.index') }}"
                        class="flex items-center space-x-3 mt-0.5 px-4 py-0.5 rounded-lg transition"
                        style="background-color: {{ request()->routeIs('barang-bmn.index') ? $sidebarHoverColor : 'transparent' }};"
                        onmouseover="this.style.backgroundColor='{{ $sidebarHoverColor }}'"
                        onmouseout="this.style.backgroundColor='{{ request()->routeIs('barang-bmn.index') ? $sidebarHoverColor : 'transparent' }}'">
                        <i class="fas fa-archive w-5 text-sm"></i>
                        <span>Barang BMN</span>
                    </a>
                </div>
            </div>

            {{-- FORM PENGAJUAN BARANG DROPDOWN --}}
            @php
                $isFormPengajuanActive = request()->routeIs('pengajuan.index*') ||
                                         request()->routeIs('pengajuan-bmn.index*');
            @endphp
            <div>
                <a href="javascript:void(0)"
                    onclick="toggleFormPengajuanBarang()"
                    class="w-full flex items-center justify-between px-4 py-0.5 rounded-lg transition cursor-pointer"
                    style="background-color: {{ $isFormPengajuanActive ? $sidebarHoverColor : 'transparent' }};
                        color: {{ $sidebarTextColor }};"
                    onmouseover="this.style.backgroundColor='{{ $sidebarHoverColor }}'"
                    onmouseout="this.style.backgroundColor='{{ $isFormPengajuanActive ? $sidebarHoverColor : 'transparent' }}'">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-file-signature w-5 text-sm"></i>
                        <span>Pengajuan Barang</span>
                    </div>
                    <i id="form-pengajuan-arrow"
                        class="fas fa-chevron-down text-xs transition-transform duration-300 {{ $isFormPengajuanActive ? 'rotate-180' : '' }}"></i>
                </a>
                {{-- SUB MENU --}}
                <div id="form-pengajuan-menu"
                    class="ml-8 space-y-0.5 overflow-hidden transition-all duration-300 ease-in-out {{ $isFormPengajuanActive ? 'max-h-96 opacity-100' : 'max-h-0 opacity-0' }}">
                    <a href="{{ route('pengajuan.index') }}"
                        class="flex items-center space-x-3 mt-0.5 px-4 py-0.5 rounded-lg transition"
                        style="background-color: {{ request()->routeIs('pengajuan.index') ? $sidebarHoverColor : 'transparent' }};"
                        onmouseover="this.style.backgroundColor='{{ $sidebarHoverColor }}'"
                        onmouseout="this.style.backgroundColor='{{ request()->routeIs('pengajuan.index') ? $sidebarHoverColor : 'transparent' }}'">
                        <i class="fas fa-file-signature w-5 text-sm"></i>
                        <span>Barang Persediaan</span>
                    </a>
                    <a href="{{ route('pengajuan-bmn.index') }}"
                        class="flex items-center space-x-3 mt-0.5 px-4 py-0.5 rounded-lg transition"
                        style="background-color: {{ request()->routeIs('pengajuan-bmn.index') ? $sidebarHoverColor : 'transparent' }};"
                        onmouseover="this.style.backgroundColor='{{ $sidebarHoverColor }}'"
                        onmouseout="this.style.backgroundColor='{{ request()->routeIs('pengajuan-bmn.index') ? $sidebarHoverColor : 'transparent' }}'">
                        <i class="fas fa-file-signature w-5 text-sm"></i>
                        <span>Barang BMN</span>
                    </a>
                </div>
            </div>

            {{-- RIWAYAT PENGAJUAN BARANG DROPDOWN --}}
            @php
                $isRiwayatPengajuanActive = request()->routeIs('pengajuan.riwayat_user*') ||
                                             request()->routeIs('pengajuan-bmn.riwayat_user*');
            @endphp
            <div>
                <a href="javascript:void(0)"
                    onclick="toggleRiwayatPengajuanBarang()"
                    class="w-full flex items-center justify-between px-4 py-0.5 rounded-lg transition cursor-pointer"
                    style="background-color: {{ $isRiwayatPengajuanActive ? $sidebarHoverColor : 'transparent' }};
                        color: {{ $sidebarTextColor }};"
                    onmouseover="this.style.backgroundColor='{{ $sidebarHoverColor }}'"
                    onmouseout="this.style.backgroundColor='{{ $isRiwayatPengajuanActive ? $sidebarHoverColor : 'transparent' }}'">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-history w-5 text-sm"></i>
                        <span>Riwayat Pengajuan</span>
                    </div>
                    <i id="riwayat-pengajuan-arrow"
                        class="fas fa-chevron-down text-xs transition-transform duration-300 {{ $isRiwayatPengajuanActive ? 'rotate-180' : '' }}"></i>
                </a>
                {{-- SUB MENU --}}
                <div id="riwayat-pengajuan-menu"
                    class="ml-8 space-y-0.5 overflow-hidden transition-all duration-300 ease-in-out {{ $isRiwayatPengajuanActive ? 'max-h-96 opacity-100' : 'max-h-0 opacity-0' }}">
                    <a href="{{ route('pengajuan.riwayat_user') }}"
                        class="flex items-center space-x-3 mt-0.5 px-4 py-0.5 rounded-lg transition"
                        style="background-color: {{ request()->routeIs('pengajuan.riwayat_user') ? $sidebarHoverColor : 'transparent' }};"
                        onmouseover="this.style.backgroundColor='{{ $sidebarHoverColor }}'"
                        onmouseout="this.style.backgroundColor='{{ request()->routeIs('pengajuan.riwayat_user') ? $sidebarHoverColor : 'transparent' }}'">
                        <i class="fas fa-history w-5 text-sm"></i>
                        <span>Barang Persediaan</span>
                    </a>
                    <a href="{{ route('pengajuan-bmn.riwayat_user') }}"
                        class="flex items-center space-x-3 mt-0.5 px-4 py-0.5 rounded-lg transition"
                        style="background-color: {{ request()->routeIs('pengajuan-bmn.riwayat_user') ? $sidebarHoverColor : 'transparent' }};"
                        onmouseover="this.style.backgroundColor='{{ $sidebarHoverColor }}'"
                        onmouseout="this.style.backgroundColor='{{ request()->routeIs('pengajuan-bmn.riwayat_user') ? $sidebarHoverColor : 'transparent' }}'">
                        <i class="fas fa-history w-5 text-sm"></i>
                        <span>Barang BMN</span>
                    </a>
                </div>
            </div>

            {{-- MENU LAIN --}}
            <a href="{{ route('catatan.index') }}"
                class="flex items-center space-x-3 px-4 py-0.5 rounded-lg transition"
                style="background-color: {{ request()->routeIs('catatan.index') ? $sidebarHoverColor : 'transparent' }};"
                onmouseover="this.style.backgroundColor='{{ $sidebarHoverColor }}'"
                onmouseout="this.style.backgroundColor='{{ request()->routeIs('catatan.index') ? $sidebarHoverColor : 'transparent' }}'">
                <i class="fas fa-sticky-note w-5 text-sm"></i>
                <span>Kirim Catatan</span>
            </a>

            {{-- ================= APLIKASI 2 ================= --}}
            <div class="px-4 pt-4 pb-0.5 text-xs font-semibold uppercase tracking-wide opacity-70">
                Aplikasi {{ $namaAplikasi2 }}
            </div>

            <a href="{{ route('dashboard2.index') }}"
                class="flex items-center space-x-3 px-4 py-0.5 rounded-lg transition"
                style="background-color: {{ request()->routeIs('dashboard2.index') ? $sidebarHoverColor : 'transparent' }};"
                onmouseover="this.style.backgroundColor='{{ $sidebarHoverColor }}'"
                onmouseout="this.style.backgroundColor='{{ request()->routeIs('dashboard2.index') ? $sidebarHoverColor : 'transparent' }}'">
                <i class="fas fa-home w-5 text-sm"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('pemeliharaan.kendaraan.index') }}"
                class="flex items-center space-x-3 px-4 py-0.5 rounded-lg transition"
                style="background-color: {{ request()->routeIs('pemeliharaan.kendaraan.index') ? $sidebarHoverColor : 'transparent' }};"
                onmouseover="this.style.backgroundColor='{{ $sidebarHoverColor }}'"
                onmouseout="this.style.backgroundColor='{{ request()->routeIs('pemeliharaan.kendaraan.index') ? $sidebarHoverColor : 'transparent' }}'">
                <i class="fas fa-car w-5 text-sm"></i>
                <span>Data Kendaraan</span>
            </a>
            <a href="{{ route('pemeliharaan.index') }}"
                class="flex items-center space-x-3 px-4 py-0.5 rounded-lg transition"
                style="background-color: {{ request()->routeIs('pemeliharaan.index') ? $sidebarHoverColor : 'transparent' }};"
                onmouseover="this.style.backgroundColor='{{ $sidebarHoverColor }}'"
                onmouseout="this.style.backgroundColor='{{ request()->routeIs('pemeliharaan.index') ? $sidebarHoverColor : 'transparent' }}'">
                <i class="fas fa-file-signature w-5 text-sm"></i>
                <span>Pengajuan Pemeliharaan</span>
            </a>
            {{-- Master Riwayat Pemeliharaan Dropdown --}}
            @php
                $isPemeliharaanActive = request()->routeIs('pemeliharaan.riwayat_user*') ||
                                        request()->routeIs('pemeliharaan.riwayat_servis.index*');
            @endphp
            <div>
                <a href="javascript:void(0)"
                    onclick="toggleMasterPemeliharaan()"
                    class="w-full flex items-center justify-between px-4 py-0.5 rounded-lg transition cursor-pointer"
                    style="background-color: {{ $isPemeliharaanActive ? $sidebarHoverColor : 'transparent' }};
                        color: {{ $sidebarTextColor }};"
                    onmouseover="this.style.backgroundColor='{{ $sidebarHoverColor }}'"
                    onmouseout="this.style.backgroundColor='{{ $isPemeliharaanActive ? $sidebarHoverColor : 'transparent' }}'">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-history w-5 text-sm"></i>
                        <span>Riwayat Pemeliharaan</span>
                    </div>
                    <i id="master-riwayat-pemeliharaan-arrow"
                        class="fas fa-chevron-down text-xs transition-transform duration-300"></i>
                </a>
                {{-- SUB MENU --}}
                <div id="master-riwayat-pemeliharaan-menu"
                    class="ml-8 space-y-0.5 overflow-hidden transition-all duration-300 ease-in-out {{ $isPemeliharaanActive ? 'max-h-96 opacity-100' : 'max-h-0 opacity-0' }}">
                    <a href="{{ route('pemeliharaan.riwayat_user') }}"
                        class="flex items-center space-x-3 px-4 mt-0.5 py-0.5 rounded-lg transition"
                        style="background-color: {{ request()->routeIs('pemeliharaan.riwayat_user') ? $sidebarHoverColor : 'transparent' }};"
                        onmouseover="this.style.backgroundColor='{{ $sidebarHoverColor }}'"
                        onmouseout="this.style.backgroundColor='{{ request()->routeIs('pemeliharaan.riwayat_user') ? $sidebarHoverColor : 'transparent' }}'">
                        <i class="fas fa-history w-5 text-sm"></i>
                        <span>Pengajuan Pemeliharaan</span>
                    </a>
                    <a href="{{ route('pemeliharaan.riwayat_servis.index') }}"
                        class="flex items-center space-x-3 px-4 py-0.5 rounded-lg transition"
                        style="background-color: {{ request()->routeIs('pemeliharaan.riwayat_servis.index') ? $sidebarHoverColor : 'transparent' }};"
                        onmouseover="this.style.backgroundColor='{{ $sidebarHoverColor }}'"
                        onmouseout="this.style.backgroundColor='{{ request()->routeIs('pemeliharaan.riwayat_servis.index') ? $sidebarHoverColor : 'transparent' }}'">
                        <i class="fas fa-wrench w-5 text-sm"></i>
                        <span>Riwayat Servis</span>
                    </a>
                </div>
            </div>
        @endif
    </nav>

    <!-- GARIS PEMISAH -->
    <div class="border-t mx-4 mt-4" style="border-color: {{ $sidebarHoverColor }};"></div>

    <!-- JAM REALTIME + INFO BUTTON -->
    <div class="p-4 text-sm text-center" style="color: {{ $sidebarTextColor }};">
        <div id="sidebar-date" class="text-sm font-medium"></div>
        <div class="flex items-center justify-center gap-2">
            <div id="sidebar-time" class="text-md font-bold tracking-wide"></div>
            <!-- INFO BUTTON (lebih kecil & pas di kanan jam) -->
            <button onclick="openInfoModal()"
                class="w-3.5 h-3.5 flex items-center justify-center rounded-full border transition duration-200 shadow-sm"
                style="border-color: {{ $sidebarTextColor }};"
                onmouseover="this.style.backgroundColor='{{ $sidebarHoverColor }}'"
                onmouseout="this.style.backgroundColor='transparent'">
                <i class="fas fa-info text-[6px]" style="color: {{ $sidebarTextColor }};"></i>
            </button>
            <!--
            <button onclick="openInfoModal()"
                class="w-3.5 h-3.5 flex items-center justify-center rounded-full border border-white hover:bg-blue-700 transition duration-200 shadow-sm">
                <i class="fas fa-info text-[6px] text-white"></i>
            </button>
            -->
        </div>
    </div>
</aside>

<!-- INFO MODAL -->
<div id="infoModal" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-[9999] pointer-events-auto">
    <div id="infoModalContent" class="relative bg-white rounded-2xl shadow-2xl w-100 p-6 text-center transform scale-90 opacity-0 transition-all duration-300">
        <button onclick="closeInfoModal()"
            class="absolute top-2 right-3 text-gray-400 hover:text-gray-700 transition duration-200 text-lg">
            <i class="fas fa-times"></i>
        </button>

        <div class="mb-4">
            <div class="w-12 h-12 mx-auto rounded-full bg-blue-100 flex items-center justify-center">
                <i class="fas fa-info text-blue-700"></i>
            </div>
        </div>
        <h3 class="text-lg font-semibold mb-2 text-gray-800">
            Informasi Website
        </h3>
        <p class="text-sm text-gray-600 mb-2">
            oleh tim IT/Pengelola Kesisteman & tim Pengelola BMN<br>
            magang Kemnaker Batch 2 (2025/2026)
        </p>
    </div>
</div>

<script>
    // live jam
    function updateSidebarClock() {
        const now = new Date();

        const optionsDate = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };

        const date = now.toLocaleDateString('id-ID', optionsDate);
        const time = now.toLocaleTimeString('id-ID');

        document.getElementById('sidebar-date').innerText = date;
        document.getElementById('sidebar-time').innerText = time;
    }

    updateSidebarClock();
    setInterval(updateSidebarClock, 1000);

    // dropdown master barang persediaan
    function toggleMasterBarangPersediaan() {
        const menu = document.getElementById('master-persediaan-menu');
        const arrow = document.getElementById('master-persediaan-arrow');

        if (menu.classList.contains('max-h-0')) {
            menu.classList.remove('max-h-0', 'opacity-0');
            menu.classList.add('max-h-96', 'opacity-100');
            arrow.classList.add('rotate-180');
        } else {
            menu.classList.remove('max-h-96', 'opacity-100');
            menu.classList.add('max-h-0', 'opacity-0');
            arrow.classList.remove('rotate-180');
        }
    }

    // dropdown master barang BMN
    function toggleMasterBarangBMN() {
        const menu = document.getElementById('master-bmn-menu');
        const arrow = document.getElementById('master-bmn-arrow');

        if (menu.classList.contains('max-h-0')) {
            menu.classList.remove('max-h-0', 'opacity-0');
            menu.classList.add('max-h-96', 'opacity-100');
            arrow.classList.add('rotate-180');
        } else {
            menu.classList.remove('max-h-96', 'opacity-100');
            menu.classList.add('max-h-0', 'opacity-0');
            arrow.classList.remove('rotate-180');
        }
    }

    // dropdown riwayat pengajuan barang
    function toggleRiwayatPengajuanBarang() {
        const menu = document.getElementById('riwayat-pengajuan-menu');
        const arrow = document.getElementById('riwayat-pengajuan-arrow');

        if (menu.classList.contains('max-h-0')) {
            menu.classList.remove('max-h-0', 'opacity-0');
            menu.classList.add('max-h-96', 'opacity-100');
            arrow.classList.add('rotate-180');
        } else {
            menu.classList.remove('max-h-96', 'opacity-100');
            menu.classList.add('max-h-0', 'opacity-0');
            arrow.classList.remove('rotate-180');
        }
    }

    // dropdown master barang
    function toggleMasterBarang() {
        const menu = document.getElementById('master-barang-menu');
        const arrow = document.getElementById('master-barang-arrow');

        if (menu.classList.contains('max-h-0')) {
            menu.classList.remove('max-h-0', 'opacity-0');
            menu.classList.add('max-h-96', 'opacity-100');
            arrow.classList.add('rotate-180');
        } else {
            menu.classList.remove('max-h-96', 'opacity-100');
            menu.classList.add('max-h-0', 'opacity-0');
            arrow.classList.remove('rotate-180');
        }
    }

    // dropdown form pengajuan barang
    function toggleFormPengajuanBarang() {
        const menu = document.getElementById('form-pengajuan-menu');
        const arrow = document.getElementById('form-pengajuan-arrow');

        if (menu.classList.contains('max-h-0')) {
            menu.classList.remove('max-h-0', 'opacity-0');
            menu.classList.add('max-h-96', 'opacity-100');
            arrow.classList.add('rotate-180');
        } else {
            menu.classList.remove('max-h-96', 'opacity-100');
            menu.classList.add('max-h-0', 'opacity-0');
            arrow.classList.remove('rotate-180');
        }
    }

    // dropdown master riwayat pemeliharaan
    function toggleMasterPemeliharaan() {
        const menu = document.getElementById('master-riwayat-pemeliharaan-menu');
        const arrow = document.getElementById('master-riwayat-pemeliharaan-arrow');

        if (menu.classList.contains('max-h-0')) {
            menu.classList.remove('max-h-0', 'opacity-0');
            menu.classList.add('max-h-96', 'opacity-100');
            arrow.classList.add('rotate-180');
        } else {
            menu.classList.remove('max-h-96', 'opacity-100');
            menu.classList.add('max-h-0', 'opacity-0');
            arrow.classList.remove('rotate-180');
        }
    }

    // modal info
    function openInfoModal() {
        const modal = document.getElementById('infoModal');
        const content = document.getElementById('infoModalContent');

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        setTimeout(() => {
            modal.classList.add('opacity-100');
            content.classList.remove('scale-90', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeInfoModal() {
        const modal = document.getElementById('infoModal');
        const content = document.getElementById('infoModalContent');

        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-90', 'opacity-0');

        setTimeout(() => {
            modal.classList.remove('opacity-100');
            modal.classList.add('hidden');
        }, 250);
    }

    // Klik luar modal untuk menutup
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('infoModal');
        if (e.target === modal) {
            closeInfoModal();
        }
    });
</script>
