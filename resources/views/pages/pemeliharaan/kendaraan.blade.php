@extends('layouts.app')

@section('title', 'Kendaraan')

@section('page-title', 'Kendaraan')
@section('breadcrumb')
    <p class="text-gray-800 cursor-pointer">
        Kendaraan
    </p>
@endsection

@section('content')

@php 
    \Carbon\Carbon::setLocale('id'); 
@endphp

@push('scripts')
<style>
    table#dataTable tbody td:nth-child(2) {
        font-weight: 600;
    }
</style>
@endpush

<div class="space-y-4">
    <div class="bg-white rounded-lg shadow-sm p-4">
        <!-- HEADER -->
        <div class="flex justify-between mb-4">
            <h1 class="text-xl font-bold text-gray-800">
                @if(auth()->user()->role == 'Administrator')
                <i class="fas fa-car mr-3"></i>Daftar Kendaraan
                @else
                <i class="fas fa-car mr-3"></i>Daftar Kendaraan Seksi Saya
                @endif
            </h1>
            @if(auth()->user()->role == 'Administrator')
            <div class="flex gap-2">
                <button onclick="window.location='{{ route('pemeliharaan.input_pajak.index') }}'"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1.5 font-normal text-sm rounded-lg">
                    <i class="fas fa-file-invoice mr-1"></i> Input Riwayat Pajak
                </button>
                <button onclick="window.location='{{ route('pemeliharaan.input_servis.index') }}'"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1.5 font-normal text-sm rounded-lg">
                    <i class="fas fa-file-invoice mr-1"></i> Input Riwayat Servis
                </button>
                <button onclick="openModal('importKendaraanModal')"
                    class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-lg text-sm">
                    <i class="fas fa-file-excel mr-1"></i> Import Excel
                </button>
                <button onclick="openModal('addKendaraanModal')"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-sm">
                    <i class="fas fa-plus-square mr-1"></i> Tambah Kendaraan
                </button>
            </div>
            @endif
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
        @if(session('error_import'))
        <div id="alert-message2"
            class="mb-2 p-2 rounded-lg bg-red-100 text-red-800 border border-red-300">
            <ul>
                @foreach(session('error_import') as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- TABLE -->
        <div class="overflow-x-auto">
            <table id="dataTable" class="strip text-gray-700 w-full row-border">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="w-8">No</th>
                        <th>Nomor Polisi</th>
                        <th>Jenis</th>
                        <th>Kendaraan</th>
                        @if(auth()->user()->role == 'Administrator')
                            <th>Seksi</th>
                            <th class="w-60">Tanggal Pajak Berkala</th>
                        @endif
                        @if(auth()->user()->role == 'Staff')
                            <th class="w-60">Tanggal Terakhir Servis</th>
                        @endif
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kendaraan as $i => $k)
                    <tr class="hover:bg-gray-50">
                        <td>{{ $i+1 }}</td>
                        <td>{{ $k->nomor_polisi }}</td>
                        <td>{{ $k->jenis_kendaraan }}</td>
                        <td>
                            {{ $k->nama_kendaraan }}
                            <div style="margin-top:-5px;">
                                Tahun: {{ $k->tahun }}
                            </div>
                        </td>
                        @if(auth()->user()->role == 'Administrator')
                        <td>{{ $k->seksi->seksi_singkat }}</td>
                        <td>
                            @if($k->tanggal_pajak_berkala)
                                @php
                                    $today = \Carbon\Carbon::today();
                                    $jatuhTempo = \Carbon\Carbon::parse($k->tanggal_pajak_berkala)->year($today->year);

                                    $sudahBayar = $k->riwayatPajak->isNotEmpty();

                                    if ($sudahBayar) {
                                        // ke tahun depan
                                        $target = $jatuhTempo->copy()->addYear();
                                        $selisih = $today->diffInDays($target);
                                    } else {
                                        if ($jatuhTempo->lt($today)) {
                                            $selisih = -$jatuhTempo->diffInDays($today);
                                        } else {
                                            $selisih = $today->diffInDays($jatuhTempo);
                                        }
                                    }
                                @endphp

                                {{ $jatuhTempo->translatedFormat('d F') }}
                                <div class="text-xs
                                    @if(!$sudahBayar && $selisih < 0) text-red-600
                                    @elseif(!$sudahBayar && $selisih <= 7) text-orange-500
                                    @elseif($sudahBayar) text-green-600
                                    @else text-gray-500
                                    @endif
                                ">
                                    @if($sudahBayar)
                                        ✅ Sudah bayar, {{ $selisih }} hari lagi
                                    @else
                                        @if($selisih > 0)
                                            ❌ Belum bayar, {{ $selisih }} hari lagi
                                        @elseif($selisih == 0)
                                            ❌ Belum bayar, Hari ini jatuh tempo
                                        @else
                                            ❌ Belum bayar, Lewat {{ abs($selisih) }} hari
                                        @endif
                                    @endif
                                </div>
                            @else
                                -
                            @endif
                        </td>
                        @endif
                        @if(auth()->user()->role == 'Staff')
                        <td>
                            @if($k->riwayatServis->isNotEmpty() && $k->rentang_waktu_servis)
                                @php
                                    $today = \Carbon\Carbon::today();

                                    $servisTerakhir = \Carbon\Carbon::parse($k->riwayatServis->first()->tanggal_servis);

                                    // tanggal servis berikutnya
                                    $jadwalServis = $servisTerakhir->copy()->addMonths($k->rentang_waktu_servis);

                                    // hitung selisih
                                    if ($jadwalServis->lt($today)) {
                                        $selisih = -$jadwalServis->diffInDays($today);
                                    } else {
                                        $selisih = $today->diffInDays($jadwalServis);
                                    }
                                @endphp

                                {{ $servisTerakhir->translatedFormat('d F Y') }}
                                <div class="text-xs
                                    @if($selisih < 0) text-red-600
                                    @elseif($selisih <= 7) text-orange-500
                                    @else text-gray-500
                                    @endif
                                ">
                                    @if($selisih > 0)
                                        🔧 Estimasi servis, {{ $selisih }} hari lagi
                                    @elseif($selisih == 0)
                                        🔧 Estimasi servis, Hari ini jadwal servis
                                    @else
                                        ⚠️ Estimasi servis, Terlambat {{ abs($selisih) }} hari
                                    @endif
                                </div>
                            @else
                                -
                            @endif
                        </td>
                        @endif
                        <td>
                            <div class="flex space-x-2">
                                <button onclick='openDetailKendaraan(@json($k))'
                                    class="text-blue-600">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick='openEditKendaraan(@json($k))'
                                    class="text-green-600">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @if(auth()->user()->role == 'Administrator')
                                <button onclick='openDeleteKendaraan(@json($k))'
                                    class="text-red-600">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>

