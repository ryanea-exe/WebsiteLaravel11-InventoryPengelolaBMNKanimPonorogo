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
    
    /* Animasi smooth */
    .tab-item {
        transition: all 0.3s ease;
        position: relative;
    }
    
    .tab-item.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #3b82f6, #1e40af);
        border-radius: 2px 2px 0 0;
    }
    
    /* Table styling */
    table#dataTable tbody tr {
        transition: all 0.2s ease;
        border-bottom: 1px solid #e5e7eb;
    }
    
    table#dataTable tbody tr:hover {
        background-color: #f0f9ff;
        transform: translateX(2px);
    }
    
    table#dataTable tbody tr:nth-child(even) {
        background-color: #fafbfc;
    }
    
    /* Status badge */
    .status-badge {
        animation: slideIn 0.3s ease-out;
    }
    
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-5px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Button animations */
    .btn-action {
        transition: all 0.2s ease;
    }
    
    .btn-action:hover {
        transform: scale(1.15);
    }
    
    /* Modal animations */
    .modal {
        animation: fadeIn 0.3s ease-out;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
    
    .modal-box {
        animation: slideUp 0.3s ease-out;
    }
    
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush

<div class="space-y-6">
    <!-- HEADER CARD -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center gap-4">
            <div class="bg-white/20 rounded-lg p-4">
                <i class="fas fa-history text-2xl"></i>
            </div>
            <div>
                <h1 class="text-3xl font-bold">Riwayat Pengajuan Pemeliharaan</h1>
                <p class="text-blue-100 mt-1">Kelola semua pengajuan pemeliharaan kendaraan</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <!-- MENU TAB FILTER -->
        <div class="border-b border-gray-200 bg-gray-50/50 px-6 py-0">
            <ul class="flex flex-wrap text-sm font-medium" id="tabMenu">
                <!-- TAB SEMUA -->
                <li>
                    <button onclick="filterSeksi('all')"
                        class="tab-item active inline-flex items-center gap-2 px-4 py-4 border-b-3 border-blue-600 text-blue-600 hover:bg-blue-50"
                        data-seksi="all">
                        <i class="fas fa-list"></i>
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
                <li>
                    <button onclick="filterSeksi('{{ $seksi->id }}')"
                        class="tab-item inline-flex items-center gap-2 px-4 py-4 border-b-3 border-transparent text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-all"
                        data-seksi="{{ $seksi->id }}">
                        <i class="fas fa-building text-blue-500"></i>
                        {{ $seksi->seksi_singkat }}
                    </button>
                </li>
                @endforeach
            </ul>
        </div>

        <div class="p-6">

            <!-- ALERT -->
            @if(session('success'))
            <div id="alert-message"
                class="mb-4 p-4 rounded-lg bg-green-50 text-green-800 border-l-4 border-green-500 flex items-center gap-3 animate-slide-in">
                <i class="fas fa-check-circle text-xl"></i>
                <div>
                    <strong>Sukses!</strong> {{ session('success') }}
                </div>
            </div>
            @endif
            @if(session('error'))
            <div id="alert-message"
                class="mb-4 p-4 rounded-lg bg-red-50 text-red-800 border-l-4 border-red-500 flex items-center gap-3 animate-slide-in">
                <i class="fas fa-exclamation-circle text-xl"></i>
                <div>
                    <strong>Gagal!</strong> {{ session('error') }}
                </div>
            </div>
            @endif

            <!-- TABLE -->
            <div class="overflow-x-auto">
                <table id="dataTable" class="strip text-gray-700 w-full row-border">
                    <thead>
                        <tr class="bg-gradient-to-r from-blue-50 to-indigo-50 border-b-2 border-blue-200">
                            <th class="px-4 py-4 text-left text-xs font-bold text-blue-900 uppercase tracking-wider w-8"><i class="fas fa-hashtag mr-2"></i>No</th>
                            <th class="px-4 py-4 text-left text-xs font-bold text-blue-900 uppercase tracking-wider"><i class="fas fa-calendar mr-2"></i>Tanggal Pengajuan</th>
                            <th class="px-4 py-4 text-left text-xs font-bold text-blue-900 uppercase tracking-wider"><i class="fas fa-barcode mr-2"></i>Kode Pengajuan</th>
                            <th class="px-4 py-4 text-left text-xs font-bold text-blue-900 uppercase tracking-wider"><i class="fas fa-car mr-2"></i>Kendaraan</th>
                            <th class="px-4 py-4 text-left text-xs font-bold text-blue-900 uppercase tracking-wider"><i class="fas fa-users mr-2"></i>Seksi Pengaju</th>
                            <th class="px-4 py-4 text-left text-xs font-bold text-blue-900 uppercase tracking-wider"><i class="fas fa-traffic-light mr-2"></i>Status</th>
                            <th class="px-4 py-4 text-left text-xs font-bold text-blue-900 uppercase tracking-wider"><i class="fas fa-cogs mr-2"></i>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($pengajuans as $pengajuan)
                        <tr class="hover:bg-blue-50 transition-colors duration-200 group" data-seksi="{{ $pengajuan->user->seksi_id }}">
                            <td class="px-4 py-4 text-sm font-medium text-gray-600">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-600 font-semibold">
                                    {{ $loop->iteration }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-700 font-medium">
                                <i class="fas fa-clock text-blue-500 mr-2"></i>
                                {{ \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->format('d M Y, H:i') }}
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-700 font-semibold text-blue-600">{{ $pengajuan->kode_pengajuan }}</td>
                            <td class="px-4 py-4 text-sm text-gray-700">
                                <div class="flex flex-col">
                                    <span class="font-semibold">{{ $pengajuan->kendaraan->nama_kendaraan }}</span>
                                    <span class="text-xs text-gray-500"><i class="fas fa-tag mr-1"></i>{{ $pengajuan->kendaraan->nomor_polisi }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-700">
                                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-blue-100/50">
                                    <i class="fas fa-user-tie text-blue-600 text-xs"></i>
                                    {{ $pengajuan->user->name }}
                                </div>
                            </td>
                            <!-- STATUS -->
                            <td class="px-4 py-4 text-sm">
                                @if($pengajuan->status === 'Disetujui')
                                <span class="status-badge inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                    <i class="fas fa-check-circle"></i> Disetujui
                                </span>
                                @elseif($pengajuan->status === 'Ditolak')
                                <span class="status-badge inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                                    <i class="fas fa-times-circle"></i> Ditolak
                                </span>
                                @else
                                <span class="status-badge inline-flex items-center gap-1.5 px-3 py-1.5 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">
                                    <i class="fas fa-hourglass-half"></i> Diajukan
                                </span>
                                @endif
                            </td>
                            <!-- AKSI -->
                            <td class="px-4 py-4 text-sm">
                                <div class="flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <!-- DETAIL -->
                                    <button onclick="openDetailModal({{ $pengajuan->id }})"
                                        title="Detail"
                                        class="btn-action inline-flex items-center justify-center w-9 h-9 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-600 hover:text-white transition-all">
                                        <i class="fas fa-eye text-sm"></i>
                                    </button>
                                    <!-- PRINT -->
                                    <div class="relative inline-block text-left">
                                        <button onclick="togglePrintMenu({{ $pengajuan->id }})"
                                            class="btn-action inline-flex items-center justify-center w-9 h-9 rounded-lg bg-gray-100 text-gray-600 hover:bg-blue-500 hover:text-white transition-all"
                                            title="Cetak">
                                            <i class="fas fa-print text-sm"></i>
                                        </button>
                                        <div id="printMenu-{{ $pengajuan->id }}"
                                            class="hidden absolute right-0 mt-2 w-60 bg-white border border-gray-200 rounded-lg shadow-xl z-50 py-1 text-sm text-gray-800">
                                            <!-- NOTA DINAS (SELALU ADA) -->
                                            <a href="{{ route('pengajuan-pemeliharaan.nodin', $pengajuan->id) }}"
                                                target="_blank"
                                                class="block px-4 py-2.5 hover:bg-blue-50 hover:text-blue-600 transition-colors flex items-center gap-2">
                                                <i class="fas fa-file-pdf text-red-500"></i> Nota Dinas
                                            </a>
                                            <!-- BAST (HANYA JIKA DISETUJUI) -->
                                        </div>
                                    </div>
                                    <button onclick="openDeleteModal(
                                            '{{ route('pengajuan.destroy', $pengajuan->id) }}',
                                            '{{ $pengajuan->kode_pengajuan }}'
                                        )"
                                        class="btn-action inline-flex items-center justify-center w-9 h-9 rounded-lg bg-red-100 text-red-600 hover:bg-red-600 hover:text-white transition-all"
                                        title="Hapus">
                                        <i class="fas fa-trash text-sm"></i>
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
<div id="detailPengajuanModal" class="modal fixed inset-0 bg-black/50 z-[9999] hidden flex items-center justify-center p-4 backdrop-blur-sm">
    <div class="modal-box bg-white rounded-2xl w-full max-w-4xl shadow-2xl max-h-[90vh] flex flex-col">
        <!-- HEADER -->
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
            <div>
                <h3 class="text-xl font-bold text-gray-800" id="detailPengajuanPemeliharaanTitle">
                    Detail Pengajuan Pemeliharaan Kendaraan
                </h3>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-4 py-2 text-sm rounded-full bg-white border border-gray-200 text-gray-700 font-semibold">
                    Status: <span id="detailStatus" class="ml-1">-</span>
                </span>
                <button onclick="closeModal('detailPengajuanModal')"
                        class="text-gray-400 hover:text-gray-600 hover:bg-gray-200 w-10 h-10 flex items-center justify-center rounded-lg transition">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>

        <!-- BODY -->
        <div class="px-6 py-6 overflow-y-auto flex-1">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200">
                    <p class="text-xs text-blue-600 font-bold uppercase tracking-wider mb-1">Tanggal Pengajuan</p>
                    <p id="detailTanggalPengajuan" class="font-semibold text-gray-800 text-lg">-</p>
                </div>
                <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-lg p-4 border border-indigo-200">
                    <p class="text-xs text-indigo-600 font-bold uppercase tracking-wider mb-1">Kode Pengajuan</p>
                    <p id="detailKodePengajuan" class="font-semibold text-gray-800 text-lg">-</p>
                </div>
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
                    <p class="text-xs text-green-600 font-bold uppercase tracking-wider mb-1">Seksi Pengaju</p>
                    <p id="detailPeminta" class="font-semibold text-gray-800 text-lg">-</p>
                </div>
                <div id="tanggalProsesContainer" class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-4 border border-purple-200">
                    <p class="text-xs text-purple-600 font-bold uppercase tracking-wider mb-1">Tanggal Proses</p>
                    <p id="detailTanggalProses" class="font-semibold text-gray-800 text-lg">-</p>
                </div>
            </div>
            
            <div id="keteranganProsesContainer" class="mb-6">
                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-2">Keterangan Proses</p>
                <div id="detailKeteranganProses" class="bg-gray-50 rounded-lg p-4 text-gray-800 text-sm border border-gray-200 font-medium">
                    -
                </div>
            </div>
            
            <!-- TABEL KENDARAAN -->
            <div class="mb-6">
                <p class="text-xs text-gray-600 font-bold uppercase tracking-wider mb-3">Detail Kendaraan</p>
                <div class="overflow-x-auto border border-gray-200 rounded-lg">
                    <table class="w-full text-sm text-gray-700">
                        <thead class="bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold text-blue-900 uppercase tracking-wider">Nomor Polisi</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-blue-900 uppercase tracking-wider">Nama Kendaraan</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-blue-900 uppercase tracking-wider">Tahun</th>
                            </tr>
                        </thead>
                        <tbody id="detailKendaraan" class="divide-y divide-gray-200 bg-white">
                            <tr>
                                <td colspan="3" class="text-center py-8 text-gray-400 italic">
                                    <i class="fas fa-inbox text-2xl mb-2"></i>
                                    <p>Tidak ada data kendaraan</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div>
                <p class="text-xs text-gray-600 font-bold uppercase tracking-wider mb-3">Keperluan & Estimasi Biaya</p>
                <div id="detailKeperluanContainer" class="bg-gray-50 rounded-lg p-4 text-sm text-gray-800 space-y-2 font-mono border border-gray-200">
                    <div class="text-gray-400 italic">
                        <i class="fas fa-inbox mr-2"></i>Tidak ada data
                    </div>
                </div>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="px-6 py-5 border-t border-gray-200 bg-gray-50 flex justify-between items-center rounded-b-2xl">
            <!-- KIRI: BUTTON AKSI -->
            <div class="flex gap-2" id="actionButtons">
                <button onclick="approvePengajuan()"
                    class="px-4 py-2 text-sm font-semibold bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-lg transition-all shadow-md hover:shadow-lg">
                    <i class="fas fa-check-circle mr-1.5"></i> Setujui
                </button>
                <button onclick="rejectPengajuan()"
                    class="px-4 py-2 text-sm font-semibold bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-lg transition-all shadow-md hover:shadow-lg">
                    <i class="fas fa-times-circle mr-1.5"></i> Tolak
                </button>
            </div>
            <!-- KANAN -->
            <div class="flex gap-2">
                <button onclick="window.location.href = `/pemeliharaan/riwayat_admin/${currentPengajuanId}`"
                    class="px-4 py-2 font-semibold text-sm bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-lg transition-all shadow-md hover:shadow-lg">
                    <i class="fas fa-arrow-right mr-1.5"></i> Detail Lengkap
                </button>
                <button onclick="closeModal('detailPengajuanModal')"
                    class="px-4 py-2 text-sm font-semibold border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-all">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL KONFIRMASI -->
<div id="confirmModal" class="fixed inset-0 bg-black/50 z-[9999] hidden flex items-center justify-center backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8 text-center">
        <div class="mb-4 flex justify-center">
            <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center">
                <i class="fas fa-question-circle text-blue-600 text-3xl"></i>
            </div>
        </div>
        <h3 id="confirmTitle" class="text-xl font-bold text-gray-800 mb-2">
            Konfirmasi
        </h3>
        <p id="confirmMessage" class="text-sm text-gray-600 mb-8">
            Apakah Anda yakin?
        </p>
        <div class="flex justify-center gap-3">
            <button onclick="closeModal('confirmModal')"
                class="px-6 py-2.5 text-sm font-semibold border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-all">
                Batal
            </button>
            <form id="confirmForm" method="POST" class="inline">
                @csrf
                <button type="submit"
                    class="px-6 py-2.5 text-sm font-semibold bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-lg transition-all shadow-md hover:shadow-lg">
                    <i class="fas fa-check mr-1.5"></i> Ya, Lanjutkan
                </button>
            </form>
        </div>
    </div>
</div>

<!-- DELETE PENGAJUAN MODAL -->
<div id="deletePengajuanModal" class="modal fixed inset-0 bg-black/50 z-[9999] hidden flex items-center justify-center p-4 backdrop-blur-sm">
    <div class="modal-box bg-white rounded-2xl w-full max-w-md shadow-2xl">
        <div class="p-8 text-center">
            <div class="mx-auto mb-4 w-16 h-16 rounded-full bg-red-100 flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-red-600 text-3xl"></i>
            </div>

            <h3 class="text-xl font-bold text-gray-800 mb-2">
                Hapus Pengajuan Pemeliharaan
            </h3>
            <p class="text-sm text-gray-600 mb-8">
                Apakah Anda yakin ingin menghapus pengajuan pemeliharaan <br>
                <span id="deleteKendaraanKode" class="font-bold text-gray-800 bg-yellow-50 px-2 py-1 rounded inline-block mt-2"></span>?
                <br><br>
                <span class="text-xs text-red-600 font-semibold">⚠️ Tindakan ini tidak dapat dibatalkan.</span>
            </p>

            <form id="deletePengajuanForm" method="POST">
                @csrf
                @method('DELETE')

                <div class="flex justify-center space-x-3">
                    <button type="button" onclick="closeModal('deletePengajuanModal')"
                        class="px-6 py-2.5 text-sm font-semibold border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-all">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-6 py-2.5 text-sm font-semibold bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-lg transition-all shadow-md hover:shadow-lg">
                        <i class="fas fa-trash mr-1.5"></i> Ya, Hapus
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
            tab.classList.remove('active', 'border-blue-600', 'text-blue-600');
            tab.classList.add('border-transparent', 'text-gray-600');
        });

        // aktifkan tab yang diklik
        const activeTab = document.querySelector(`[data-seksi="${seksiId}"]`);
        activeTab.classList.add('active', 'border-blue-600', 'text-blue-600');
        activeTab.classList.remove('border-transparent', 'text-gray-600');

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
                    container.innerHTML = `<div class="text-gray-400 italic"><i class="fas fa-inbox mr-2"></i>Tidak ada data</div>`;
                } else {
                    details.forEach((item, index) => {
                        const biaya = parseInt(item.estimasi_biaya) || 0;
                        total += biaya;

                        container.innerHTML += `
                            <div class="grid grid-cols-[30px_1fr_20px_120px] items-center gap-1 py-1">
                                <div>${index + 1}.</div>
                                <div>${item.keperluan}</div>
                                <div class="text-center">:</div>
                                <div class="text-right">Rp. ${biaya.toLocaleString('id-ID')}</div>
                            </div>
                        `;
                    });

                    // garis + total
                    container.innerHTML += `
                        <div class="border-t border-gray-300 my-3"></div>
                        <div class="grid grid-cols-12 font-bold text-lg">
                            <div class="col-span-9 text-right pr-2">Total</div>
                            <div class="col-span-3 text-right text-blue-600">
                                Rp. ${total.toLocaleString('id-ID')}
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
                                class="text-center py-8 text-gray-400 italic">
                                <i class="fas fa-inbox text-2xl mb-2"></i>
                                <p>Tidak ada data kendaraan</p>
                            </td>
                        </tr>`;
                } else {
                    tbody.innerHTML = `
                        <tr class="hover:bg-blue-50">
                            <td class="px-4 py-3 text-sm font-medium text-gray-700">${kendaraan.nomor_polisi ?? '-'}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">${kendaraan.nama_kendaraan ?? '-'}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">${kendaraan.tahun ?? '-'}</td>
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

        document.getElementById('confirmTitle').innerText = '✔️ Setujui Pengajuan';
        document.getElementById('confirmMessage').innerText = 'Apakah Anda yakin ingin menyetujui pengajuan ini?';

        const form = document.getElementById('confirmForm');
        form.action = `/pemeliharaan/${currentPengajuanId}/approve`;

        openModal('confirmModal');
    }

    function rejectPengajuan() {
        if (!currentPengajuanId) return;

        document.getElementById('confirmTitle').innerText = '❌ Tolak Pengajuan';
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
