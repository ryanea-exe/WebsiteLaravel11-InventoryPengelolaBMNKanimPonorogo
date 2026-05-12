@extends('layouts.app')

@section('title', 'Pengajuan Barang')
@section('page-title', 'Pengajuan Barang')

@section('content')

<div class="space-y-4">
    <div class="bg-white rounded-lg shadow-sm p-4">
        <h1 class="pb-4 text-xl font-bold">Form Pengajuan Permintaan Barang</h1>

        <!-- ALERT -->
        @if(session('success'))
        <div id="alert-message"
            class="mb-4 p-2 rounded-lg bg-green-100 text-green-800 border border-green-300">
            <strong>Sukses!</strong> {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div id="alert-message"
            class="mb-4 p-2 rounded-lg bg-red-100 text-red-800 border border-red-300">
            <strong>Gagal!</strong> {{ session('error') }}
        </div>
        @endif

        <hr class="pb-4">

        <form action="{{ route('pengajuan.store') }}" method="POST">
            @csrf

            <!-- Tanggal -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pengajuan <span class="text-red-500">*</span></label>
                <input type="text"
                    value="{{ now()->format('d-m-Y') }}"
                    class="w-full border rounded-lg px-3 py-2 bg-gray-100 cursor-not-allowed" readonly>
            </div>
            <!-- PILIH BARANG -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Barang <span class="text-red-500">*</span></label>

                <div class="flex gap-2">
                    <!-- ID BARANG (HIDDEN) -->
                    <input type="hidden" name="barang_id" id="barang_id">

                    <!-- NAMA BARANG (READONLY) -->
                    <input type="text" id="nama_barang"
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg bg-gray-100"
                        placeholder="Pilih barang" readonly>

                    <!-- BUTTON MODAL -->
                    <button type="button"
                            onclick="openBarangModal()"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-search mr-1"></i> Pilih
                    </button>
                </div>
            </div>
            <!-- Jumlah Permintaan -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Permintaan <span class="text-red-500">*</span></label>
                <input type="number" name="jumlah" min="1"
                    class="w-full border rounded-lg px-3 py-2" required>
            </div>
            <!-- Keperluan -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Keperluan Penggunaan <span class="text-red-500">*</span></label>
                <textarea name="keperluan" rows="3"
                        class="w-full border rounded-lg px-3 py-2" required></textarea>
            </div>

            <!-- Tombol -->
            <div class="flex justify-end gap-2 mt-6 pt-4 border-t border-gray-200">
                <button type="reset"
                        class="px-5 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100">
                    Reset
                </button>
                <button type="submit"
                        class="px-5 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                    Ajukan Permintaan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL PILIH BARANG -->
<div id="barangModal" class="modal fixed inset-0 bg-black/50 z-[9999] hidden flex items-center justify-center p-4">
    <div class="modal-box bg-white rounded-lg w-full max-w-3xl shadow-lg max-h-[90vh] overflow-y-auto">
        <!-- HEADER -->
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">
                Pilih Barang
            </h3>
            <button onclick="closeModal('barangModal')" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- BODY -->
        <div class="p-4 overflow-x-auto max-h-[400px]">
            <input type="text" id="searchBarang" placeholder="Cari barang..."
                class="w-full mb-4 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr class="uppercase"">
                        <th onclick="sortTable('kode_barang')"
                            class="px-4 py-2 text-left text-sm font-medium text-gray-700 cursor-pointer select-none">
                            Kode Barang
                            <i class="fas fa-sort ml-1 text-xs text-gray-400"></i>
                        </th>
                        <th onclick="sortTable('nama_barang')"
                            class="px-4 py-2 text-left text-sm font-medium text-gray-700 cursor-pointer select-none">
                            Nama Barang
                            <i class="fas fa-sort ml-1 text-xs text-gray-400"></i>
                        </th>
                        <th onclick="sortTable('jumlah')"
                            class="px-4 py-2 text-left text-sm font-medium text-gray-700 cursor-pointer select-none">
                            Stok
                            <i class="fas fa-sort ml-1 text-xs text-gray-400"></i>
                        </th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody id="barangTableBody" class="divide-y">
                    @foreach($barangs as $barang)
                    <tr class="hover:bg-gray-100 {{ $barang->jumlah == 0 ? 'bg-gray-100 text-gray-400' : '' }}">
                        <td class="px-4 py-2 text-sm font-normal">{{ $barang->kode_barang }}</td>
                        <td class="px-4 py-2 text-sm font-normal">{{ $barang->nama_barang }}</td>
                        <td class="px-4 py-2 text-sm font-normal">
                            @if($barang->jumlah > 0)
                                {{ $barang->jumlah }}
                            @else
                                <span class="text-red-600 font-semibold">
                                    Stok Habis
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-center">
                            @if($barang->jumlah > 0)
                                <button type="button"
                                        onclick="selectBarang('{{ $barang->id }}', '{{ $barang->nama_barang }}')"
                                        class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 text-sm">
                                    Pilih
                                </button>
                            @else
                                <button type="button"
                                        disabled
                                        class="px-3 py-1 bg-gray-300 text-gray-500 rounded text-sm cursor-not-allowed">
                                    Tidak Tersedia
                                </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="flex justify-between items-center mt-4">
                <p class="text-sm text-gray-600" id="paginationInfo"></p>

                <div class="space-x-2">
                    <button onclick="prevPage()"
                            class="px-3 py-1 border rounded hover:bg-gray-100">
                        Prev
                    </button>
                    <button onclick="nextPage()"
                            class="px-3 py-1 border rounded hover:bg-gray-100">
                        Next
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    // modal show table data
    function openBarangModal() {
        openModal('barangModal');
    }

    function closeBarangModal() {
        closeModal('barangModal');
    }

    function selectBarang(id, nama) {
        document.getElementById('barang_id').value = id;
        document.getElementById('nama_barang').value = nama;
        closeBarangModal();
    }

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

<script>
    const dataBarang = @json($barangs);

    let filteredData = [...dataBarang];
    let currentPage = 1;
    const perPage = 25;
    let sortField = null;
    let sortAsc = true;

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
                    <td colspan="4" class="px-4 py-6 text-center text-sm text-gray-500">
                        Barang tidak ditemukan
                    </td>
                </tr>
            `;
            document.getElementById('paginationInfo').innerText = '';
            return;
        }

        pageData.forEach(b => {
            const disabled = b.jumlah <= 0;
            const stok = disabled
                ? '<span class="text-red-600 font-medium text-sm">Stok Habis</span>'
                : b.jumlah;

            tbody.innerHTML += `
            <tr class="${disabled ? 'bg-gray-100 text-gray-400' : ''}">
                <td class="px-4 py-2 text-sm font-normal">${b.kode_barang}</td>
                <td class="px-4 py-2 text-sm font-normal">${b.nama_barang}</td>
                <td class="px-4 py-2 text-sm font-normal">${stok}</td>
                <td class="px-4 py-2 text-sm">
                    ${disabled ? 
                        '<button disabled class="px-3 py-1 bg-gray-300 text-gray-500 rounded text-xs">Tidak Tersedia</button>' :
                        `<button onclick="selectBarang('${b.id}','${b.nama_barang}')"
                            class="px-3 py-1 bg-blue-600 text-white rounded text-xs hover:bg-blue-700">
                            Pilih
                        </button>`
                    }
                </td>
            </tr>`;
        });

        document.getElementById('paginationInfo').innerText =
            `Menampilkan ${start + 1} – ${Math.min(end, filteredData.length)} dari ${filteredData.length} data`;
    }

    // SEARCH REALTIME
    document.getElementById('searchBarang').addEventListener('input', function () {
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
</script>
@endsection