{{-- ================= MODAL IMPORT ================= --}}
<div id="importKendaraanModal" class="modal fixed inset-0 bg-black/50 z-[9999] hidden flex items-center justify-center p-4">
    <div class="modal-box max-w-xl w-full">
        <form action="{{ route('pemeliharaan.kendaraan.import') }}" method="POST" enctype="multipart/form-data"
            class="bg-white rounded-2xl max-h-[90vh] flex flex-col overflow-hidden">
            @csrf
            <!-- HEADER -->
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between flex-shrink-0">
                <h3 class="text-lg font-semibold text-gray-800">
                    Import Data Kendaraan
                </h3>
                <button type="button" onclick="closeModal('importKendaraanModal')"
                    class="text-gray-500 hover:text-gray-800">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- BODY -->
            <div class="px-6 py-4 overflow-y-auto flex-1">
                <input type="file" name="file"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-500"
                    accept=".xls,.xlsx" required>
                <p class="text-xs text-gray-500 mt-2">
                    Format kolom: nomor_polisi, nama_kendaraan, jenis_kendaraan, tahun, seksi_singkat, tanggal_pajak_berkala, rentang_waktu_servis, keterangan
                </p>
            </div>

            <!-- FOOTER -->
            <div class="flex justify-end space-x-2 px-6 py-4 border-t border-gray-200 bg-white flex-shrink-0">
                <button type="button" onclick="closeModal('importKendaraanModal')"
                    class="px-3 py-1.5 border border-gray-300 rounded-lg text-gray-700 text-sm hover:bg-gray-100 transition">
                    Batal
                </button>
                <button type="submit"
                    class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm">
                    <i class="fas fa-save mr-1"></i> Import
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ================= MODAL TAMBAH ================= --}}
<div id="addKendaraanModal" class="modal fixed inset-0 bg-black/50 z-[9999] hidden flex items-center justify-center p-4">
    <div class="modal-box max-w-3xl w-full">
        <form action="{{ route('pemeliharaan.kendaraan.store') }}" method="POST"
            class="bg-white rounded-2xl max-h-[90vh] flex flex-col overflow-hidden">
            @csrf
            <!-- HEADER -->
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between flex-shrink-0">
                <h3 class="text-lg font-semibold text-gray-800">
                    Tambah Kendaraan
                </h3>
                <button type="button" onclick="closeModal('addKendaraanModal')"
                    class="text-gray-500 hover:text-gray-800">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- BODY -->
            <div class="px-6 py-4 overflow-y-auto flex-1">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Polisi <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="text" name="nomor_polisi" id="a_nomor"
                                class="w-full px-3 py-1.5 border border-gray-300 rounded-lg pr-10"
                                placeholder="Contoh: AE 1234 XX" required>
                            <!-- Spinner -->
                            <div id="addNopolSpinner"
                                class="absolute right-3 top-1/2 -translate-y-1/2 hidden">
                                <i class="fas fa-spinner fa-spin text-gray-400"></i>
                            </div>
                            <!-- Success -->
                            <div id="addNopolSuccess"
                                class="absolute right-3 top-1/2 -translate-y-1/2 hidden">
                                <i class="fas fa-check text-green-500"></i>
                            </div>
                            <!-- Error -->
                            <div id="addNopolErrorIcon"
                                class="absolute right-3 top-1/2 -translate-y-1/2 hidden">
                                <i class="fas fa-times text-red-500"></i>
                            </div>
                        </div>
                        <p id="addNopolErrorText"
                            class="text-xs text-red-600 mt-1 hidden">
                            Nomor polisi sudah digunakan.
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kendaraan <span class="text-red-500">*</span></label>
                        <select name="jenis_kendaraan"
                            class="w-full px-3 py-1.5 border border-gray-300 rounded-lg" required>
                            <option value="">-- Pilih Jenis Kendaraan --</option>
                            <option value="Motor">Motor</option>
                            <option value="Mobil">Mobil</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kendaraan (Merk/Type/Warna)<span class="text-red-500">*</span></label>
                        <input type="text" name="nama_kendaraan"
                            class="w-full px-3 py-1.5 border border-gray-300 rounded-lg"
                            placeholder="Contoh: Toyota Veloz Hitam" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Kendaraan <span class="text-red-500">*</span></label>
                        <input type="number" name="tahun"
                            class="w-full px-3 py-1.5 border border-gray-300 rounded-lg"
                            placeholder="Contoh: 2025" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Seksi <span class="text-red-500">*</span></label>
                        <select name="seksi_id"
                            class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            <option value="">-- Pilih Seksi --</option>
                            @foreach($seksis as $seksi)
                                <option value="{{ $seksi->id }}">
                                    {{ $seksi->seksi }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pajak Berkala</label>
                        <input type="date" name="tanggal_pajak_berkala"
                            class="w-full px-3 py-1.5 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rentang Waktu Servis (Bulan)</label>
                        <input type="number" name="rentang_waktu_servis"
                            class="w-full px-3 py-1.5 border border-gray-300 rounded-lg"
                            placeholder="Contoh: 6 Bulan">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan (Opsional)</label>
                        <textarea name="keterangan"
                            class="w-full px-3 py-1.5 border border-gray-300 rounded-lg"
                            placeholder="Tambahan informasi kendaraan"></textarea>
                    </div>
                </div>
            </div>

            <!-- FOOTER -->
            <div class="flex justify-end space-x-2 px-6 py-4 border-t border-gray-200 bg-white flex-shrink-0">
                <button type="button" onclick="closeModal('addKendaraanModal')"
                    class="px-3 py-1.5 border border-gray-300 rounded-lg text-gray-700 text-sm hover:bg-gray-100 transition">
                    Batal
                </button>
                <button type="submit" id="addKendaraanSubmitBtn"
                    class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm">
                    <i class="fas fa-save mr-1"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ================= MODAL DETAIL ================= --}}
