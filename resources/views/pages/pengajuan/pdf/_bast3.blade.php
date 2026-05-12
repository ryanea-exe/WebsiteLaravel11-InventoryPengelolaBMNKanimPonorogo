<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Berita Acara Serah Terima</title>
    <style>
        @page {
            size: A4;
            margin: 20mm;
            margin-top: 10mm;
            margin-bottom: 5mm;
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
            transform: translateX(-70px); /* geser "Nomor" ke kiri */
        }
        /* PARAGRAF */
        .paragraf {
            text-align: justify;
            text-indent: 1.25cm;
        }
        /* TABEL */
        table.data {
            width: 100%;
            line-height: 0.9;
            border-collapse: collapse;
            /* margin: 15px 0 20px; */
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
            margin-top: 20px;
        }
        /* Update CSS untuk Tanda Tangan */
        .table-ttd {
            width: 100%;
            margin-top: 20px;
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
            height: 50px; /* Mengatur tinggi ruang tanda tangan */
        }
        /* Tambahan untuk memastikan sel tabel kosong tetap memiliki tinggi */
        .table-ttd td.spacer {
            height: 45px;
            line-height: 45px;
        }
        .nip-wrapper {
            text-align: left;
            display: inline-block;
            width: 200px; /* Sesuaikan lebar agar teks NIP rata kiri di bawah nama */
        }
        .identitas {
            margin-left: 20px;
            line-height: 0.9;
            margin-bottom: 15px;
        }
        .identitas td {
            padding: 2px 4px;
            vertical-align: top;
        }
        .identitas .label {
            width: 80px;
        }
        .identitas .titik {
            width: 10px;
            text-align: center;
        }
    </style>
</head>

@php
    \Carbon\Carbon::setLocale('id');

    function terbilang($angka) {
        $angka = abs($angka);
        $huruf = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];

        if ($angka < 12) {
            return $huruf[$angka];
        } elseif ($angka < 20) {
            return terbilang($angka - 10) . " Belas";
        } elseif ($angka < 100) {
            return terbilang($angka / 10) . " Puluh " . terbilang($angka % 10);
        } elseif ($angka < 200) {
            return "Seratus " . terbilang($angka - 100);
        } elseif ($angka < 1000) {
            return terbilang($angka / 100) . " Ratus " . terbilang($angka % 100);
        } elseif ($angka < 2000) {
            return "Seribu " . terbilang($angka - 1000);
        } elseif ($angka < 1000000) {
            return terbilang($angka / 1000) . " Ribu " . terbilang($angka % 1000);
        }

        return $angka;
    }

    $tanggal = \Carbon\Carbon::parse($pengajuan->tanggal_proses);
    $hari = $tanggal->translatedFormat('l');
    $tgl  = terbilang($tanggal->day);
    $bulan = $tanggal->translatedFormat('F');
    $tahun = terbilang($tanggal->year);
