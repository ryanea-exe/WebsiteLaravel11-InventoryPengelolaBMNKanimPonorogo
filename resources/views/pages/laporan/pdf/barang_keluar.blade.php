<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Barang Keluar</title>

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

    <h3 align="center">LAPORAN BARANG PERSEDIAAN KELUAR</h3>
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
                <th>Peminta</th>
            </tr>
        </thead>
        <tbody>
            @php
                $grouped = $laporan->groupBy('pengajuan_id');
            @endphp

            @forelse($grouped as $items)
            @php
                $first = $items->first();
            @endphp
            <tr>
                <td align="center">{{ $loop->iteration }}</td>
                <td>{{ \Carbon\Carbon::parse($first->pengajuan->tanggal_proses)->format('d M Y, H:i') }}</td>
                <td>
                    @foreach($items as $item)
                        <div>{{ $item->barang->kode_barang ?? '-' }}</div>
                    @endforeach
                </td>
                <td>
                    @foreach($items as $item)
                        <div>{{ $item->barang->nama_barang ?? '-' }}</div>
                    @endforeach
                </td>
                <td>
                    @foreach($items as $item)
                        <div>{{ $item->jumlah }} {{ $item->barang->satuan ?? '' }}</div>
                    @endforeach
                </td>
                <td>{{ $first->pengajuan->user->name ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" align="center">
                    Tidak ada data barang keluar pada periode ini
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
