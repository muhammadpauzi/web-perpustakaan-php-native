<?php

require 'mysql.php';
require 'global.php';

guardAuth();

$sql = "SELECT *, tp.kode as tp_kode, tp.id as tp_id, op.nama as op_nama, an.nama as an_nama FROM transaksi_peminjaman as tp LEFT JOIN anggota as an ON an.id = tp.anggota_id LEFT JOIN users as op ON op.id = tp.operator_id";
$transaksiPeminjaman = query($sql)->fetch_all(MYSQLI_ASSOC);

$sql = "SELECT buku_transaksi_peminjaman.id as id, buku_transaksi_peminjaman.buku_id, buku_transaksi_peminjaman.transaksi_peminjaman_id, buku_transaksi_peminjaman.jml_buku, buku.judul, buku.id as buku_id FROM buku_transaksi_peminjaman LEFT JOIN buku ON buku.id = buku_transaksi_peminjaman.buku_id";
$bukuTransaksiPeminjaman = query($sql)->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi</title>
    <style>
        * {
            font-size: 10px;
        }

        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
        }

        .no-wrap {
            white-space: nowrap;
        }

        .col {
            text-transform: uppercase;
            text-align: center;
        }

        .title {
            font-size: 20px;
            font-weight: bolder;
            text-align: center;
        }
    </style>
</head>

<body>
    <h1 class="title">Laporan Transaksi Peminjaman Buku</h1>
    <table style="width: 100%;">
        <thead>
            <tr>
                <th class="col">#</th>
                <th class="col">ID</th>
                <th class="col">Kode</th>
                <th class="col">Anggota</th>
                <th class="col">Operator</th>
                <th class="col" class="no-wrap">Di pinjam pada</th>
                <th class="col" class="no-wrap">Jatuh tempo pada</th>
                <th class="col" class="no-wrap">Di kembalikan pada</th>
                <th class="col">Denda</th>
                <th class="col">Buku</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            <?php foreach ($transaksiPeminjaman as $tr) : ?>
                <tr>
                    <th scope="row"><?= $no++; ?></th>
                    <td>
                        <?= $tr['tp_id']; ?>
                    </td>
                    <td class="no-wrap"><?= $tr['tp_kode']; ?></td>
                    <td class="no-wrap">
                        <?= $tr['an_nama'] ?>
                    </td>
                    <td class="no-wrap"><?= $tr['op_nama'] ?></td>
                    <td class="no-wrap"><?= formatDate($tr['dipinjam_pada'], 'd/m/Y H:i'); ?></td>
                    <td class="no-wrap"><?= formatDate($tr['jatuh_tempo_pada'], 'd/m/Y'); ?></td>
                    <td class="no-wrap"><?= formatDate($tr['dikembalikan_pada'], 'd/m/Y H:i'); ?></td>
                    <td class="no-wrap">Rp. <?= formatRupiah($tr['denda']); ?></td>
                    <td>
                        <?php
                        $currentBuku = array_filter($bukuTransaksiPeminjaman, fn ($item) => $item['transaksi_peminjaman_id'] == $tr['tp_id']);
                        $ar = array_map(fn ($item) => sprintf("%s (%s)", $item['judul'], $item['jml_buku']), $currentBuku);
                        ?>
                        <?= join(', ', $ar) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>

<?php
require 'mysql-footer.php';
?>