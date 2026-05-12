@extends('layouts.app')

@section('title', 'Input Pemeliharaan')

@section('page-title', 'Input Riwayat Pemeliharaan')
@section('breadcrumb')
<p class="text-gray-800 cursor-pointer">
    Input Riwayat Pemeliharaan
</p>
@endsection

@section('content')

<div class="space-y-4">
    <div x-data="{ tab: '{{ request('tab','pajak') }}' }" class="bg-white rounded-lg shadow-sm">
        <!-- TAB BUTTON -->
        <div class="border-b border-gray-200">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
                <!-- TAB PAJAK -->
                <li class="mr-1">
                    <button 
                        @click="tab='pajak'"
                        :class="tab=='pajak' 
                            ? 'border-blue-500 text-blue-600' 
                            : 'border-transparent text-gray-800 hover:text-blue-600 hover:border-blue-500'"
                        class="inline-block p-3 border-b-2">
                        <i class="fas fa-file-invoice mr-1"></i> Riwayat Pajak
                    </button>
                </li>
                <!-- TAB SERVIS -->
                <li class="mr-1">
                    <button 
                        @click="tab='servis'"
                        :class="tab=='servis' 
                            ? 'border-blue-500 text-blue-600' 
                            : 'border-transparent text-gray-800 hover:text-blue-600 hover:border-blue-500'"
                        class="inline-block p-3 border-b-2">
                        <i class="fas fa-tools mr-1"></i> Riwayat Servis
                    </button>
                </li>
            </ul>
        </div>

        <!-- TAB PAJAK -->
        <div x-show="tab=='pajak'" class="x-transition.opacity.duration.200ms">
            <!-- FORM PAJAK (ISI LAMA KAMU) -->
            <form action="{{ route('pemeliharaan.input_pajak.store') }}" method="POST"
                class="bg-white rounded-lg shadow-sm">
                @csrf

                <!-- HEADER -->
                <div class="px-4 py-4 border-b mb-4">
                    <h1 class="text-xl font-bold text-gray-800">
                        <i class="fas fa-file-invoice mr-3"></i>Form Input Riwayat Pajak
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pajak <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_pajak"
                            class="w-full border rounded-lg px-4 py-2"
                            required>
                    </div>
                    <!-- PILIH KENDARAAN -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kendaraan <span class="text-red-500">*</span></label>
                        <button type="button" onclick="openKendaraanModal('pajak')"
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
                    <!-- NAMA PENGURUS -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pengurus</label>
                        <input type="text" name="nama_pengurus" placeholder="Masukkan nama yang mengurus pajak kendaraan ini"
                            class="w-full border rounded-lg px-4 py-2">
                    </div>
                    <!-- KETERANGAN -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan (Opsional)</label>
                        <textarea name="keterangan" rows="2" placeholder="Masukkan keterangan (opsional)"
                            class="w-full border rounded-lg px-4 py-2"></textarea>
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
                        <i class="fas fa-save mr-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>

        <!-- TAB SERVIS -->
        <div x-show="tab=='servis'" class="x-transition.opacity.duration.200ms">
            @include('pages.pemeliharaan.input_servis')
        </div>
    </div>
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
    const dataKendaraan = @json($kendaraan ?? []);

    let filteredKendaraan = [...dataKendaraan];
    let currentKendaraanPage = 1;
    const perPageKendaraan = 10;
    let sortFieldKendaraan = null;
    let sortAscKendaraan = true;
    let activeMode = 'pajak'; // pajak | servis

    // OPEN MODAL
    function openKendaraanModal(mode = 'pajak'){
        activeMode = mode;
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
        if(activeMode === 'pajak'){
            document.getElementById('kendaraan_id').value = id;

            const tbody = document.getElementById('selectedKendaraanTable');
            document.getElementById('emptyKendaraanRow')?.remove();

            tbody.innerHTML = `
                <tr class="hover:bg-blue-100 transition">
                    <td class="px-4 py-2 text-sm">${noPol}</td>
                    <td class="px-4 py-2 text-sm">${nama}</td>
                    <td class="px-4 py-2 text-sm">${tahun}</td>
                    <td class="px-4 py-2 text-sm">
                        <button type="button" onclick="removeKendaraan('pajak')"
                            class="px-3 py-1.5 bg-red-100 text-red-600 rounded text-xs">
                            Hapus
                        </button>
                    </td>
                </tr>
            `;

            document.getElementById("kendaraanError").classList.add("hidden");
        } else {
            document.getElementById('kendaraan_id_servis').value = id;

            const tbody = document.getElementById('selectedKendaraanTable_servis');
            document.getElementById('emptyKendaraanRow_servis')?.remove();

            tbody.innerHTML = `
                <tr class="hover:bg-blue-100 transition">
                    <td class="px-4 py-2 text-sm">${noPol}</td>
                    <td class="px-4 py-2 text-sm">${nama}</td>
                    <td class="px-4 py-2 text-sm">${tahun}</td>
                    <td class="px-4 py-2 text-sm">
                        <button type="button" onclick="removeKendaraan('servis')"
                            class="px-3 py-1.5 bg-red-100 text-red-600 rounded text-xs">
                            Hapus
                        </button>
                    </td>
                </tr>
            `;

            document.getElementById("kendaraanError_servis").classList.add("hidden");
        }

        closeModal('kendaraanModal');
    }

    function removeKendaraan(mode){
        if(mode === 'pajak'){
            document.getElementById('kendaraan_id').value = '';
            document.getElementById('selectedKendaraanTable').innerHTML = `
                <tr id="emptyKendaraanRow">
                    <td colspan="4" class="text-center text-sm italic py-4">
                        Belum ada kendaraan dipilih
                    </td>
                </tr>
            `;
        } else {
            document.getElementById('kendaraan_id_servis').value = '';
            document.getElementById('selectedKendaraanTable_servis').innerHTML = `
                <tr id="emptyKendaraanRow_servis">
                    <td colspan="4" class="text-center text-sm italic py-4">
                        Belum ada kendaraan dipilih
                    </td>
                </tr>
            `;
        }
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

    const formPemeliharaan = document.querySelector("form[action='{{ route('pemeliharaan.input_pajak.store') }}']");
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
