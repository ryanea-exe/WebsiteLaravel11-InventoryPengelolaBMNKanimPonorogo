@extends('layouts.app')

@section('title', 'Detail Pengajuan')

@section('page-title', 'Detail Pengajuan Persediaan')
@section('breadcrumb')
    <p class="text-gray-800 cursor-pointer">
        Detail Pengajuan Persediaan
    </p>
@endsection

@section('content')

@php
    \Carbon\Carbon::setLocale('id');
@endphp

<div class="bg-white rounded-lg shadow-sm space-y-4">
    <!-- HEADER -->
    <div class="flex items-center justify-between border-b px-4 py-4">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">
                Detail Pengajuan Barang Persediaan - {{ $pengajuan->kode_pengajuan }}
            </h2>
        </div>

        <span>Status:
            <span class="px-4 py-1.5 text-sm rounded-full font-semibold
                @if($pengajuan->status === 'Disetujui')
                    bg-green-100 text-green-700
                @elseif($pengajuan->status === 'Disetujui Sebagian')
                    bg-blue-100 text-blue-700
                @elseif($pengajuan->status === 'Ditolak')
                    bg-red-100 text-red-700
                @else
                    bg-yellow-100 text-yellow-700
                @endif">
                {{ $pengajuan->status }}
            </span>
        </span>
    </div>

    <!-- ALERT -->
    @if(session('success'))
    <div class="px-4">
        <div id="alert-message"
            class="mb-2 p-2 rounded-lg bg-green-100 text-green-800 border border-green-300">
            <strong>Sukses!</strong> {{ session('success') }}
        </div>
    </div>
    @endif
    @if(session('error'))
    <div class="px-4">
        <div id="alert-message"
            class="mb-2 p-2 rounded-lg bg-red-100 text-red-800 border border-red-300">
            <strong>Gagal!</strong> {{ session('error') }}
        </div>
    </div>
    @endif

    <!-- INFO GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 px-4">
        <div>
            <p class="text-sm text-gray-500 mb-1">Tanggal Pengajuan</p>
            <p class="font-medium text-gray-800">
                {{ \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->translatedFormat('d F Y, H:i') }}
            </p>
        </div>
        <div>
            <p class="text-sm text-gray-500 mb-1">Kode Pengajuan</p>
            <p class="font-semibold text-blue-600">
                {{ $pengajuan->kode_pengajuan }}
            </p>
        </div>
        <div>
            <p class="text-sm text-gray-500 mb-1">Peminta</p>
            <p class="font-medium text-gray-800">
                {{ $pengajuan->user->name }}
            </p>
        </div>
        <div>
            <p class="text-sm text-gray-500 mb-1">Keperluan</p>
            <p class="bg-gray-50 rounded-lg p-3 text-gray-800 text-sm">
                {{ $pengajuan->keperluan }}
            </p>
        </div>
        @if($pengajuan->status !== 'Diajukan')
        <div>
            <p class="text-sm text-gray-500 mb-1">Tanggal Proses</p>
            <p class="font-medium text-gray-800">
                {{ \Carbon\Carbon::parse($pengajuan->tanggal_proses)->translatedFormat('d F Y, H:i') }}
            </p>
        </div>
        <div>
            <p class="text-sm text-gray-500 mb-1">Keterangan Proses</p>
            <p class="bg-gray-50 rounded-lg p-3 text-gray-800 text-sm">
                {{ $pengajuan->keterangan_proses ?: '-' }}
            </p>
        </div>
        @endif
    </div>

    <!-- FORM UPDATE -->
    <div>
        <p class="text-sm text-gray-500 px-4 mb-4">Barang</p>
    </div>

    @if($pengajuan->status === 'Diajukan')
    <form id="updateStatusForm">
    @endif

        <!-- TABEL BARANG -->
        <div class="overflow-x-auto px-4 mb-4" style="margin-top:-4px">
            <table class="w-full text-gray-700 border border-gray-300">
                <thead class="bg-gray-200 border-b border-gray-300">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Kode Barang</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Nama Barang</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Jumlah Permintaan</th>
                        @if($pengajuan->status === 'Diajukan')
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Stok Tersedia</th>
                        @endif
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Jumlah Disetujui</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-300">
                    @foreach($pengajuan->details as $detail)

                    @php
                        $stok = $detail->barang->jumlah ?? 0;
                        $jumlah = $detail->jumlah;
                        $stokKurang = $stok < $jumlah;
                    @endphp

                    <tr class="{{ $stokKurang ? 'bg-red-50' : '' }}">
                        <td class="px-4 py-2 text-sm">
                            {{ $detail->barang->kode_barang }}
                        </td>
                        <td class="px-4 py-2 text-sm">
                            {{ $detail->barang->nama_barang }}
                        </td>
                        <td class="px-4 py-2 text-sm">
                            {{ $jumlah }} {{ $detail->barang->satuan ?? '' }}
                        </td>
                        <!-- KOLOM STOK -->
                        @if($pengajuan->status === 'Diajukan')
                        <td class="px-4 py-2 text-sm">
                            @if($stokKurang)
                                <span class="text-red-600 font-semibold">
                                    Tersedia: {{ $stok }} (Permintaan: {{ $jumlah }})
                                </span>
                            @else
                                <span class="text-green-600 font-semibold">
                                    {{ $stok }}
                                </span>
                            @endif
                        </td>
                        @endif
                        <!-- STATUS -->
                        <td class="px-4 py-2 text-sm">
                            @if($pengajuan->status === 'Diajukan')
                                <select class="status-select border rounded px-2 py-1 text-sm"
                                    data-id="{{ $detail->id }}"
                                    data-stok="{{ $detail->barang->jumlah }}"
                                    data-jumlah="{{ $detail->jumlah }}">
                                    <option value="">-- Pilih --</option>
                                    <option value="Disetujui">Disetujui</option>
                                    <option value="Ditolak">Ditolak</option>
                                </select>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if($detail->status === 'Disetujui')
                                        bg-green-100 text-green-700
                                    @elseif($detail->status === 'Ditolak')
                                        bg-red-100 text-red-700
                                    @else
                                        bg-yellow-100 text-yellow-700
                                    @endif">
                                    {{ $detail->status ?? 'Diajukan' }}
                                </span>
                            @endif
                        </td>
                        <!-- JUMLAH DISETUJUI -->
                        <td class="px-4 py-2 text-sm">
                            @if($pengajuan->status === 'Diajukan')
                                <input type="number" min="0" max="{{ $detail->jumlah }}"
                                    class="jumlah-disetujui border rounded px-2 py-1 w-24 text-sm"
                                    data-id="{{ $detail->id }}"
                                    placeholder="0">
                            @else
                                <span class="text-sm text-gray-700">
                                    @if($detail->status === 'Ditolak')
                                        -
                                    @else
                                        {{ $detail->jumlah_disetujui ?? '-' }} {{ $detail->barang->satuan ?? '' }}
                                    @endif
                                </span>
                            @endif
                        </td>
                    </tr>

                    @endforeach
                </tbody>
            </table>

            @if($pengajuan->status === 'Diajukan')
            <div class="mt-4">
                <label class="block text-sm text-gray-500 mb-2">Keterangan Proses</label>
                <textarea id="keteranganProses"
                    class="w-full border rounded-lg p-2 text-sm"
                    rows="2" placeholder="Contoh: Permintaan 10 unit, disetujui 5 karena stok terbatas..."></textarea>
            </div>
            @endif

            <div id="stokErrorMessage"
                class="hidden mt-3 p-2 rounded-lg bg-red-100 text-red-700 border border-red-300 text-sm">
            </div>
        </div>

        <!-- FOOTER BUTTON -->
        <div class="px-4 py-4 flex justify-end gap-2 border-t border-gray-200">
            <button type="button" onclick="window.location='{{ route('pengajuan.riwayat_admin') }}'"
                class="px-3 py-1.5 border rounded-lg text-gray-700 hover:bg-gray-100 text-sm font-normal">
                Kembali
            </button>
            @if($pengajuan->status === 'Diajukan')
            <button type="button" onclick="simpanStatus()"
                class="px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-normal">
                <i class="fas fa-save mr-1"></i> Simpan Status Pengajuan
            </button>
            @endif
        </div>

    @if($pengajuan->status === 'Diajukan')
    </form>
    @endif
</div>

<script>
    document.querySelectorAll('.status-select').forEach(select => {
        select.addEventListener('change', function () {
            const stok = parseInt(this.dataset.stok);
            const jumlah = parseInt(this.dataset.jumlah);

            /* 
            const errorBox = document.getElementById('stokErrorMessage');

            if (this.value === 'Disetujui' && stok < jumlah) {
                errorBox.textContent = `Stok tidak mencukupi. Stok tersedia ${stok}, permintaan ${jumlah}.`;
                errorBox.classList.remove('hidden');

                errorBox.style.opacity = '1';
                errorBox.style.transition = 'opacity 0.5s ease';

                setTimeout(() => {
                    errorBox.style.opacity = '0';
                    setTimeout(() => {
                        errorBox.classList.add('hidden');
                    }, 500);
                }, 5000);

                this.value = '';
            } 
            */
        });
    });

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

        fetch(`/pengajuan/{{ $pengajuan->id }}/update-detail-status`, {
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
            const data = await res.json();

            if (!res.ok) {
                throw data;
            }

            return data;
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
