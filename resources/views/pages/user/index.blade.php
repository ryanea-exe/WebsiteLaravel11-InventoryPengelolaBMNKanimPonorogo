@extends('layouts.app')

@section('title', 'User')

@section('page-title', 'User')
@section('breadcrumb')
    <p class="text-gray-800 cursor-pointer">
        User
    </p>
@endsection

@section('content')

@push('scripts')
<style>
    table#dataTable tbody td {
        padding-top: 4px;
        padding-bottom: 4px;
    }
</style>
@endpush

<div class="space-y-4">
    <div class="bg-white rounded-lg shadow-sm p-4">
        <!-- Header -->
        <div class="flex justify-between mb-4">
            <h1 class="text-xl font-bold text-gray-800">
                <i class="fas fa-users mr-3"></i>Daftar User
            </h1>
            <div class="flex justify-end gap-2">
                <button onclick="window.location='{{ route('seksi.index') }}'"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1.5 font-normal text-sm rounded-lg">
                    <i class="fas fa-sitemap mr-1"></i> Data Seksi
                </button>
                <button onclick="openModal('addUserModal')" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg font-normal text-sm transition flex items-center">
                    <i class="fas fa-user-plus mr-1"></i> Tambah User
                </button>
                <!-- <button
                    class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-lg font-normal text-sm transition flex items-center">
                    <i class="fas fa-file-excel mr-1"></i> Export
                </button> -->
            </div>
        </div>

        <!-- User Cards -->
        <!--
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($users as $user)
            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition">
                <div class="flex items-start justify-between">
                    <div class="flex items-center space-x-2">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xl">
                            <img src="{{ $user->photo_url }}"
                                class="w-14 h-14 rounded-full object-cover border"
                                alt="Foto Profil">
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">{{ $user['name'] }}</h3>
                            <p class="text-sm text-gray-600">{{ $user['email'] }}</p>
                            <div class="flex items-center space-x-2 mt-2">
                                <span class="px-2 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded">{{ $user['role'] }}</span>
                                @if($user['status'] === 'Aktif')
                                <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded">Aktif</span>
                                @else
                                <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded">Nonaktif</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex space-x-2 mt-4 pt-4 border-t border-gray-200">
                    <button class="flex-1 text-blue-600 hover:text-blue-800 text-sm font-medium">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </button>
                    <button class="flex-1 text-red-600 hover:text-red-800 text-sm font-medium">
                        <i class="fas fa-trash mr-1"></i> Hapus
                    </button>
                </div>
            </div>
            @endforeach
        </div>
        -->

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
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-8">No</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Terakhir Login</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($users as $index => $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-800">{{ $index + 1 }}</td>
                        <td class="px-6 py-1 whitespace-nowrap">
                            <div class="flex items-center space-x-3">
                                <img src="{{ $user->photo_url }}"
                                    class="w-9 h-9 rounded-full object-cover border"
                                    alt="Foto Profil">
                                <span class="text-sm font-medium text-gray-800">
                                    {{ $user->name }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-1 whitespace-nowrap">
                            <span class="px-3 py-1 text-xs font-semibold text-blue-700 bg-blue-100 rounded-full">{{ $user['role'] }}</span>
                        </td>
                        <td class="px-6 py-1 whitespace-nowrap">
                            @if($user['status'] === 'Aktif')
                            <span class="px-3 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">
                                <i class="fas fa-circle text-[10px]"></i> Aktif
                            </span>
                            @else
                            <span class="px-3 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">
                                <i class="fas fa-circle text-[10px]"></i> Tidak Aktif
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-1 whitespace-nowrap text-sm">
                            @if($user->last_login_at)
                                <span class="text-gray-800">
                                    {{ $user->last_login_formatted }}
                                </span>
                            @else
                                <span class="text-gray-400 italic">
                                    Belum Pernah Login
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-1 whitespace-nowrap text-sm">
                            <div class="flex space-x-2">
                                <button
                                    onclick='openUserDetailModal(@json($user))'
                                    class="text-blue-600 hover:text-blue-800"
                                    title="Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button
                                    onclick="openEditModal({{ $user }})"
                                    class="text-green-600 hover:text-green-800"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button
                                    onclick="openDeleteModal({{ $user->id }}, '{{ $user->name }}')"
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

<!-- Add User Modal (Hidden by default) -->
<div id="addUserModal" class="modal fixed inset-0 bg-black/50 z-[9999] hidden flex items-center justify-center p-4">
    <div class="modal-box max-w-3xl w-full">
        <form action="{{ route('user.store') }}" method="POST" enctype="multipart/form-data"
            class="bg-white rounded-2xl max-h-[90vh] flex flex-col overflow-hidden">
            @csrf
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">
                    Tambah User
                </h3>
                <button type="button" onclick="closeModal('addUserModal')" 
                    class="text-gray-500 hover:text-gray-800">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Scrollable Field -->
            <div class="px-6 py-4 overflow-y-auto flex-1">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Masukkan nama lengkap" required>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="email" name="email" id="add_email"
                                class="w-full border rounded-lg px-3 py-2 pr-10"
                                placeholder="email@example.com" required>
                            <div id="addEmailSpinner"
                                class="absolute right-3 top-1/2 -translate-y-1/2 hidden">
                                <i class="fas fa-spinner fa-spin text-gray-400"></i>
                            </div>
                            <div id="addEmailSuccess"
                                class="absolute right-3 top-1/2 -translate-y-1/2 hidden">
                                <i class="fas fa-check text-green-500"></i>
                            </div>
                            <div id="addEmailErrorIcon"
                                class="absolute right-3 top-1/2 -translate-y-1/2 hidden">
                                <i class="fas fa-times text-red-500"></i>
                            </div>
                        </div>

                        <p id="addEmailErrorText"
                            class="text-xs text-red-600 mt-1 hidden">
                            Email sudah digunakan.
                        </p>
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password <span class="text-red-500">*</span></label>
                        <input type="password" id="password" name="password" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" minlength="8" placeholder="Minimal 8 karakter" required>
                    </div>
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role <span class="text-red-500">*</span></label>
                        <select id="role" name="role" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            <option value="">-- Pilih Role --</option>
                            <option value="Administrator">Administrator</option>
                            <option value="Staff">Staff</option>
                        </select>
                    </div>
                    <div>
                        <label for="kepala_seksi" class="block text-sm font-medium text-gray-700 mb-2">Seksi <span class="text-red-500">*</span></label>
                        <select name="seksi_id" class="w-full px-3 py-1.5 border rounded-lg" required>
                            <option value="">-- Pilih Seksi --</option>
                            @foreach($seksis as $s)
                                <option value="{{ $s->id }}">
                                    {{ $s->seksi }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="photo" class="block text-sm font-medium text-gray-700 mb-2"> Foto Profil (Opsional) </label>
                        <div id="photoPreviewContainer" class="mt-3 hidden">
                            <img id="photoPreview"
                                class="w-24 h-24 object-cover border">
                            <p id="photoInfo"
                                class="text-xs text-gray-600 mt-2"></p>
                        </div>
                        <input type="file" id="photo" name="photo"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg
                            focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            accept="image/*">
                        <p class="text-xs text-gray-500 mt-1">
                            Format JPG/PNG, maksimal 2MB
                        </p>
                        <p id="photoError"
                            class="mt-1 text-xs text-red-600 hidden"></p>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="flex justify-end space-x-2 px-6 py-4 border-t border-gray-200">
                <button type="button" onclick="closeModal('addUserModal')" 
                    class="px-3 py-1.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 font-medium transition font-normal text-sm">
                    Batal
                </button>
                <button type="submit" id="addSubmitBtn"
                    class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition font-normal text-sm">
                    <i class="fas fa-save mr-1"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- DETAIL USER MODAL -->
<div id="detailUserModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[9999] flex items-center justify-center p-4">
    <div class="modal-box bg-white rounded-2xl w-full max-w-2xl shadow-lg max-h-[90vh] overflow-y-auto">
        <!-- HEADER -->
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h3 class="text-lg font-semibold text-gray-800" id="detailUserTitle">
                Detail User
            </h3>
            <button onclick="closeUserDetailModal()"
                class="text-gray-500 hover:text-gray-800">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- BODY -->
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500 mb-2">Foto Profil</p>
                    <img id="detailUserPhoto" class="w-24 h-24 rounded-lg object-cover border">
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Nama Lengkap</p>
                    <p id="detailUserName" class="font-medium text-gray-800">-</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Email</p>
                    <p id="detailUserEmail" class="font-medium text-blue-600">-</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Seksi</p>
                    <p id="detailUserSeksi" class="font-medium text-gray-800">-</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Role</p>
                    <p id="detailUserRole" class="font-medium text-gray-800">-</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Status</p>
                    <p id="detailUserStatus" class="font-medium">-</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Terakhir Login</p>
                    <p id="detailUserLastLogin" class="font-medium text-gray-800">-</p>
                </div>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="px-6 py-4 border-t flex justify-end">
            <button
                onclick="closeUserDetailModal()"
                class="px-3 py-1.5 border rounded-lg text-gray-700 hover:bg-gray-100 font-normal text-sm">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div id="editUserModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[9999] flex items-center justify-center p-4">
    <div class="modal-box max-w-3xl w-full">
        <form id="editUserForm" method="POST" enctype="multipart/form-data"
            class="bg-white rounded-2xl max-h-[90vh] flex flex-col overflow-hidden">
            @csrf
            @method('PUT')
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800" id="editUserTitle">
                    Edit User
                </h3>
                <button type="button" onclick="closeModal('editUserModal')" 
                    class="text-gray-500 hover:text-gray-800">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Scrollable Field -->
            <div class="px-6 py-4 overflow-y-auto flex-1">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" id="edit_name" class="w-full px-3 py-1.5 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <div class="relative">
                            <input type="email" name="email" id="edit_email"
                                class="w-full border rounded-lg px-3 py-2 pr-10"
                                placeholder="email@example.com" required>
                            <div id="editEmailSpinner"
                                class="absolute right-3 top-1/2 -translate-y-1/2 hidden">
                                <i class="fas fa-spinner fa-spin text-gray-400"></i>
                            </div>
                            <div id="editEmailSuccess"
                                class="absolute right-3 top-1/2 -translate-y-1/2 hidden">
                                <i class="fas fa-check text-green-500"></i>
                            </div>
                            <div id="editEmailErrorIcon"
                                class="absolute right-3 top-1/2 -translate-y-1/2 hidden">
                                <i class="fas fa-times text-red-500"></i>
                            </div>
                        </div>
                        <p id="editEmailErrorText"
                            class="text-xs text-red-600 mt-1 hidden">
                            Email sudah digunakan.
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-xs text-gray-500">(Kosongkan jika tidak diubah)</span></label>
                        <input type="password" name="password" minlength="8" class="w-full px-3 py-1.5 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <select name="role" id="edit_role" class="w-full px-3 py-1.5 border rounded-lg">
                            <option value="Administrator">Administrator</option>
                            <option value="Staff">Staff</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Seksi</label>
                        <select name="seksi_id" class="w-full px-3 py-1.5 border rounded-lg">
                            <option value="">-- Pilih Seksi --</option>
                            @foreach($seksis as $s)
                                <option value="{{ $s->id }}">
                                    {{ $s->seksi }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" id="edit_status" class="w-full px-3 py-1.5 border rounded-lg">
                            <option value="Aktif">Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Foto Profil (Opsional)</label>
                        <!-- PREVIEW FOTO -->
                        <div id="editPhotoPreviewContainer" class="mt-3">
                            <img id="editPhotoPreview"
                                class="w-24 h-24 object-cover border">
                            <p id="editPhotoInfo"
                                class="text-xs text-gray-600 mt-2"></p>
                        </div>
                        <input type="file" name="photo" id="edit_photo"
                            class="w-full mt-3 px-4 py-2 border border-gray-300 rounded-lg
                            focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            accept="image/*">
                        <p class="text-xs text-gray-500 mt-1">
                            Format JPG/PNG, maksimal 2MB
                        </p>
                        <p id="editPhotoError"
                            class="mt-1 text-xs text-red-600 hidden"></p>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="flex justify-end space-x-2 px-6 py-4 border-t">
                <button type="button" onclick="closeModal('editUserModal')"
                    class="px-3 py-1.5 border rounded-lg text-gray-700 hover:bg-gray-100 font-normal text-sm">
                    Batal
                </button>
                <button type="submit" id="editSubmitBtn"
                    class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded-lg font-normal text-sm">
                    <i class="fas fa-save mr-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- DELETE CONFIRMATION MODAL -->
<div id="deleteUserModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[9999] flex items-center justify-center p-4">
    <div class="modal-box bg-white rounded-2xl w-full max-w-md shadow-lg">
        <div class="p-6 text-center">
            <div class="mx-auto mb-4 w-14 h-14 rounded-full bg-red-100 flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
            </div>

            <h3 class="text-lg font-semibold text-gray-800 mb-2">
                Konfirmasi Hapus User
            </h3>
            <p class="text-sm text-gray-600 mb-6">
                Apakah Anda yakin ingin menghapus user
                <span id="deleteUserName" class="font-semibold text-gray-800"></span>?
                <br> Tindakan ini tidak dapat dibatalkan.
            </p>

            <form id="deleteUserForm" method="POST">
                @csrf
                @method('DELETE')

                <div class="flex justify-center space-x-2">
                    <button type="button" onclick="closeDeleteModal()"
                        class="px-3 py-1.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition font-normal text-sm">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-lg transition font-normal text-sm">
                        <i class="fas fa-trash mr-1"></i> Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // modal show data
    function openUserDetailModal(user) {
        // 🔥 ubah judul modal (pakai nama user yang dikirim)
        document.getElementById('detailUserTitle').innerText =
            `Detail User - ${user.name}`;

        document.getElementById('detailUserName').textContent  = user.name;
        document.getElementById('detailUserEmail').textContent = user.email;
        document.getElementById('detailUserRole').textContent  = user.role;
        document.getElementById('detailUserSeksi').textContent = user.seksi?.seksi ?? '-';

        // STATUS
        const statusEl = document.getElementById('detailUserStatus');
        statusEl.textContent = user.status;
        statusEl.className = user.status === 'Aktif'
            ? 'font-semibold text-green-700'
            : 'font-semibold text-red-700';

        // LAST LOGIN
        if (user.last_login_at) {
            const lastLogin = new Date(user.last_login_at);
            const tanggal = lastLogin.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            });
            const waktu = lastLogin.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            });
            document.getElementById('detailUserLastLogin').textContent =
                `${tanggal}, ${waktu}`;
        } else {
            document.getElementById('detailUserLastLogin').textContent =
                'Belum Pernah Login';
        }
        /* document.getElementById('detailUserLastLogin').textContent =
            user.last_login_formatted ?? 'Belum Pernah Login'; */

        // FOTO
        document.getElementById('detailUserPhoto').src = user.photo_url;

        openModal('detailUserModal');
    }

    function closeUserDetailModal() {
        closeModal('detailUserModal');
    }

    // modal edit data
    function openEditModal(user) {
        // 🔥 ubah judul modal (pakai nama user yang dikirim)
        document.getElementById('editUserTitle').innerText =
            `Edit User - ${user.name}`;

        document.getElementById('editUserForm').action = `/user/${user.id}`;
        document.getElementById('edit_name').value   = user.name;
        document.getElementById('edit_email').value  = user.email;
        document.getElementById('edit_role').value   = user.role;
        document.getElementById('edit_status').value = user.status;
        
        // SET SEKSI
        const seksiSelect = document.querySelector('#editUserForm select[name="seksi_id"]');
        if (seksiSelect) {
            seksiSelect.value = user.seksi_id ?? '';
        }

        // FOTO SAAT INI
        const preview = document.getElementById('editPhotoPreview');
        const info    = document.getElementById('editPhotoInfo');

        preview.src = user.photo_url;
        info.textContent = 'Foto profil saat ini';

        openModal('editUserModal');
    }

    // modal delete data
    function openDeleteModal(userId, userName) {
        const modal = document.getElementById('deleteUserModal');
        const form = document.getElementById('deleteUserForm');
        const nameSpan = document.getElementById('deleteUserName');

        nameSpan.textContent = userName;
        form.action = `/user/${userId}`;

        openModal('deleteUserModal');
    }

    function closeDeleteModal() {
        closeModal('deleteUserModal');
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
    // preview foto add data
    document.addEventListener('DOMContentLoaded', function () {
        const photoInput = document.getElementById('photo');
        const previewContainer = document.getElementById('photoPreviewContainer');
        const previewImage = document.getElementById('photoPreview');
        const photoInfo = document.getElementById('photoInfo');
        const photoError = document.getElementById('photoError');

        if (!photoInput) return;

        photoInput.addEventListener('change', function () {
            const file = this.files[0];

            // Reset
            previewContainer.classList.add('hidden');
            photoError.classList.add('hidden');
            previewImage.src = '';

            if (!file) return;

            // Validasi tipe file
            if (!file.type.startsWith('image/')) {
                photoError.textContent = 'File harus berupa gambar.';
                photoError.classList.remove('hidden');
                this.value = '';
                return;
            }

            // Validasi ukuran max 2MB
            const maxSize = 2 * 1024 * 1024;
            if (file.size > maxSize) {
                photoError.textContent = 'Ukuran foto maksimal 2MB.';
                photoError.classList.remove('hidden');
                this.value = '';
                return;
            }

            // Info ukuran
            const sizeKB = (file.size / 1024).toFixed(1);
            const sizeMB = (file.size / 1024 / 1024).toFixed(2);
            const sizeText = sizeMB >= 1
                ? `${sizeMB} MB`
                : `${sizeKB} KB`;

            photoInfo.textContent = `${file.name} • ${sizeText}`;

            // Preview
            const reader = new FileReader();
            reader.onload = function (e) {
                previewImage.src = e.target.result;
                previewContainer.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        });
    });

    // preview foto edit data
    document.addEventListener('DOMContentLoaded', function () {
        const input   = document.getElementById('edit_photo');
        const preview = document.getElementById('editPhotoPreview');
        const info    = document.getElementById('editPhotoInfo');
        const error   = document.getElementById('editPhotoError');

        if (!input) return;

        input.addEventListener('change', function () {
            const file = this.files[0];

            error.classList.add('hidden');

            if (!file) return;

            // Validasi image
            if (!file.type.startsWith('image/')) {
                error.textContent = 'File harus berupa gambar.';
                error.classList.remove('hidden');
                this.value = '';
                return;
            }

            // Validasi max 2MB
            const maxSize = 2 * 1024 * 1024;
            if (file.size > maxSize) {
                error.textContent = 'Ukuran foto maksimal 2MB.';
                error.classList.remove('hidden');
                this.value = '';
                return;
            }

            // Info file
            const sizeKB = (file.size / 1024).toFixed(1);
            const sizeMB = (file.size / 1024 / 1024).toFixed(2);
            const sizeText = sizeMB >= 1 ? `${sizeMB} MB` : `${sizeKB} KB`;

            info.textContent = `${file.name} • ${sizeText}`;

            // Preview
            const reader = new FileReader();
            reader.onload = e => preview.src = e.target.result;
            reader.readAsDataURL(file);
        });
    });
</script>

<script>
    /* ===============================
    CHECK EMAIL - ADD USER
    ================================ */
    document.getElementById('add_email')?.addEventListener('blur', function () {
        const email = this.value;
        const errorEl = document.getElementById('addEmailError');

        if (!email) return;

        fetch(`{{ route('user.checkEmail') }}?email=${email}`)
            .then(res => res.json())
            .then(data => {
                if (data.exists) {
                    errorEl.classList.remove('hidden');
                    this.classList.add('border-red-500');
                } else {
                    errorEl.classList.add('hidden');
                    this.classList.remove('border-red-500');
                }
            });
    });

    /* ===============================
    CHECK EMAIL - EDIT USER
    ================================ */
    document.getElementById('edit_email')?.addEventListener('blur', function () {
        const email = this.value;
        const userId = document.getElementById('editUserForm')
                        .action.split('/').pop();
        const errorEl = document.getElementById('editEmailError');

        if (!email) return;

        fetch(`{{ route('user.checkEmail') }}?email=${email}&ignore_id=${userId}`)
            .then(res => res.json())
            .then(data => {
                if (data.exists) {
                    errorEl.classList.remove('hidden');
                    this.classList.add('border-red-500');
                } else {
                    errorEl.classList.add('hidden');
                    this.classList.remove('border-red-500');
                }
            });
    });

    /* ===============================
    PREVENT SUBMIT IF EMAIL EXISTS
    ================================ */
    document.querySelectorAll('#addUserModal form, #editUserModal form')
        .forEach(form => {
            form.addEventListener('submit', function (e) {
                const hasError =
                    !document.getElementById('addEmailErrorText')?.classList.contains('hidden') ||
                    !document.getElementById('editEmailErrorText')?.classList.contains('hidden');

                if (hasError) {
                    e.preventDefault();
                }
            });
        });

    function setupEmailValidation(config) {
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
            const email = this.value;

            if (!email) {
                resetState();
                submitBtn.disabled = false;
                return;
            }

            clearTimeout(timeout);

            timeout = setTimeout(() => {
                setLoading();

                let url = `{{ route('user.checkEmail') }}?email=${email}`;

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
    setupEmailValidation({
        inputId: 'add_email',
        spinnerId: 'addEmailSpinner',
        successId: 'addEmailSuccess',
        errorIconId: 'addEmailErrorIcon',
        errorTextId: 'addEmailErrorText',
        submitBtnId: 'addSubmitBtn'
    });

    /* INIT EDIT */
    setupEmailValidation({
        inputId: 'edit_email',
        spinnerId: 'editEmailSpinner',
        successId: 'editEmailSuccess',
        errorIconId: 'editEmailErrorIcon',
        errorTextId: 'editEmailErrorText',
        submitBtnId: 'editSubmitBtn',
        ignoreIdGetter: function () {
            const form = document.getElementById('editUserForm');
            return form ? form.action.split('/').pop() : null;
        }
    });
</script>

@endsection
