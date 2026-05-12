<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Berita Acara Serah Terima</title>

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

        .isi {
            text-align: justify;
            line-height: 1.8;
        }

        .ttd-table td {
            text-align: center;
            padding-top: 60px;
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
        BERITA ACARA SERAH TERIMA
    </div>

    <!-- ISI -->
    <div class="isi">
        <p>
            Pada hari ini {{ now()->translatedFormat('l') }},
            tanggal {{ now()->format('d') }}
            bulan {{ now()->translatedFormat('F') }}
            tahun {{ now()->format('Y') }},
            telah dilakukan serah terima barang dengan rincian sebagai berikut:
        </p>

        <table border="1" cellpadding="6" cellspacing="0">
            <tr>
                <td width="30%">Nama Barang</td>
                <td>{{ $pengajuan->barang->nama_barang }}</td>
            </tr>
            <tr>
                <td>Kode Barang</td>
                <td>{{ $pengajuan->barang->kode_barang }}</td>
            </tr>
            <tr>
                <td>Jumlah</td>
                <td>{{ $pengajuan->jumlah }}</td>
            </tr>
        </table>

        <p>
            Dengan ditandatanganinya berita acara ini, maka barang tersebut telah
            diterima dengan baik dan dalam kondisi layak digunakan.
        </p>
    </div>

    <!-- TANDA TANGAN -->
    <table class="ttd-table">
        <tr>
            <td width="50%">
                Yang Menyerahkan
                <br><br><br><br>
                <strong><u>NAMA PETUGAS</u></strong><br>
                NIP. 19XXXXXXXX
            </td>
            <td width="50%">
                Yang Menerima
                <br><br><br><br>
                <strong><u>{{ $pengajuan->user->name }}</u></strong><br>
                NIP. -
            </td>
        </tr>
    </table>

</body>
</html>
