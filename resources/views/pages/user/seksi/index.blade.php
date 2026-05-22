@extends('layouts.app')

@section('title', 'Seksi')

@section('page-title', 'Seksi')
@section('breadcrumb')
    <p class="text-gray-800 cursor-pointer">
        User / Seksi
    </p>
@endsection

@section('content')

<div class="space-y-4">
    <div class="bg-white rounded-lg shadow-sm p-4">
        <!-- HEADER -->
        <div class="flex justify-between mb-4">
            <h1 class="text-xl font-bold text-gray-800">
                <i class="fas fa-network-wired mr-3"></i>Daftar Seksi
            </h1>
            <div class="flex justify-end gap-2">
                <button onclick="window.location='{{ route('user.index') }}'"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1.5 font-normal text-sm rounded-lg">
                    <i class="fas fa-arrow-circle-left mr-1"></i> Kembali
                </button>
                <button onclick="openModal('addSeksiModal')"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 font-normal text-sm rounded-lg transition">
                    <i class="fas fa-plus-square mr-1"></i> Tambah Seksi
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

        <!-- Tabel -->
        <div class="overflow-x-auto">
            <table id="dataTable" class="strip text-gray-700 w-full row-border">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase w-8">No</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nama Seksi</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Seksi Singkat</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Kepala Seksi</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">NIP Kepala Seksi</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($seksis as $index => $seksi)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-800">{{ $index + 1 }}</td>
                        <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-800">{{ $seksi->seksi }}</td>
                        <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-800">{{ $seksi->seksi_singkat }}</td>
                        <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-800">{{ $seksi->nama_kepala ?? '-' }}</td>
                        <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-800">{{ $seksi->nip_kepala ?? '-' }}</td>
                        <td class="px-6 py-2 whitespace-nowrap text-sm">
                            <div class="flex space-x-2">
                                <button
                                    type="button"
                                    onclick="openEditModal(
                                        {{ $seksi->id }},
                                        '{{ $seksi->seksi }}',
                                        '{{ $seksi->seksi_singkat }}',
                                        '{{ $seksi->nama_kepala }}',
                                        '{{ $seksi->nip_kepala }}'
                                    )"
                                    class="text-green-600 hover:text-green-800"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button
                                    onclick="openDeleteModal({{ $seksi->id }}, '{{ $seksi->seksi }}')"
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
<div id="addSeksiModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="modal-box max-w-xl w-full">
        <form action="{{ route('seksi.store') }}" method="POST"
            class="bg-white rounded-2xl max-h-[90vh] flex flex-col overflow-hidden">
            @csrf

            <!-- Header -->
            <div class="px-6 py-4 border-b flex justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Tambah Seksi</h3>
                <button type="button" onclick="closeModal('addSeksiModal')"
                    class="text-gray-500 hover:text-gray-800">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Scrollable Field -->
            <div class="px-6 py-4 overflow-y-auto flex-1">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Seksi <span class="text-red-500">*</span></label>
                        <input type="text" name="seksi" class="w-full px-3 py-1.5 border rounded-lg" placeholder="Masukkan nama seksi" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Singkatan Seksi <span class="text-red-500">*</span></label>
                        <input type="text" name="seksi_singkat" class="w-full px-3 py-1.5 border rounded-lg" placeholder="Masukkan singkatan seksi" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kepala Seksi</label>
                        <input type="text" name="nama_kepala" class="w-full px-3 py-1.5 border rounded-lg" placeholder="Masukkan nama kepala seksi">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIP Kepala Seksi</label>
                        <input type="text" name="nip_kepala" class="w-full px-3 py-1.5 border rounded-lg" placeholder="Masukkan NIP kepala seksi">
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="flex justify-end px-6 py-4 space-x-2 border-t border-gray-200">
                <button type="button" onclick="closeModal('addSeksiModal')"
                    class="px-3 py-1.5 text-sm font-normal border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100">
                    Batal
                </button>
                <button type="submit"
                    class="px-3 py-1.5 text-sm font-normal bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-save mr-1"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ================= MODAL EDIT ================= --}}
