@extends('layouts.app')

@section('title', 'Detail Laporan')
@section('page-title', 'Detail Laporan Inventaris BMN')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('laporan.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Laporan
        </a>
    </div>

    <!-- Report Header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-start justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Laporan Inventaris Periode</h2>
                <p class="text-gray-600 mt-2">01 Januari 2024 - 15 Januari 2024</p>
                <div class="mt-4 flex items-center gap-4">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-calendar mr-2"></i>
                        Dibuat: 15 Januari 2024
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-user mr-2"></i>
                        Oleh: Admin Imigrasi
                    </div>
                </div>
            </div>
            <div class="flex gap-2">
                <button onclick="window.print()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                    <i class="fas fa-print mr-2"></i> Print
                </button>
                <button class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition">
                    <i class="fas fa-file-excel mr-2"></i> Excel
                </button>
                <button class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition">
                    <i class="fas fa-file-pdf mr-2"></i> PDF
                </button>
            </div>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-blue-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Item</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">1,245</h3>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-boxes text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Barang Masuk</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">156</h3>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-arrow-down text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-orange-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Barang Keluar</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">89</h3>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-arrow-up text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-purple-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Nilai</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-2">Rp 2.5M</h3>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Report Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700">
            <h3 class="text-lg font-semibold text-white">Rincian Transaksi Inventaris</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Jenis</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kode BMN</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama Barang</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Satuan</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Lokasi</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                    $detailReports = [
                        ['date' => '2024-01-15', 'type' => 'Masuk', 'code' => 'BMN-2024-001', 'name' => 'Laptop Dell Latitude 5420', 'category' => 'Elektronik', 'unit' => 'Unit', 'qty' => 5, 'location' => 'Gudang Utama', 'note' => 'Pengadaan DIPA 2024'],
                        ['date' => '2024-01-15', 'type' => 'Keluar', 'code' => 'BMN-2023-045', 'name' => 'Printer HP LaserJet Pro', 'category' => 'Elektronik', 'unit' => 'Unit', 'qty' => 2, 'location' => 'Ruang TU', 'note' => 'Distribusi ke bagian TU'],
                        ['date' => '2024-01-14', 'type' => 'Masuk', 'code' => 'BMN-2024-002', 'name' => 'Kursi Kantor Ergonomis', 'category' => 'Furniture', 'unit' => 'Unit', 'qty' => 10, 'location' => 'Gudang Utama', 'note' => 'Hibah dari Kanwil'],
                        ['date' => '2024-01-14', 'type' => 'Keluar', 'code' => 'BMN-2023-067', 'name' => 'Meja Kerja Staff', 'category' => 'Furniture', 'unit' => 'Unit', 'qty' => 3, 'location' => 'Ruang Staff', 'note' => 'Penambahan ruang kerja'],
                        ['date' => '2024-01-13', 'type' => 'Masuk', 'code' => 'BMN-2024-003', 'name' => 'AC Split 2 PK Daikin', 'category' => 'Elektronik', 'unit' => 'Unit', 'qty' => 1, 'location' => 'Gudang Utama', 'note' => 'Pengadaan langsung'],
                        ['date' => '2024-01-13', 'type' => 'Keluar', 'code' => 'BMN-2024-001', 'name' => 'Laptop Dell Latitude 5420', 'category' => 'Elektronik', 'unit' => 'Unit', 'qty' => 1, 'location' => 'Ruang Kepala', 'note' => 'Untuk Kepala Kantor'],
                        ['date' => '2024-01-12', 'type' => 'Masuk', 'code' => 'BMN-2024-004', 'name' => 'Scanner Epson L3210', 'category' => 'Elektronik', 'unit' => 'Unit', 'qty' => 3, 'location' => 'Gudang Utama', 'note' => 'Pengadaan DIPA 2024'],
                        ['date' => '2024-01-11', 'type' => 'Masuk', 'code' => 'BMN-2024-005', 'name' => 'Komputer Desktop HP', 'category' => 'Elektronik', 'unit' => 'Unit', 'qty' => 8, 'location' => 'Gudang Utama', 'note' => 'Pengadaan DIPA 2024'],
                    ];
                    @endphp

                    @foreach($detailReports as $index => $report)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($report['date'])->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($report['type'] === 'Masuk')
                            <span class="inline-flex items-center px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                                <i class="fas fa-arrow-down mr-1"></i> Masuk
                            </span>
                            @else
                            <span class="inline-flex items-center px-3 py-1 text-xs font-semibold text-orange-800 bg-orange-100 rounded-full">
                                <i class="fas fa-arrow-up mr-1"></i> Keluar
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono font-semibold text-blue-600">{{ $report['code'] }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $report['name'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $report['category'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $report['unit'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 text-right">{{ $report['qty'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $report['location'] }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $report['note'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50 border-t-2 border-gray-300">
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-sm font-bold text-gray-800 text-right">Total Transaksi:</td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-900 text-right">{{ count($detailReports) }} Item</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Report Footer -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <h4 class="font-semibold text-gray-800 mb-4">Catatan Laporan</h4>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-600 mr-2 mt-0.5"></i>
                        <span>Semua data telah diverifikasi oleh admin sistem</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-600 mr-2 mt-0.5"></i>
                        <span>Laporan ini mencakup seluruh transaksi periode yang ditentukan</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-600 mr-2 mt-0.5"></i>
                        <span>Data telah disesuaikan dengan SIMAK BMN</span>
                    </li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold text-gray-800 mb-4">Pengesahan</h4>
                <div class="text-center space-y-16">
                    <div>
                        <p class="text-sm text-gray-600">Ponorogo, 15 Januari 2024</p>
                        <p class="text-sm font-semibold text-gray-800 mt-1">Kepala Kantor Imigrasi Ponorogo</p>
                        <div class="h-16 flex items-center justify-center">
                            <p class="text-gray-400 italic text-xs">(Tanda Tangan)</p>
                        </div>
                        <p class="text-sm font-bold text-gray-800 border-t-2 border-gray-800 inline-block px-8 pt-1">Nama Kepala Kantor</p>
                        <p class="text-xs text-gray-600 mt-1">NIP. 19XXXXXXXXXXXXXXX</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .print-area, .print-area * {
            visibility: visible;
        }
        .print-area {
            position: absolute;
            left: 0;
            top: 0;
        }
    }
</style>
@endsection
