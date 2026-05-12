<!-- Report Table -->
@if(!request()->filled('jenis_laporan'))
<div class="bg-white rounded-lg shadow p-8 text-center text-gray-600">
    <i class="fas fa-filter text-2xl mb-2 text-gray-400"></i>
    <p class="text-md font-semibold mb-1">
        Laporan belum ditampilkan
    </p>
    <p class="text-sm">
        Silakan pilih <b>Jenis Laporan</b> dan rentang tanggal,
        lalu klik <b>Tampilkan Laporan</b>.
    </p>
</div>

@else
    @if($jenis === 'semua')
    <div class="bg-white rounded-lg shadow-sm p-4">
        <h3 class="text-lg font-bold mb-6 text-gray-800">Laporan Semua Transaksi</h3>
        <p class="text-sm text-gray-500 mb-4">
            Periode:
            <span class="font-medium text-gray-700">
                {{ $dari ? \Carbon\Carbon::parse($dari)->format('d M Y') : '-' }}
            </span>
            s/d
            <span class="font-medium text-gray-700">
                {{ $sampai ? \Carbon\Carbon::parse($sampai)->format('d M Y') : '-' }}
            </span>
        </p>

        <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jenis Transaksi</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode Barang</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Barang</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jumlah</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Keterangan</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($laporan as $i => $row)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-900">{{ $i + 1 }}</td>
                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-900">
                        {{ \Carbon\Carbon::parse($row->tanggal)->format('d M Y') }}
                    </td>
                    <td class="px-6 py-2 whitespace-nowrap">
                        @if($row->jenis === 'Masuk')
                            <span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Masuk</span>
                        @else
                            <span class="px-3 py-1 text-xs font-semibold text-orange-800 bg-orange-100 rounded-full">Keluar</span>
                        @endif
                    </td>
                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-900">{{ $row->barang->kode_barang ?? '-' }}</td>
                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-900">{{ $row->barang->nama_barang ?? '-' }}</td>
                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-900">{{ $row->barang->kategori->nama ?? '-' }}</td>
                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-900">{{ $row->jumlah }} {{ $row->barang->satuan ?? '' }}</td>
                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-900">{{ $row->keterangan ?? '-' }}</td>
                </tr>
                @empty
                    {{-- 🔴 JIKA DATA KOSONG --}}
                    <tr>
                        <td colspan="8" class="px-4 py-6 text-center text-gray-500 italic">
                            Tidak ada data transaksi pada rentang tanggal ini
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    @elseif($jenis === 'barang_masuk')
    <div class="bg-white rounded-lg shadow-sm p-4">
        <h3 class="text-lg font-bold mb-6 text-gray-800">Laporan Barang Masuk</h3>
        <p class="text-sm text-gray-500 mb-4">
            Periode:
            <span class="font-medium text-gray-700">
                {{ $dari ? \Carbon\Carbon::parse($dari)->format('d M Y') : '-' }}
            </span>
            s/d
            <span class="font-medium text-gray-700">
                {{ $sampai ? \Carbon\Carbon::parse($sampai)->format('d M Y') : '-' }}
            </span>
        </p>

        <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode Barang</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Barang</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jumlah</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Harga Total</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Keterangan</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($laporan as $i => $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-900">{{ $i + 1 }}</td>
                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-900">
                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                    </td>
                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-900">{{ $item->barang->kode_barang ?? '-' }}</td>
                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-900">{{ $item->barang->nama_barang ?? '-' }}</td>
                    <td class="px-6 py-2 whitespace-nowrap text-sm text-green-600 font-semibold">
                        {{ $item->jumlah }} {{ $item->barang->satuan ?? '' }}
                    </td>
                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-900 font-semibold">
                        Rp {{ number_format($item->harga_total,0,',','.') }}
                    </td>
                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-900">{{ $item->keterangan ?? '-' }}</td>
                </tr>
                @empty
                    {{-- 🔴 JIKA DATA KOSONG --}}
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-500 italic">
                            Tidak ada data barang masuk pada rentang tanggal ini
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    @elseif($jenis === 'barang_keluar')
    <div class="bg-white rounded-lg shadow-sm p-4">
        <h3 class="text-lg font-bold mb-6 text-gray-800">Laporan Barang Keluar</h3>
        <p class="text-sm text-gray-500 mb-4">
            Periode:
            <span class="font-medium text-gray-700">
                {{ $dari ? \Carbon\Carbon::parse($dari)->format('d M Y') : '-' }}
            </span>
            s/d
            <span class="font-medium text-gray-700">
                {{ $sampai ? \Carbon\Carbon::parse($sampai)->format('d M Y') : '-' }}
            </span>
        </p>

        <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode Barang</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Barang</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jumlah</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Peminta</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Keperluan</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($laporan as $i => $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-900">{{ $i + 1 }}</td>
                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-900">
                        {{ \Carbon\Carbon::parse($item->tanggal_proses)->format('d M Y') }}
                    </td>
                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-900">
                        {{ $item->barang->kode_barang ?? '-' }}
                    </td>
                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-900">
                        {{ $item->barang->nama_barang ?? '-' }}
                    </td>
                    <td class="px-6 py-2 whitespace-nowrap text-sm text-red-600 font-semibold">
                        {{ $item->jumlah }} {{ $item->barang->satuan ?? '' }}
                    </td>
                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-900">
                        {{ $item->user->name ?? '-' }}
                    </td>
                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-900">
                        {{ $item->keperluan ?? '-' }}
                    </td>
                </tr>
                @empty
                    {{-- 🔴 JIKA DATA KOSONG --}}
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-500 italic">
                            Tidak ada data barang keluar pada rentang tanggal ini
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    @elseif($jenis === 'stok_barang')
    <div class="bg-white rounded-lg shadow-sm p-4">
        <h3 class="text-lg font-bold mb-6 text-gray-800">Laporan Stok Barang</h3>
        <p class="text-sm text-gray-500 mb-4">
            Periode:
            <span class="font-medium text-gray-700">
                {{ $dari ? \Carbon\Carbon::parse($dari)->format('d M Y') : '-' }}
            </span>
            s/d
            <span class="font-medium text-gray-700">
                {{ $sampai ? \Carbon\Carbon::parse($sampai)->format('d M Y') : '-' }}
            </span>
        </p>

        <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">No</th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Kode Barang</th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Nama Barang</th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Kategori</th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Stok Awal</th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Masuk</th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Keluar</th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Stok Akhir</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($laporan as $i => $row)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-2 text-sm">{{ $i + 1 }}</td>
                    <td class="px-6 py-2 text-sm">{{ $row->barang->kode_barang }}</td>
                    <td class="px-6 py-2 text-sm">{{ $row->barang->nama_barang }}</td>
                    <td class="px-6 py-2 text-sm">{{ $row->barang->kategori->nama ?? '-' }}</td>
                    <td class="px-6 py-2 text-sm">{{ $row->stok_awal }} {{ $row->barang->satuan }}</td>
                    <td class="px-6 py-2 text-sm text-green-600 font-semibold">+ {{ $row->masuk }}</td>
                    <td class="px-6 py-2 text-sm text-red-600 font-semibold">- {{ $row->keluar }}</td>
                    <td class="px-6 py-2 text-sm font-bold">
                        {{ $row->stok_akhir }} {{ $row->barang->satuan }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-6 text-center text-gray-500 italic">
                        Tidak ada data stok barang
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    @endif   {{-- END jenis laporan --}}

@endif   {{-- END default else --}}