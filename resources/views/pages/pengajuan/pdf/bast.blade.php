<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tanda Terima</title>

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
        }
        .nomor {
            text-align: center;
            margin-bottom: 20px;
            transform: translateX(-100px); /* geser "Nomor" ke kiri */
        }
        .tanggal {
            text-align: right;
            margin-bottom: 30px;
        }

        /* PARAGRAF */
        .paragraf {
            text-align: justify;
            text-indent: 1.25cm;
            margin-bottom: 20px;
        }
        /* TABEL */
        table.data {
            width: 100%;
            border-collapse: collapse;
            /* margin: 15px 0 20px; */
            margin-top: -10px;
            margin-bottom: 20px;
        }
        table.data th,
        table.data td {
            border: 1px solid #000;
            padding: 6px;
        }

        /* TTD */
        .ttd-row {
            width: 100%;
            margin-top: 50px;
        }

        /* Update CSS untuk Tanda Tangan */
        .table-ttd {
            width: 100%;
            margin-top: 50px;
            border: none;
        }
        .table-ttd td {
            width: 50%;
            text-align: center;
            vertical-align: top; /* Kunci agar teks mulai dari atas yang sama */
            border: none !important; /* Pastikan tidak ada border */
            padding: 0;
        }
        .space-ttd {
            height: 60px; /* Mengatur tinggi ruang tanda tangan */
        }

        /* Tambahan untuk memastikan sel tabel kosong tetap memiliki tinggi */
        .table-ttd td.spacer {
            height: 50px;
            line-height: 60px;
        }
        .nip-wrapper {
            text-align: left;
            display: inline-block;
            width: 170px; /* Sesuaikan lebar agar teks NIP rata kiri di bawah nama */
        }
        .ttd-bawah {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>

<body>
    @php \Carbon\Carbon::setLocale('id'); @endphp

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
    <div class="judul">TANDA TERIMA BARANG PERSEDIAAN</div>
    <div class="nomor">
        Nomor :
    </div>

    <!-- ISI -->
    <p class="paragraf">
        Telah diterima barang berupa persediaan sebagai hasil dari permintaan
        yang ditindaklanjuti, dengan rincian sebagai berikut:
    </p>

    <table class="data">
        <thead>
            <tr style="background-color: #b0ceff;">
                <th width="5%">No</th>
                <th>Nama Barang</th>
                <th width="10%">Jumlah</th>
                <th width="15%">Satuan</th>
                <th width="15%">Kondisi</th>
            </tr>
        </thead>
        <tbody>
        @php $no = 1; @endphp

        @foreach($pengajuan->details as $detail)
            @if($detail->status === 'Disetujui')
            <tr>
                <td align="center">{{ $no++ }}</td>
                <td>{{ $detail->barang->nama_barang }}</td>
                <td align="center">{{ $detail->jumlah_disetujui }}</td>
                <td align="center">{{ $detail->barang->satuan ?? '-' }}</td>
                <td align="center">Baik</td>
            </tr>
            @endif
        @endforeach
        </tbody>
    </table>

    <p class="paragraf">
        Barang tersebut diterima sesuai dengan jenis dan jumlahnya.
    </p>
    <p class="paragraf">
        Demikian tanda terima ini dibuat untuk dipergunakan sebagaimana mestinya.
    </p>

    <!-- TANDA TANGAN -->
    <table class="table-ttd">
        <tr>
            <td>
                Yang menerima,<br>
                Pelaksana Seksi {{ $pengajuan->user->seksi->seksi_singkat }}
            </td>
            <td>
                Yang menyerahkan,<br>
                Pelaksana Urusan Umum
            </td>
        </tr>
        <tr>
            <td class="spacer">&nbsp;</td>
            <td class="spacer">&nbsp;</td>
        </tr>
        <tr>
            <td>
                <strong><u>{{ $pengajuan->user->name }}</u></strong><br>
                <div class="nip-wrapper">NIP.</div>
            </td>
            <td>
                <strong><u>{{ $settings2->nama_staffbmn_tu ?? '-' }}</u></strong><br>
                <div>NIP. {{ $settings2->nip_staffbmn_tu ?? '-' }}</div>
            </td>
        </tr>
    </table>

    <div class="ttd-bawah">
        Mengetahui,<br>
        Kepala Urusan Umum
        <div class="space-ttd">&nbsp;</div> <strong><u>{{ $settings2->nama_kaurumum_tu ?? '-' }}</u></strong><br>
        <div>NIP. {{ $settings2->nip_kaurumum_tu ?? '-' }}</div>
    </div>
</body>
</html>
