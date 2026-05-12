<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Barang Masuk</title>

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

    <h3 align="center">LAPORAN BARANG PERSEDIAAN MASUK</h3>
    <p>
        Periode: {{ $dari ? \Carbon\Carbon::parse($dari)->translatedFormat('d F Y') : '-' }}
        s/d {{ $sampai ? \Carbon\Carbon::parse($sampai)->translatedFormat('d F Y') : '-' }}
    </p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Harga Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporan as $i => $item)
            <tr>
                <td align="center">{{ $i + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y, H:i') }}</td>
                <td>
                    @foreach($item->details as $detail)
                        <div>{{ $detail->barang->kode_barang ?? '-' }}</div>
                    @endforeach
                </td>
                <td>
                    @foreach($item->details as $detail)
                        <div>{{ $detail->barang->nama_barang ?? '-' }}</div>
                    @endforeach
                </td>
                <td>
                    @foreach($item->details as $detail)
                        <div>
                            {{ $detail->jumlah }} {{ $detail->barang->satuan ?? '' }}
                        </div>
                    @endforeach
                </td>
                <td>
                    @php
                        $total = $item->details->sum('harga_total');
                    @endphp
                    Rp. {{ number_format($total,0,',','.') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" align="center">
                    Tidak ada data barang masuk pada periode ini
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
