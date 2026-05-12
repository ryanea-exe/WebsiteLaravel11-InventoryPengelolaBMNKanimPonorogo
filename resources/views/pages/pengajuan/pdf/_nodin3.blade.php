<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota Dinas</title>

    <style>
        @page {
            size: A4;
            margin: 25mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.25;
            color: #000;
        }

        .kop {
            text-align: center;
        }

        .kop .instansi {
            font-weight: bold;
            font-size: 11pt;
        }

        .kop .unit {
            font-weight: bold;
            font-size: 11pt;
        }

        .kop .alamat {
            font-size: 10pt;
        }

        hr {
            border: 1.5px solid #000;
            /* margin: 10px 0 20px; */
            margin-bottom: 30px;
        }

        .judul {
            text-align: center;
            font-weight: bold;
            font-size: 11pt;
            text-transform: uppercase;
        }

        /* NOMOR */
        .nomor {
            text-align: center;
            margin-bottom: 30px;
        }

        .nomor span {
            display: inline-block;
            transform: translateX(-60px); /* geser "Nomor" ke kiri */
        }

        /* INFO TABLE */
        table.info {
            margin-bottom: 20px;
        }

        table.info td {
            padding: 2px 0;
            vertical-align: top;
        }

        table.info .label {
            width: 18%;
        }

        table.info .separator {
            width: 5%;
            text-align: center;
            padding-left: 20px;
        }

        /* PARAGRAF */
        .paragraf {
            text-indent: 1.25cm;
            text-align: justify;
            margin-bottom: 20px;
        }

        /* TABEL DATA */
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-top: -10px;
            margin-bottom: 20px;
        }

        table.data th,
        table.data td {
            border: 1px solid #000;
            padding: 6px;
        }

        table.data th {
            text-align: center;
            font-weight: bold;
        }

        /* TANDA TANGAN */
        .ttd {
            width: 45%;
            float: right;
            text-align: center;
            margin-top: 30px;
        }

        .nip {
            display: inline-block;
            /* transform: translateX(-50px); */ /* geser "NIP." ke kiri */
        }
    </style>
</head>

<body>
    @php 
        \Illuminate\Support\Carbon::setLocale('id'); 
    @endphp

    <!-- KOP -->
    <div class="kop">
        <div class="instansi">
            KEMENTERIAN IMIGRASI DAN PEMASYARAKATAN REPUBLIK INDONESIA
        </div>
        <div class="unit">
            KANTOR IMIGRASI KELAS II NON TPI PONOROGO
        </div>
        <!--
        <div class="alamat">
            Jl. Sekar Putih Timur, Tonatan Kec. Ponorogo, Kabupaten Ponorogo, Jawa Timur 63418<br>
            Telepon (0352) 3574930 | Pos-el: kanim_ponorogo@imigrasi.go.id
        </div>
        -->
    </div>
    <hr>

    <!-- JUDUL -->
    <div class="judul">NOTA DINAS</div>
    <!-- NOMOR -->
    <div class="nomor">
        <span>Nomor :</span>
    </div>

    <!-- INFO -->
    <table class="info">
        <tr>
            <td class="label">Yth</td>
            <td class="separator">:</td>
            <td>Kepala Sub Bagian Tata Usaha</td>
        </tr>
        <tr>
            <td class="label">Dari</td>
            <td class="separator">:</td>
            <td>Kepala {{ $pengajuan->user->seksi->seksi ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Hal</td>
            <td class="separator">:</td>
            <td>Permintaan Persediaan</td>
        </tr>
        <tr>
            <td class="label">Lampiran</td>
            <td class="separator">:</td>
            <td>-</td>
        </tr>
        <tr>
            <td class="label">Tanggal</td>
            <td class="separator">:</td>
            <td>{{ \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->translatedFormat('d F Y') }}</td>
        </tr>
    </table>

    <!-- ISI -->
    <p class="paragraf">
        Dalam rangka pelaksanaan tugas pelayanan keimigrasian, bersama ini disampaikan permohonan barang persediaan sebagai berikut:
    </p>

    <!-- TABEL -->
    <table class="data">
        <tr>
            <th width="5%">No</th>
            <th>Jenis Persediaan</th>
            <th width="10%">Jumlah</th>
            <th width="15%">Satuan</th>
        </tr>
        @foreach($pengajuan->details as $i => $detail)
        <tr>
            <td align="center">{{ $i + 1 }}</td>
            <td>{{ $detail->barang->nama_barang }}</td>
            <td align="center">{{ $detail->jumlah }}</td>
            <td align="center">{{ $detail->barang->satuan ?? '-' }}</td>
        </tr>
        @endforeach
    </table>

    <p class="paragraf">
        Demikian permohonan ini kami sampaikan, atas perkenaan Bapak/Ibu kami sampaikan terima kasih.
    </p>

    <!-- TANDA TANGAN -->
    <div class="ttd">
        <!--
        Ponorogo, {{ \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->translatedFormat('d F Y') }}<br>
        Kepala {{ $pengajuan->user->seksi->seksi ?? '-' }}<br><br><br><br>
        --> <br><br><br><br>
        <strong><u>{{ $pengajuan->user->seksi->nama_kepala ?? '-' }}</u></strong><br>
        <!-- <span class="nip">NIP. {{ $pengajuan->user->seksi->nip_kepala ?? '-' }}</span> -->
    </div>

</body>
</html>
