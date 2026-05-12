@extends('layouts.app')

@section('title', 'Riwayat Pengajuan')

@section('page-title', 'Riwayat Pengajuan BMN')
@section('breadcrumb')
    <p class="text-gray-800 cursor-pointer">
        Riwayat Pengajuan BMN
    </p>
@endsection

@section('content')

@push('scripts')
<style>
    table#dataTable tbody td:nth-child(3) {
        /* color: #2563EB !important; */
        font-weight: 600;
    }
</style>
@endpush

<div class="space-y-4">
    <div class="bg-white rounded-lg shadow-sm">
        <!-- MENU TAB FILTER -->
        <div class="border-b border-gray-200">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="tabMenu">
                <!-- TAB SEMUA -->
                <li class="mr-1">
                    <button onclick="filterSeksi('all')"
                        class="tab-item inline-block px-3 py-2 border-b-2 border-blue-600 text-blue-600"
                        data-seksi="all">
                        Semua Seksi
                    </button>
                </li>
                <!-- TAB SEKSI DINAMIS -->
                @php
                    $seksis = $pengajuans
                        ->map(function($p) {
                            return $p->user->seksi;
                        })
                        ->filter() // buang null kalau ada
                        ->unique('id');
                @endphp

                @foreach($seksis as $seksi)
                <li class="mr-1">
                    <button onclick="filterSeksi('{{ $seksi->id }}')"
                        class="tab-item inline-block px-3 py-2 border-b-2 border-transparent hover:border-blue-500 hover:text-blue-600"
                        data-seksi="{{ $seksi->id }}">
                        {{ $seksi->seksi_singkat }}
                    </button>
                </li>
                @endforeach
            </ul>
        </div>

        <div class="p-4">
            <!-- HEADER -->
            <div class="flex justify-between mb-4">
                <h1 class="text-xl font-bold text-gray-800">
                    <i class="fas fa-history mr-3"></i>Daftar Riwayat Pengajuan Barang BMN
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
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase w-8">No</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase">Tanggal Pengajuan</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase">Kode Pengajuan</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase">Peminta</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($pengajuans as $pengajuan)
                        <tr class="hover:bg-gray-50" data-seksi="{{ $pengajuan->user->seksi_id }}">
                            <td class="px-6 py-4 text-sm">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 text-sm">{{ \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->format('d M Y, H:i') }}</td>
                            <td class="px-6 py-4 text-sm">{{ $pengajuan->kode_pengajuan }}</td>
                            <td class="px-6 py-4 text-sm">{{ $pengajuan->user->name }}</td>
                            <td class="px-6 py-4 text-sm">
                                @if($pengajuan->status === 'Disetujui')
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                        Disetujui
                                    </span>
                                @elseif($pengajuan->status === 'Disetujui Sebagian')
                                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                                        Disetujui Sebagian
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
                            <td class="px-6 py-4 text-sm">
                                <div class="flex space-x-2">
                                    <button onclick="openDetailModal({{ $pengajuan->id }})"
                                        class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <div class="relative inline-block text-left">
                                        <button onclick="togglePrintMenu({{ $pengajuan->id }})"
                                            class="text-gray-600 hover:text-gray-800"
                                            title="Cetak">
                                            <i class="fas fa-print"></i>
                                        </button>
                                        <div id="printMenu-{{ $pengajuan->id }}"
                                            class="hidden absolute right-0 mt-2 w-60 bg-white border rounded-lg shadow-lg z-50">
                                            <ul class="py-1 text-sm text-gray-800">
                                                <!-- NOTA DINAS (SELALU ADA) -->
                                                <li>
                                                    <a href="{{ route('pengajuan-bmn.nodin', $pengajuan->id) }}" target="_blank"
                                                        class="block px-4 py-2 hover:bg-gray-100">
                                                        📝 Nota Dinas
                                                    </a>
                                                </li>
                                                <!-- BAST (HANYA JIKA DISETUJUI) -->
                                                @if(in_array($pengajuan->status, ['Disetujui', 'Disetujui Sebagian']))
                                                <li>
                                                    <a href="{{ route('pengajuan-bmn.bast', $pengajuan->id) }}" target="_blank"
                                                        class="block px-4 py-2 hover:bg-gray-100">
                                                        📄 Berita Acara Serah Terima
                                                    </a>
                                                </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                    <button onclick="openDeleteModal(
                                            '{{ route('pengajuan.destroy', $pengajuan->id) }}',
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
</div>

