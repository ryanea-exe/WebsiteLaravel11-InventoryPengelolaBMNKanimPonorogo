@php
    \Carbon\Carbon::setLocale('id');
@endphp

<!-- Report Table -->
@if(!request()->filled('jenis_laporan'))
<div class="bg-white rounded-lg shadow p-8 text-center text-gray-600">
    <i class="fas fa-filter text-2xl mb-2 text-gray-400"></i>
    <p class="text-md font-semibold mb-1">
        Laporan belum ditampilkan
    </p>
    <p class="text-sm">
        Silakan pilih <b>Jenis Laporan</b> dan rentang tanggal, lalu klik <b>Tampilkan Laporan</b>.
    </p>
</div>

@else
    @if($jenis === 'semua')
    <div class="bg-white rounded-lg shadow-sm p-4">
        <h3 class="text-lg font-bold mb-4 text-gray-800">
            <i class="fas fa-file-alt mr-3"></i>Laporan Semua Transaksi Barang Persediaan
        </h3>
        <p class="text-sm text-gray-500 mb-4">
            Periode:
            <span class="font-medium text-gray-700">
                {{ $dari ? \Carbon\Carbon::parse($dari)->translatedFormat('d F Y') : '-' }}
            </span>
            s/d
            <span class="font-medium text-gray-700">
                {{ $sampai ? \Carbon\Carbon::parse($sampai)->translatedFormat('d F Y') : '-' }}
            </span>
        </p>

        <div class="overflow-x-auto" style="box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);">
        <table class="w-full rounded-lg overflow-hidden">
            <thead class="bg-gray-200 border-b border-gray-300">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider w-8">No</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tanggal</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Jenis Transaksi</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kode Barang</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama Barang</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kategori</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Jumlah</th>
                    <!-- <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Keterangan</th> -->
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-300 border-b border-gray-300">
                @php
                    $grouped = $laporan->groupBy(function($item) {
                        return $item->jenis === 'Keluar'
                            ? 'keluar_'.$item->pengajuan_id
                            : 'masuk_'.$item->id;
                    });
                @endphp

                @forelse($grouped as $group)
                @php
                    $first = $group->first();
                @endphp
                <tr class="hover:bg-gray-100">
                    <td class="px-4 py-2 text-sm text-gray-700">{{ $loop->iteration }}</td>
                    <td class="px-4 py-2 text-sm text-gray-700">{{ \Carbon\Carbon::parse($first->tanggal)->format('d M Y, H:i') }}</td>
                    <td class="px-4 py-2">
                        @if($first->jenis === 'Masuk')
                            <span class="px-3 py-1 text-xs font-semibold text-green-600 bg-green-100 rounded-full">
                                <i class="fas fa-arrow-down text-[10px]"></i> Masuk
                            </span>
                        @else
                            <span class="px-3 py-1 text-xs font-semibold text-red-600 bg-red-100 rounded-full">
                                <i class="fas fa-arrow-up text-[10px]"></i> Keluar
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-700">
                        @if($first->jenis === 'Masuk')
                            @foreach($first->details as $detail)
                                <div>{{ $detail->barang->kode_barang }}</div>
                            @endforeach
                        @else
                            @foreach($group as $item)
                                <div>{{ $item->barang->kode_barang }}</div>
                            @endforeach
                        @endif
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-700">
                        @if($first->jenis === 'Masuk')
                            @foreach($first->details as $detail)
                                <div>{{ $detail->barang->nama_barang }}</div>
                            @endforeach
                        @else
                            @foreach($group as $item)
                                <div>{{ $item->barang->nama_barang }}</div>
                            @endforeach
                        @endif
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-700">
                        @if($first->jenis === 'Masuk')
                            @foreach($first->details as $detail)
                                <div>{{ $detail->barang->kategori->nama ?? '-' }}</div>
                            @endforeach
                        @else
                            @foreach($group as $item)
                                <div>{{ $item->barang->kategori->nama ?? '-' }}</div>
                            @endforeach
                        @endif
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-700">
                        @if($first->jenis === 'Masuk')
                            @foreach($first->details as $detail)
                                <div class="text-green-600 font-semibold">
                                    {{ $detail->jumlah }} {{ $detail->barang->satuan ?? '' }}
                                </div>
                            @endforeach
                        @else
                            @foreach($group as $item)
                                <div class="text-red-600 font-semibold">
                                    {{ $item->jumlah }} {{ $item->barang->satuan ?? '' }}
                                </div>
                            @endforeach
                        @endif
                    </td>
                    <!-- <td class="px-4 py-2 text-sm text-gray-700">{{ $first->keterangan ?? '-' }}</td> -->
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-4 text-center text-gray-700 italic">
                        Tidak ada data transaksi pada rentang tanggal ini
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    @elseif($jenis === 'semua_bmn')
    <div class="bg-white rounded-lg shadow-sm p-4">
        <h3 class="text-lg font-bold mb-4 text-gray-800">
            <i class="fas fa-file-alt mr-3"></i>Laporan Semua Transaksi Barang BMN
        </h3>
        <p class="text-sm text-gray-500 mb-4">
            Periode:
            <span class="font-medium text-gray-700">
                {{ $dari ? \Carbon\Carbon::parse($dari)->translatedFormat('d F Y') : '-' }}
            </span>
            s/d
            <span class="font-medium text-gray-700">
                {{ $sampai ? \Carbon\Carbon::parse($sampai)->translatedFormat('d F Y') : '-' }}
            </span>
        </p>

        <div class="overflow-x-auto" style="box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);">
        <table class="w-full rounded-lg overflow-hidden">
            <thead class="bg-gray-200 border-b border-gray-300">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider w-8">No</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kode Barang</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama Barang</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Jumlah</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Peminta</th>
                    <!-- <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Keperluan</th> -->
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-300 border-b border-gray-300">
                @php
                    $grouped = $laporan->groupBy('pengajuan_id');
                @endphp

                @forelse($grouped as $i => $items)
                @php
                    $firstItem = $items->first();
                @endphp
                <tr class="hover:bg-gray-100">
                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-700">{{ $loop->iteration }}</td>
                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-700">{{ \Carbon\Carbon::parse($firstItem->pengajuanBMN->tanggal_proses)->format('d M Y, H:i') }}</td>
                    <td class="px-6 py-2 text-sm text-gray-700">
                        @foreach($items as $item)
                            <div>{{ $item->barang->kode_barang ?? '-' }}</div>
                        @endforeach
                    </td>
                    <td class="px-6 py-2 text-sm text-gray-700">
                        @foreach($items as $item)
                            <div>{{ $item->barang->nama_barang ?? '-' }} ({{ $item->barang->merk_type ?? '-' }})</div> 
                        @endforeach
                    </td>
                    <td class="px-6 py-2 text-sm text-red-600 font-semibold">
                        @foreach($items as $item)
                            <div>
                                {{ $item->jumlah_disetujui }} {{ $item->barang->satuan ?? '' }}
                            </div>
                        @endforeach
                    </td>
                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-700">{{ $firstItem->pengajuanBMN->user->name ?? '-' }}</td>
                    <!-- <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-700">{{ $firstItem->pengajuan->keperluan ?? '-' }}</td> -->
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-4 text-center text-gray-700 italic">
                        Tidak ada data barang keluar pada rentang tanggal ini
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    @elseif($jenis === 'barang_masuk')
    <div class="bg-white rounded-lg shadow-sm p-4">
        <h3 class="text-lg font-bold mb-4 text-gray-800">
            <i class="fas fa-file-alt mr-3"></i>Laporan Barang Persediaan Masuk
        </h3>
        <p class="text-sm text-gray-500 mb-4">
            Periode:
            <span class="font-medium text-gray-700">
                {{ $dari ? \Carbon\Carbon::parse($dari)->translatedFormat('d F Y') : '-' }}
            </span>
            s/d
            <span class="font-medium text-gray-700">
                {{ $sampai ? \Carbon\Carbon::parse($sampai)->translatedFormat('d F Y') : '-' }}
            </span>
        </p>

        <div class="overflow-x-auto" style="box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);">
        <table class="w-full rounded-lg overflow-hidden">
            <thead class="bg-gray-200 border-b border-gray-300">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider w-8">No</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kode Barang</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama Barang</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Jumlah</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Harga Total</th>
                    <!-- <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Keterangan</th> -->
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-300 border-b border-gray-300">
                @forelse($laporan as $i => $item)
                <tr class="hover:bg-gray-100">
                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-700">{{ $i + 1 }}</td>
                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-700">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y, H:i') }}</td>
                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-700">
                        @foreach($item->details as $detail)
                            <div>{{ $detail->barang->kode_barang }}</div>
                        @endforeach
                    </td>
                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-700">
                        @foreach($item->details as $detail)
                            <div>{{ $detail->barang->nama_barang }}</div>
                        @endforeach
                    </td>
                    <td class="px-6 py-2 whitespace-nowrap text-sm text-green-600 font-semibold">
                        @foreach($item->details as $detail)
                            <div>
                                {{ $detail->jumlah }} {{ $detail->barang->satuan ?? '' }}
                            </div>
                        @endforeach
                    </td>
                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-700 font-semibold">
                        @foreach($item->details as $detail)
                            <div>
                                {{ $detail->harga_total ? 'Rp. ' . number_format($detail->harga_total,0,',','.') : 'Rp. -' }}
                            </div>
                        @endforeach
                    </td>
                    <!-- <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-700">{{ $item->keterangan ?? '-' }}</td> -->
                </tr>
                @empty
                    {{-- 🔴 JIKA DATA KOSONG --}}
                    <tr>
                        <td colspan="6" class="px-4 py-4 text-center text-gray-700 italic">
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
        <h3 class="text-lg font-bold mb-4 text-gray-800">
            <i class="fas fa-file-alt mr-3"></i>Laporan Barang Persediaan Keluar
        </h3>
        <p class="text-sm text-gray-500 mb-4">
            Periode:
            <span class="font-medium text-gray-700">
                {{ $dari ? \Carbon\Carbon::parse($dari)->translatedFormat('d F Y') : '-' }}
            </span>
            s/d
            <span class="font-medium text-gray-700">
                {{ $sampai ? \Carbon\Carbon::parse($sampai)->translatedFormat('d F Y') : '-' }}
            </span>
        </p>

        <div class="overflow-x-auto" style="box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);">
        <table class="w-full rounded-lg overflow-hidden">
            <thead class="bg-gray-200 border-b border-gray-300">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider w-8">No</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kode Barang</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama Barang</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Jumlah</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Peminta</th>
                    <!-- <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Keperluan</th> -->
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-300 border-b border-gray-300">
                @php
                    $grouped = $laporan->groupBy('pengajuan_id');
                @endphp

                @forelse($grouped as $i => $items)
                @php
                    $firstItem = $items->first();
                @endphp
                <tr class="hover:bg-gray-100">
                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-700">{{ $loop->iteration }}</td>
                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-700">{{ \Carbon\Carbon::parse($firstItem->pengajuan->tanggal_proses)->format('d M Y, H:i') }}</td>
                    <td class="px-6 py-2 text-sm text-gray-700">
                        @foreach($items as $item)
                            <div>{{ $item->barang->kode_barang ?? '-' }}</div>
                        @endforeach
                    </td>
                    <td class="px-6 py-2 text-sm text-gray-700">
                        @foreach($items as $item)
                            <div>{{ $item->barang->nama_barang ?? '-' }}</div>
                        @endforeach
                    </td>
                    <td class="px-6 py-2 text-sm text-red-600 font-semibold">
                        @foreach($items as $item)
                            <div>
                                {{ $item->jumlah_disetujui }} {{ $item->barang->satuan ?? '' }}
                            </div>
                        @endforeach
                    </td>
                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-700">{{ $firstItem->pengajuan->user->name ?? '-' }}</td>
                    <!-- <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-700">{{ $firstItem->pengajuan->keperluan ?? '-' }}</td> -->
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-4 text-center text-gray-700 italic">
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
        <h3 class="text-lg font-bold mb-4 text-gray-800">
            <i class="fas fa-file-alt mr-3"></i>Laporan Stok Barang
        </h3>
        <p class="text-sm text-gray-500 mb-4">
            Periode:
            <span class="font-medium text-gray-700">
                {{ $dari ? \Carbon\Carbon::parse($dari)->translatedFormat('d F Y') : '-' }}
            </span>
            s/d
            <span class="font-medium text-gray-700">
                {{ $sampai ? \Carbon\Carbon::parse($sampai)->translatedFormat('d F Y') : '-' }}
            </span>
        </p>

        <div class="overflow-x-auto" style="box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);">
        <table class="w-full rounded-lg overflow-hidden">
            <thead class="bg-gray-200 border-b border-gray-300">
                <tr>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-700 uppercase tracking-wider w-8">No</th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-700 uppercase tracking-wider">Kode Barang</th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama Barang</th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-700 uppercase tracking-wider">Kategori</th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-700 uppercase tracking-wider">Stok Awal</th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-700 uppercase tracking-wider">Masuk</th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-700 uppercase tracking-wider">Keluar</th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-700 uppercase tracking-wider">Stok Akhir</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-300 border-b border-gray-300">
                @forelse($laporan as $i => $row)
                <tr class="hover:bg-gray-100">
                    <td class="px-6 py-2 text-sm text-gray-700">{{ $i + 1 }}</td>
                    <td class="px-6 py-2 text-sm text-gray-700">{{ $row->barang->kode_barang }}</td>
                    <td class="px-6 py-2 text-sm text-gray-700">{{ $row->barang->nama_barang }}</td>
                    <td class="px-6 py-2 text-sm text-gray-700">{{ $row->barang->kategori->nama ?? '-' }}</td>
                    <td class="px-6 py-2 text-sm text-gray-700">{{ $row->stok_awal }} {{ $row->barang->satuan }}</td>
                    <td class="px-6 py-2 text-sm text-green-600 font-semibold">+ {{ $row->masuk }}</td>
                    <td class="px-6 py-2 text-sm text-red-600 font-semibold">- {{ $row->keluar }}</td>
                    <td class="px-6 py-2 text-sm text-gray-700 font-bold">{{ $row->stok_akhir }} {{ $row->barang->satuan }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-4 text-center text-gray-700 italic">
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
