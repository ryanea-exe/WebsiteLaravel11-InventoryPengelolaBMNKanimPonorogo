<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @if(request()->is('pemeliharaan*') || request()->is('dashboard2'))
            @yield('title') - {{ $namaAplikasi2 ?? 'Pemeliharaan' }}
        @else
            @yield('title') - {{ $namaAplikasi ?? 'Singobarong' }}
        @endif
    </title>

    <!-- Favicon -->
    @php
        $setting = \App\Models\Setting::first();
        $faviconPath = ($setting && $setting->logo)
            ? asset('storage/logo/'.$setting->logo)
            : asset('images/logo-ditjen-imigrasi.png');
    @endphp
    <link rel="icon" type="image/png" href="{{ $faviconPath }}">

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- FONT POPPINS -->
    <!-- <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet"> -->
    <!-- FONT ABRIL FATFACE & LATO -->
    <!-- <link href="https://fonts.googleapis.com/css2?family=Abril+Fatface&family=Lato:wght@300;400;700&display=swap" rel="stylesheet"> -->
    <!-- FONT GEIST -->
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@100..900&family=Geist+Mono:wght@100..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        body {
            /* font-family: 'Poppins', sans-serif; */
            /* font-family: 'Abril Fatface', serif; */ /* font-family: 'Lato', sans-serif; */
            font-family: 'Geist', sans-serif; /* font-family: 'Geist Mono', monospace; */
        }

        /* ================================
        GLOBAL PAGE TRANSITION EFFECT
        ================================ */
        .dropdown-menu {
            transition: all 0.2s ease-out;
            transform-origin: top right;
        }
        
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

        /* Sidebar smooth */
        #sidebar {
            transition: transform 0.3s ease-in-out;
        }

        /* Hover animation for cards, buttons, tables */
        button,
        a,
        .card,
        .table,
        .dataTables_wrapper {
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

        /* ================================
        GLOBAL MODAL ANIMATION SAFE
        ================================ */

        .modal-overlay {
            opacity: 0;
            transition: opacity 0.25s ease;
        }

        .modal-overlay.active {
            opacity: 1;
        }

        .modal-box {
            transform: scale(0.95) translateY(10px);
            opacity: 0;
            transition: all 0.25s ease;
        }

        .modal-box.active {
            transform: scale(1) translateY(0);
            opacity: 1;
        }

        /* Overlay */
        .modal {
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.25s ease;
        }

        .modal.show {
            opacity: 1;
            pointer-events: auto;
        }

        /* Modal Box */
        .modal-box {
            transform: scale(0.95) translateY(10px);
            opacity: 0;
            transition: all 0.25s ease;
        }

        .modal.show .modal-box {
            transform: scale(1) translateY(0);
            opacity: 1;
        }

        .modal.active {
            opacity: 1;
            pointer-events: auto;
        }
    </style>
    
    <style>
        /* --- Custom DataTables Styling --- */
        .dataTables_wrapper {
            font-size: 0.875rem;
            padding: 0 !important;
            padding-bottom: 8px !important;
            border-collapse: separate !important;
            border-spacing: 0;
            border-radius: 10px !important;
            overflow: hidden;
            background: white;
            overflow-x: unset !important;
            overflow-x: clip;
        }

        /* Rounded header kiri atas */
        table.dataTable thead th:first-child {
            border-top-left-radius: 10px;
        }

        /* Rounded header kanan atas */
        table.dataTable thead th:last-child {
            border-top-right-radius: 10px;
        }

        /* Rounded bawah kiri */
        table.dataTable tbody tr:last-child td:first-child {
            border-bottom-left-radius: 10px;
        }

        /* Rounded bawah kanan */
        table.dataTable tbody tr:last-child td:last-child {
            border-bottom-right-radius: 10px;
        }

        /* Tambahan agar terlihat seperti card */
        table.dataTable {
            width: 100% !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        /* Tabel Utama */
        table.dataTable.no-footer {
            border-bottom: 1px solid #e5e7eb !important;
            margin-bottom: 10px;
        }

        table.dataTable thead th {
            background-color: #E5E7EB !important;
            color: #374151 !important;
            font-weight: 600 !important;
            text-transform: uppercase;
            font-size: 0.75rem !important;
            letter-spacing: 0.05em;
            padding: 12px 16px !important;
            border-bottom: 1px solid #D1D5DB!important;
        }

        table.dataTable tbody td {
            font-size: 0.875rem;
            color: #374151;
        }

        table.dataTable td.dataTables_empty {
            padding: 1rem !important; /* sama dengan py-4 px-4 */
        }

        /* Hover Effect */
        table.dataTable tbody tr:hover {
            background-color: #F3F4F6 !important;
            transition: all 0.2s ease;
        }

        /* Search & Length Filter */
        .dataTables_filter {
            display: flex !important;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
            font-weight: 500;
            color: #374151;
        }

        .dataTables_filter label {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .dataTables_filter input {
            border: 1px solid #e2e8f0 !important;
            border-radius: 8px !important;
            padding: 6px 35px 6px 12px !important;
            outline: none !important;
            width: 250px !important;
            transition: all 0.2s ease;
        }

        .dataTables_filter input:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 0.5rem;
        }

        /* Icon di kanan */
        .dataTables_filter {
            position: relative;
        }

        .dataTables_filter::after {
            content: "\f002";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            position: absolute;
            right: 12px;
            top: 47.5%;
            transform: translateY(-50%);
            color: #9ca3af;
            pointer-events: none;
        }

        /* === Modern Length Menu === */
        .dataTables_length {
            display: flex !important;
            align-items: center;
            font-size: 0.875rem;
            font-weight: 400;
            color: #374151;
        }

        .dataTables_length select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;

            background-color: #ffffff;
            border: 1px solid #e2e8f0 !important;
            border-radius: 8px !important;
            padding: 6px 30px 6px 10px !important;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .dataTables_length select:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
            outline: none;
        }

        /* Custom arrow */
        .dataTables_length {
            position: relative;
        }

        .dataTables_length::after {
            content: "\f078";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            position: absolute;
            right: 45px;
            top: 47.5%;
            transform: translateY(-50%);
            color: #9ca3af;
            pointer-events: none;
        }

        /* Pagination Modern */
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border: 1px solid #e2e8f0 !important;
            background: white !important;
            border-radius: 6px !important;
            padding: 4px 12px !important;
            margin-left: 4px !important;
            font-weight: 500 !important;
            color: #64748b !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #2563eb !important;
            color: white !important;
            border-color: #2563eb !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.current) {
            background: #f1f5f9 !important;
            border-color: #cbd5e1 !important;
            color: #1e293b !important;
        }

        .dataTables_info {
            font-size: 0.85rem;
            padding-top: 1rem !important;
            color: #64748b !important;
        }
    </style>
