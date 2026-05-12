@extends('layouts.app')

@section('title', 'Barang BMN Keluar')

@section('page-title', 'Barang BMN Keluar')
@section('breadcrumb')
    <p class="text-gray-800 cursor-pointer">
        Barang BMN Keluar
    </p>
@endsection

@section('content')

@push('scripts')
<style>
    table#dataTable tbody td:nth-child(6) {
        color: #DC2626 !important;
        font-weight: 600;
    }
</style>
@endpush

<div class="space-y-4">
    <div class="bg-white rounded-lg shadow-sm p-4">
        <!-- HEADER -->
        <div class="flex justify-between mb-4">
            <h1 class="text-xl font-bold text-gray-800">
                <i class="fas fa-file-export mr-3"></i>Daftar Barang BMN Keluar
            </h1>
            <div class="flex justify-end gap-2">
                <!-- <button
                    class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-lg font-medium transition flex items-center font-normal text-sm">
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
                        <th class="px-2 py-3 text-left text-sm font-medium text-gray-500 uppercase w-8">No</th>
                        <th class="px-2 py-3 text-left text-sm font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-2 py-3 text-left text-sm font-medium text-gray-500 uppercase">Kode Barang</th>
                        <th class="px-2 py-3 text-left text-sm font-medium text-gray-500 uppercase">Nama Barang</th>
                        <th class="px-2 py-3 text-left text-sm font-medium text-gray-500 uppercase">Merk/Type</th>
                        <th class="px-2 py-3 text-left text-sm font-medium text-gray-500 uppercase">Jumlah</th>
                        <th class="px-2 py-3 text-left text-sm font-medium text-gray-500 uppercase">Peminta</th>                        
                        <th class="px-2 py-3 text-left text-sm font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @foreach($pengajuans as $pengajuan)
                    @php
                        $approvedItems = $pengajuan->details->where('status', 'Disetujui');
                    @endphp
                    {{-- KODE LAMA
                    @php
                        $approvedItems = $pengajuan->details->where('status', 'Disetujui');
                        $kodeBarangList = $approvedItems->pluck('barang.kode_barang')->join(', ');
                        $namaBarangList = $approvedItems->pluck('barang.nama_barang')->join(', ');
                        $jumlahPerBarang = $approvedItems->map(function($item) {
                            return $item->jumlah . ' ' . ($item->barang->satuan ?? '');
                        })->join(', ');
                    @endphp
                    --}}

                    <tr class="hover:bg-gray-50">
                        <td class="px-2 py-4 text-sm">{{ $loop->iteration }}</td>
                        <td class="px-2 py-4 text-sm">{{ $pengajuan->tanggal_proses ? \Carbon\Carbon::parse($pengajuan->tanggal_proses)->format('d M Y, H:i') : '-' }}</td>
                        <td class="px-2 py-4 text-sm">
                            @foreach($approvedItems as $item)
                                <div>{{ $item->barang->kode_barang }}</div>
                            @endforeach
                        </td>
                        <td class="px-2 py-4 text-sm">
                            @foreach($approvedItems as $item)
                                <div>{{ $item->barang->nama_barang }}</div>
                            @endforeach
                        </td>
                        <td class="px-2 py-4 text-sm">
                            @foreach($approvedItems as $item)
                                <div>{{ $item->barang->merk_type }}</div>
                            @endforeach
                        </td>
                        <td class="px-2 py-4 text-sm font-semibold text-red-600">
                            @foreach($approvedItems as $item)
                                <div>
                                    {{ $item->jumlah_disetujui }} {{ $item->barang->satuan ?? '' }}
                                </div>
                            @endforeach
                        </td>
                        <td class="px-2 py-4 text-sm">{{ $pengajuan->user->name }}</td>
                        <td class="px-2 py-4 text-sm">
                            <div class="flex space-x-2">
                                <button onclick="openDetailModal({{ $pengajuan->id }})"
                                    class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="openDeleteModal(
                                        '{{ route('barang-keluar.destroy', $pengajuan->id) }}',
                                        '{{ $pengajuan->kode_pengajuan }}'
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

