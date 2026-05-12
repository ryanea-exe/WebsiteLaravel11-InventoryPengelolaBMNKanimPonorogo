@extends('layouts.app')

@section('title', 'Laporan')

@section('page-title', 'Laporan')
@section('breadcrumb')
    <p class="text-gray-800 cursor-pointer">
        Laporan
    </p>
@endsection

@section('content')
<div class="space-y-4">
    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-sm p-4">
        <h3 class="text-lg font-bold mb-4 text-gray-800">
            <i class="fas fa-filter mr-3"></i>Filter Laporan
        </h3>

        @php
            $jenisDipilih = request()->filled('jenis_laporan');

            $defaultDari = $jenisDipilih
                ? request('tanggal_dari', now()->startOfMonth()->toDateString())
                : '';
            $defaultSampai = $jenisDipilih
                ? request('tanggal_sampai', now()->toDateString())
                : '';
        @endphp

        <form id="form-laporan" action="{{ route('laporan.generate') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="jenis_laporan" class="block text-sm font-medium text-gray-700 mb-1">Jenis Laporan</label>
                    <select id="jenis_laporan" name="jenis_laporan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">-- Pilih Jenis Laporan --</option>
                        <option value="semua_bmn" {{ request('jenis_laporan')=='semua_bmn'?'selected':'' }}>
                            BMN - Semua Transaksi
                        </option>
                        <option value="semua" {{ request('jenis_laporan')=='semua'?'selected':'' }}>
                            Persediaan - Semua Transaksi
                        </option>
                        <option value="barang_masuk" {{ request('jenis_laporan')=='barang_masuk'?'selected':'' }}>
                            Persediaan - Barang Masuk
                        </option>
                        <option value="barang_keluar" {{ request('jenis_laporan') == 'barang_keluar' ? 'selected' : '' }}>
                            Persediaan - Barang Keluar
                        </option>
                        <option value="stok_barang">
                            Persediaan - Stok Barang
                        </option>
                    </select>
                </div>
                <div>
                    <label for="tanggal_dari" class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                    <input type="date" id="tanggal_dari" name="tanggal_dari" value="{{ $defaultDari }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg
                                focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        {{ !request()->filled('jenis_laporan') ? 'disabled' : '' }}>
                </div>
                <div>
                    <label for="tanggal_sampai" class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                    <input type="date" id="tanggal_sampai" name="tanggal_sampai" value="{{ $defaultSampai }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg
                                focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        {{ !request()->filled('jenis_laporan') ? 'disabled' : '' }}>
                </div>
                <div>
                    <label for="kategori" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select id="kategori" name="kategori" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" disabled>
                        <option value="">Semua Kategori</option>
                        <option value="Elektronik">Elektronik</option>
                        <option value="Furniture">Furniture</option>
                        <option value="Kendaraan">Kendaraan</option>
                        <option value="Alat Tulis">Alat Tulis</option>
                    </select>
                </div>
            </div>

            <div class="flex gap-2 mt-4">
                <button type="submit" name="submit_type" value="view"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-normal text-sm">
                    <i class="fas fa-search mr-1"></i> Tampilkan Laporan
                </button>
                <button type="submit" name="submit_type" value="pdf"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-normal text-sm">
                    <i class="fas fa-file-pdf mr-1"></i> Export PDF
                </button>
                <!--
                <button type="submit" name="submit_type" value="excel"
                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-normal text-sm transition">
                    <i class="fas fa-file-excel mr-1"></i> Export Excel
                </button>
                -->
            </div>
        </form>

        <div id="alert-error" class="hidden mt-4 p-2 rounded-lg bg-red-100 text-red-800 border border-red-300 text-sm">
            <strong>Gagal!</strong>
            Laporan PDF belum ditampilkan, Silakan pilih Jenis Laporan dan rentang tanggal terlebih dahulu!
        </div>
    </div>

    @php
        \Carbon\Carbon::setLocale('id');
    @endphp
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-sm p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">BMN - Total Semua Transaksi</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $totalTransaksiBMN ?? 0 }}</h3>
                    <p class="text-blue-100 text-xs mt-1">Periode {{ now()->translatedFormat('F Y') }}</p>
                </div>
                <div class="w-14 h-14 flex-shrink-0 aspect-square bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-exchange-alt text-2xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-sm p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Persediaan - Total Semua Transaksi</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $totalTransaksi ?? 0 }}</h3>
                    <p class="text-blue-100 text-xs mt-1">Periode {{ now()->translatedFormat('F Y') }}</p>
                </div>
                <div class="w-14 h-14 flex-shrink-0 aspect-square bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-exchange-alt text-2xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-sm p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Persediaan - Total Transaksi Barang Masuk</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $totalBarangMasuk ?? 0 }}</h3>
                    <p class="text-green-100 text-xs mt-1">Periode {{ now()->translatedFormat('F Y') }}</p>
                </div>
                <div class="w-14 h-14 flex-shrink-0 aspect-square bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-arrow-down text-2xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-sm p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Persediaan - Total Transaksi Barang Keluar</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $totalBarangKeluar ?? 0 }}</h3>
                    <p class="text-orange-100 text-xs mt-1">Periode {{ now()->translatedFormat('F Y') }}</p>
                </div>
                <div class="w-14 h-14 flex-shrink-0 aspect-square bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-arrow-up text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Hasil Laporan -->
    <div id="result-area" class="bg-white rounded-lg shadow-sm">
        <div id="report-content">
            @include('pages.laporan.table')
        </div>
    </div>
