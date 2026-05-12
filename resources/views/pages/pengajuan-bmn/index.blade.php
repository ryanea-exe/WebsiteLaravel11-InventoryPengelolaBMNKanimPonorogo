@extends('layouts.app')

@section('title', 'Pengajuan Barang BMN')

@section('page-title', 'Pengajuan Barang BMN')
@section('breadcrumb')
    <p class="text-gray-800 cursor-pointer">
        Pengajuan Barang BMN
    </p>
@endsection

@section('content')

@php
    \Carbon\Carbon::setLocale('id'); 
@endphp

<div class="space-y-4">
    <form action="{{ route('pengajuan-bmn.store') }}" method="POST"
        class="bg-white rounded-lg shadow-sm">
        @csrf

        <!-- Header -->
        <div class="px-4 py-4 border-b mb-4">
            <h1 class="text-xl font-bold text-gray-800">
                <i class="fas fa-file-signature mr-3"></i>Form Pengajuan Barang BMN
            </h1>
        </div>

        <!-- Scrollable Field -->
        <div class="px-4 pb-2 overflow-y-auto flex-1">
            <!-- ALERT -->
            <div id="alert-container">
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
            </div>

            <!-- Tanggal Pengajuan -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pengajuan <span class="text-red-500">*</span></label>
                <input type="text" value="{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}"
                    class="w-full border rounded-lg px-4 py-2 bg-gray-100 cursor-not-allowed" 
                    readonly>
            </div>
            <!-- Pilih Barang -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Barang <span class="text-red-500">*</span>
                </label>
                <button type="button" onclick="openBarangModal()"
                    class="mb-2 px-4 py-2 font-normal text-sm rounded-lg bg-green-600 text-white hover:bg-green-700 transition">
                    <i class="fas fa-plus-square mr-1"></i> Tambah Barang
                </button>
                <div class="overflow-x-auto" style="box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);">
                    <table class="w-full rounded-lg overflow-hidden">
                        <thead class="bg-gray-200 border-b border-gray-300">
                            <tr>
                                <th class="px-4 py-3 text-xs font-semibold text-gray-700 text-left uppercase tracking-wider w-8">No</th>
                                <th class="px-4 py-3 text-xs font-semibold text-gray-700 text-left uppercase tracking-wider">Nama Barang</th>
                                <th class="px-4 py-3 text-xs font-semibold text-gray-700 text-left uppercase tracking-wider">Merk/Type</th>
                                <th class="px-4 py-3 text-xs font-semibold text-gray-700 text-left uppercase tracking-wider">Jumlah</th>
                                <th class="px-4 py-3 text-xs font-semibold text-gray-700 text-left uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="selectedItems" class="border-b border-gray-300">
                            <tr id="emptyRow">
                                <td colspan="5"
                                    class="px-4 py-4 text-center text-sm text-gray-700 italic">
                                    Belum ada barang dipilih
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p id="barangError" class="text-sm text-red-500 mt-1 hidden opacity-0 transition-opacity duration-500">
                    Barang wajib dipilih minimal 1.
                </p>
            </div>
            <!-- Keperluan -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Keperluan Penggunaan <span class="text-red-500">*</span></label>
                <textarea name="keperluan" rows="2"
                    class="w-full border rounded-lg px-4 py-2" required></textarea>
            </div>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-2 px-4 py-4 border-t border-gray-200">
            <button type="reset"
                class="px-3 py-1.5 font-normal text-sm border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100">
                Reset
            </button>
            <button type="submit"
                class="px-3 py-1.5 font-normal text-sm rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                <i class="fas fa-file-signature mr-1"></i> Ajukan Permintaan
            </button>
        </div>
    </form>
</div>

