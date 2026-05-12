<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Laporan - Sistem Inventaris BMN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { 
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
            .no-print { display: none; }
            .page-break { page-break-before: always; }
        }
        @page {
            size: A4;
            margin: 15mm;
        }
    </style>
</head>
<body class="bg-white">
    <!-- Print Button -->
    <div class="no-print fixed top-4 right-4 z-50">
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg shadow-lg font-semibold">
            <i class="fas fa-print mr-2"></i> Print Laporan
        </button>
        <button onclick="window.close()" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg shadow-lg font-semibold ml-2">
            <i class="fas fa-times mr-2"></i> Tutup
        </button>
    </div>

    <div class="max-w-4xl mx-auto p-8">
        <!-- Header Surat -->
        <div class="border-b-4 border-blue-600 pb-4 mb-6">
            <div class="flex items-start space-x-4">
                <img src="/placeholder.svg?height=80&width=80" alt="Logo Imigrasi" class="h-20 w-20">
                <div class="flex-1 text-center">
                    <h1 class="text-xl font-bold text-gray-800 uppercase">Kementerian Hukum dan Hak Asasi Manusia</h1>
                    <h2 class="text-lg font-bold text-blue-700 uppercase">Kantor Imigrasi Kelas II Non TPI Ponorogo</h2>
                    <p class="text-sm text-gray-600 mt-2">
                        Jl. Basuki Rahmat No. 45, Ponorogo, Jawa Timur 63419<br>
                        Telp: (0352) 481234 | Email: kanim.ponorogo@imigrasi.go.id
                    </p>
                </div>
                <div class="h-20 w-20"></div>
            </div>
        </div>

        <!-- Judul Laporan -->
        <div class="text-center mb-6">
            <h3 class="text-xl font-bold text-gray-800 uppercase">Laporan Inventaris Barang Milik Negara (BMN)</h3>
            <p class="text-gray-600 mt-2">Periode: 01 Januari 2024 - 15 Januari 2024</p>
            <p class="text-sm text-gray-500 mt-1">Nomor: LI/001/I/2024</p>
        </div>

        <!-- Ringkasan -->
        <div class="mb-6 bg-gray-50 p-4 rounded-lg">
            <h4 class="font-bold text-gray-800 mb-3">Ringkasan Laporan:</h4>
            <div class="grid grid-cols-4 gap-4 text-center">
                <div class="bg-white p-3 rounded border border-gray-200">
                    <p class="text-xs text-gray-600">Total Item</p>
                    <p class="text-2xl font-bold text-blue-600">1,245</p>
                </div>
                <div class="bg-white p-3 rounded border border-gray-200">
                    <p class="text-xs text-gray-600">Barang Masuk</p>
                    <p class="text-2xl font-bold text-green-600">156</p>
                </div>
                <div class="bg-white p-3 rounded border border-gray-200">
                    <p class="text-xs text-gray-600">Barang Keluar</p>
                    <p class="text-2xl font-bold text-orange-600">89</p>
                </div>
                <div class="bg-white p-3 rounded border border-gray-200">
                    <p class="text-xs text-gray-600">Total Nilai</p>
                    <p class="text-xl font-bold text-purple-600">Rp 2.5M</p>
                </div>
            </div>
        </div>

        <!-- Tabel Data -->
        <table class="w-full border-collapse border border-gray-400 text-sm mb-6">
            <thead>
                <tr class="bg-blue-700 text-white">
                    <th class="border border-gray-400 px-2 py-2 text-left">No</th>
                    <th class="border border-gray-400 px-2 py-2 text-left">Tanggal</th>
                    <th class="border border-gray-400 px-2 py-2 text-left">Jenis</th>
                    <th class="border border-gray-400 px-2 py-2 text-left">Kode BMN</th>
                    <th class="border border-gray-400 px-2 py-2 text-left">Nama Barang</th>
                    <th class="border border-gray-400 px-2 py-2 text-left">Kategori</th>
                    <th class="border border-gray-400 px-2 py-2 text-center">Jumlah</th>
                    <th class="border border-gray-400 px-2 py-2 text-left">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @php
                $printReports = [
                    ['date' => '2024-01-15', 'type' => 'Masuk', 'code' => 'BMN-2024-001', 'name' => 'Laptop Dell Latitude 5420', 'category' => 'Elektronik', 'qty' => 5, 'note' => 'Pengadaan DIPA 2024'],
                    ['date' => '2024-01-15', 'type' => 'Keluar', 'code' => 'BMN-2023-045', 'name' => 'Printer HP LaserJet Pro', 'category' => 'Elektronik', 'qty' => 2, 'note' => 'Distribusi ke TU'],
                    ['date' => '2024-01-14', 'type' => 'Masuk', 'code' => 'BMN-2024-002', 'name' => 'Kursi Kantor Ergonomis', 'category' => 'Furniture', 'qty' => 10, 'note' => 'Hibah dari Kanwil'],
                    ['date' => '2024-01-14', 'type' => 'Keluar', 'code' => 'BMN-2023-067', 'name' => 'Meja Kerja Staff', 'category' => 'Furniture', 'qty' => 3, 'note' => 'Penambahan ruang kerja'],
                    ['date' => '2024-01-13', 'type' => 'Masuk', 'code' => 'BMN-2024-003', 'name' => 'AC Split 2 PK Daikin', 'category' => 'Elektronik', 'qty' => 1, 'note' => 'Pengadaan langsung'],
                    ['date' => '2024-01-13', 'type' => 'Keluar', 'code' => 'BMN-2024-001', 'name' => 'Laptop Dell Latitude 5420', 'category' => 'Elektronik', 'qty' => 1, 'note' => 'Untuk Kepala Kantor'],
                    ['date' => '2024-01-12', 'type' => 'Masuk', 'code' => 'BMN-2024-004', 'name' => 'Scanner Epson L3210', 'category' => 'Elektronik', 'qty' => 3, 'note' => 'Pengadaan DIPA 2024'],
                    ['date' => '2024-01-11', 'type' => 'Masuk', 'code' => 'BMN-2024-005', 'name' => 'Komputer Desktop HP', 'category' => 'Elektronik', 'qty' => 8, 'note' => 'Pengadaan DIPA 2024'],
                ];
                @endphp

                @foreach($printReports as $index => $report)
                <tr>
                    <td class="border border-gray-400 px-2 py-2">{{ $index + 1 }}</td>
                    <td class="border border-gray-400 px-2 py-2">{{ \Carbon\Carbon::parse($report['date'])->format('d/m/Y') }}</td>
                    <td class="border border-gray-400 px-2 py-2">{{ $report['type'] }}</td>
                    <td class="border border-gray-400 px-2 py-2 font-mono">{{ $report['code'] }}</td>
                    <td class="border border-gray-400 px-2 py-2">{{ $report['name'] }}</td>
                    <td class="border border-gray-400 px-2 py-2">{{ $report['category'] }}</td>
                    <td class="border border-gray-400 px-2 py-2 text-center font-bold">{{ $report['qty'] }}</td>
                    <td class="border border-gray-400 px-2 py-2">{{ $report['note'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Tanda Tangan -->
        <div class="grid grid-cols-2 gap-8 mt-8">
            <div></div>
            <div class="text-center">
                <p class="mb-1">Ponorogo, 15 Januari 2024</p>
                <p class="font-semibold mb-16">Kepala Kantor Imigrasi Ponorogo</p>
                <p class="font-bold border-t-2 border-black inline-block px-8 pt-1">Nama Kepala Kantor</p>
                <p class="text-xs mt-1">NIP. 19XXXXXXXXXXXXXXX</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 pt-4 border-t border-gray-300 text-xs text-gray-600 text-center">
            <p>Dokumen ini dicetak dari Sistem Inventaris BMN Kantor Imigrasi Ponorogo</p>
            <p>Dicetak pada: {{ now()->format('d F Y, H:i') }} WIB</p>
        </div>
    </div>

    <script>
        // Auto print on load (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
