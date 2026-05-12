@extends('layouts.app')

@section('title', 'Pengajuan Pemeliharaan')

@section('page-title', 'Pengajuan Pemeliharaan')
@section('breadcrumb')
<p class="text-gray-800 cursor-pointer">
    Pengajuan Pemeliharaan Kendaraan
</p>
@endsection

@section('content')

@php 
    \Carbon\Carbon::setLocale('id'); 
@endphp

<div class="space-y-4">
    <form action="{{ route('pemeliharaan.store') }}" method="POST"
        class="bg-white rounded-lg shadow-sm">
        @csrf

        <!-- HEADER -->
        <div class="px-4 py-4 border-b mb-4">
            <h1 class="text-xl font-bold text-gray-800">
                <i class="fas fa-file-signature mr-3"></i>Form Pengajuan Pemeliharaan Kendaraan
            </h1>
        </div>

        <!-- BODY -->
        <div class="px-4 pb-2">
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

            <!-- TANGGAL -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pengajuan <span class="text-red-500">*</span></label>
                <input type="text" name="tanggal_pengajuan" value="{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}"
                    class="w-full border rounded-lg px-4 py-2 bg-gray-100 cursor-not-allowed" readonly>
            </div>
            <!-- PILIH KENDARAAN -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Kendaraan <span class="text-red-500">*</span></label>
                <button type="button" onclick="openKendaraanModal()"
                    class="px-4 py-2 text-sm rounded-lg bg-green-600 text-white hover:bg-green-700 whitespace-nowrap text-md mb-2">
                    <i class="fas fa-search mr-1 text-xs"></i> Pilih Kendaraan
                </button>
                <div class="w-full overflow-x-auto" style="box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);">
                    <table class="w-full rounded-lg overflow-hidden">
                        <thead class="bg-gray-200 border-b border-gray-300">
                            <tr>
                                <th class="px-4 py-3 text-xs font-semibold text-gray-700 text-left uppercase tracking-wider">Nomor Polisi</th>
                                <th class="px-4 py-3 text-xs font-semibold text-gray-700 text-left uppercase tracking-wider">Nama Kendaraan</th>
                                <th class="px-4 py-3 text-xs font-semibold text-gray-700 text-left uppercase tracking-wider">Tahun</th>
                                <th class="px-4 py-3 text-xs font-semibold text-gray-700 text-left uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="selectedKendaraanTable" class="border-b border-gray-300">
                            <tr id="emptyKendaraanRow">
                                <td colspan="4"
                                    class="px-4 py-4 text-center text-sm text-gray-700 italic">
                                    Belum ada kendaraan dipilih
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <input type="hidden" name="kendaraan_id" id="kendaraan_id">
                <p id="kendaraanError" 
                    class="text-sm text-red-500 mt-1 hidden opacity-0 transition-opacity duration-500">
                    Kendaraan belum dipilih.
                </p>
            </div>
            <!-- KEPERLUAN & ESTIMASI BIAYA -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Keperluan & Estimasi Biaya <span class="text-red-500">*</span></label></label>
                <div id="detailContainer" class="space-y-2 mb-2">
                    <div class="detail-row flex items-center gap-2">
                        <span class="detail-number w-4 text-sm text-gray-600">1.</span>
                        <input type="text" name="keperluan[]" placeholder="Keperluan"
                            class="flex-1 border px-3 py-2 rounded-lg text-sm" required>
                        <input type="number" name="estimasi_biaya[]" placeholder="Estimasi Biaya (Rp.)"
                            class="flex-1 border px-3 py-2 rounded-lg text-sm" required>
                        <button type="button" onclick="hapusDetail(this)"
                            class="w-8 h-8 flex items-center justify-center rounded-full bg-red-100 text-red-600 hover:bg-red-200">
                            -
                        </button>
                    </div>
                </div>
                <button type="button" onclick="tambahDetail()"
                    class="mb-2 px-2 py-1 text-xs rounded-lg bg-green-600 text-white hover:bg-green-700">
                    <i class="fas fa-plus-square mr-1"></i> Tambah Keperluan
                </button>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="flex justify-end gap-2 px-4 py-4 border-t border-gray-200">
            <button type="reset" onclick="resetForm()"
                class="px-3 py-1.5 text-sm border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100">
                Reset
            </button>
            <button type="submit"
                class="px-3 py-1.5 text-sm rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                <i class="fas fa-file-signature mr-1"></i> Ajukan Pemeliharaan
            </button>
        </div>
    </form>
</div>

