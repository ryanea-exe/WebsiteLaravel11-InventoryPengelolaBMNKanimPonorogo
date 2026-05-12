@extends('layouts.app')

@section('title', 'Riwayat Permintaan')
@section('page-title', 'Riwayat Permintaan')

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
            Riwayat Permintaan Barang
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
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase">Tanggal Permintaan</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase">Kode Barang</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase">Nama Barang</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase">Jumlah</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase">Peminta</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($pengajuans as $pengajuan)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-grey-800">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 text-sm text-grey-800">
                            {{ \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-grey-800">{{ $pengajuan->barang->kode_barang }}</td>
                        <td class="px-6 py-4 text-sm text-grey-800">{{ $pengajuan->barang->nama_barang }}</td>
                        <td class="px-6 py-4 text-sm text-grey-800">{{ $pengajuan->jumlah }} {{ $pengajuan->barang->satuan }}</td>
                        <td class="px-6 py-4 text-sm text-grey-800">{{ $pengajuan->user->name }}</td>
                        <!-- STATUS -->
                        <td class="px-6 py-4 text-sm">
                            @if($pengajuan->status === 'Disetujui')
                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                    Disetujui
                                </span>
                            @elseif($pengajuan->status === 'Ditolak')
                                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                                    Ditolak
                                </span>
                            @else
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">
                                    Diajukan
                                </span>
                            @endif
                        </td>
                        <!-- AKSI (SEMUA BISA DIHAPUS) -->
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
                                <!-- PRINT -->
                                <div class="relative inline-block text-left">
                                    <button
                                        onclick="togglePrintMenu({{ $pengajuan->id }})"
                                        class="text-gray-600 hover:text-gray-800"
                                        title="Cetak">
                                        <i class="fas fa-print"></i>
                                    </button>
                                    <div id="printMenu-{{ $pengajuan->id }}"
                                        class="hidden absolute right-0 mt-2 w-48 bg-white border rounded-lg shadow-lg z-50">
                                        <ul class="py-1 text-sm text-gray-800">
                                            <!-- NOTA DINAS (SELALU ADA) -->
                                            <li>
                                                <a href="{{ route('pengajuan.nodin', $pengajuan->id) }}" target="_blank"
                                                class="block px-4 py-2 hover:bg-gray-100">
                                                    📝 Nota Dinas
                                                </a>
                                            </li>
                                            <!-- BAST (HANYA JIKA DISETUJUI) -->
                                            @if($pengajuan->status === 'Disetujui')
                                            <li>
                                                <a href="{{ route('pengajuan.bast', $pengajuan->id) }}" target="_blank"
                                                class="block px-4 py-2 hover:bg-gray-100">
                                                    📄 Berita Acara Serah Terima
                                                </a>
                                            </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                                <!-- HAPUS -->
                                <button
                                    onclick="openDeleteModal(
                                        '{{ route('pengajuan.destroy', $pengajuan->id) }}',
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
<div id="detailPengajuanModal" class="modal fixed inset-0 bg-black/50 z-[9999] hidden flex items-center justify-center p-4">
    <div class="modal-box bg-white rounded-2xl w-full max-w-3xl shadow-lg overflow-hidden">
        <!-- HEADER -->
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h3 class="text-lg font-semibold text-gray-800" id="detailPermintaanBarangTitle">
                Detail Permintaan Barang
            </h3>

            <span class="px-4 py-1.5 text-sm rounded-full text-gray-700">
                Status: <span id="detailStatus">-</span>
            </span>
        </div>

        <!-- BODY -->
        <div class="px-6 py-6">

            <!-- INFO GRID -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Tanggal Pengajuan</p>
                    <p id="detailTanggalPengajuan" class="font-medium text-gray-800">-</p>
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
                    <p class="text-sm text-gray-500 mb-1">Jumlah Permintaan</p>
                    <p id="detailJumlah" class="font-medium text-gray-800">-</p>
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
                       class="bg-gray-50 rounded-lg p-3 text-gray-800 text-sm">
                        -
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Tanggal Proses</p>
                    <p id="detailTanggalProses" class="font-medium text-gray-800">-</p>
                </div>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="px-6 py-4 border-t flex items-center justify-between">
            <!-- SLOT KIRI (STATUS / ACTION) -->
            <div class="flex items-center">
                <!-- STATUS INFO -->
                <div id="statusInfo"></div>
                <!-- ACTION APPROVE -->
                <div id="detailAction" class="flex gap-2 hidden">
                    <button
                        onclick="submitStatus('Ditolak')"
                        class="px-5 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700">
                        ❌ Tolak
                    </button>
                    <button
                        onclick="submitStatus('Disetujui')"
                        class="px-5 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">
                        ✅ Setujui
                    </button>
                </div>
            </div>

            <!-- SLOT KANAN -->
            <button
                onclick="closeModal('detailPengajuanModal')"
                class="px-5 py-2 border rounded-lg text-gray-700 hover:bg-gray-100">
                Tutup
            </button>
        </div>

        <!-- FORM HIDDEN -->
        <form id="updateStatusForm" method="POST" class="hidden">
            @csrf
            @method('PUT')
        </form>
    </div>
</div>

<!-- DELETE PENGAJUAN MODAL -->
<div id="deletePengajuanModal" class="modal fixed inset-0 bg-black/50 z-[9999] hidden flex items-center justify-center p-4">
    <div class="modal-box bg-white rounded-lg w-full max-w-md shadow-lg">
        <div class="p-6 text-center">
            <div class="mx-auto mb-4 w-14 h-14 rounded-full bg-red-100 flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
            </div>

            <h3 class="text-lg font-semibold text-gray-800 mb-2">
                Konfirmasi Hapus Permintaan
            </h3>
            <p class="text-sm text-gray-600 mb-6">
                Apakah Anda yakin ingin menghapus permintaan barang
                <span id="deleteBarangKode" class="font-semibold text-gray-800"></span>?
                <br>
                Tindakan ini tidak dapat dibatalkan.
            </p>

            <form id="deletePengajuanForm" method="POST">
                @csrf
                @method('DELETE')

                <div class="flex justify-center space-x-2">
                    <button type="button"
                        onclick="closeModal('deletePengajuanModal')"
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
    // modal show data
    let currentPengajuan = {};
    const actionBox     = document.getElementById('detailAction');
    const updateForm    = document.getElementById('updateStatusForm');

    function openDetailModal(id) {
        fetch(`/pengajuan/${id}/detail`)
            .then(res => res.json())
            .then(data => {
                currentPengajuan = data;

                document.getElementById('detailKodeBarang').textContent = data.barang.kode_barang;
                document.getElementById('detailNamaBarang').textContent = data.barang.nama_barang;
                document.getElementById('detailJumlah').textContent =
                    `${data.jumlah} ${data.barang.satuan ?? ''}`;
                document.getElementById('detailStok').textContent =
                    `${data.barang.jumlah} ${data.barang.satuan ?? ''}`;
                document.getElementById('detailPeminta').textContent = data.user.name;
                document.getElementById('detailKeperluan').textContent = data.keperluan;
                document.getElementById('detailStatus').textContent = data.status;

                document.getElementById('detailPermintaanBarangTitle').innerText =
                    `Detail Permintaan Barang - ${data.barang.kode_barang}`;

                // tanggal
                const tgl = new Date(data.tanggal_pengajuan);
                document.getElementById('detailTanggalPengajuan').textContent =
                    tgl.toLocaleDateString('id-ID', { day:'2-digit', month:'long', year:'numeric' });

                if (data.tanggal_proses && data.status !== 'Diajukan') {
                    const tp = new Date(data.tanggal_proses);
                    document.getElementById('detailTanggalProses').textContent =
                        tp.toLocaleDateString('id-ID', { day:'2-digit', month:'long', year:'numeric' });
                } else {
                    document.getElementById('detailTanggalProses').textContent = '-';
                }

                // status
                const statusEl = document.getElementById('detailStatus');
                statusEl.className = 'px-4 py-1.5 text-sm rounded-full';

                if (data.status === 'Disetujui') {
                    statusEl.classList.add('bg-green-50','text-green-700');
                    actionBox.classList.add('hidden');
                } else if (data.status === 'Ditolak') {
                    statusEl.classList.add('bg-red-50','text-red-700');
                    actionBox.classList.add('hidden');
                } else {
                    statusEl.classList.add('bg-yellow-50','text-yellow-700');
                    actionBox.classList.remove('hidden');
                }

                updateForm.action = `/pengajuan/${data.id}/status`;

                // 🔥 BUKA MODAL VIA GLOBAL
                openModal('detailPengajuanModal');
            });
    }

    function submitStatus(status) {
        // CEK STOK SAAT APPROVE
        if (status === 'Disetujui') {
            if (currentPengajuan.jumlah > currentPengajuan.barang.jumlah) {
                closeDetailModal();
                return;
            }
        }

        // hapus input status lama jika ada
        const oldInput = updateForm.querySelector('input[name="status"]');
        if (oldInput) {
            oldInput.remove();
        }

        // buat input status baru
        const input = document.createElement('input');
        input.type  = 'hidden';
        input.name  = 'status';
        input.value = status;

        updateForm.appendChild(input);

        updateForm.submit();
    }

    // modal delete data
    function openDeleteModal(actionUrl, kodeBarang) {
        document.getElementById('deletePengajuanForm').action = actionUrl;
        document.getElementById('deleteBarangKode').textContent = kodeBarang;

        openModal('deletePengajuanModal');
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

    // menu dropdown aksi cetak
    function togglePrintMenu(id) {
        const menu = document.getElementById(`printMenu-${id}`);
        document.querySelectorAll('[id^="printMenu-"]').forEach(el => {
            if (el !== menu) el.classList.add('hidden');
        });
        menu.classList.toggle('hidden');
    }

    document.addEventListener('click', function (e) {
        if (!e.target.closest('.relative')) {
            document.querySelectorAll('[id^="printMenu-"]').forEach(el => {
                el.classList.add('hidden');
            });
        }
    });
</script>

@endsection
