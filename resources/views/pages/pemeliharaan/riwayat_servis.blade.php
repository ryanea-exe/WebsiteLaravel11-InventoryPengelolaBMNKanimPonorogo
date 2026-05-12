@extends('layouts.app')

@section('title', 'Riwayat Servis')

@section('page-title', 'Riwayat Servis')
@section('breadcrumb')
<p class="text-gray-800 cursor-pointer">
    Riwayat Servis
</p>
@endsection

@section('content')

@php 
    \Carbon\Carbon::setLocale('id'); 
@endphp

<div class="space-y-4">
    <div class="bg-white rounded-lg shadow-sm p-4">
        <!-- HEADER -->
        <div class="flex justify-between mb-4">
            <h1 class="text-xl font-bold text-gray-800">
                @if(auth()->user()->role === 'Administrator')
                <i class="fas fa-wrench mr-3"></i>Riwayat Servis Kendaraan
                @else
                <i class="fas fa-wrench mr-3"></i>Riwayat Servis Kendaraan Seksi Saya
                @endif
            </h1>
        </div>

        <!-- ALERT -->
        @if(session('success'))
        <div id="alert-message"
            class="mb-2 p-2 rounded-lg bg-green-100 text-green-800 border border-green-300">
            <strong>Sukses!</strong> {{ session('success') }}
        </div>
        @endif

        <!-- TABLE -->
        <div class="overflow-x-auto">
            <table id="dataTable" class="strip text-gray-700 w-full row-border">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="w-8">No</th>
                        <th>Kendaraan</th>
                        @if(auth()->user()->role == 'Administrator')
                        <th>Seksi</th>
                        @endif
                        <th>Tanggal Servis</th>
                        <th>Pengurus</th>
                        <th>Keterangan</th>
                        @if(auth()->user()->role == 'Staff')
                        <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($riwayatServis as $i => $r)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $r->kendaraan->nama_kendaraan ?? '-' }} <b>({{ $r->kendaraan->nomor_polisi ?? '-' }})</b></td>
                        @if(auth()->user()->role == 'Administrator')
                        <td>{{ $r->kendaraan->seksi->seksi_singkat }}</td>
                        @endif
                        <td>{{ \Carbon\Carbon::parse($r->tanggal_servis)->translatedFormat('d F Y') }}</td>
                        <td>{{ $r->nama_pengurus ?? '-' }}</td>
                        <td>{{ $r->keterangan ?? '-' }}</td>
                        @if(auth()->user()->role == 'Staff')
                        <td>
                            <div class="flex space-x-2">
                                <button onclick='openEdit(@json($r))' class="text-green-600">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick='openDelete(@json($r))' class="text-red-600">
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

{{-- ================= EDIT ================= --}}
<div id="editModal" class="modal fixed inset-0 bg-black/50 z-[9999] hidden flex items-center justify-center p-4">
    <div class="modal-box max-w-xl w-full">
        <form id="editForm" method="POST"
            class="bg-white rounded-2xl max-h-[90vh] flex flex-col overflow-hidden">
            @csrf
            @method('PUT')
            <!-- HEADER -->
            <div class="px-6 py-4 border-b flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800" id="editTitle">
                    Edit Riwayat Servis
                </h3>
                <button type="button" onclick="closeModal('editModal')"
                    class="text-gray-500 hover:text-gray-800">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- BODY -->
            <div class="px-6 py-4 overflow-y-auto flex-1">
                <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                    <input type="hidden" id="e_kendaraan_id" name="kendaraan_id">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Servis <span class="text-red-500">*</span></label>
                        <input id="e_tanggal" type="date" name="tanggal_servis"
                            class="w-full px-3 py-1.5 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pengurus <span class="text-red-500">*</span></label>
                        <input id="e_pengurus" type="text" name="nama_pengurus"
                            class="w-full px-3 py-1.5 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan (Opsional)</label>
                        <textarea id="e_ket" name="keterangan"
                            class="w-full px-3 py-1.5 border border-gray-300 rounded-lg"></textarea>
                    </div>
                </div>
            </div>

            <!-- FOOTER -->
            <div class="flex justify-end space-x-2 px-6 py-4 border-t">
                <button type="button" onclick="closeModal('editModal')"
                    class="px-3 py-1.5 border border-gray-300 rounded-lg text-gray-700 text-sm hover:bg-gray-100">
                    Batal
                </button>
                <button type="submit"
                    class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm">
                    <i class="fas fa-save mr-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ================= DELETE ================= --}}
<div id="deleteModal" class="modal fixed inset-0 bg-black/50 z-[9999] hidden flex items-center justify-center p-4">
    <div class="modal-box bg-white rounded-2xl w-full max-w-md shadow-lg">
        <div class="p-6 text-center">
            <div class="mx-auto mb-4 w-14 h-14 rounded-full bg-red-100 flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
            </div>

            <h3 class="text-lg font-semibold text-gray-800 mb-2">
                Konfirmasi Hapus Riwayat Servis
            </h3>
            <p class="text-sm text-gray-600 mb-6">
                Apakah Anda yakin ingin menghapus riwayat servis
                <span id="deleteText" class="font-semibold text-gray-800"></span>?
                <br>Tindakan ini tidak dapat dibatalkan.
            </p>

            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')

                <div class="flex justify-center space-x-2">
                    <button type="button" onclick="closeModal('deleteModal')"
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
    function openModal(id){
        document.getElementById(id).classList.remove('hidden');
    }
    function closeModal(id){
        document.getElementById(id).classList.add('hidden');
    }

    function openDetail(data){
        document.getElementById('d_kendaraan').innerText = data.kendaraan?.nama_kendaraan ?? '-';
        document.getElementById('d_tanggal').innerText = data.tanggal_servis;
        document.getElementById('d_pengurus').innerText = data.nama_pengurus ?? '-';
        document.getElementById('d_ket').innerText = data.keterangan ?? '-';

        openModal('detailModal');
    }

    function openEdit(data){
        const form = document.getElementById('editForm');
        form.action = `/pemeliharaan/riwayat_servis/${data.id}`;

        document.getElementById('editTitle').innerText =
            `Edit Riwayat Servis - ${data.kendaraan?.nama_kendaraan ?? '-'} (${data.kendaraan?.nomor_polisi ?? '-'})`;
        document.getElementById('e_kendaraan_id').value = data.kendaraan_id;
        document.getElementById('e_tanggal').value = data.tanggal_servis;
        document.getElementById('e_pengurus').value = data.nama_pengurus ?? '';
        document.getElementById('e_ket').value = data.keterangan ?? '';

        openModal('editModal');
    }

    function openDelete(data){
        const form = document.getElementById('deleteForm');

        form.action = `/pemeliharaan/riwayat_servis/${data.id}`;
        document.getElementById('deleteText').innerText =
            `${data.kendaraan?.nama_kendaraan ?? '-'} (${data.kendaraan?.nomor_polisi ?? '-'})`;

        openModal('deleteModal');
    }

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
