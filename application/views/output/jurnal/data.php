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
    </style>

</head>

<body>

    <table width="100%">
        <tr>
            <td style="text-align: center;">
                <h3><?= $title ?></h3>
            </td>
        </tr>
    </table>

    <table width="100%" class="tabel1">
        <thead>
            <tr>
                <th>#</th>
                <th>Tanggal</th>
                <th>Nama Pengguna</th>
                <th>Keterangan</th>
                <th>Debet</th>
                <th>Kredit</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; ?>
            <?php foreach ($data1 as $row) : ?>
                <tr>
                    <th scope="row"><?= $i++ ?></th>
                    <td><?= date('d-m-Y', $row->tanggal) ?></td>
                    <td><?= $row->pengguna_nama ?></td>
                    <td><?= $row->keterangan ?></td>
                    <td><?= number_format($row->debet, 2) ?></td>
                    <td><?= number_format($row->kredit, 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p style="font-size:x-small;text-align:right">Dicetak pada: <?= tanggal_indonesia(date('Y-m-d')) ?></p>

</body>

</html>