<div id="detailKendaraanModal" class="modal fixed inset-0 bg-black/50 z-[9999] hidden flex items-center justify-center p-4">
    <div class="modal-box bg-white rounded-2xl w-full max-w-3xl shadow-lg overflow-hidden">
        <!-- HEADER -->
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h3 class="text-lg font-semibold text-gray-800" id="detailKendaraanTitle">
                Detail Kendaraan
            </h3>
            <button onclick="closeModal('detailKendaraanModal')"
                class="text-gray-500 hover:text-gray-800">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- BODY -->
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Nomor Polisi</p>
                    <p id="d_nomor" class="font-medium text-blue-600">-</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Jenis</p>
                    <p id="d_jenis" class="font-medium text-gray-800">-</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Nama Kendaraan</p>
                    <p id="d_nama" class="font-medium text-gray-800">-</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Tahun Kendaraan</p>
                    <p id="d_tahun" class="font-medium text-gray-800">-</p>
                </div>
                @if(auth()->user()->role == 'Administrator')
                <div>
                    <p class="text-sm text-gray-500 mb-1">Seksi</p>
                    <p id="d_seksi" class="font-medium text-gray-800">-</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Tanggal Pajak Berkala</p>
                    <p id="d_pajak" class="font-medium text-gray-800">-</p>
                </div>
                @endif
                <div>
                    <p class="text-sm text-gray-500 mb-1">Rentang Waktu Servis</p>
                    <p id="d_waktu" class="font-medium text-gray-800">-</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Tanggal Terakhir Servis</p>
                    <p id="d_servis" class="font-medium text-gray-800">-</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Keterangan</p>
                    <p id="d_ket" class="bg-gray-50 rounded-lg p-3 text-gray-800 text-sm">-</p>
                </div>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="px-6 py-4 border-t flex justify-end">
            <button onclick="closeModal('detailKendaraanModal')"
                class="px-3 py-1.5 border border-gray-300 rounded-lg text-gray-700 text-sm hover:bg-gray-100">
                Tutup
            </button>
        </div>
    </div>
