@extends('layouts.app')

@section('title', 'Barang BMN')

@section('page-title', 'Barang BMN')
@section('breadcrumb')
    <p class="text-gray-800 cursor-pointer">
        Barang BMN
    </p>
@endsection

@section('content')

@push('scripts')
<style>
    table#dataTable tbody td:nth-child(2) {
        /* color: #2563EB !important; */
        font-weight: 600;
    }
</style>
@endpush

<div class="space-y-4">
    <div class="bg-white rounded-lg shadow-sm p-4">
        <!-- HEADER -->
        <div class="flex justify-between mb-4">
            <h1 class="text-xl font-bold text-gray-800">
                <i class="fas fa-archive mr-3"></i>Daftar Barang BMN
            </h1>
            @if(auth()->user()->role === 'Administrator')
            <div class="flex justify-end gap-2">
                <button onclick="window.location='{{ route('kategori.index') }}'"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1.5 font-normal rounded-lg text-sm">
                    <i class="fas fa-sitemap mr-1"></i> Data Kategori
                </button>
                <button onclick="openModal('addBarangModal')" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg font-normal transition flex items-center text-sm">
                    <i class="fas fa-plus-square mr-1"></i> Tambah Barang
                </button>
                <!-- <button
                    class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-lg font-normal transition flex items-center text-sm">
                    <i class="fas fa-file-excel mr-1"></i> Export
                </button> -->
            </div>
            @endif
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
                        <th class="px-2 py-3 text-left text-sm font-semibold text-gray-500 uppercase tracking-wider w-8">No</th>
                        <th class="px-2 py-3 text-left text-sm font-semibold text-gray-500 uppercase tracking-wider">Kode Barang</th>
                        <th class="px-2 py-3 text-left text-sm font-semibold text-gray-500 uppercase tracking-wider">Nama Barang</th>
                        <th class="px-2 py-3 text-left text-sm font-semibold text-gray-500 uppercase tracking-wider">Merk/Type</th>
                        <th class="px-2 py-3 text-left text-sm font-semibold text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-2 py-3 text-left text-sm font-semibold text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th class="px-2 py-3 text-left text-sm font-semibold text-gray-500 uppercase tracking-wider">Satuan</th>
                        @if(auth()->user()->role === 'Administrator')
                        <th class="px-2 py-3 text-left text-sm font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($barang as $index => $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-2 py-2 text-sm text-grey-800">{{ $index + 1 }}</td>
                        <td class="px-2 py-2 text-sm text-grey-800">{{ $item->kode_barang }}</td>
                        <td class="px-2 py-2 text-sm text-grey-800">{{ $item->nama_barang }}</td>
                        <td class="px-2 py-2 text-sm text-grey-800">{{ $item->merk_type }}</td>
                        <td class="px-2 py-2 text-sm text-grey-800">{{ $item->kategori->nama ?? '-' }}</td>
                        <td class="px-2 py-2 text-sm text-grey-800">{{ $item->jumlah }}</td>
                        <td class="px-2 py-2 text-sm text-grey-800">{{ $item->satuan }}</td>
                        @if(auth()->user()->role === 'Administrator')
                        <td class="px-2 py-2 text-sm">
                            <div class="flex space-x-2">
                                <button onclick='openDetailBarangModal(@json($item))'
                                    class="text-blue-600 hover:text-blue-800"
                                    title="Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick='openEditModal(@json($item))'
                                    class="text-green-600 hover:text-green-800"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="openDeleteModal({{ $item->id }}, '{{ $item->kode_barang }}')"
                                    class="text-red-600 hover:text-red-800"
                                    title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ADD BARANG MODAL -->