<!-- MODAL PILIH BARANG -->
<div id="barangModal" class="modal fixed inset-0 bg-black/50 z-[9999] hidden flex items-center justify-center p-4">
    <div class="modal-box bg-white rounded-2xl w-full max-w-3xl shadow-lg max-h-[90vh] overflow-y-auto">
        <!-- HEADER -->
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">
                Pilih Barang BMN
            </h3>
            <button onclick="closeModal('barangModal')"
                class="text-gray-500 hover:text-gray-800">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- BODY -->
        <div class="p-4 overflow-x-auto max-h-[400px]">
            <div class="relative mb-4">
                <input type="text" id="searchBarangMasuk" placeholder="Cari barang..."
                    class="w-full px-4 py-2 pr-20 border rounded-lg focus:ring-2 focus:ring-blue-500">
                <!-- Tombol Clear -->
                <button type="button" id="clearSearchBarangMasuk"
                    class="hidden absolute right-10 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
                <!-- Icon Search -->
                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                    <i class="fas fa-search"></i>
                </span>
            </div>
            <table class="w-full rounded-lg overflow-hidden" style="box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);">
                <thead class="bg-gray-200 border-b border-gray-300">
                    <tr class="uppercase">
                        <th onclick="sortTable('kode_barang')"
                            class="px-4 py-3 text-xs font-semibold text-gray-700 text-left uppercase tracking-wider cursor-pointer select-none">
                            Kode Barang <i class="fas fa-sort ml-1 text-xs text-gray-500"></i>
                        </th>
                        <th onclick="sortTable('nama_barang')"
                            class="px-4 py-3 text-xs font-semibold text-gray-700 text-left uppercase tracking-wider cursor-pointer select-none">
                            Nama Barang <i class="fas fa-sort ml-1 text-xs text-gray-500"></i>
                        </th>
                        <th onclick="sortTable('merk_type')"
                            class="px-4 py-3 text-xs font-semibold text-gray-700 text-left uppercase tracking-wider cursor-pointer">
                            Merk/Type <i class="fas fa-sort ml-1 text-xs text-gray-500"></i>
                        </th>
                        <th onclick="sortTable('jumlah')"
                            class="px-4 py-3 text-xs font-semibold text-gray-700 text-left uppercase tracking-wider cursor-pointer select-none">
                            Stok <i class="fas fa-sort ml-1 text-xs text-gray-500"></i>
                        </th>
                        <th class="px-4 py-3 text-xs font-semibold text-gray-700 text-left uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody id="barangTableBody" class="divide-y divide-gray-300 border-b border-gray-300"></tbody>
            </table>

            <div class="flex justify-between items-center mt-4">
                <p class="text-xs text-gray-500" id="paginationInfo"></p>
                <div class="space-x-2">
                    <button onclick="prevPage()"
                        class="px-3 py-1 border rounded hover:bg-gray-100 text-gray-700">
                        <
                    </button>
                    <button onclick="nextPage()"
                        class="px-3 py-1 border rounded hover:bg-gray-100 text-gray-700">
                        >
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    let selectedItems = {};
    
    function updateNomor() {
        const rows = document.querySelectorAll('#selectedItems tr');
        let no = 1;

        rows.forEach(row => {
            if (row.id !== 'emptyRow') {
                row.querySelector('.col-no').innerText = no++;
            }
        });
    }

    const form = document.querySelector("form[action='{{ route('pengajuan.store') }}']");
    form.addEventListener("submit", function(e){
        e.preventDefault();

        const barangError = document.getElementById("barangError");

        if(Object.keys(selectedItems).length === 0){
            barangError.classList.remove("hidden");
            barangError.style.opacity = "1";
            barangError.scrollIntoView({behavior:"smooth", block:"center"});
            return;
        }

        const formData = new FormData(form);

        fetch(form.action, {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(res => res.json())
        .then(res => {
            if(res.success){
                // refresh halaman
                location.reload();
            }else{
                showAlert("error", res.message);
            }
        })
        .catch(() => {
            showAlert("error","Terjadi kesalahan saat memproses pengajuan.");
        });
    });

    // RENDER TABLE
    function renderTable() {
        const start = (currentPage - 1) * perPage;
        const end = start + perPage;
        const pageData = filteredData.slice(start, end);
        const tbody = document.getElementById('barangTableBody');
        tbody.innerHTML = '';

        if (filteredData.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="4" class="px-4 py-4 text-center text-sm text-gray-700 italic">
                        Barang tidak ditemukan
                    </td>
                </tr>
            `;
            document.getElementById('paginationInfo').innerText = '';
            return;
        }

        pageData.forEach(b => {
            const stokHabis = b.jumlah <= 0;
            const sudahDipilih = selectedItems[b.id];
            const stok = stokHabis
                ? '<span class="text-red-600 text-sm font-medium">Stok Habis</span>'
                : b.jumlah;

            tbody.innerHTML += `
            <tr class="${stokHabis ? 'bg-red-50' : 'hover:bg-gray-100'}">
                <td class="px-4 py-1 text-sm font-normal text-gray-700">${b.kode_barang}</td>
                <td class="px-4 py-1 text-sm font-normal text-gray-700">${b.nama_barang}</td>
                <td class="px-4 py-1 text-sm font-normal text-gray-700">${b.merk_type}</td>
                <td class="px-4 py-1 text-sm font-normal text-gray-700">${stok}</td>
                <td class="px-4 py-1 text-sm text-gray-700">
                    ${stokHabis ? 
                        '<button disabled class="px-3 py-1 bg-gray-300 text-gray-600 rounded text-xs">Tidak Tersedia</button>' :
                        sudahDipilih ?
                        '<button disabled class="px-3 py-1 bg-blue-100 text-blue-600 rounded text-xs">Sudah Dipilih</button>' :
                        `<button onclick="selectBarang('${b.id}','${b.nama_barang}','${b.merk_type}','${b.jumlah}')"
                            class="px-3 py-1 bg-green-600 text-white rounded text-xs hover:bg-green-700">
                            Pilih
                        </button>`
                    }
                </td>
            </tr>`;
        });

        document.getElementById('paginationInfo').innerText =
            `Menampilkan ${start + 1} – ${Math.min(end, filteredData.length)} dari ${filteredData.length} data`;
    }

    function selectBarang(id, nama, merk, stok) {
        if (selectedItems[id]) return;

        document.getElementById("barangError").classList.add("hidden");
        selectedItems[id] = { nama, jumlah: 1 };
        document.getElementById('emptyRow')?.remove();

        document.getElementById('selectedItems').insertAdjacentHTML('beforeend', `
            <tr id="row-${id}" class="hover:bg-blue-100 transition">
                <td class="px-4 py-1 text-sm text-gray-700 col-no"></td>
                <td class="px-4 py-1 text-sm text-gray-700">
                    ${nama}
                    <input type="hidden" name="barang_id[]" value="${id}">
                    <input type="hidden" class="stok-barang" value="${stok}">
                </td>
                <td class="px-4 py-1 text-sm text-gray-700">
                    ${merk ?? '-'}
                </td>
                <td class="px-4 py-1 text-sm text-gray-700">
                    <input type="number"
                        name="jumlah[]"
                        min="1"
                        value="1"
                        data-stok="${stok}"
                        onchange="validasiStok(this)"
                        class="w-20 px-2 py-1 rounded-lg border border-blue-200 bg-white/60 backdrop-blur-sm focus:border-blue-500 input-focus text-sm">
                </td>
                <td class="px-4 py-1 text-sm text-gray-700">
                    <button type="button" onclick="removeItem(${id})"
                        class="px-3 py-1.5 rounded-lg bg-red-100 text-red-600 hover:bg-red-200 transition text-xs font-normal">
                        Hapus
                    </button>
                </td>
            </tr>
        `);
        updateNomor();

        document.getElementById("barangError").style.opacity = "0";
        setTimeout(() => {
            document.getElementById("barangError").classList.add("hidden");
        }, 500);

        renderTable();
        closeBarangModal();
    }

    /*
    function validasiStok(input){
        const stok = parseInt(input.dataset.stok);
        const jumlah = parseInt(input.value);

        if(jumlah > stok){
            alert(`Jumlah melebihi stok tersedia (${stok})`);

            input.value = stok;
        }
    }
    */

    function showAlert(type, message) {
        const container = document.getElementById("alert-container");

        let color = "green";

        if(type === "error"){
            color = "red";
        }

        container.innerHTML = `
            <div id="alert-message"
                class="mb-2 p-2 rounded-lg bg-${color}-100 text-${color}-800 border border-${color}-300">
                <strong>${type === 'error' ? 'Gagal!' : 'Sukses!'}</strong> ${message}
            </div>
        `;

        setTimeout(function () {
            const alert = document.getElementById('alert-message');
            if (alert) {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        }, 5000);
    }

    function removeItem(id) {
        delete selectedItems[id];
        document.getElementById(`row-${id}`).remove();

        if (Object.keys(selectedItems).length === 0) {
            document.getElementById('selectedItems').innerHTML = `
                <tr id="emptyRow">
                    <td colspan="5" class="px-4 py-4 text-center text-sm text-gray-700 italic">
                        Belum ada barang dipilih
                    </td>
                </tr>`;
        }
        renderTable();
    }

    function resetItems() {
        selectedItems = {};
        document.getElementById('selectedItems').innerHTML = `
            <tr id="emptyRow">
                <td colspan="5" class="px-4 py-4 text-center text-sm text-gray-700 italic">
                    Belum ada barang dipilih
                </td>
            </tr>`;
    }

    document.addEventListener('DOMContentLoaded', () => {
        const box = document.getElementById('errorModalBox');

        if (!box) return;

        setTimeout(() => {
            box.classList.remove('scale-95', 'opacity-0');
            box.classList.add('scale-100', 'opacity-100');
        }, 10);

        document.body.classList.add('overflow-hidden');
    });
</script>

<script>
    // modal show table data
    function openBarangModal() {
        openModal('barangModal');
    }

    function closeBarangModal() {
        closeModal('barangModal');
    }

    const dataBarang = @json($barangs);

    let filteredData = [...dataBarang];
    let currentPage = 1;
    const perPage = 25;
    let sortField = null;
    let sortAsc = true;

    // SEARCH REALTIME
    document.getElementById('searchBarangMasuk').addEventListener('input', function () {
        const keyword = this.value.toLowerCase();
        filteredData = dataBarang.filter(b =>
            b.nama_barang.toLowerCase().includes(keyword) ||
            b.kode_barang.toLowerCase().includes(keyword)
        );

        currentPage = 1;
        renderTable();
    });

    // SORTING
    function sortTable(field) {
        sortAsc = sortField === field ? !sortAsc : true;
        sortField = field;

        filteredData.sort((a, b) => {
            if (a[field] < b[field]) return sortAsc ? -1 : 1;
            if (a[field] > b[field]) return sortAsc ? 1 : -1;
            return 0;
        });

        renderTable();
    }

    // PAGINATION
    function nextPage() {
        if (currentPage * perPage < filteredData.length) {
            currentPage++;
            renderTable();
        }
    }
    function prevPage() {
        if (currentPage > 1) {
            currentPage--;
            renderTable();
        }
    }

    // INIT
    renderTable();

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