<!-- MODAL PILIH KENDARAAN -->
<div id="kendaraanModal" class="modal fixed inset-0 bg-black/50 z-[9999] hidden flex items-center justify-center p-4">
    <div class="modal-box bg-white rounded-2xl w-full max-w-3xl shadow-lg max-h-[90vh] overflow-y-auto">
        <!-- HEADER -->
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">
                Pilih Kendaraan
            </h3>
            <button onclick="closeModal('kendaraanModal')"
                class="text-gray-500 hover:text-gray-800">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- BODY -->
        <div class="p-4 overflow-x-auto max-h-[400px]">
            <!-- SEARCH -->
            <div class="relative mb-4">
                <input type="text" id="searchKendaraan"
                    placeholder="Cari kendaraan..."
                    class="w-full px-4 py-2 pr-20 border rounded-lg focus:ring-2 focus:ring-blue-500">
                <button type="button" id="clearSearchKendaraan"
                    class="hidden absolute right-10 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                    <i class="fas fa-search"></i>
                </span>
            </div>
            <!-- TABLE -->
            <table class="w-full rounded-lg overflow-hidden" style="box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                <thead class="bg-gray-200 border-b border-gray-300">
                    <tr class="uppercase">
                        <th class="px-4 py-3 text-xs font-semibold text-gray-700 text-left">
                            No
                        </th>
                        <th onclick="sortKendaraan('nomor_polisi')"
                            class="px-4 py-3 text-xs font-semibold text-gray-700 text-left cursor-pointer">
                            Nomor Polisi <i class="fas fa-sort ml-1 text-xs text-gray-500"></i>
                        </th>
                        <th onclick="sortKendaraan('nama_kendaraan')"
                            class="px-4 py-3 text-xs font-semibold text-gray-700 text-left cursor-pointer">
                            Nama Kendaraan <i class="fas fa-sort ml-1 text-xs text-gray-500"></i>
                        </th>
                        <th onclick="sortKendaraan('tahun')"
                            class="px-4 py-3 text-xs font-semibold text-gray-700 text-left cursor-pointer">
                            Tahun <i class="fas fa-sort ml-1 text-xs text-gray-500"></i>
                        </th>
                        <th class="px-4 py-3 text-xs font-semibold text-gray-700 text-left">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody id="kendaraanTableBody" class="divide-y divide-gray-300 border-b border-gray-300"></tbody>
            </table>

            <!-- PAGINATION -->
            <div class="flex justify-between items-center mt-4">
                <p class="text-xs text-gray-500" id="kendaraanPaginationInfo"></p>
                <div class="space-x-2">
                    <button onclick="prevKendaraanPage()" class="px-3 py-1 border rounded hover:bg-gray-100 text-gray-700">
                        <
                    </button>
                    <button onclick="nextKendaraanPage()" class="px-3 py-1 border rounded hover:bg-gray-100 text-gray-700">
                        >
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const dataKendaraan = @json($kendaraans ?? []);

    let filteredKendaraan = [...dataKendaraan];
    let currentKendaraanPage = 1;
    const perPageKendaraan = 10;
    let sortFieldKendaraan = null;
    let sortAscKendaraan = true;

    // OPEN MODAL
    function openKendaraanModal(){
        openModal('kendaraanModal');
        renderKendaraanTable();
    }

    // RENDER TABLE
    function renderKendaraanTable(){
        const start = (currentKendaraanPage - 1) * perPageKendaraan;
        const end = start + perPageKendaraan;
        const pageData = filteredKendaraan.slice(start, end);

        const tbody = document.getElementById('kendaraanTableBody');
        tbody.innerHTML = '';

        if(filteredKendaraan.length === 0){
            tbody.innerHTML = `
                <tr>
                    <td colspan="2" class="text-center py-4 text-sm italic">
                        Kendaraan tidak ditemukan
                    </td>
                </tr>
            `;
            document.getElementById('kendaraanPaginationInfo').innerText = '';
            return;
        }

        pageData.forEach((k, index) => {
            tbody.innerHTML += `
                <tr class="hover:bg-gray-100">
                    <td class="px-4 py-2 text-sm text-gray-700 w-8">${start + index + 1}</td>
                    <td class="px-4 py-2 text-sm text-gray-700">${k.nomor_polisi}</td>
                    <td class="px-4 py-2 text-sm text-gray-700">${k.nama_kendaraan}</td>
                    <td class="px-4 py-2 text-sm text-gray-700">${k.tahun}</td>
                    <td class="px-4 py-2 text-sm">
                        <button onclick="selectKendaraan('${k.id}','${k.nomor_polisi}','${k.nama_kendaraan}','${k.tahun}')"
                            class="px-3 py-1 bg-green-600 text-white rounded text-xs hover:bg-green-700">
                            Pilih
                        </button>
                    </td>
                </tr>
            `;
        });

        document.getElementById('kendaraanPaginationInfo').innerText =
            `Menampilkan ${start + 1} – ${Math.min(end, filteredKendaraan.length)} dari ${filteredKendaraan.length} data`;
    }

    // SELECT
    function selectKendaraan(id, noPol, nama, tahun){
        document.getElementById('kendaraan_id').value = id;

        const tbody = document.getElementById('selectedKendaraanTable');
        document.getElementById('emptyKendaraanRow')?.remove();

        tbody.innerHTML = `
            <tr class="hover:bg-blue-100 transition">
                <td class="px-4 py-2 text-sm text-gray-700">${noPol}</td>
                <td class="px-4 py-2 text-sm text-gray-700">${nama}</td>
                <td class="px-4 py-2 text-sm text-gray-700">${tahun}</td>
                <td class="px-4 py-2 text-sm">
                    <button type="button" onclick="removeKendaraan()"
                        class="px-3 py-1.5 rounded-lg bg-red-100 text-red-600 hover:bg-red-200 transition text-xs font-normal">
                        Hapus
                    </button>
                </td>
            </tr>
        `;

        document.getElementById("kendaraanError").classList.add("hidden");
        
        closeModal('kendaraanModal');
    }

    function removeKendaraan(){
        document.getElementById('kendaraan_id').value = '';
        document.getElementById('selectedKendaraanTable').innerHTML = `
            <tr id="emptyKendaraanRow">
                <td colspan="4" class="text-center text-sm italic py-4 text-gray-700">
                    Belum ada kendaraan dipilih
                </td>
            </tr>
        `;
    }

    // SEARCH
    document.getElementById('searchKendaraan').addEventListener('input', function(){
        const keyword = this.value.toLowerCase();

        filteredKendaraan = dataKendaraan.filter(k =>
            k.nama_kendaraan.toLowerCase().includes(keyword) ||
            k.nomor_polisi.toLowerCase().includes(keyword)
        );

        currentKendaraanPage = 1;
        renderKendaraanTable();

        document.getElementById('clearSearchKendaraan').classList.toggle('hidden', !this.value);
    });

    // CLEAR SEARCH
    document.getElementById('clearSearchKendaraan').addEventListener('click', function(){
        document.getElementById('searchKendaraan').value = '';
        filteredKendaraan = [...dataKendaraan];
        this.classList.add('hidden');
        renderKendaraanTable();
    });

    // SORT
    function sortKendaraan(field){
        sortAscKendaraan = sortFieldKendaraan === field ? !sortAscKendaraan : true;
        sortFieldKendaraan = field;

        filteredKendaraan.sort((a,b)=>{
            if(a[field] < b[field]) return sortAscKendaraan ? -1 : 1;
            if(a[field] > b[field]) return sortAscKendaraan ? 1 : -1;
            return 0;
        });

        renderKendaraanTable();
    }

    // PAGINATION
    function nextKendaraanPage(){
        if(currentKendaraanPage * perPageKendaraan < filteredKendaraan.length){
            currentKendaraanPage++;
            renderKendaraanTable();
        }
    }

    function prevKendaraanPage(){
        if(currentKendaraanPage > 1){
            currentKendaraanPage--;
            renderKendaraanTable();
        }
    }

    function tambahDetail() {
        const container = document.getElementById('detailContainer');

        const div = document.createElement('div');
        div.classList.add('detail-row', 'flex', 'items-center', 'gap-2');

        div.innerHTML = `
            <span class="detail-number w-4 text-sm text-gray-600"></span>
            <input type="text" name="keperluan[]" placeholder="Keperluan"
                class="flex-1 border px-3 py-2 rounded-lg text-sm" required>
            <input type="number" name="estimasi_biaya[]" placeholder="Estimasi Biaya (Rp.)"
                class="flex-1 border px-3 py-2 rounded-lg text-sm" required>
            <button type="button" onclick="hapusDetail(this)"
                class="w-8 h-8 flex items-center justify-center rounded-full bg-red-100 text-red-600 hover:bg-red-200">
                -
            </button>
        `;

        container.appendChild(div);

        updateNomorDetail();
    }

    function hapusDetail(button) {
        const row = button.closest('.detail-row');
        const container = document.getElementById('detailContainer');

        if (container.children.length === 1) {
            alert('Minimal harus ada 1 data');
            return;
        }

        row.remove();
        updateNomorDetail();
    }

    function updateNomorDetail() {
        const rows = document.querySelectorAll('#detailContainer .detail-row');

        rows.forEach((row, index) => {
            const nomor = row.querySelector('.detail-number');
            nomor.textContent = (index + 1) + '.';
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        updateNomorDetail();
    });

    const formPemeliharaan = document.querySelector("form[action='{{ route('pemeliharaan.store') }}']");
    formPemeliharaan.addEventListener("submit", function(e){
        e.preventDefault();

        const kendaraanId = document.getElementById("kendaraan_id").value;
        const errorText = document.getElementById("kendaraanError");

        if(!kendaraanId){
            errorText.classList.remove("hidden");
            errorText.style.opacity = "1";

            errorText.scrollIntoView({behavior:"smooth", block:"center"});

            // ⏱️ AUTO HIDE 5 DETIK
            setTimeout(function () {
                const alert = document.getElementById('alert-message');
                if (alert) {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }
            }, 5000); // 5 detik

            return;
        }
        this.submit();
    });

    // AUTO HIDE ALERT
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