@endphp

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
                    Jl. Sekar Putih Timur, Tonatan Kec. Ponorogo, Kabupaten Ponorogo 63418<br>
                    Telepon (0352) 3574930, Faksimili -<br>
                    Laman: <a href="https://ponorogo.imigrasi.go.id/" style="color:#1a73e8; text-decoration:none;"> <u>www.ponorogo.imigrasi.go.id</u></a>, 
                    Pos-el: <a href="mailto:kanim_ponorogo@imigrasi.go.id" style="color:#1a73e8; text-decoration:none;"> <u>kanim_ponorogo@imigrasi.go.id</u></a>
                </div>
            </td>
        </tr>
    </table>
    <hr>

    <!-- JUDUL -->
    <div class="judul">BERITA ACARA SERAH TERIMA BARANG MILIK NEGARA</div>
    <div class="nomor">
        Nomor :
    </div>

    <!-- KALIMAT 1 -->
    <p class="paragraf">
        Pada hari ini {{ $hari }} tanggal <b>{{ $tgl }}</b> bulan <b>{{ $bulan }}</b> 
        Tahun <b>{{ $tahun }}</b> ({{ $tanggal->format('d-m-Y') }}), 
        yang bertanda tangan di bawah:
    </p>

    <!-- IDENTITAS -->
    <table class="identitas">
        <tr>
            <td width="20">I.</td>
            <td width="90">Nama</td>
            <td width="5">:</td>
            <td>{{ $settings2->nama_kaurumum_tu ?? '-' }}</td>
        </tr>
        <tr>
            <td></td>
            <td>NIP</td>
            <td>:</td>
            <td>{{ $settings2->nip_kaurumum_tu ?? '-' }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Jabatan</td>
            <td>:</td>
            <td>Kepala Urusan Umum</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>Kantor Imigrasi Kelas II Non TPI Ponorogo</td>
        </tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr>
            <td></td>
            <td colspan="3"><b>SELANJUTNYA DISEBUT SEBAGAI PIHAK PERTAMA</b></td>
        </tr>
    </table>
    <!-- <p><b>Selanjutnya disebut sebagai PIHAK PERTAMA</b></p> -->

    <table class="identitas">
        <tr>
            <td width="20">II.</td>
            <td width="90">Nama</td>
            <td width="5">:</td>
            <td>{{ $pengajuan->user->seksi->nama_kepala ?? '-' }}</td>
        </tr>
        <tr>
            <td></td>
            <td>NIP</td>
            <td>:</td>
            <td>{{ $pengajuan->user->seksi->nip_kepala ?? '-' }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Jabatan</td>
            <td>:</td>
            <td>Kepala {{ $pengajuan->user->seksi->seksi ?? '-' }}</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>Kantor Imigrasi Kelas II Non TPI Ponorogo</td>
        </tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr>
            <td></td>
            <td colspan="3"><b>SELANJUTNYA DISEBUT SEBAGAI PIHAK KEDUA</b></td>
        </tr>
    </table>
    <!-- <p><b>Selanjutnya disebut sebagai PIHAK KEDUA</b></p> -->

    <!-- KALIMAT 2 -->
    <p class="paragraf">
        Berdasarkan Peraturan Menteri Keuangan Nomor : 246/PMK.06/2014 tentang Tata Cara Pelaksanaan Penggunaan Barang Milik Negara, 
        bahwa Pihak Pertama menyerahkan Barang Milik Negara Kepada Pihak Kedua dan Pihak Kedua menerima penyerahan Barang Milik Negara
        dari Pihak Pertama, dengan rincian barang sebagai berikut:
    </p>

    <!-- TABEL BARANG -->
    <table class="data">
        <thead>
            <tr>
                <th width="5%">NO</th>
                <th>NAMA BARANG</th>
                <th width="15%">JUMLAH</th>
                <th width="15%">NUP</th>
                <th width="15%">KETERANGAN</th>
            </tr>
        </thead>
        <tbody>
        @php $no = 1; @endphp
        @foreach($pengajuan->details as $detail)
            @if($detail->status === 'Disetujui')
            <tr>
                <td align="center">{{ $no++ }}</td>
                <td>{{ $detail->barang->nama_barang }}</td>
                <td align="center">{{ $detail->jumlah }} {{ $detail->barang->satuan ?? '-' }}</td>
                <td align="center"></td>
                <td align="center">Baik</td>
            </tr>
            @endif
        @endforeach
        </tbody>
    </table>

    <!-- KALIMAT 3 -->
    <p class="paragraf">
        Demikianlah Berita Acara Serah Terima ini dibuat dengan sebenarnya berdasarkan kekuatan sumpah jabatan / sumpah PNS,
        untuk dapat dipergunakan sebagaimana mestinya dan ditandatangani pada tanggal tersebut di atas,
    </p>

    <!-- TANDA TANGAN -->
    <table class="table-ttd">
        <tr>
            <td>Pihak Kedua,</td>
            <td>Pihak Kesatu,</td>
        </tr>
        <tr>
            <td class="spacer">&nbsp;</td>
            <td class="spacer">&nbsp;</td>
        </tr>
        <tr>
            <td>
                <strong><u>{{ $pengajuan->user->seksi->nama_kepala ?? '-' }}</u></strong><br>
                <div class="nip-wrapper">NIP. {{ $pengajuan->user->seksi->nip_kepala ?? '-' }}</div>
            </td>
            <td>
                <strong><u>{{ $settings2->nama_kaurumum_tu ?? '-' }}</u></strong><br>
                <div class="nip-wrapper">NIP. {{ $settings2->nip_kaurumum_tu ?? '-' }}</div>
            </td>
        </tr>
    </table>
    <table class="table-ttd" style="margin-top:10px;">
        <tr>
            <td>Mengetahui,</td>
        </tr>
        <tr>
            <td>Kepala Sub Bagian Tata Usaha</td>
        </tr>
        <tr>
            <td class="spacer">&nbsp;</td>
        </tr>
        <tr>
            <td>
                <strong><u>{{ $settings2->nama_kasubbag_tu ?? '-' }}</u></strong><br>
                <div class="nip-wrapper">NIP. {{ $settings2->nip_kasubbag_tu ?? '-' }}</div>
            </td>
        </tr>
    </table>
</body>
</html>
