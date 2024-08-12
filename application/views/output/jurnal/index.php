<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>

    <style type="text/css">
        * {
            font-family: Verdana, Arial, sans-serif;
        }

        table {
            font-size: x-small;
        }

        tfoot tr td {
            font-weight: bold;
            font-size: x-small;
        }

        .gray {
            background-color: lightgray;
        }

        .tabel1 {
            border-collapse: collapse;
            width: 100%;
        }

        .tabel1 td,
        .tabel1 th {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
    </style>

</head>

<body>

    <table width="100%">
        <tr>
            <td align="top">
                <!-- Optional logo or image -->
            </td>
            <td align="right">
                <h3><?= $title ?></h3>
                <pre></pre>
            </td>
        </tr>
    </table>

    <table width="100%">
        <tr>
            <td><strong>Dari:</strong> Admin Jurnal Umum</td>
            <td><strong>Tanggal:</strong> <?= tanggal_indonesia(date('Y-m-d')) ?></td>
        </tr>
    </table>

    <br />

    <table width="100%" class="tabel1">
        <thead style="background-color: lightgray;">
            <tr>
                <th style="text-align: center;">#</th>
                <th style="text-align: center;">Tanggal</th>
                <th style="text-align: center;">Nama Pengguna</th>
                <th style="text-align: center;">Keterangan</th>
                <th style="text-align: center;">Debet</th>
                <th style="text-align: center;">Kredit</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1;
            foreach ($data1 as $row) : ?>
                <tr>
                    <th scope="row" style="text-align: center;"><?= $i++ ?></th>
                    <td style="text-align: center;"><?= date('d-m-Y', $row->tanggal) ?></td>
                    <td style="text-align: center;"><?= $row->pengguna_nama ?></td>
                    <td style="text-align: center;"><?= $row->keterangan ?></td>
                    <td style="text-align: right;"><?= number_format($row->debet, 2) ?></td>
                    <td style="text-align: right;"><?= number_format($row->kredit, 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>

        <tfoot>
            <tr>
                <td colspan="4" style="text-align: right;">Total Jurnal</td>
                <td style="text-align: right;" class="gray"><?= number_format(array_sum(array_column($data1, 'debet')), 2) ?></td>
                <td style="text-align: right;" class="gray"><?= number_format(array_sum(array_column($data1, 'kredit')), 2) ?></td>
            </tr>
        </tfoot>
    </table>

    <p style="font-size:x-small;text-align:right">Dicetak pada: <?= tanggal_indonesia(date('Y-m-d')) ?></p>

</body>

</html>