</div>

<!-- Style Animasi Laporan -->
<style>
    /* animasi isi laporan saja */
    .report-enter {
        opacity: 0;
        transform: translateY(10px);
    }

    .report-active {
        opacity: 1;
        transform: translateY(0);
        transition: all 0.3s ease;
    }

    /* spinner */
    .spinner {
        border: 4px solid #e5e7eb;
        border-top: 4px solid #2563eb;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>

<script>
    // pengisian tanggal otomatis setelah memilih laporan
    document.addEventListener('DOMContentLoaded', function () {
        const jenis = document.getElementById('jenis_laporan');
        const dari = document.getElementById('tanggal_dari');
        const sampai = document.getElementById('tanggal_sampai');

        function formatDate(date) {
            const year  = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day   = String(date.getDate()).padStart(2, '0');

            return `${year}-${month}-${day}`;
        }

        function setDefaultTanggal() {
            const today = new Date();
            const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);

            // enable input
            dari.disabled = false;
            sampai.disabled = false;

            // isi default hanya kalau kosong
            if (!dari.value) {
                dari.value = formatDate(firstDay);
            }

            if (!sampai.value) {
                sampai.value = formatDate(today);
            }
        }

        // 🔥 trigger saat jenis laporan berubah
        jenis.addEventListener('change', function () {
            if (this.value) {
                setDefaultTanggal();
            } else {
                // reset kalau jenis dikosongkan
                dari.value = '';
                sampai.value = '';
                dari.disabled = true;
                sampai.disabled = true;
            }
        });
    });

    // ajax penampilan laporan tanpa refresh
    document.getElementById('form-laporan').addEventListener('submit', function (e) {
        const submitType = e.submitter?.value;

        if (submitType === 'pdf' || submitType === 'excel') {
            return;
        }

        e.preventDefault();

        const form = this;
        const reportContent = document.getElementById('report-content');

        // tampilkan loading DI DALAM CARD
        reportContent.innerHTML = `
            <div class="flex justify-center items-center py-14">
                <div class="spinner"></div>
            </div>
        `;

        const url = form.action + '?' + new URLSearchParams(new FormData(form));

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.text())
        .then(html => {
            reportContent.innerHTML = `
                <div id="reportInner" class="report-enter">
                    ${html}
                </div>
            `;

            requestAnimationFrame(() => {
                document
                    .getElementById('reportInner')
                    .classList.add('report-active');
            });

        })
        .catch(() => {
            reportContent.innerHTML = `
                <div class="text-center text-red-600 py-4">
                    Gagal memuat laporan
                </div>
            `;
        });
    });

    // validasi export pdf jika belum pilih jenis laporan
    document.getElementById('form-laporan').addEventListener('submit', function (e) {
        const submitType = e.submitter?.value;
        const jenis = document.getElementById('jenis_laporan').value;
        const dari = document.getElementById('tanggal_dari').value;
        const sampai = document.getElementById('tanggal_sampai').value;

        // VALIDASI EXPORT PDF
        if (submitType === 'pdf') {
            if (!jenis || !dari || !sampai) {
                e.preventDefault();

                const alertBox = document.getElementById('alert-error');
                alertBox.classList.remove('hidden');

                // auto hide setelah 5 detik
                setTimeout(() => {
                    alertBox.style.transition = 'opacity 0.5s';
                    alertBox.style.opacity = '0';

                    setTimeout(() => {
                        alertBox.classList.add('hidden');
                        alertBox.style.opacity = '1';
                    }, 500);
                }, 5000);

                return;
            }

            return; // jika valid lanjut export
        }

        if (submitType === 'excel') {
            return;
        }

        // ==========================
        // AJAX tampilkan laporan
        // ==========================

        e.preventDefault();

        const form = this;
        const reportContent = document.getElementById('report-content');

        reportContent.innerHTML = `
            <div class="flex justify-center items-center py-14">
                <div class="spinner"></div>
            </div>
        `;

        const url = form.action + '?' + new URLSearchParams(new FormData(form));

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.text())
        .then(html => {
            reportContent.innerHTML = `
                <div id="reportInner" class="report-enter">
                    ${html}
                </div>
            `;

            requestAnimationFrame(() => {
                document
                    .getElementById('reportInner')
                    .classList.add('report-active');
            });

        })
        .catch(() => {
            reportContent.innerHTML = `
                <div class="text-center text-red-600 py-4">
                    Gagal memuat laporan
                </div>
            `;
        });
    });
</script>

@endsection
