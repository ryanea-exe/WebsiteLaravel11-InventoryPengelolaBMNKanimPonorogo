@extends('layouts.app')

@section('title', 'Riwayat Pengajuan')

@section('page-title', 'Riwayat Pengajuan Pemeliharaan')
@section('breadcrumb')
    <p class="text-gray-800 cursor-pointer">
        Riwayat Pengajuan Pemeliharaan Kendaraan
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
            <h1 class="text-xl font-bold mb-6 text-gray-800">
                <i class="fas fa-history mr-3"></i>Riwayat Pengajuan Pemeliharaan Kendaraan
            </h1>

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
                            <th class="px-2 py-3 text-left text-sm font-semibold text-gray-600 uppercase w-8">No</th>
                            <th class="px-2 py-3 text-left text-sm font-semibold text-gray-600 uppercase">Tanggal Pengajuan</th>
                            <th class="px-2 py-3 text-left text-sm font-semibold text-gray-500 uppercase">Kode Pengajuan</th>
                            <th class="px-2 py-3 text-left text-sm font-semibold text-gray-500 uppercase">Kendaraan</th>
                            <th class="px-2 py-3 text-left text-sm font-semibold text-gray-500 uppercase">Seksi Pengaju</th>
                            <th class="px-2 py-3 text-left text-sm font-semibold text-gray-500 uppercase">Status</th>
                            <th class="px-2 py-3 text-left text-sm font-semibold text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($pengajuans as $pengajuan)
                        <tr class="hover:bg-gray-50" data-seksi="{{ $pengajuan->user->seksi_id }}">
                            <td class="px-2 py-3 text-sm text-grey-800">{{ $loop->iteration }}</td>
                            <td class="px-2 py-3 text-sm text-grey-800">
                                {{ \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->format('d M Y, H:i') }}
                            </td>
                            <td class="px-2 py-3 text-sm text-grey-800">{{ $pengajuan->kode_pengajuan }}</td>
                            <td class="px-2 py-3 text-sm text-grey-800">{{ $pengajuan->kendaraan->nama_kendaraan }} <b>({{ $pengajuan->kendaraan->nomor_polisi }})</b></td>
                            <td class="px-2 py-3 text-sm text-grey-800">{{ $pengajuan->user->name }}</td>
                            <!-- STATUS -->
                            <td class="px-2 py-3 text-sm">
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
                            <!-- AKSI -->
                            <td class="px-2 py-3 text-sm">
                                <div class="flex items-center space-x-2">
                                    <!-- DETAIL -->
                                    <button onclick="openDetailModal({{ $pengajuan->id }})"
                                        title="Detail"
                                        class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <!-- PRINT -->
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
                                                    <a href="{{ route('pengajuan-pemeliharaan.nodin', $pengajuan->id) }}"
                                                        target="_blank"
                                                        class="block px-4 py-2 hover:bg-gray-100">
                                                        📝 Nota Dinas
                                                    </a>
                                                </li>
                                                <!-- BAST (HANYA JIKA DISETUJUI) -->
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
    <div class="modal-box bg-white rounded-2xl w-full max-w-4xl shadow-xl max-h-[90vh] flex flex-col">
        <!-- HEADER -->
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h3 class="text-lg font-semibold text-gray-800" id="detailPengajuanPemeliharaanTitle">
                Detail Pengajuan Pemeliharaan Kendaraan
            </h3>
            <div class="flex items-center gap-2">
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
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4 mb-4">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Tanggal Pengajuan</p>
                    <p id="detailTanggalPengajuan"
                       class="font-medium text-gray-800">-</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Kode Pengajuan</p>
                    <p id="detailKodePengajuan"
                       class="font-medium text-gray-800">-</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Seksi Pengaju</p>
                    <p id="detailPeminta"
                       class="font-medium text-gray-800">-</p>
                </div>
                <div>
                </div>
                <div id="tanggalProsesContainer">
                    <p class="text-sm text-gray-500 mb-1">Tanggal Proses</p>
                    <p id="detailTanggalProses"
                        class="font-medium text-gray-800">-</p>
                </div>
                <div id="keteranganProsesContainer">
                    <p class="text-sm text-gray-500 mb-1">Keterangan Proses</p>
                    <div id="detailKeteranganProses"
                        class="bg-gray-50 rounded-lg p-3 text-gray-800 text-sm">
                        -
                    </div>
                </div>
            </div>
            <!-- TABEL KENDARAAN -->
             <div>
                <p class="text-sm text-gray-500 mb-2">Kendaraan</p>
            </div>
            <div class="overflow-x-auto border border-gray-300 mb-4">
                <table class="w-full text-sm text-gray-700">
                    <thead class="bg-gray-200 border-b border-gray-300">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Nomor Polisi</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Nama Kendaraan</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Tahun</th>
                        </tr>
                    </thead>
                    <tbody id="detailKendaraan"
                           class="divide-y divide-gray-300 bg-white">
                        <tr>
                            <td colspan="3"
                                class="text-center py-6 text-gray-400 italic">
                                Tidak ada data kendaraan
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div>
                <p class="text-sm text-gray-500 mb-1">Keperluan & Estimasi Biaya</p>
                <div id="detailKeperluanContainer"
                    class="bg-gray-50 rounded-lg p-4 text-sm text-gray-800 space-y-1 font-mono">
                    <div class="text-gray-400 italic">
                        Tidak ada data
                    </div>
                </div>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="px-6 py-4 border-t flex justify-between items-center">
            <!-- KIRI: BUTTON AKSI -->
            <div class="flex gap-2" id="actionButtons">
                <button onclick="approvePengajuan()"
                    class="px-3 py-1.5 text-sm bg-green-600 hover:bg-green-700 text-white rounded-lg">
                    ✔ Setujui
                </button>
                <button onclick="rejectPengajuan()"
                    class="px-3 py-1.5 text-sm bg-red-600 hover:bg-red-700 text-white rounded-lg">
                    ✖ Tolak
                </button>
            </div>
            <!-- KANAN -->
            <div class="flex gap-2">
                <button onclick="window.location.href = `/pemeliharaan/riwayat_admin/${currentPengajuanId}`"
                    class="px-3 py-1.5 font-normal text-sm border rounded-lg bg-blue-600 hover:bg-blue-700 text-white ">
                    Detail
                </button>
                <button onclick="closeModal('detailPengajuanModal')"
                    class="px-3 py-1.5 text-sm border rounded-lg text-gray-700 hover:bg-gray-100">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL KONFIRMASI -->