</div>

{{-- ================= MODAL EDIT ================= --}}
<div id="editKendaraanModal" class="modal fixed inset-0 bg-black/50 z-[9999] hidden flex items-center justify-center p-4">
    <div class="modal-box max-w-3xl w-full">
        <form id="editKendaraanForm" method="POST"
            class="bg-white rounded-2xl max-h-[90vh] flex flex-col overflow-hidden">
            @csrf
            @method('PUT')
            <!-- HEADER -->
            <div class="px-6 py-4 border-b flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800" id="editKendaraanTitle">
                    Edit Kendaraan
                </h3>
                <button type="button" onclick="closeModal('editKendaraanModal')"
                    class="text-gray-500 hover:text-gray-800">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- BODY -->
            <div class="px-6 py-4 overflow-y-auto flex-1">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Polisi <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="text" name="nomor_polisi" id="e_nomor"
                                class="w-full px-3 py-1.5 border border-gray-300 rounded-lg pr-10" required>
                            <div id="editNopolSpinner"
                                class="absolute right-3 top-1/2 -translate-y-1/2 hidden">
                                <i class="fas fa-spinner fa-spin text-gray-400"></i>
                            </div>
                            <div id="editNopolSuccess"
                                class="absolute right-3 top-1/2 -translate-y-1/2 hidden">
                                <i class="fas fa-check text-green-500"></i>
                            </div>
                            <div id="editNopolErrorIcon"
                                class="absolute right-3 top-1/2 -translate-y-1/2 hidden">
                                <i class="fas fa-times text-red-500"></i>
                            </div>
                        </div>
                        <p id="editNopolErrorText"
                            class="text-xs text-red-600 mt-1 hidden">
                            Nomor polisi barang sudah digunakan.
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis <span class="text-red-500">*</span></label>
                        <select id="e_jenis" name="jenis_kendaraan"
                            class="w-full px-3 py-1.5 border border-gray-300 rounded-lg">
                            <option value="Motor">Motor</option>
                            <option value="Mobil">Mobil</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kendaraan (Merk/Type/Warna)<span class="text-red-500">*</span></label>
                        <input id="e_nama" name="nama_kendaraan"
                            class="w-full px-3 py-1.5 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Kendaraan <span class="text-red-500">*</span></label>
                        <input id="e_tahun" name="tahun"
                            class="w-full px-3 py-1.5 border border-gray-300 rounded-lg">
                    </div>
                    @if(auth()->user()->role == 'Administrator')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Seksi <span class="text-red-500">*</span></label>
                        <select name="seksi_id" id="e_seksi"
                            class="w-full px-3 py-1.5 border border-gray-300 rounded-lg" required>
                            @foreach($seksis as $seksi)
                                <option value="{{ $seksi->id }}">
                                    {{ $seksi->seksi }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pajak</label>
                        <input id="e_pajak" type="date" name="tanggal_pajak_berkala"
                            class="w-full px-3 py-1.5 border border-gray-300 rounded-lg">
                    </div>
                    @endif
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rentang Waktu Servis (Bulan)</label>
                        <input id="e_waktu" type="number" name="rentang_waktu_servis"
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
                <button type="button" onclick="closeModal('editKendaraanModal')"
                    class="px-3 py-1.5 border border-gray-300 rounded-lg text-gray-700 text-sm hover:bg-gray-100">
                    Batal
                </button>
                <button type="submit" id="editKendaraanSubmitBtn"
                    class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm">
                    <i class="fas fa-save mr-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ================= DELETE ================= --}}
<div id="deleteKendaraanModal" class="modal fixed inset-0 bg-black/50 z-[9999] hidden flex items-center justify-center p-4">
    <div class="modal-box bg-white rounded-2xl w-full max-w-md shadow-lg">
        <div class="p-6 text-center">
            <div class="mx-auto mb-4 w-14 h-14 rounded-full bg-red-100 flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
            </div>

            <h3 class="text-lg font-semibold text-gray-800 mb-2">
                Konfirmasi Hapus Kendaraan
            </h3>
            <p class="text-sm text-gray-600 mb-6">
                Apakah Anda yakin ingin menghapus kendaraan
                <span id="deleteKendaraanText" class="font-semibold text-gray-800"></span>?
                <br>Tindakan ini tidak dapat dibatalkan.
            </p>

            <form id="deleteKendaraanForm" method="POST">
                @csrf
                @method('DELETE')

                <div class="flex justify-center space-x-2">
                    <button type="button"
                        onclick="closeModal('deleteKendaraanModal')"
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

    // DETAIL
    function openDetailKendaraan(data){
        const today = new Date();

        // =========================
        // PAJAK
        // =========================
        let pajakText = '-';
        let pajakInfo = '';

        if (data.tanggal_pajak_berkala) {
            const jatuhTempo = new Date(data.tanggal_pajak_berkala);
            jatuhTempo.setFullYear(today.getFullYear());

            const sudahBayar = data.riwayat_pajak && data.riwayat_pajak.length > 0;

            let target = new Date(jatuhTempo);
            let selisih;

            if (sudahBayar) {
                target.setFullYear(target.getFullYear() + 1);
                selisih = Math.ceil((target - today) / (1000 * 60 * 60 * 24));
            } else {
                selisih = Math.ceil((jatuhTempo - today) / (1000 * 60 * 60 * 24));
            }

            pajakText = jatuhTempo.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'long'
            });

            if (sudahBayar) {
                pajakInfo = `✅ Sudah bayar, ${selisih} hari lagi`;
            } else {
                if (selisih > 0) {
                    pajakInfo = `❌ Belum bayar, ${selisih} hari lagi`;
                } else if (selisih === 0) {
                    pajakInfo = `❌ Belum bayar, Hari ini jatuh tempo`;
                } else {
                    pajakInfo = `❌ Belum bayar, Lewat ${Math.abs(selisih)} hari`;
                }
            }
        }

        // =========================
        // SERVIS
        // =========================
        let servisText = '-';
        let servisInfo = '';

        if (data.riwayat_servis && data.riwayat_servis.length > 0 && data.rentang_waktu_servis) {
            const servisTerakhir = new Date(data.riwayat_servis[0].tanggal_servis);

            const jadwalServis = new Date(servisTerakhir);
            jadwalServis.setMonth(jadwalServis.getMonth() + data.rentang_waktu_servis);

            let selisih = Math.ceil((jadwalServis - today) / (1000 * 60 * 60 * 24));

            servisText = servisTerakhir.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            });

            if (selisih > 0) {
                servisInfo = `🔧 Estimasi servis, ${selisih} hari lagi`;
            } else if (selisih === 0) {
                servisInfo = `🔧 Estimasi servis, Hari ini`;
            } else {
                servisInfo = `⚠️ Estimasi servis, Terlambat ${Math.abs(selisih)} hari`;
            }
        }

        // =========================
        // SET DATA
        // =========================
        document.getElementById('detailKendaraanTitle').innerText =
            `Detail Kendaraan - ${data.nomor_polisi}`;
        document.getElementById('d_nomor').textContent = data.nomor_polisi;
        document.getElementById('d_jenis').textContent = data.jenis_kendaraan;
        document.getElementById('d_nama').textContent = data.nama_kendaraan;
        document.getElementById('d_tahun').textContent = data.tahun;

        const seksiEl = document.getElementById('d_seksi');
        if (seksiEl) {
            seksiEl.textContent = data.seksi ? data.seksi.seksi : '-';
        }
        const pajakEl = document.getElementById('d_pajak');
        if (pajakEl) {
            pajakEl.innerHTML = pajakText + `<div class="text-xs mt-1">${pajakInfo}</div>`;
        }

        document.getElementById('d_servis').innerHTML = servisText + `<div class="text-xs mt-1">${servisInfo}</div>`;
        document.getElementById('d_waktu').textContent = (data.rentang_waktu_servis ?? '-') + ' Bulan';
        document.getElementById('d_ket').textContent = data.keterangan ?? '-';

        openModal('detailKendaraanModal');
    }

    // EDIT
    function openEditKendaraan(data){
        const form = document.getElementById('editKendaraanForm');

        form.action = `/pemeliharaan/kendaraan/${data.id}`;

        document.getElementById('editKendaraanTitle').innerText =
            `Edit Kendaraan - ${data.nomor_polisi}`;
        document.getElementById('e_nomor').value = data.nomor_polisi;
        document.getElementById('e_jenis').value = data.jenis_kendaraan;
        document.getElementById('e_nama').value = data.nama_kendaraan;
        document.getElementById('e_tahun').value = data.tahun;

         // ✅ AMAN (cek dulu ada atau tidak)
        const seksiField = document.getElementById('e_seksi');
        if (seksiField) {
            seksiField.value = data.seksi_id;
        }
        const pajakField = document.getElementById('e_pajak');
        if (pajakField) {
            pajakField.value = data.tanggal_pajak_berkala ?? '';
        }
        
        document.getElementById('e_waktu').value = data.rentang_waktu_servis ?? '';
        document.getElementById('e_ket').value = data.keterangan ?? '';

        openModal('editKendaraanModal');
    }

    function openDeleteKendaraan(data){
        const form = document.getElementById('deleteKendaraanForm');

        // set action (route delete)
        form.action = `/pemeliharaan/kendaraan/${data.id}`;
        // isi nama kendaraan
        document.getElementById('deleteKendaraanText').innerText =
            `${data.nomor_polisi} - ${data.nama_kendaraan}`;

        openModal('deleteKendaraanModal');
    }

    // AUTO HIDE ALERT
    setTimeout(function () {
        const alert = document.getElementById('alert-message' || 'alert-message2');
        if (alert) {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }
    }, 5000); // 5 detik
</script>

<script>
    function setupNopolValidation(config) {
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
            const nopol = this.value.trim();

            if (!nopol) {
                resetState();
                submitBtn.disabled = false;
                return;
            }

            clearTimeout(timeout);

            timeout = setTimeout(() => {
                setLoading();

                let url = `{{ route('pemeliharaan.kendaraan.checkNopol') }}?nomor_polisi=${nopol}`;

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
    setupNopolValidation({
        inputId: 'a_nomor',
        spinnerId: 'addNopolSpinner',
        successId: 'addNopolSuccess',
        errorIconId: 'addNopolErrorIcon',
        errorTextId: 'addNopolErrorText',
        submitBtnId: 'addKendaraanSubmitBtn'
    });

    /* INIT EDIT */
    setupNopolValidation({
        inputId: 'e_nomor',
        spinnerId: 'editNopolSpinner',
        successId: 'editNopolSuccess',
        errorIconId: 'editNopolErrorIcon',
        errorTextId: 'editNopolErrorText',
        submitBtnId: 'editKendaraanSubmitBtn',
        ignoreIdGetter: function () {
            const form = document.getElementById('editKendaraanForm');
            return form ? form.action.split('/').pop() : null;
        }
    });
</script>

@endsection