<div id="addBarangModal" class="modal fixed inset-0 bg-black/50 z-[9999] hidden flex items-center justify-center p-4">
    <div class="modal-box max-w-xl w-full">
        <form action="{{ route('barang-bmn.store') }}" method="POST"
            class="bg-white rounded-2xl max-h-[90vh] flex flex-col overflow-hidden">
            @csrf

            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between flex-shrink-0">
                <h3 class="text-lg font-semibold text-gray-800">
                    Tambah Barang
                </h3>
                <button type="button" onclick="closeModal('addBarangModal')"
                    class="text-gray-500 hover:text-gray-800">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Scrollable Field -->
            <div class="px-6 py-4 overflow-y-auto flex-1">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Kode Barang <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" name="kode_barang" id="add_kode_barang"
                                class="w-full px-3 py-1.5 border border-gray-300 rounded-lg pr-10"
                                placeholder="Contoh: BRG-001" required>
                            <!-- Spinner -->
                            <div id="addKodeSpinner"
                                class="absolute right-3 top-1/2 -translate-y-1/2 hidden">
                                <i class="fas fa-spinner fa-spin text-gray-400"></i>
                            </div>
                            <!-- Success -->
                            <div id="addKodeSuccess"
                                class="absolute right-3 top-1/2 -translate-y-1/2 hidden">
                                <i class="fas fa-check text-green-500"></i>
                            </div>
                            <!-- Error -->
                            <div id="addKodeErrorIcon"
                                class="absolute right-3 top-1/2 -translate-y-1/2 hidden">
                                <i class="fas fa-times text-red-500"></i>
                            </div>
                        </div>
                        <p id="addKodeErrorText"
                            class="text-xs text-red-600 mt-1 hidden">
                            Kode barang sudah digunakan.
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_barang"
                            class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Masukkan nama barang" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Merk/Type <span class="text-red-500">*</span></label>
                        <input type="text" name="merk_type"
                            class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Masukkan merk/type barang" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                        <select name="kategori_id"
                            class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id }}">
                                    {{ $kategori->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Awal <span class="text-red-500">*</span></label>
                        <input type="number" name="jumlah"
                            class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            min="0" value="0" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Satuan <span class="text-red-500">*</span></label>
                        <input type="text" name="satuan"
                            class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Contoh: Unit, Buah, Set" required>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="flex justify-end space-x-2 px-6 py-4 border-t border-gray-200 bg-white flex-shrink-0">
                <button type="button" onclick="closeModal('addBarangModal')"
                    class="px-3 py-1.5 border border-gray-300 rounded-lg text-gray-700 font-normal text-sm hover:bg-gray-100 transition">
                    Batal
                </button>
                <button type="submit" id="addBarangSubmitBtn"
                    class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 font-normal text-sm text-white rounded-lg transition">
                    <i class="fas fa-save mr-1"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- DETAIL BARANG MODAL -->
<div id="detailBarangModal" class="modal fixed inset-0 bg-black/50 z-[9999] hidden flex items-center justify-center p-4">
    <div class="modal-box bg-white rounded-2xl w-full max-w-3xl shadow-lg overflow-hidden">
        <!-- HEADER -->
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h3 class="text-lg font-semibold text-gray-800" id="detailBarangTitle">
                Detail Barang
            </h3>
            <button type="button" onclick="closeModal('detailBarangModal')"
                class="text-gray-500 hover:text-gray-800">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- BODY -->
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Kode Barang</p>
                    <p id="detailKodeBarang" class="font-medium text-blue-600">-</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Nama Barang</p>
                    <p id="detailNamaBarang" class="font-medium text-gray-800">-</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Merk/Type</p>
                    <p id="detailMerkType" class="font-medium text-gray-800">-</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Kategori</p>
                    <p id="detailKategori" class="font-medium text-gray-800">-</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Jumlah Stok</p>
                    <p id="detailJumlah" class="font-medium text-gray-800">-</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Satuan</p>
                    <p id="detailSatuan" class="font-medium text-gray-800">-</p>
                </div>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="px-6 py-4 border-t flex justify-end">
            <button onclick="closeModal('detailBarangModal')"
                class="px-3 py-1.5 border rounded-lg text-gray-700 font-medium text-sm hover:bg-gray-100">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Edit Barang Modal -->
<div id="editBarangModal" class="modal fixed inset-0 bg-black/50 z-[9999] hidden flex items-center justify-center p-4">
    <div class="modal-box max-w-xl w-full">
        <form id="editBarangForm" method="POST"
            class="bg-white rounded-2xl max-h-[90vh] flex flex-col overflow-hidden">
            @csrf
            @method('PUT')

            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800" id="editBarangTitle">
                    Edit Barang
                </h3>
                <button type="button" onclick="closeModal('editBarangModal')"
                    class="text-gray-500 hover:text-gray-800">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Scrollable Field -->
            <div class="px-6 py-4 overflow-y-auto flex-1">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kode Barang <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="text" name="kode_barang" id="edit_kode_barang"
                                class="w-full px-3 py-1.5 border border-gray-300 rounded-lg pr-10" required>
                            <div id="editKodeSpinner"
                                class="absolute right-3 top-1/2 -translate-y-1/2 hidden">
                                <i class="fas fa-spinner fa-spin text-gray-400"></i>
                            </div>
                            <div id="editKodeSuccess"
                                class="absolute right-3 top-1/2 -translate-y-1/2 hidden">
                                <i class="fas fa-check text-green-500"></i>
                            </div>
                            <div id="editKodeErrorIcon"
                                class="absolute right-3 top-1/2 -translate-y-1/2 hidden">
                                <i class="fas fa-times text-red-500"></i>
                            </div>
                        </div>
                        <p id="editKodeErrorText"
                            class="text-xs text-red-600 mt-1 hidden">
                            Kode barang sudah digunakan.
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_barang" id="edit_nama_barang"
                            class="w-full px-3 py-1.5 border border-gray-300 rounded-lg" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Merk/Type <span class="text-red-500">*</span></label>
                        <input type="text" name="merk_type" id="edit_merk_type"
                            class="w-full px-3 py-1.5 border border-gray-300 rounded-lg" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                        <select name="kategori_id" id="edit_kategori_id"
                            class="w-full px-3 py-1.5 border border-gray-300 rounded-lg" required>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id }}">
                                    {{ $kategori->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                        <input type="number" name="jumlah" id="edit_jumlah"
                            class="w-full px-3 py-1.5 border border-gray-300 rounded-lg" min="0">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Satuan <span class="text-red-500">*</span></label>
                        <input type="text" name="satuan" id="edit_satuan"
                            class="w-full px-3 py-1.5 border border-gray-300 rounded-lg" required>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="flex justify-end space-x-2 px-6 py-4 border-t border-gray-200">
                <button type="button" onclick="closeModal('editBarangModal')"
                    class="px-3 py-1.5 border border-gray-300 font-normal text-sm text-gray-700 rounded-lg hover:bg-gray-100">
                    Batal
                </button>
                <button type="submit" id="editBarangSubmitBtn"
                    class="px-3 py-1.5 bg-green-600 font-normal text-sm hover:bg-green-700 text-white rounded-lg">
                    <i class="fas fa-save mr-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- DELETE BARANG MODAL -->
<div id="deleteBarangModal" class="modal fixed inset-0 bg-black/50 z-[9999] hidden flex items-center justify-center p-4">
    <div class="modal-box bg-white rounded-2xl w-full max-w-md shadow-lg">
        <div class="p-6 text-center">
            <div class="mx-auto mb-4 w-14 h-14 rounded-full bg-red-100 flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
            </div>

            <h3 class="text-lg font-semibold text-gray-800 mb-2">
                Konfirmasi Hapus Barang
            </h3>
            <p class="text-sm text-gray-600 mb-6">
                Apakah Anda yakin ingin menghapus barang
                <span id="deleteBarangKode" class="font-semibold text-gray-800"></span>?
                <br>Tindakan ini tidak dapat dibatalkan.
            </p>

            <form id="deleteBarangForm" method="POST">
                @csrf
                @method('DELETE')

                <div class="flex justify-center space-x-2">
                    <button type="button"
                        onclick="closeModal('deleteBarangModal')"
                        class="px-3 py-1.5 border border-gray-300 rounded-lg font-normal text-sm text-gray-700 hover:bg-gray-100 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-3 py-1.5 bg-red-600 hover:bg-red-700 font-normal text-sm text-white rounded-lg transition">
                        <i class="fas fa-trash mr-1"></i> Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // modal show/detail data
    const detailBarangModal = document.getElementById('detailBarangModal');

    function openDetailBarangModal(barang) {
        document.getElementById('detailKodeBarang').textContent = barang.kode_barang;
        document.getElementById('detailNamaBarang').textContent = barang.nama_barang;
        document.getElementById('detailMerkType').textContent = barang.merk_type ?? '-';
        document.getElementById('detailKategori').textContent = barang.kategori?.nama ?? '-';
        document.getElementById('detailJumlah').textContent = barang.jumlah;
        document.getElementById('detailSatuan').textContent = barang.satuan;

        document.getElementById('detailBarangTitle').innerText =
            `Detail Barang - ${barang.kode_barang}`;

        openModal('detailBarangModal'); // ✅ pakai global
    }

    // modal edit data
    function openEditModal(barang) {
        document.getElementById('editBarangForm').action = `/barang-bmn/${barang.id}`;

        document.getElementById('edit_kode_barang').value = barang.kode_barang;
        document.getElementById('edit_nama_barang').value = barang.nama_barang;
        document.getElementById('edit_merk_type').value = barang.merk_type;
        document.getElementById('edit_kategori_id').value = barang.kategori_id;
        document.getElementById('edit_jumlah').value = barang.jumlah;
        document.getElementById('edit_satuan').value = barang.satuan;

        document.getElementById('editBarangTitle').innerText =
            `Edit Barang - ${barang.kode_barang}`;

        openModal('editBarangModal'); // ✅ pakai global
    }

    // modal delete data
    function openDeleteModal(barangId, kodeBarang) {
        document.getElementById('deleteBarangKode').textContent = kodeBarang;
        document.getElementById('deleteBarangForm').action = `/barang-bmn/${barangId}`;

        openModal('deleteBarangModal'); // ✅ pakai global
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
    function setupKodeValidation(config) {
        const input = document.getElementById(config.inputId);
        const spinner = document.getElementById(config.spinnerId);
        const successIcon = document.getElementById(config.successId);
        const errorIcon = document.getElementById(config.errorIconId);
        const errorText = document.getElementById(config.errorTextId);
        const submitBtn = document.getElementById(config.submitBtnId);

        if (!input) return;

        let timeout = null;

        function resetState() {
            spinner.classList.add('hidden');
            successIcon.classList.add('hidden');
            errorIcon.classList.add('hidden');
            errorText.classList.add('hidden');
            input.classList.remove('border-red-500', 'border-green-500');
        }

        function setLoading() {
            resetState();
            spinner.classList.remove('hidden');
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }

        function setValid() {
            resetState();
            successIcon.classList.remove('hidden');
            input.classList.add('border-green-500');
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }

        function setInvalid() {
            resetState();
            errorIcon.classList.remove('hidden');
            errorText.classList.remove('hidden');
            input.classList.add('border-red-500');
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }

        input.addEventListener('input', function () {
            const kode = this.value.trim();

            if (!kode) {
                resetState();
                submitBtn.disabled = false;
                return;
            }

            clearTimeout(timeout);

            timeout = setTimeout(() => {
                setLoading();

                let url = `{{ route('barang-bmn.checkKode') }}?kode_barang=${kode}`;

                if (config.ignoreIdGetter) {
                    const ignoreId = config.ignoreIdGetter();
                    if (ignoreId) {
                        url += `&ignore_id=${ignoreId}`;
                    }
                }

                fetch(url)
                    .then(res => res.json())
                    .then(data => {
                        if (data.exists) {
                            setInvalid();
                        } else {
                            setValid();
                        }
                    })
                    .catch(() => {
                        resetState();
                    });

            }, 100); // debounce 500ms
        });
    }

    /* INIT ADD */
    setupKodeValidation({
        inputId: 'add_kode_barang',
        spinnerId: 'addKodeSpinner',
        successId: 'addKodeSuccess',
        errorIconId: 'addKodeErrorIcon',
        errorTextId: 'addKodeErrorText',
        submitBtnId: 'addBarangSubmitBtn'
    });

    /* INIT EDIT */
    setupKodeValidation({
        inputId: 'edit_kode_barang',
        spinnerId: 'editKodeSpinner',
        successId: 'editKodeSuccess',
        errorIconId: 'editKodeErrorIcon',
        errorTextId: 'editKodeErrorText',
        submitBtnId: 'editBarangSubmitBtn',
        ignoreIdGetter: function () {
            const form = document.getElementById('editBarangForm');
            return form ? form.action.split('/').pop() : null;
        }
    });
</script>

@endsection
