<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Stok Barang</title>

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

    <h3 align="center">LAPORAN STOK BARANG PERSEDIAAN</h3>
    <p>
        Periode: {{ $dari ? \Carbon\Carbon::parse($dari)->translatedFormat('d F Y') : '-' }}
        s/d {{ $sampai ? \Carbon\Carbon::parse($sampai)->translatedFormat('d F Y') : '-' }}
    </p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Stok Awal</th>
                <th>Masuk</th>
                <th>Keluar</th>
                <th>Stok Akhir</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporan as $i => $row)
            <tr>
                <td align="center">{{ $i + 1 }}</td>
                <td>{{ $row->barang->kode_barang }}</td>
                <td>{{ $row->barang->nama_barang }}</td>
                <td>{{ $row->barang->kategori->nama ?? '-' }}</td>
                <td align="right">
                    {{ $row->stok_awal }} {{ $row->barang->satuan }}
                </td>
                <td align="right">
                    {{ $row->masuk }} {{ $row->barang->satuan }}
                </td>
                <td align="right">
                    {{ $row->keluar }} {{ $row->barang->satuan }}
                </td>
                <td align="right">
                    <strong>{{ $row->stok_akhir }} {{ $row->barang->satuan }}</strong>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" align="center">
                    Tidak ada data stok barang pada periode ini
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
