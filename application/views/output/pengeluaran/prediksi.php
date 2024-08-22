<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title) ?></title>

    <style type="text/css">
        * {
            font-family: Verdana, Arial, sans-serif;
        }

        table {
            font-size: x-small;
            border-collapse: collapse;
            width: 100%;
        }

        tfoot tr td {
            font-weight: bold;
            font-size: x-small;
        }

        .gray {
            background-color: lightgray;
        }

        .tabel1 td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }

        .tabel1 th {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
            background-color: lightgray;
        }

        .tableKop td {
            padding: 8px;
            text-align: center;
        }

        .tableKop th {
            padding: 8px;
            text-align: center;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h5 {
            margin: 0;
        }

        .footer {
            font-size: x-small;
            text-align: right;
            margin-top: 40px;
        }

        .signature {
            margin-top: 40px;
            text-align: right;
            font-size: small;
        }
    </style>
</head>

<body>
    <table class="tableKop" style="border-color: black !important; border-bottom-style: double !important; line-height: 1.2;">
        <tr>
            <td style="width: 15% !important;" class="text-start">
                <img src="<?= base_url('assets/img/LogoPPTQ.png'); ?>" width="150">
            </td>
            <td style="text-align: center;">
                <span style="font-size: 15px;">SISTEM INFORMASI KEUANGAN</span><br>
                <span style="font-size: 15px;"><?= strtoupper(htmlspecialchars($title)) ?></span><br>
                <span style="font-size: 15px;"><?= base_url() ?></span>
            </td>
            <td style="width: 15% !important;" class="text-end text-right">
                <img src="<?= base_url('assets/img/LogoSAHAL.png'); ?>" width="150">
            </td>
        </tr>
    </table>
    <br>
    <table width="100%" class="tabel1">
        <thead>
            <tr>
                <th style="font-size: x-large;">Prediksi Pengeluaran bulan <?= tanggal_indonesia2(date('Y-m', strtotime('-1 month'))) ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="font-size:xx-large;"><?= money($prediksi) ?></td>
            </tr>
        </tbody>
    </table>

    <div class="signature">
        <p><?= tanggal_indonesia(date('Y-m-d')) ?><br>
            Kepala Pesantren<br><br><br><br><br>
            <span>Shelin</span>
        </p>
    </div>
</body>

</html>