<div id="editSeksiModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="modal-box max-w-xl w-full">
        <form id="editSeksiForm" method="POST"
            class="bg-white rounded-2xl max-h-[90vh] flex flex-col overflow-hidden">
            @csrf
            @method('PUT')

            <!-- Header -->
            <div class="px-6 py-4 border-b flex justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Edit Seksi</h3>
                <button type="button" onclick="closeModal('editSeksiModal')"
                    class="text-gray-500 hover:text-gray-800">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Scrollable Field -->
            <div class="px-6 py-4 overflow-y-auto flex-1">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Seksi <span class="text-red-500">*</span></label>
                        <input type="text" name="seksi" id="edit_seksi" required class="w-full px-3 py-1.5 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Singkatan Seksi <span class="text-red-500">*</span></label>
                        <input type="text" name="seksi_singkat" id="edit_seksi_singkat" required class="w-full px-3 py-1.5 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kepala</label>
                        <input type="text" name="nama_kepala" id="edit_nama_kepala" class="w-full px-3 py-1.5 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIP Kepala</label>
                        <input type="text" name="nip_kepala" id="edit_nip_kepala" class="w-full px-3 py-1.5 border rounded-lg">
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="flex justify-end px-6 py-4 space-x-2">
                <button type="button" onclick="closeModal('editSeksiModal')"
                    class="px-3 py-1.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 font-normal text-sm">
                    Batal
                </button>
                <button type="submit"
                    class="px-3 py-1.5 bg-green-600 text-white rounded-lg hover:bg-green-700 font-normal text-sm">
                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ================= MODAL DELETE ================= --}}
<div id="deleteSeksiModal" class="modal fixed inset-0 bg-black/50 z-[9999] hidden flex items-center justify-center p-4">
    <div class="modal-box bg-white rounded-lg w-full max-w-md shadow-lg">
        <div class="p-6 text-center">
            <div class="mx-auto mb-4 w-14 h-14 rounded-full bg-red-100 flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
            </div>

            <h3 class="text-lg font-semibold text-gray-800 mb-2">
                Konfirmasi Hapus Seksi
            </h3>
            <p class="text-sm text-gray-600 mb-6">
                Yakin ingin menghapus seksi
                <span id="deleteSeksiName" class="font-semibold text-gray-800"></span>?
                <br> Tindakan ini tidak dapat dibatalkan.
            </p>

            <form id="deleteSeksiForm" method="POST">
                @csrf
                @method('DELETE')

                <div class="flex justify-center space-x-2">
                    <button type="button" onclick="closeModal('deleteSeksiModal')"
                        class="px-3 py-1.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 font-normal text-sm">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-3 py-1.5 bg-red-600 text-white rounded-lg hover:bg-red-700 font-normal text-sm">
                        <i class="fas fa-save mr-1"></i> Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ================= SCRIPT (IDENTIK USERS) ================= --}}
<script>
    // modal edit data
    function openEditModal(id, seksi, seksi_singkat, nama_kepala, nip_kepala) {
        const form = document.getElementById('editSeksiForm');

        form.action = `/seksi/${id}`;
        document.getElementById('edit_seksi').value = seksi;
        document.getElementById('edit_seksi_singkat').value = seksi_singkat ?? '';
        document.getElementById('edit_nama_kepala').value = nama_kepala ?? '';
        document.getElementById('edit_nip_kepala').value = nip_kepala ?? '';

        openModal('editSeksiModal');
    }

    // modal delete data
    function openDeleteModal(id, name) {
        document.getElementById('deleteSeksiName').textContent = name;
        document.getElementById('deleteSeksiForm').action = `/seksi/${id}`;
        openModal('deleteSeksiModal');
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
