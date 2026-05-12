@extends('layouts.app')

@section('title', 'Kategori')

@section('page-title', 'Kategori')
@section('breadcrumb')
    <p class="text-gray-800 cursor-pointer">
        Barang / Kategori
    </p>
@endsection

@section('content')

<div class="space-y-4">
    <div class="bg-white rounded-lg shadow-sm p-4">
        <!-- HEADER -->
        <div class="flex justify-between mb-4">
            <h1 class="text-xl font-bold text-gray-800">
                <i class="fas fa-th-large mr-3"></i>Daftar Kategori
            </h1>
            <div class="flex justify-end gap-2">
                <button onclick="window.location='{{ route('barang.index') }}'"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1.5 font-normal text-sm rounded-lg">
                    <i class="fas fa-arrow-circle-left mr-1"></i> Data Barang Persediaan
                </button>
                <button onclick="window.location='{{ route('barang-bmn.index') }}'"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1.5 font-normal text-sm rounded-lg">
                    <i class="fas fa-arrow-circle-left mr-1"></i> Data Barang BMN
                </button>
                <button onclick="openModal('addKategoriModal')"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg font-normal text-sm transition">
                    <i class="fas fa-plus-square mr-1"></i> Tambah Kategori
                </button>
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
                <thead class="bg-gray-200 border-b border-gray-300">
                    <tr>
                        <th class="px-2 py-3 text-left text-xs font-semibold text-gray-500 uppercase w-8">No</th>
                        <th class="px-2 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nama Kategori</th>
                        <th class="px-2 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-300">
                    @foreach($kategoris as $index => $kategori)
                    <tr class="hover:bg-gray-100">
                        <td class="px-2 py-2 whitespace-nowrap text-sm text-gray-800">{{ $index + 1 }}</td>
                        <td class="px-2 py-2 whitespace-nowrap text-sm text-gray-800">
                            {{ $kategori->nama }}
                        </td>
                        <td class="px-2 py-2 whitespace-nowrap text-sm">
                            <div class="flex space-x-2">
                                <button onclick="openEditModal( {{ $kategori->id }}, '{{ $kategori->nama }}' )"
                                    class="text-green-600 hover:text-green-800"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="openDeleteModal({{ $kategori->id }}, '{{ $kategori->nama }}')"
                                    class="text-red-600 hover:text-red-800"
                                    title="Hapus">
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

{{-- ================= MODAL TAMBAH ================= --}}
<div id="addKategoriModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="modal-box max-w-xl w-full">
        <form action="{{ route('kategori.store') }}" method="POST"
            class="bg-white rounded-2xl max-h-[90vh] flex flex-col overflow-hidden">
            @csrf

            <!-- Header -->
            <div class="px-2 py-4 border-b flex justify-between">
                <h3 class="text-lg font-semibold text-gray-800">
                    Tambah Kategori
                </h3>
                <button type="button" onclick="closeModal('addKategoriModal')"
                    class="text-gray-500 hover:text-gray-800">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Scrollable Field -->
            <div class="px-2 py-4 overflow-y-auto flex-1">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" class="w-full px-3 py-1.5 border rounded-lg" placeholder="Masukkan nama kategori" required>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="flex justify-end border-t px-2 py-4 space-x-2">
                <button type="button" onclick="closeModal('addKategoriModal')"
                    class="px-3 py-1.5 border border-gray-300 font-normal text-sm text-gray-700 rounded-lg hover:bg-gray-100">
                    Batal
                </button>
                <button type="submit"
                    class="px-3 py-1.5 bg-blue-600 font-normal text-sm text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-save mr-1"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ================= MODAL EDIT ================= --}}
<div id="editKategoriModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="modal-box max-w-xl w-full">
        <form id="editKategoriForm" method="POST"
            class="bg-white rounded-2xl max-h-[90vh] flex flex-col overflow-hidden">
            @csrf
            @method('PUT')

            <!-- Header -->
            <div class="px-2 py-4 border-b flex justify-between">
                <h3 class="text-lg font-semibold text-gray-800">
                    Edit Kategori
                </h3>
                <button type="button" onclick="closeModal('editKategoriModal')"
                    class="text-gray-500 hover:text-gray-800">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Scrollable Field -->
            <div class="px-2 py-4 overflow-y-auto flex-1">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori</label>
                        <input type="text" name="nama" id="edit_nama"
                            class="w-full px-4 py-2 border rounded-lg">
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="flex justify-end px-2 py-4 border-t space-x-2">
                <button type="button" onclick="closeModal('editKategoriModal')"
                    class="px-4 py-2 border border-gray-300 font-normal text-sm text-gray-700 rounded-lg hover:bg-gray-100">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-green-600 text-white font-normal text-sm rounded-lg hover:bg-green-700">
                    <i class="fas fa-save mr-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ================= MODAL DELETE ================= --}}
<div id="deleteKategoriModal" class="modal fixed inset-0 bg-black/50 z-[9999] hidden flex items-center justify-center p-4">
    <div class="modal-box bg-white rounded-2xl w-full max-w-md shadow-lg">
        <div class="p-6 text-center">
            <div class="mx-auto mb-4 w-14 h-14 rounded-full bg-red-100 flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
            </div>

            <h3 class="text-lg font-semibold text-gray-800 mb-2">
                Konfirmasi Hapus Kategori
            </h3>
            <p class="text-sm text-gray-600 mb-6">
                Yakin ingin menghapus kategori
                <span id="deleteKategoriNama" class="font-semibold text-gray-800"></span>?
                <br> Tindakan ini tidak dapat dibatalkan.
            </p>

            <form id="deleteKategoriForm" method="POST">
                @csrf
                @method('DELETE')

                <div class="flex justify-center space-x-2">
                    <button type="button" onclick="closeModal('deleteKategoriModal')"
                        class="px-3 py-1.5 border border-gray-300 font-normal text-sm text-gray-700 rounded-lg hover:bg-gray-100">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-3 py-1.5 bg-red-600 text-white font-normal text-sm rounded-lg hover:bg-red-700">
                        <i class="fas fa-trash mr-1"></i> Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ================= SCRIPT (IDENTIK USERS) ================= --}}
<script>
    // modal edit data
    function openEditModal(id, nama) {
        const form = document.getElementById('editKategoriForm');

        form.action = `/kategori/${id}`;
        document.getElementById('edit_nama').value = nama;

        openModal('editKategoriModal');
    }

    // modal delete data
    function openDeleteModal(id, nama) {
        document.getElementById('deleteKategoriNama').textContent = nama;
        document.getElementById('deleteKategoriForm').action = `/kategori/${id}`;
        openModal('deleteKategoriModal');
    }

    function closeDeleteModal() {
        closeModal('deleteKategoriModal');
    }

    // auto hide alert
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
