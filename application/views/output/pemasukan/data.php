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

        .header h3 {
            margin: 0;
        }

        .header p {
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
    <table class="tableKop" style="border-color: black !important;border-bottom-style:double !important;line-height:1.2;">
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
    <div class="header">
        <?php if ($start_date && $end_date): ?>
            <h5><strong>Periode: <?= tanggal_indonesia($start_date) ?> s/d <?= tanggal_indonesia($end_date) ?></strong></h5>
        <?php else: ?>
            <h5><strong>Periode: Semua Tanggal</strong></h5>
        <?php endif; ?>
    </div>
    <table width="100%" class="tabel1">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Sumber</th>
                <th>Keterangan</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <?php if (isset($dataTransaksi) && is_array($dataTransaksi) && !empty($dataTransaksi)): ?>
                <?php $i = 1; ?>
                <?php foreach ($dataTransaksi as $row): ?>
                    <tr>
                        <th scope="row"><?= $i++ ?></th>
                        <td><?= tanggal_indonesia(date('Y-m-d', $row['tanggal'])) ?></td>
                        <td><?= htmlspecialchars($row['sumber']) ?></td>
                        <td><?= htmlspecialchars($row['keterangan']) ?></td>
                        <td><?= number_format($row['jumlah'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center;">Data tidak tersedia</td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align: right;">Total</td>
                <td><?= number_format($totalJumlah, 0, ',', '.') ?></td>
            </tr>
        </tfoot>
    </table>

    <div class="signature">
        <p><?= tanggal_indonesia(date('Y-m-d')) ?><br>
            Kepala Pesantren<br><br><br><br><br>
            <span>Teh Shellin</span>
        </p>
    </div>
</body>

</html>