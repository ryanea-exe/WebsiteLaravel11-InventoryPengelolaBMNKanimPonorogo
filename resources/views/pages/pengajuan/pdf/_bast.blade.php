<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Berita Acara Serah Terima</title>

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
            line-height: 1.3;
        }

        .kop-text .instansi {
            font-weight: bold;
            font-size: 10pt;
        }

        .kop-text .alamat {
            font-size: 9pt;
        }

        hr {
            border: 1.5px solid #000;
            /* margin: 10px 0 20px; */
        }

        .tanggal {
            text-align: right;
            margin-bottom: 30px;
        }

        .judul {
            text-align: center;
            font-weight: bold;
            font-size: 12pt;
        }

        .nomor {
            text-align: center;
            margin-bottom: 30px;
            transform: translateX(-70px); /* geser "Nomor" ke kiri */
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
            height: 60px;
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
            <td width="90">
                <img src="{{ public_path('images/logo-kemenimipas.png') }}" width="90">
            </td>
            <td class="kop-text">
                <div class="instansi">KEMENTERIAN IMIGRASI DAN PEMASYARAKATAN REPUBLIK INDONESIA</div>
                <div class="instansi">DIREKTORAT JENDERAL IMIGRASI</div>
                <div class="instansi">KANTOR WILAYAH JAWA TIMUR</div>
                <div class="instansi">KANTOR IMIGRASI KELAS II NON TPI PONOROGO</div>
                <div class="alamat">
                    Jl. Sekar Putih Timur, Tonatan Kec. Ponorogo, Kabupaten Ponorogo, Jawa Timur 63418<br>
                    Telepon (0352) 3574930 | Pos-el: kanim_ponorogo@imigrasi.go.id
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
            <tr>
                <th width="5%">No</th>
                <th>Jenis Persediaan</th>
                <th width="10%">Jumlah</th>
                <th width="15%">Satuan</th>
                <th width="15%">Kondisi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td align="center">1</td>
                <td>{{ $pengajuan->barang->nama_barang }}</td>
                <td align="center">{{ $pengajuan->jumlah }}</td>
                <td align="center">{{ $pengajuan->barang->satuan ?? '-' }}</td>
                <td align="center">Baik</td>
            </tr>
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
                Pelaksana {{ $pengajuan->user->seksi->seksi }}
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
                <strong><u>Rizki Karima Vito Chrismastanto</u></strong><br>
                <div class="nip-wrapper">NIP.</div>
            </td>
        </tr>
    </table>

    <div class="ttd-bawah">
        Mengetahui,<br>
        Kepala Urusan Umum
        <div class="space-ttd">&nbsp;</div> <strong><u>Gilang Tri Parama Yudha</u></strong><br>
        <div class="nip-wrapper">NIP.</div>
    </div>
</body>
</html>
