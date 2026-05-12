@extends('layouts.app')

@section('title', 'Barang Keluar')
@section('page-title', 'Barang Keluar')

@section('content')

<div class="space-y-4">
    <!-- ACTION BUTTON (KANAN) -->
    <div class="flex justify-end gap-2">
        <button
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2
            rounded-lg font-medium transition flex items-center">
            <i class="fas fa-file-excel mr-2"></i> Export
        </button>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-4">
        <h1 class="text-xl font-bold mb-6 text-gray-800">
            Daftar Barang Keluar
        </h1>

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
        
        <!-- TABLE -->
        <div class="overflow-x-auto">
            <table id="dataTable" class="strip hover text-gray-700 w-full row-border">
                <thead class="bg-gray-100 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase">No</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase">Kode Barang</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase">Nama Barang</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase">Jumlah</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($pengajuans as $pengajuan)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-grey-800">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 text-sm text-grey-800">
                            {{ \Carbon\Carbon::parse($pengajuan->pengajuan->tanggal_pengajuan)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-grey-800">{{ $pengajuan->barang->kode_barang }}</td>
                        <td class="px-6 py-4 text-sm text-grey-800">{{ $pengajuan->barang->nama_barang }}</td>
                        <td class="px-6 py-4 text-sm font-semibold text-red-600">
                            {{ $pengajuan->jumlah }} {{ $pengajuan->barang->satuan ?? '' }}
                        </td>
                        <!-- AKSI -->
                        <td class="px-6 py-4 text-sm">
                            <div class="flex space-x-2">
                                <!-- DETAIL -->
                                <!--
                                <a href="{{ route('pengajuan.show', $pengajuan->id) }}"
                                    title="Detail"
                                    class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-eye"></i>
                                </a>
                                -->
                                <button
                                    onclick="openDetailModal({{ $pengajuan->id }})"
                                    title="Detail"
                                    class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <!-- HAPUS -->
                                <button
                                    onclick="openDeleteModal(
                                        '{{ route('barang-keluar.destroy', $pengajuan->id) }}',
                                        '{{ $pengajuan->barang->kode_barang }}'
                                    )"
                                    title="Hapus"
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

<!-- DETAIL PENGAJUAN MODAL -->
<div id="detailPengajuanModal" class="modal fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-3xl shadow-lg overflow-hidden">
        <!-- HEADER -->
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h3 class="text-lg font-semibold text-gray-800" id="detailBarangKeluarTitle">
                Detail Barang Keluar
            </h3>
            <button onclick="closeDetailModal()"
                class="text-gray-500 hover:text-gray-800">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- BODY -->
        <div class="px-6 py-6">

            <!-- INFO GRID -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Kode Pengajuan</p>
                    <p id="detailKodePengajuan" class="font-medium text-gray-800">-</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Tanggal Keluar</p>
                    <p id="detailTanggalProses" class="font-medium text-gray-800">-</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Kode Barang</p>
                    <p id="detailKodeBarang" class="font-medium text-blue-800">-</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Nama Barang</p>
                    <p id="detailNamaBarang" class="font-medium text-gray-800">-</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Jumlah Keluar</p>
                    <p id="detailJumlah" class="font-semibold text-red-600">-</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Stok Tersedia</p>
                    <p id="detailStok" class="font-medium text-gray-800">-</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Peminta</p>
                    <p id="detailPeminta" class="font-medium text-gray-800">-</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Keperluan</p>
                    <p id="detailKeperluan"
                       class="bg-gray-50 rounded-lg p-2 text-gray-800 text-sm">
                        -
                    </p>
                </div>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="px-6 py-4 border-t flex items-center justify-end">
            <!-- TUTUP -->
            <button
                onclick="closeDetailModal()"
                class="px-5 py-2 border rounded-lg text-gray-700 hover:bg-gray-100">
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
                    <button type="button"
                        onclick="closeDeleteModal()"
                        class="px-5 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
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
        fetch(`/pengajuan/${id}/detail`)
            .then(res => res.json())
            .then(data => {
                // Memformat Tanggal (asumsi data.tanggal_proses dalam format YYYY-MM-DD)
                const tglProses = new Date(data.tanggal_proses);
                document.getElementById('detailTanggalProses').textContent =
                    tglProses.toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: 'long',
                        year: 'numeric'
                    });

                document.getElementById('detailKodePengajuan').textContent = data.kode_pengajuan;
                document.getElementById('detailKodeBarang').textContent = data.barang.kode_barang;
                document.getElementById('detailNamaBarang').textContent = data.barang.nama_barang;
                document.getElementById('detailJumlah').textContent = `${data.jumlah} ${data.barang.satuan ?? ''}`;
                document.getElementById('detailStok').textContent = `${data.barang.jumlah} ${data.barang.satuan ?? ''}`;
                document.getElementById('detailPeminta').textContent = data.user.name;
                document.getElementById('detailKeperluan').textContent = data.keperluan;

                detailModal.classList.add('show');

                // 🔥 ubah judul modal (pakai kode barang yang dikirim)
                document.getElementById('detailBarangKeluarTitle').innerText =
                    `Detail Barang Keluar - ${data.barang.kode_barang}`;
            });
    }

    function closeDetailModal() {
        detailModal.classList.remove('show');
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