</head>

<body class="bg-gray-100">
    <!-- MOBILE OVERLAY -->
    <div id="sidebarOverlay"
        onclick="toggleSidebar()"
        class="fixed inset-0 bg-black/40 z-20 hidden md:hidden transition-opacity duration-300">
    </div>

    <!-- LAYOUT 1 -->
    <div class="flex h-screen overflow-hidden">
        {{-- Sidebar --}}
        @include('components.sidebar')

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col min-h-screen overflow-x-hidden">

            {{-- Navbar --}}
            @include('components.navbar')

            {{-- Page Content --}}
            <main class="flex-1 overflow-y-auto overflow-x-hidden">
                <div class="p-3 sm:p-4 md:p-5">
                    @yield('content')
                </div>

                {{-- Footer --}}
                @include('components.footer')
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#dataTable').DataTable({
                responsive: true,
                pageLength: 25,
                ordering: true,
                searching: true,
                lengthChange: true,
                // autoWidth: false,   // 🔥 WAJIB
                // scrollX: false,     // 🔥 pastikan false
                language: {
                    search: "", // Hilangkan label Cari:
                    searchPlaceholder: "Cari data",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                    zeroRecords: "<span class='italic text-gray-700'>Data tidak ditemukan</span>",
                    paginate: {
                        previous: "<",
                        next: ">"
                    }
                },
                columnDefs: [
                    { orderable: false, targets: -1 }
                ]
                // columnDefs: [] // 🔥 override global
            });
        });

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            sidebar.classList.toggle('-translate-x-full');

            if (sidebar.classList.contains('-translate-x-full')) {
                overlay.classList.add('hidden');
            } else {
                overlay.classList.remove('hidden');
            }
        }

        function toggleDropdown(id) {
            const dropdown = document.getElementById(id);

            // Tutup dropdown lain
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                if (menu !== dropdown) {
                    closeDropdown(menu);
                }
            });

            // Toggle dropdown ini
            if (dropdown.classList.contains('opacity-0')) {
                openDropdown(dropdown);

                // 🔽 TAMBAHAN: rotate icon jika profile
                if (id === 'profileDropdown') {
                    document.getElementById('profileChevron')?.classList.add('rotate-180');
                }

            } else {
                closeDropdown(dropdown);

                // 🔼 RESET ROTATE
                if (id === 'profileDropdown') {
                    document.getElementById('profileChevron')?.classList.remove('rotate-180');
                }
            }
        }

        function openDropdown(dropdown) {
            dropdown.classList.remove('opacity-0', 'scale-95', 'translate-y-2', 'pointer-events-none');
            dropdown.classList.add('opacity-100', 'scale-100', 'translate-y-0');
        }

        function closeDropdown(dropdown) {
            dropdown.classList.add('opacity-0', 'scale-95', 'translate-y-2', 'pointer-events-none');
            dropdown.classList.remove('opacity-100', 'scale-100', 'translate-y-0');

            // 🔽 tambahan reset icon jika ini profile dropdown
            if (dropdown.id === 'profileDropdown') {
                document.getElementById('profileChevron')?.classList.remove('rotate-180');
            }
        }

        // Klik di luar → tutup dropdown
        document.addEventListener('click', function (e) {
            document.querySelectorAll('.dropdown').forEach(dropdownWrapper => {
                const menu = dropdownWrapper.querySelector('.dropdown-menu');
                const button = dropdownWrapper.querySelector('button');

                if (!dropdownWrapper.contains(e.target)) {
                    closeDropdown(menu);
                }
            });
        });
    </script>

    <!-- ANIMASI & TRANSISI -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const main = document.querySelector("main");
            main.classList.add("content-loaded");
        });

        // Transisi hanya untuk link di dalam aplikasi
        document.querySelectorAll("a").forEach(link => {
            if (link.hostname === window.location.hostname) {
                link.addEventListener("click", function (e) {
                    const target = link.getAttribute("href");

                    if (target && 
                        !target.startsWith("#") &&
                        !link.hasAttribute("target") &&
                        !link.closest("#sidebar") // ⬅ Sidebar tidak ikut efek
                    ) {
                        e.preventDefault();

                        const main = document.querySelector("main");
                        main.classList.remove("content-loaded");
                        main.classList.add("content-fade-out");

                        setTimeout(() => {
                            window.location = target;
                        }, 250);
                    }
                });
            }
        });

        /* =========================================
        GLOBAL MODAL ANIMATION CONTROLLER
        Aman tanpa observer
        ========================================= */
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            if (!modal) return;

            const box = modal.querySelector('div');

            modal.classList.remove('hidden');
            modal.classList.add('modal-overlay');

            if (box) {
                box.classList.add('modal-box');
            }

            // Trigger animation
            setTimeout(() => {
                modal.classList.add('active');
                box?.classList.add('active');
            }, 10);
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (!modal) return;

            const box = modal.querySelector('div');

            modal.classList.remove('active');
            box?.classList.remove('active');

            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200);
        }

        document.addEventListener("DOMContentLoaded", function () {
            // Tandai semua modal otomatis
            document.querySelectorAll('[id$="Modal"]').forEach(modal => {
                modal.classList.add('modal');

                const box = modal.querySelector('div');
                if (box) {
                    box.classList.add('modal-box');
                }
            });

        });
    </script>

    @stack('scripts')
</body>
</html>
