<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Semua Transaksi</title>

    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
        }
        th {
            background: #f2f2f2;
            text-align: center;
        }
        td {
            vertical-align: middle;
        }
    </style>
</head>

<body>
    @php
        \Carbon\Carbon::setLocale('id');
    @endphp
    
    <h3 align="center">LAPORAN SEMUA TRANSAKSI BARANG PERSEDIAAN</h3>
    <p>
        Periode: {{ $dari ? \Carbon\Carbon::parse($dari)->translatedFormat('d F Y') : '-' }}
        s/d {{ $sampai ? \Carbon\Carbon::parse($sampai)->translatedFormat('d F Y') : '-' }}
    </p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Jenis Transaksi</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @php
                $grouped = $laporan->groupBy(function($item) {
                    return $item->jenis === 'Keluar'
                        ? 'keluar_'.$item->pengajuan_id
                        : 'masuk_'.$item->id;
                });
            @endphp

            @forelse($grouped as $items)
            @php
                $first = $items->first();
            @endphp
            <tr>
                <td align="center">{{ $loop->iteration }}</td>
                <td>{{ \Carbon\Carbon::parse($first->tanggal)->format('d M Y, H:i') }}</td>
                <td>{{ $first->jenis }}</td>
                <td>
                    @if($first->jenis === 'Masuk')
                        @foreach($first->details as $detail)
                            <div>{{ $detail->barang->kode_barang ?? '-' }}</div>
                        @endforeach
                    @else
                        @foreach($items as $item)
                            <div>{{ $item->barang->kode_barang ?? '-' }}</div>
                        @endforeach
                    @endif
                </td>
                <td>
                    @if($first->jenis === 'Masuk')
                        @foreach($first->details as $detail)
                            <div>{{ $detail->barang->nama_barang ?? '-' }}</div>
                        @endforeach
                    @else
                        @foreach($items as $item)
                            <div>{{ $item->barang->nama_barang ?? '-' }}</div>
                        @endforeach
                    @endif
                </td>
                <td>
                    @if($first->jenis === 'Keluar')
                        @foreach($items as $item)
                            <div>{{ $item->jumlah }} {{ $item->barang->satuan ?? '' }}</div>
                        @endforeach
                    @else
                        @foreach($items as $item)
                            @foreach($item->details as $detail)
                                <div>{{ $detail->jumlah }} {{ $detail->barang->satuan ?? '' }}</div>
                            @endforeach
                        @endforeach
                    @endif
                </td>
            </tr>

            @empty
            <tr>
                <td colspan="6" align="center">
                    Tidak ada data transaksi pada periode ini
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