<!-- DETAIL PENGAJUAN MODAL -->
<div id="detailPengajuanModal" class="modal fixed inset-0 bg-black/50 z-[9999] hidden flex items-center justify-center p-4">
    <div class="modal-box bg-white rounded-2xl w-full max-w-4xl shadow-lg max-h-[90vh] flex flex-col">
        <!-- HEADER -->
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h3 class="text-lg font-semibold text-gray-800" id="detailPengajuanBarangTitle">
                Detail Pengajuan Barang BMN
            </h3>
            <div class="gap-2">
                <span class="px-4 py-1.5 text-sm rounded-full text-gray-700">
                    Status: <span id="detailStatus">-</span>
                </span>
                <button onclick="closeModal('detailPengajuanModal')" 
                        class="text-gray-500 hover:text-gray-800">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>

        <!-- BODY -->
        <div class="px-6 py-4 overflow-y-auto flex-1">
            <!-- INFO GRID -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4 mb-4">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Tanggal Pengajuan</p>
                    <p id="detailTanggalPengajuan" class="font-medium text-gray-800">-</p>
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
                    <p id="detailKeperluan" class="bg-gray-50 rounded-lg p-3 text-gray-800 text-sm">-</p>
                </div>
                <div id="tanggalProsesContainer">
                    <p class="text-sm text-gray-500 mb-1">Tanggal Proses</p>
                    <p id="detailTanggalProses" class="font-medium text-gray-800">-</p>
                </div>
                <div id="keteranganProsesContainer">
                    <p class="text-sm text-gray-500 mb-1">Keterangan Proses</p>
                    <p id="keteranganProsesText" class="bg-gray-50 rounded-lg p-3 text-gray-800 text-sm">-</p>
                </div>
            </div>

            <div>
                <p class="text-sm text-gray-500 mb-2">Barang</p>
            </div>

            <!-- TABEL BARANG -->
            <div class="overflow-x-auto">
                <table class="w-full text-gray-700 border border-gray-300">
                    <thead class="bg-gray-200 border-b border-gray-300">
                        <tr id="headerBarang">
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Kode Barang</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Nama Barang</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Merk/Type</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Jumlah Permintaan</th>
                            <th id="headerStok"
                                class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider hidden">
                                Stok Tersedia
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                            <th id="headerDisetujui"
                                class="px-4 py-3 text-left text-xs font-semibold uppercase hidden">
                                Jumlah Disetujui
                            </th>
                        </tr>
                    </thead>
                    <tbody id="detailBarangList" class="bg-white divide-y divide-gray-300">
                        <tr>
                            <td colspan="5"
                                class="text-center py-4 text-gray-700 italic">
                                Tidak ada data barang
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="keteranganContainer" class="mt-4 hidden">
                <label class="block text-sm text-gray-500 mb-2">Keterangan Proses</label>
                <textarea id="keteranganProses"
                    class="w-full border rounded-lg p-2 text-sm"
                    rows="2" placeholder="Contoh: Permintaan 10 unit, disetujui 5 karena stok terbatas..."></textarea>
            </div>

            <div id="stokErrorMessage"
                class="hidden mt-3 p-2 rounded-lg bg-red-100 text-red-700 border border-red-300 text-sm">
            </div>
        </div>

        <!-- FOOTER -->
        <div class="px-6 py-4 border-t flex justify-end gap-2">
            <button onclick="goToDetailPage()"
                class="px-3 py-1.5 font-normal text-sm border rounded-lg bg-blue-600 hover:bg-blue-700 text-white ">
                Detail
            </button>
            <button onclick="closeModal('detailPengajuanModal')"
                class="px-3 py-1.5 text-sm font-normal border rounded-lg text-gray-700 hover:bg-gray-100">
                Tutup
            </button>
            <button id="btnSimpan" onclick="simpanStatus()"
                class="px-3 py-1.5 text-sm font-normal bg-blue-600 text-white rounded-lg hidden">
                <i class="fas fa-save mr-2"></i> Simpan Status Pengajuan
            </button>
        </div>
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
                Konfirmasi Hapus Pengajuan
            </h3>
            <p class="text-sm text-gray-600 mb-6">
                Apakah Anda yakin ingin menghapus pengajuan
                <span id="deleteBarangKode" class="font-semibold text-gray-800"></span>?
                <br>
                Tindakan ini tidak dapat dibatalkan.
            </p>

            <form id="deletePengajuanForm" method="POST">
                @csrf
                @method('DELETE')

                <div class="flex justify-center space-x-2">
                    <button type="button" onclick="closeModal('deletePengajuanModal')"
                        class="px-3 py-1.5 text-sm font-normal border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-3 py-1.5 text-sm font-normal bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
                        <i class="fas fa-trash mr-1"></i> Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // menu tab filter
    function filterSeksi(seksiId) {
        const rows = document.querySelectorAll('#dataTable tbody tr');
        const tabs = document.querySelectorAll('.tab-item');

        // reset semua tab
        tabs.forEach(tab => {
            tab.classList.remove('border-blue-600', 'text-blue-600');
            tab.classList.add('border-transparent');
        });

        // aktifkan tab yang diklik
        const activeTab = document.querySelector(`[data-seksi="${seksiId}"]`);
        activeTab.classList.add('border-blue-600', 'text-blue-600');
        activeTab.classList.remove('border-transparent');

        // filter tabel
        rows.forEach(row => {
            const seksi = row.getAttribute('data-seksi');

            if (seksiId === 'all' || seksi === seksiId) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // modal show data
    let currentPengajuanId = null;
    const updateForm    = document.getElementById('updateStatusForm');

    function openDetailModal(id) {
        currentPengajuanId = id;
        fetch(`/pengajuan-bmn/${id}/detail`)
            .then(res => res.json())
            .then(data => {
                currentPengajuan = data;

                // ===== INFO UTAMA =====
                const tglPengajuan = new Date(data.tanggal_pengajuan);
                const tanggal = tglPengajuan.toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric'
                });
                const waktu = tglPengajuan.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit'
                });
                document.getElementById('detailTanggalPengajuan').textContent = `${tanggal}, ${waktu}`;
                document.getElementById('detailPengajuanBarangTitle').innerText = `Detail Pengajuan Barang - ${data.kode_pengajuan}`;
                document.getElementById('detailKodePengajuan').textContent = data.kode_pengajuan;
                document.getElementById('detailPeminta').textContent = data.user.name;
                document.getElementById('detailKeperluan').textContent = data.keperluan;
                document.getElementById('detailStatus').textContent = data.status;

                const headerStok = document.getElementById('headerStok');
                const headerDisetujui = document.getElementById('headerDisetujui');
                if (data.status === 'Diajukan') {
                    headerStok.classList.remove('hidden');
                    headerDisetujui.classList.remove('hidden');
                } else {
                    headerStok.classList.add('hidden');
                    headerDisetujui.classList.remove('hidden');
                }

                // status
                const statusEl = document.getElementById('detailStatus');
                statusEl.className = 'px-3 py-1 text-sm rounded-full font-semibold';
                if (data.status === 'Disetujui') {
                    statusEl.classList.add('bg-green-100','text-green-700');
                }
                else if (data.status === 'Disetujui Sebagian') {
                    statusEl.classList.add('bg-blue-100','text-blue-700');
                }
                else if (data.status === 'Ditolak') {
                    statusEl.classList.add('bg-red-100','text-red-700');
                }
                else {
                    statusEl.classList.add('bg-yellow-100','text-yellow-700');
                }

                if (data.tanggal_proses) {
                    const tglProses = new Date(data.tanggal_proses);
                    const tanggal = tglProses.toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: 'long',
                        year: 'numeric'
                    });
                    const waktu = tglProses.toLocaleTimeString('id-ID', {
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                    document.getElementById('detailTanggalProses').textContent =
                        `${tanggal}, ${waktu}`;
                } else {
                    document.getElementById('detailTanggalProses').textContent = '-';
                }

                // ===== TABEL BARANG =====
                const tbody = document.getElementById('detailBarangList');
                tbody.innerHTML = '';
                if (!data.details || data.details.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="4"
                                class="text-center py-4 text-gray-700 italic">
                                Tidak ada data barang
                            </td>
                        </tr>`;
                } else {
                    data.details.forEach(item => {
                        let selectStatus = '';
                        let inputDisetujui = '';

                        if (data.status === 'Diajukan') {
                            selectStatus = `
                                <select 
                                    class="status-select border rounded px-2 py-1 text-sm"
                                    data-id="${item.id}">
                                    <option value="">-- Pilih --</option>
                                    <option value="Disetujui">Disetujui</option>
                                    <option value="Ditolak">Ditolak</option>
                                </select>
                            `;
                            inputDisetujui = `
                                <input 
                                    type="number"
                                    min="0"
                                    max="${item.jumlah}"
                                    class="jumlah-disetujui border rounded px-2 py-1 w-24 text-sm"
                                    data-id="${item.id}"
                                    placeholder="0">
                            `;
                        } else {
                            // Jika sudah selesai → tampil badge warna
                            let badgeClass = '';
                            let statusText = item.status ?? 'Diajukan';

                            if (statusText === 'Disetujui') {
                                badgeClass = 'bg-green-50 text-green-700';
                            } 
                            else if (statusText === 'Ditolak') {
                                badgeClass = 'bg-red-50 text-red-700';
                            } 
                            else {
                                badgeClass = 'bg-yellow-50 text-yellow-700';
                            }

                            selectStatus = `
                                <span class="px-3 py-1 rounded-full text-xs font-semibold ${badgeClass}">
                                    ${statusText}
                                </span>
                            `;

                            let satuan = item.barang.satuan ?? '';
                            let jumlahDisetujui = item.status === 'Ditolak'
                                ? '-'
                                : (item.jumlah_disetujui ?? '-');
                            inputDisetujui = `
                                <span class="text-sm text-gray-700">
                                    ${jumlahDisetujui} ${jumlahDisetujui !== '-' ? satuan : ''}
                                </span>
                            `;
                        }

                        let stok = item.barang.jumlah ?? 0;
                        let permintaan = item.jumlah;
                        let stokKurang = stok < permintaan;

                        let stokCell = '';
                        let rowClass = '';

                        if (data.status === 'Diajukan') {
                            let stokText = stokKurang
                                ? `<span class="text-red-600 font-semibold">
                                    Stok tersedia: ${stok} (Permintaan: ${permintaan})
                                </span>`
                                : `<span class="text-green-600 font-semibold">${stok}</span>`;

                            stokCell = `<td class="px-4 py-2 text-sm">${stokText}</td>`;
                        }

                        tbody.innerHTML += `
                            <tr class="${rowClass}">
                                <td class="px-4 py-2 text-sm">${item.barang.kode_barang}</td>
                                <td class="px-4 py-2 text-sm">${item.barang.nama_barang}</td>
                                <td class="px-4 py-2 text-sm">${item.barang.merk_type ?? '-'}</td>
                                <td class="px-4 py-2 text-sm">${permintaan} ${item.barang.satuan ?? ''}</td>
                                ${stokCell}
                                <td class="px-4 py-2 text-sm">${selectStatus}</td>
                                <td class="px-4 py-2 text-sm">${inputDisetujui}</td>
                            </tr>`;
                    });
                }

                const ketContainer = document.getElementById('keteranganContainer');
                const ketTextarea = document.getElementById('keteranganProses');
                const ketText = document.getElementById('keteranganProsesText');
                if (data.status === 'Diajukan') {
                    ketContainer.classList.remove('hidden');
                    ketTextarea.classList.remove('hidden');
                    ketText.classList.add('hidden');
                } else {
                    // sembunyikan seluruh bagian bawah tabel
                    ketContainer.classList.add('hidden');
                    // tampilkan keterangan di bagian atas
                    ketText.textContent = (data.keterangan_proses && data.keterangan_proses.trim() !== '')
                        ? data.keterangan_proses
                        : '-';
                }

                const tanggalProsesContainer = document.getElementById('tanggalProsesContainer');
                if (data.status === 'Diajukan') {
                    tanggalProsesContainer.classList.add('hidden');
                } else {
                    tanggalProsesContainer.classList.remove('hidden');
                }

                const keteranganProsesContainer = document.getElementById('keteranganProsesContainer');
                if (data.status === 'Diajukan') {
                    keteranganProsesContainer.classList.add('hidden');
                } else {
                    keteranganProsesContainer.classList.remove('hidden');
                    ketText.textContent = data.keterangan_proses && data.keterangan_proses.trim() !== ''? data.keterangan_proses: '-';
                }

                // ===== TOMBOL SIMPAN =====
                if (data.status === 'Diajukan') {
                    document.getElementById('btnSimpan')
                        .classList.remove('hidden');
                } else {
                    document.getElementById('btnSimpan')
                        .classList.add('hidden');
                }

                // ===== BUKA MODAL =====
                openModal('detailPengajuanModal');
            })
            .catch(error => {
                console.error(error);
                alert('Gagal mengambil data detail.');
            });
    }

    function goToDetailPage() {
        if (currentPengajuanId) {
            window.location.href = `/pengajuan-bmn/riwayat_admin/${currentPengajuanId}`;
        }
    }

    function simpanStatus() {
        const selects = document.querySelectorAll('.status-select');
        const jumlahs = document.querySelectorAll('.jumlah-disetujui');

        let details = [];

        selects.forEach(el => {
            let id = el.dataset.id;
            let status = el.value;

            let jumlah = 0;

            jumlahs.forEach(j => {
                if (j.dataset.id === id) {
                    jumlah = j.value;
                }
            });

            if (status !== '') {
                details.push({
                    id: id,
                    status: status,
                    jumlah_disetujui: jumlah
                });
            }
        });

        const errorBox = document.getElementById('stokErrorMessage');

        /* ✅ VALIDASI JIKA TIDAK ADA STATUS DIPILIH */
        if (details.length === 0) {
            errorBox.textContent = 'Silakan pilih status minimal satu barang terlebih dahulu.';
            errorBox.classList.remove('hidden');

            errorBox.style.opacity = '1';
            errorBox.style.transition = 'opacity 0.5s';

            setTimeout(() => {
                errorBox.style.opacity = '0';
                setTimeout(() => {
                    errorBox.classList.add('hidden');
                }, 500);
            }, 4000);

            return; // ⛔ hentikan proses, jangan kirim fetch
        }

        const keterangan = document.getElementById('keteranganProses').value;

        fetch(`/pengajuan/${currentPengajuan.id}/update-detail-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                details: details,
                keterangan_proses: keterangan
            })
        })
        .then(async res => {
            const text = await res.text();

            try {
                const data = JSON.parse(text);

                if (!res.ok) {
                    throw data;
                }

                return data;
            } catch (e) {
                console.error('Response bukan JSON:', text);
                throw { message: 'Server error (bukan JSON)' };
            }
        })
        .then(res => {
            if (res.success) {
                location.reload();
            }
        })
        .catch(err => {
            errorBox.textContent = err.message || 'Terjadi kesalahan.';
            errorBox.classList.remove('hidden');

            errorBox.style.opacity = '1';

            setTimeout(() => {
                errorBox.style.opacity = '0';
                setTimeout(() => {
                    errorBox.classList.add('hidden');
                }, 500);
            }, 5000);
        });
    }

    function showAlert(message, type = 'success') {
        const container = document.getElementById('dynamic-alert-container');

        let bgColor = type === 'success'
            ? 'bg-green-100 border-green-300 text-green-800'
            : 'bg-red-100 border-red-300 text-red-800';

        const alert = document.createElement('div');
        alert.className = `mb-2 p-2 rounded-lg border ${bgColor}`;
        alert.innerHTML = `<strong>${type === 'success' ? 'Sukses!' : 'Gagal!'}</strong> ${message}`;

        container.innerHTML = '';
        container.appendChild(alert);

        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 45000);
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