<div id="confirmModal" class="fixed inset-0 bg-black/50 z-[9999] hidden flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 text-center">
        <h3 id="confirmTitle" class="text-lg font-semibold text-gray-800 mb-3">
            Konfirmasi
        </h3>
        <p id="confirmMessage" class="text-sm text-gray-600 mb-6">
            Apakah Anda yakin?
        </p>
        <div class="flex justify-center gap-2">
            <button onclick="closeModal('confirmModal')"
                class="px-3 py-1.5 text-sm border rounded-lg text-gray-700 hover:bg-gray-100">
                Batal
            </button>
            <form id="confirmForm" method="POST">
                @csrf
                <button type="submit"
                    class="px-3 py-1.5 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                    Ya, Lanjutkan
                </button>
            </form>
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
                Konfirmasi Hapus Pengajuan Pemeliharaan
            </h3>
            <p class="text-sm text-gray-600 mb-6">
                Apakah Anda yakin ingin menghapus pengajuan pemeliharaan
                <span id="deleteKendaraanKode" class="font-semibold text-gray-800"></span>?
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
    let currentPengajuan = {};
    let currentPengajuanId = null;

    function openDetailModal(id) {
        currentPengajuanId = id;
        fetch(`/pemeliharaan/${id}/detail`)
            .then(res => {
                if (!res.ok) {
                    throw new Error('HTTP status ' + res.status);
                }
                return res.json();
            })
            .then(data => {
                console.log(data); // debug dulu

                // ======================
                // INFO UTAMA
                // ======================
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
                document.getElementById('detailPengajuanPemeliharaanTitle').innerText = `Detail Pengajuan Pemeliharaan - ${data.kode_pengajuan}`;
                document.getElementById('detailKodePengajuan').textContent = data.kode_pengajuan ?? '-';
                document.getElementById('detailPeminta').textContent = data.user?.name ?? '-';
                
                // Field Keperluan & Estimasi Biaya
                const container = document.getElementById('detailKeperluanContainer');
                container.innerHTML = '';

                let total = 0;

                // 🔥 FIX DISINI
                const details = data.details ?? [];

                if (details.length === 0) {
                    container.innerHTML = `<div class="text-gray-400 italic">Tidak ada data</div>`;
                } else {
                    details.forEach((item, index) => {
                        const biaya = parseInt(item.estimasi_biaya) || 0;
                        total += biaya;

                        container.innerHTML += `
                            <div class="grid grid-cols-[30px_1fr_20px_120px] items-center gap-1">
                                <div>${index + 1}.</div>
                                <div>${item.keperluan}</div>
                                <div class="text-center">:</div>
                                <div class="text-right">Rp. ${biaya.toLocaleString('id-ID')}</div>
                            </div>
                        `;
                    });

                    // garis + total
                    container.innerHTML += `
                        <div class="border-t border-gray-300 my-2"></div>
                        <div class="grid grid-cols-12 font-semibold">
                            <div class="col-span-9 text-right pr-2">Total</div>
                            <div class="col-span-3 text-right">
                                + Rp. ${total.toLocaleString('id-ID')}
                            </div>
                        </div>
                    `;
                }

                const statusEl = document.getElementById('detailStatus');
                statusEl.className = 'px-3 py-1 text-sm rounded-full font-semibold';

                if (data.status === 'Disetujui') {
                    statusEl.classList.add('bg-green-100','text-green-700');
                }
                else if (data.status === 'Ditolak') {
                    statusEl.classList.add('bg-red-100','text-red-700');
                }
                else {
                    statusEl.classList.add('bg-yellow-100','text-yellow-700');
                }

                statusEl.textContent = data.status ?? '-';
                const actionButtons = document.getElementById('actionButtons');
                if (data.status === 'Disetujui' || data.status === 'Ditolak') {
                    actionButtons.classList.add('invisible');
                } else {
                    actionButtons.classList.remove('invisible');
                }

                const tanggalProsesContainer = document.getElementById('tanggalProsesContainer');
                const keteranganProsesContainer = document.getElementById('keteranganProsesContainer');
                if (data.status === 'Diajukan') {
                    tanggalProsesContainer.classList.add('hidden');
                    keteranganProsesContainer.classList.add('hidden');
                } else {
                    tanggalProsesContainer.classList.remove('hidden');
                    keteranganProsesContainer.classList.remove('hidden');
                }

                // TANGGAL PROSES
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

                document.getElementById('detailKeteranganProses').textContent =
                    (data.keterangan_proses && data.keterangan_proses.trim() !== '')
                    ? data.keterangan_proses
                    : '-';

                // TABEL KENDARAAN
                const tbody = document.getElementById('detailKendaraan');
                tbody.innerHTML = '';

                const kendaraan = data.kendaraan ?? {};

                if (!kendaraan || !kendaraan.id) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="3"
                                class="text-center py-6 text-gray-400 italic">
                                Tidak ada data kendaraan
                            </td>
                        </tr>`;
                } else {
                    tbody.innerHTML = `
                        <tr>
                            <td class="px-4 py-2 text-sm">${kendaraan.nomor_polisi ?? '-'}</td>
                            <td class="px-4 py-2 text-sm">${kendaraan.nama_kendaraan ?? '-'}</td>
                            <td class="px-4 py-2 text-sm">${kendaraan.tahun ?? '-'}</td>
                        </tr>`;
                }

                openModal('detailPengajuanModal');
            })
            .catch(error => {
                console.error(error);
                alert('Gagal mengambil data detail. Cek console.');
            });
    }

    function closeDetailModal() {
        closeModal('detailPengajuanModal');
    }

    function approvePengajuan() {
        if (!currentPengajuanId) return;

        document.getElementById('confirmTitle').innerText = 'Setujui Pengajuan';
        document.getElementById('confirmMessage').innerText = 'Apakah Anda yakin ingin menyetujui pengajuan ini?';

        const form = document.getElementById('confirmForm');
        form.action = `/pemeliharaan/${currentPengajuanId}/approve`;

        openModal('confirmModal');
    }

    function rejectPengajuan() {
        if (!currentPengajuanId) return;

        document.getElementById('confirmTitle').innerText = 'Tolak Pengajuan';
        document.getElementById('confirmMessage').innerText = 'Apakah Anda yakin ingin menolak pengajuan ini?';

        const form = document.getElementById('confirmForm');
        form.action = `/pemeliharaan/${currentPengajuanId}/reject`;

        openModal('confirmModal');
    }

    // modal delete data
    function openDeleteModal(id, kode) {
        const modal = document.getElementById('deletePengajuanModal');
        const form = document.getElementById('deletePengajuanForm');
        const kodeEl = document.getElementById('deleteKendaraanKode');

        // isi kode pengajuan
        kodeEl.textContent = kode;

        // set action form
        form.action = `/pemeliharaan/${id}/cancel`;

        // tampilkan modal
        openModal('deletePengajuanModal');
    }

    // dropdown aksi cetak
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
