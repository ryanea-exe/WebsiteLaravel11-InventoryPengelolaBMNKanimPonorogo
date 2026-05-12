@extends('layouts.app')

@section('title', 'Barang Masuk')
@section('page-title', 'Barang Masuk')

@section('content')

{{-- Tambahkan baris ini untuk memastikan locale Carbon adalah Indonesia --}}
@php \Illuminate\Support\Carbon::setLocale('id'); @endphp

@if ($errors->any())
<div class="bg-red-100 text-red-800 p-3 rounded mb-3">
    <ul class="list-disc pl-5">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@push('scripts')
<style>
    table#dataTable tbody td:nth-child(5) {
        color: #16a34a !important;
        font-weight: 600;
    }
</style>
@endpush

<div class="space-y-4">
    <div class="bg-white rounded-lg shadow-sm p-4">
        <!-- HEADER -->
        <div class="flex justify-between mb-4">
            <h1 class="text-xl font-bold text-gray-800">
                <i class="fas fa-file-import mr-3"></i>Daftar Barang Masuk
            </h1>
            <div class="flex justify-end gap-2">
                <button onclick="openModal('addBarangMasukModal')"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-normal text-sm px-3 py-1.5 rounded-lg transition flex items-center">
                    <i class="fas fa-plus-square mr-1"></i> Tambah Barang Masuk
                </button>
                <!-- <button
                    class="bg-green-600 hover:bg-green-700 text-white font-normal text-sm px-3 py-1.5 rounded-lg transition flex items-center">
                    <i class="fas fa-file-excel mr-1"></i> Export
                </button> -->
            </div>
        </div>

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

        <!-- TABLE -->
        <div class="overflow-x-auto">
            <table id="dataTable" class="strip text-gray-700 w-full row-border">
                <thead class="bg-gray-100 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase w-8">No</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase">Kode Barang</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase">Nama Barang</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase">Jumlah</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase">Harga Total</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($barangMasuk as $index => $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-grey-800">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-6 py-4 text-sm text-grey-800">
                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-grey-800">
                            {{ $item->barang->kode_barang ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-grey-800">
                            {{ $item->barang->nama_barang ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-green-600">
                            {{ $item->jumlah }} {{ $item->barang->satuan ?? '' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-grey-800">
                            {{ $item->harga_total ? 'Rp. ' . number_format($item->harga_total,0,',','.') : 'Rp. -' }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <div class="flex space-x-2">
                                <button onclick="openDetailBarangMasukModal(
                                        '{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y, H:i') }}',
                                        '{{ $item->barang->kode_barang ?? '-' }}',
                                        '{{ $item->barang->nama_barang ?? '-' }}',
                                        '{{ $item->harga_satuan ? 'Rp. ' . number_format($item->harga_satuan,0,',','.') : 'Rp. -' }}',
                                        '{{ $item->jumlah }}',
                                        '{{ $item->barang->satuan ?? '' }}',
                                        '{{ $item->harga_total ? 'Rp. ' . number_format($item->harga_total,0,',','.') : 'Rp. -' }}',
                                        '{{ $item->keterangan }}'
                                    )"
                                    class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="openDeleteModal(
                                        '{{ route('barang-masuk.destroy', $item->id) }}',
                                        '{{ $item->barang->kode_barang ?? '-' }}' 
                                    )"
                                    class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Barang Masuk Modal -->
<div id="addBarangMasukModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="modal-box max-w-xl w-full">
        <form action="{{ route('barang-masuk.store') }}" method="POST"
            class="bg-white rounded-2xl max-h-[90vh] flex flex-col overflow-hidden">
            @csrf

            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Tambah Barang Masuk</h3>
                <button type="button" onclick="closeModal('addBarangMasukModal')" 
                    class="text-gray-500 hover:text-gray-800">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Scrollable Field -->
            <div class="px-6 py-4 overflow-y-auto flex-1">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" readonly
                            class="w-full px-3 py-1.5 border border-gray-300 bg-gray-100 rounded-lg focus:ring-2 focus:ring-blue-500 cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Barang <span class="text-red-500">*</span></label>
                        <div class="flex gap-2">
                            <!-- ID BARANG -->
                            <input type="hidden" name="barang_id" id="barang_id">
                            <!-- NAMA BARANG -->
                            <input type="text" id="nama_barang"
                                class="flex-1 px-3 py-1.5 border border-gray-300 rounded-lg bg-gray-100"
                                placeholder="Pilih barang" readonly required>
                            <!-- BUTTON -->
                            <button type="button" onclick="openBarangMasukModal()"
                                class="px-3 py-1.5 bg-green-600 text-white rounded-lg hover:bg-green-700 font-normal text-sm">
                                <i class="fas fa-search mr-1"></i> Pilih
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Masuk <span class="text-red-500">*</span></label>
                        <input type="number" name="jumlah" min="1"
                            class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="Masukkan jumlah barang">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga Satuan (Opsional)</label>
                        <input type="number" name="harga_satuan" min="0"
                            class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="Masukkan harga satuan">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan (Opsional)</label>
                        <textarea name="keterangan" rows="3"
                            class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="Keterangan tambahan (opsional)"></textarea>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="flex justify-end space-x-2 px-6 py-4 border-t border-gray-200">
                <button type="button" onclick="closeModal('addBarangMasukModal')"
                    class="px-3 py-1.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 font-normal text-sm">
                    Batal
                </button>
                <button type="submit"
                    class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-normal text-sm">
                    <i class="fas fa-save mr-1"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- DETAIL BARANG MASUK MODAL -->
<div id="detailBarangMasukModal" class="modal fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-3xl shadow-lg overflow-hidden">
        <!-- HEADER -->
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h3 class="text-lg font-semibold text-gray-800" id="detailBarangMasukTitle">
                Detail Barang Masuk
            </h3>
            <button onclick="closeDetailBarangMasukModal()"
                class="text-gray-500 hover:text-gray-800">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- BODY -->
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Tanggal Masuk</p>
                    <p id="detailTanggal" class="font-medium text-gray-800">-</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Kode Barang</p>
                    <p id="detailKodeBarang" class="font-medium text-blue-600">-</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Nama Barang</p>
                    <p id="detailNamaBarang" class="font-medium text-gray-800">-</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Harga Satuan</p>
                    <p id="detailHargaSatuan" class="font-medium text-gray-800">-</p>
                </div>                
                <div>
                    <p class="text-sm text-gray-500 mb-1">Jumlah Masuk</p>
                    <p id="detailJumlah" class="font-semibold text-green-600">-</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Harga Total</p>
                    <p id="detailHargaTotal" class="font-medium text-gray-800">-</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Keterangan</p>
                    <p id="detailKeterangan" class="bg-gray-50 rounded-lg p-2 text-gray-800 text-sm">-</p>
                </div>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="px-6 py-4 border-t flex justify-end">
            <button onclick="closeDetailBarangMasukModal()"
                class="px-3 py-1.5 border rounded-lg text-gray-700 hover:bg-gray-100 text-sm font-normal">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- DELETE BARANG MASUK MODAL -->
<div id="deleteBarangMasukModal" class="modal fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg w-full max-w-md shadow-lg">
        <div class="p-6 text-center">
            <div class="mx-auto mb-4 w-14 h-14 rounded-full bg-red-100 flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
            </div>

            <h3 class="text-lg font-semibold text-gray-800 mb-2">
                Konfirmasi Hapus Barang Masuk
            </h3>
            <p class="text-sm text-gray-600 mb-6">
                Apakah Anda yakin ingin menghapus data barang masuk
                <span id="deleteBarangKode" class="font-semibold text-gray-800"></span>?
                <br>
                Tindakan ini tidak dapat dibatalkan.
            </p>

            <form id="deleteBarangMasukForm" method="POST">
                @csrf
                @method('DELETE')

                <div class="flex justify-center space-x-2">
                    <button type="button" onclick="closeDeleteModal()"
                        class="px-3 py-1.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition text-sm font-normal">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-lg transition text-sm font-normal">
                        <i class="fas fa-trash mr-1"></i> Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL PILIH BARANG -->
<div id="barangMasukModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg w-full max-w-3xl shadow-lg">
        <!-- HEADER -->
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">
                Pilih Barang
            </h3>
            <button onclick="closeBarangMasukModal()" class="text-gray-500 hover:text-gray-800">
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
                        <th onclick="sortBarangMasuk('kode_barang')"
                            class="px-4 py-3 text-left text-xs font-semibold text-gray-700 cursor-pointer select-none tracking-wider">
                            Kode Barang
                            <i class="fas fa-sort ml-1 text-xs text-gray-400"></i>
                        </th>
                        <th onclick="sortBarangMasuk('nama_barang')"
                            class="px-4 py-3 text-left text-xs font-semibold text-gray-700 cursor-pointer select-none tracking-wider">
                            Nama Barang
                            <i class="fas fa-sort ml-1 text-xs text-gray-400"></i>
                        </th>
                        <th onclick="sortBarangMasuk('jumlah')"
                            class="px-4 py-3 text-left text-xs font-semibold text-gray-700 cursor-pointer select-none tracking-wider">
                            Stok
                            <i class="fas fa-sort ml-1 text-xs text-gray-400"></i>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody id="barangMasukTableBody" class="divide-y divide-gray-300 border-b border-gray-300"></tbody>
            </table>

            <!-- PAGINATION -->
            <div class="flex justify-between items-center mt-4">
                <p class="text-xs text-gray-500" id="paginationBarangMasukInfo"></p>
                <div class="space-x-2">
                    <button onclick="prevBarangMasukPage()"
                            class="px-3 py-1 border rounded hover:bg-gray-100 text-gray-700">
                        <
                    </button>
                    <button onclick="nextBarangMasukPage()"
                            class="px-3 py-1 border rounded hover:bg-gray-100 text-gray-700">
                        >
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // modal show/detail data
    const detailModal = document.getElementById('detailBarangMasukModal');

    function openDetailBarangMasukModal(
        tanggal, kode, nama, harga_satuan, jumlah, satuan, harga_total, keterangan
    ) {
        document.getElementById('detailTanggal').textContent    = tanggal;
        document.getElementById('detailKodeBarang').textContent = kode;
        document.getElementById('detailNamaBarang').textContent = nama;
        document.getElementById('detailHargaSatuan').textContent      = harga_satuan;
        document.getElementById('detailJumlah').textContent     = satuan ? `${jumlah} ${satuan}` : jumlah;
        document.getElementById('detailHargaTotal').textContent      = harga_total;
        document.getElementById('detailKeterangan').textContent      = keterangan ? keterangan : '-';;

        detailModal.classList.add('show');

        // 🔥 ubah judul modal (pakai kode barang yang dikirim)
        document.getElementById('detailBarangMasukTitle').innerText =
            `Detail Barang Masuk - ${kode}`;
    }

    function closeDetailBarangMasukModal() {
        detailModal.classList.remove('show');
    }

    // modal delete data
    const deleteModal = document.getElementById('deleteBarangMasukModal');
    const deleteForm  = document.getElementById('deleteBarangMasukForm');
    const deleteKode  = document.getElementById('deleteBarangKode');

    function openDeleteModal(actionUrl, kodeBarang) {
        deleteForm.action = actionUrl;
        deleteKode.textContent = kodeBarang;
        deleteModal.classList.add('show');
    }

    function closeDeleteModal() {
        deleteModal.classList.remove('show');
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
    // modal pilih barang
    const dataBarangMasuk = @json($barang);
    const searchInput = document.getElementById('searchBarangMasuk');
    const clearButton = document.getElementById('clearSearchBarangMasuk');

    let filteredBarangMasuk = [...dataBarangMasuk];
    let currentBarangMasukPage = 1;
    const barangMasukPerPage = 25;
    let sortBarangMasukField = null;
    let sortBarangMasukAsc = true;

    function openBarangMasukModal() {
        openModal('barangMasukModal');
        renderBarangMasukTable();
    }

    function closeBarangMasukModal() {
        closeModal('barangMasukModal');
    }

    function selectBarangMasuk(id, nama) {
        document.getElementById('barang_id').value = id;
        document.getElementById('nama_barang').value = nama;
        closeBarangMasukModal();
    }

    function renderBarangMasukTable() {
        const start = (currentBarangMasukPage - 1) * barangMasukPerPage;
        const end   = start + barangMasukPerPage;
        const pageData = filteredBarangMasuk.slice(start, end);

        const tbody = document.getElementById('barangMasukTableBody');
        tbody.innerHTML = '';

        if (filteredBarangMasuk.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="4" class="px-4 py-4 text-center text-sm text-gray-700 italic">
                        Barang tidak ditemukan
                    </td>
                </tr>
            `;
            document.getElementById('paginationBarangMasukInfo').innerText = '';
            return;
        }

        pageData.forEach(b => {
            const selectedId = document.getElementById('barang_id').value;
            const isSelected = selectedId == b.id;
            const stok = b.jumlah <= 0
                ? '<span class="text-red-600 font-medium text-sm">Stok Habis</span>'
                : b.jumlah;
            let tombol;

            if (isSelected) {
                tombol = `
                    <span class="inline-flex items-center justify-center px-2 py-1 text-xs bg-gray-200 text-gray-700 rounded">
                        Sudah dipilih
                    </span>
                `;
            } else {
                tombol = `
                    <button
                        onclick="selectBarangMasuk('${b.id}', '${b.nama_barang}')"
                        class="px-2 py-1 bg-green-600 text-white rounded text-xs hover:bg-green-700">
                        Pilih
                    </button>
                `;
            }

            tbody.innerHTML += `
                <tr class="hover:bg-gray-100">
                    <td class="px-4 py-1 text-sm font-normal text-gray-700">${b.kode_barang}</td>
                    <td class="px-4 py-1 text-sm font-normal text-gray-700">${b.nama_barang}</td>
                    <td class="px-4 py-1 text-sm font-normal text-gray-700">${stok}</td>
                    <td class="px-4 py-1 text-sm font-normal text-gray-700">
                        ${tombol}
                    </td>
                </tr>
            `;
        });

        document.getElementById('paginationBarangMasukInfo').innerText =
            `Menampilkan ${start + 1} – ${Math.min(end, filteredBarangMasuk.length)} dari ${filteredBarangMasuk.length} data`;
    }

    // SEARCH
    document.getElementById('searchBarangMasuk').addEventListener('input', function () {
        const keyword = this.value.toLowerCase();
        filteredBarangMasuk = dataBarangMasuk.filter(b =>
            b.nama_barang.toLowerCase().includes(keyword) ||
            b.kode_barang.toLowerCase().includes(keyword)
        );
        currentBarangMasukPage = 1;
        renderBarangMasukTable();
    });

    searchInput.addEventListener('input', function () {
        const keyword = this.value.toLowerCase();

        // Tampilkan / sembunyikan tombol clear
        if (this.value.length > 0) {
            clearButton.classList.remove('hidden');
        } else {
            clearButton.classList.add('hidden');
        }

        filteredBarangMasuk = dataBarangMasuk.filter(b =>
            b.nama_barang.toLowerCase().includes(keyword) ||
            b.kode_barang.toLowerCase().includes(keyword)
        );

        currentBarangMasukPage = 1;
        renderBarangMasukTable();
    });

    // tombol clear
    clearButton.addEventListener('click', function () {
        searchInput.value = '';
        clearButton.classList.add('hidden');
        filteredBarangMasuk = [...dataBarangMasuk];
        currentBarangMasukPage = 1;
        renderBarangMasukTable();
    });

    // SORTING
    function sortBarangMasuk(field) {
        sortBarangMasukAsc = sortBarangMasukField === field ? !sortBarangMasukAsc : true;
        sortBarangMasukField = field;

        filteredBarangMasuk.sort((a, b) => {
            if (a[field] < b[field]) return sortBarangMasukAsc ? -1 : 1;
            if (a[field] > b[field]) return sortBarangMasukAsc ? 1 : -1;
            return 0;
        });

        renderBarangMasukTable();
    }

    // PAGINATION
    function nextBarangMasukPage() {
        if (currentBarangMasukPage * barangMasukPerPage < filteredBarangMasuk.length) {
            currentBarangMasukPage++;
            renderBarangMasukTable();
        }
    }

    function prevBarangMasukPage() {
        if (currentBarangMasukPage > 1) {
            currentBarangMasukPage--;
            renderBarangMasukTable();
        }
    }
</script>

@endsection