<!-- DETAIL BARANG KELUAR MODAL -->
<div id="detailPengajuanModal" class="modal fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-3xl shadow-lg overflow-hidden">
        <!-- HEADER -->
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h3 class="text-lg font-semibold text-gray-800">
                Detail Barang Keluar
            </h3>
            <button onclick="closeDetailModal()" class="text-gray-500 hover:text-gray-800">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- BODY -->
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4 mb-4">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Tanggal Keluar</p>
                    <p id="detailTanggalProses" class="font-medium text-gray-800">-</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Kode Pengajuan</p>
                    <p id="detailKodePengajuan" class="font-medium text-blue-600">-</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Peminta</p>
                    <p id="detailPeminta" class="font-medium text-gray-800">-</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Keperluan</p>
                    <p id="detailKeperluan" class="bg-gray-50 rounded-lg p-2 text-gray-800 text-sm">-</p>
                </div>
            </div>

            <div>
                <p class="text-sm text-gray-500 mb-2">Barang</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-gray-700 border border-gray-200">
                    <thead class="bg-gray-100 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Kode Barang</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Nama Barang</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Merk/Type</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody id="detailBarangList" class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td colspan="5" class="text-center py-4 text-gray-400 italic">Tidak ada barang disetujui</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="px-6 py-4 border-t flex justify-end">
            <button onclick="closeDetailModal()" class="px-3 py-1.5 border rounded-lg text-gray-700 hover:bg-gray-100 text-sm font-normal">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- DELETE BARANG KELUAR MODAL -->
<div id="deletePengajuanModal" class="modal fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg w-full max-w-md shadow-lg">
        <div class="p-6 text-center">
            <!-- ICON -->
            <div class="mx-auto mb-4 w-14 h-14 rounded-full bg-red-100 flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
            </div>

            <!-- TITLE -->
            <h3 class="text-lg font-semibold text-gray-800 mb-2">
                Konfirmasi Hapus Barang Keluar
            </h3>
            <!-- TEXT -->
            <p class="text-sm text-gray-600 mb-6">
                Apakah Anda yakin ingin menghapus data barang keluar
                <span id="deleteBarangKode" class="font-semibold text-gray-800"></span>?
                <br>
                Tindakan ini tidak dapat dibatalkan.
            </p>

            <!-- FORM -->
            <form id="deletePengajuanForm" method="POST">
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

<script>
    // modal show/detail data
    const detailModal = document.getElementById('detailPengajuanModal');

    function openDetailModal(id) {
        fetch(`/barang-keluar-bmn/${id}/detail`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('detailKodePengajuan').textContent = data.kode_pengajuan;
                document.getElementById('detailPeminta').textContent = data.user.name;
                document.getElementById('detailKeperluan').textContent = data.keperluan;

                const tglProses = data.tanggal_proses ? new Date(data.tanggal_proses) : null;
                if (tglProses) {
                    const tanggal = tglProses.toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: 'long',
                        year: 'numeric'
                    });
                    const waktu = tglProses.toLocaleTimeString('id-ID', {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: false
                    });
                    document.getElementById('detailTanggalProses').textContent =
                        `${tanggal}, ${waktu}`;
                } else {
                    document.getElementById('detailTanggalProses').textContent = '-';
                }

                const tbody = document.getElementById('detailBarangList');
                tbody.innerHTML = '';

                if (!data.details || data.details.length === 0) {
                    tbody.innerHTML = `<tr>
                        <td colspan="4" class="text-center py-4 text-gray-400 italic">Tidak ada barang disetujui</td>
                    </tr>`;
                } else {
                    data.details.forEach(item => {
                        tbody.innerHTML += `<tr>
                            <td class="px-4 py-2 text-sm text-gray-700">${item.barang.kode_barang}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">${item.barang.nama_barang}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">${item.barang.merk_type}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">${item.jumlah_disetujui} ${item.barang.satuan ?? ''}</td>
                        </tr>`;
                    });
                }

                // buka modal dengan sistem global
                openModal('detailPengajuanModal');
            });
    }

    function closeDetailModal() {
        closeModal('detailPengajuanModal');
    }

    // modal delete data
    const deleteModal = document.getElementById('deletePengajuanModal');
    const deleteForm  = document.getElementById('deletePengajuanForm');
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

@endsection
