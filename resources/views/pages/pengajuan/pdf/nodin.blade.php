<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Formulir Pengajuan</title>

    <style>
        @page {
            size: A4;
            margin: 20mm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.25;
            color: #000;
        }

        /* KOP */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
        }
        .kop-table td {
            vertical-align: middle;
        }
        .kop-text {
            text-align: center;
            line-height: 1.25;
        }
        .kop-text .instansi {
            font-weight: bold;
            font-size: 10pt;
        }
        .kop-text .instansi2 {
            font-size: 10pt;
        }
        .kop-text .alamat {
            font-size: 9pt;
        }
        hr {
            border: 1.5px solid #000;
            /* margin: 10px 0 20px; */
        }
        .judul {
            text-align: center;
            font-weight: bold;
            font-size: 11pt;
            margin-bottom: 60px;
        }
        .tanggal {
            text-align: right;
            margin-bottom: 30px;
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

    <!-- KOP SURAT -->
    <table class="kop-table">
        <tr>
            <td width="95">
                <img src="{{ public_path('images/logo-kemenimipas.png') }}" width="90">
            </td>
            <td class="kop-text">
                <div class="instansi2">KEMENTERIAN IMIGRASI DAN PEMASYARAKATAN REPUBLIK INDONESIA</div>
                <div class="instansi">DIREKTORAT JENDERAL IMIGRASI</div>
                <div class="instansi">KANTOR WILAYAH JAWA TIMUR</div>
                <div class="instansi" style="font-size: 11pt;">KANTOR IMIGRASI KELAS II NON TPI PONOROGO</div>
                <div class="alamat">
                    Jl. Sekar Putih Timur, Kel. Tonatan Kec. Ponorogo Kab. Ponorogo 63418<br>
                    Telepon (0352) 3574930, Faksimili -<br>
                    Laman: <a href="https://ponorogo.imigrasi.go.id/" style="color:#1a73e8; text-decoration:none;"> <u>www.ponorogo.imigrasi.go.id</u></a>, 
                    Pos-el: <a href="mailto:kanim_ponorogo@imigrasi.go.id" style="color:#1a73e8; text-decoration:none;"> <u>kanim_ponorogo@imigrasi.go.id</u></a>
                </div>
            </td>
        </tr>
    </table>
    <hr>

    <!-- TANGGAL -->
    <div class="tanggal">
        Ponorogo, {{ \Carbon\Carbon::parse($pengajuan->tanggal_proses)->translatedFormat('d F Y') }}
    </div>

    <!-- JUDUL -->
    <div class="judul">FORMULIR PERMOHONAN BARANG PERSEDIAAN</div>

    <!-- TABEL -->
    <table class="data">
        <tr style="background-color: #b0ceff;">
            <th width="5%">No</th>
            <th>Nama Barang</th>
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
        Demikian pengajuan ini untuk dapat diproses sebagaimana mestinya.
    </p>

    <!-- TANDA TANGAN -->
    <div class="ttd">
        Pemohon,
        <br><br><br><br><br>
        <strong><u>{{ $pengajuan->user->name ?? '-' }}</u></strong><br>
    </div>

</body>
</html>
