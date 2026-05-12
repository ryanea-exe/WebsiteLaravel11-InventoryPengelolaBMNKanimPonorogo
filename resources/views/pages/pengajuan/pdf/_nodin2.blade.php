<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota Dinas</title>

    <style>
        body {
            font-family: "Times New Roman", serif;
            font-size: 12px;
            color: #000;
            margin: 30px;
        }

        .kop hr {
            border: 2px solid #000;
            margin: 6px 0 20px 0;
        }

        .judul {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .info td {
            padding: 2px 0;
            vertical-align: top;
        }

        .isi {
            text-align: justify;
            line-height: 1.8;
            margin-top: 10px;
        }

        .ttd {
            width: 40%;
            float: right;
            text-align: center;
            margin-top: 40px;
        }

        @media print {
            body {
                margin: 25mm;
            }
        }
    </style>
</head>
<body>

    <!-- KOP SURAT -->
    <div class="kop">
        <table>
            <tr>
                <td width="90" align="center">
                    <img src="{{ public_path('images/logo-kemenimipas.png') }}" width="80">
                </td>
                <td align="center">
                    <div style="font-weight:bold; font-size:14px;">
                        KEMENTERIAN IMIGRASI DAN PEMASYARAKATAN
                    </div>
                    <div style="font-weight:bold; font-size:13px;">
                        KANTOR IMIGRASI KELAS II NON TPI PONOROGO
                    </div>
                    <div style="font-size:11px;">
                        Jl. Ir. H. Juanda No. 38 Ponorogo Jawa Timur 63419<br>
                        Telepon (0352) xxxx | Email: imigrasi.ponorogo@kemenkumham.go.id
                    </div>
                </td>
            </tr>
        </table>
        <hr>
    </div>

    <!-- JUDUL -->
    <div class="judul">
        NOTA DINAS
    </div>

    <!-- INFO NOTA DINAS -->
    <table class="info">
        <tr>
            <td width="20%">Nomor</td>
            <td width="2%">:</td>
            <td>{{ $pengajuan->nomor_nodin ?? '-' }}</td>
        </tr>
        <tr>
            <td>Sifat</td>
            <td>:</td>
            <td>Penting</td>
        </tr>
        <tr>
            <td>Lampiran</td>
            <td>:</td>
            <td>-</td>
        </tr>
        <tr>
            <td>Hal</td>
            <td>:</td>
            <td>Permintaan Barang</td>
        </tr>
    </table>

    <!-- ISI -->
    <div class="isi">
        <p>
            Sehubungan dengan kebutuhan operasional pada
            <strong>Kantor Imigrasi Kelas II Non TPI Ponorogo</strong>,
            bersama ini kami mengajukan permintaan barang sebagai berikut:
        </p>

        <table border="1" cellpadding="6" cellspacing="0">
            <tr align="center">
                <th width="5%">No</th>
                <th>Nama Barang</th>
                <th width="25%">Kode Barang</th>
                <th width="15%">Jumlah</th>
            </tr>
            <tr align="center">
                <td>1</td>
                <td>{{ $pengajuan->barang->nama_barang }}</td>
                <td>{{ $pengajuan->barang->kode_barang }}</td>
                <td>{{ $pengajuan->jumlah }}</td>
            </tr>
        </table>

        <p>
            Demikian Nota Dinas ini disampaikan, atas perhatian dan kerja samanya
            diucapkan terima kasih.
        </p>
    </div>

    <!-- TANDA TANGAN -->
    <div class="ttd">
        Ponorogo, {{ \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->translatedFormat('d F Y') }}<br>
        Kepala Kantor Imigrasi<br>
        Kelas II Non TPI Ponorogo
        <br><br><br><br>
        <strong><u>NAMA PEJABAT</u></strong><br>
        NIP. 19XXXXXXXX
    </div>

</body>
</html>
