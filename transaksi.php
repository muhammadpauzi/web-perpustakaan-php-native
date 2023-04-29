<?php

require 'mysql.php';
require 'global.php';
require 'templates/dashboard/header.php';
require 'templates/dashboard/navbar.php';

guardAuth();
if (isset($_POST['hapus'])) {
    $id = $_POST['id'];

    query("DELETE FROM transaksi_peminjaman WHERE id='$id'");
    query("DELETE FROM buku_transaksi_peminjaman WHERE transaksi_peminjaman_id='$id'");

    setFlash('alert', 'success', 'Data transaksi peminjaman buku berhasil dihapus');
}

$sqlQuerySearch = '';

$keyword = isset($_POST['keyword']) ? $_POST['keyword'] : '';

if (isset($_POST['search']) && $keyword) {
    $sqlQuerySearch = " WHERE kode LIKE '%$keyword%'";
}

$sql = "SELECT *, tp.kode as tp_kode, tp.id as tp_id, op.nama as op_nama, an.nama as an_nama FROM transaksi_peminjaman as tp LEFT JOIN anggota as an ON an.id = tp.anggota_id LEFT JOIN users as op ON op.id = tp.operator_id" . $sqlQuerySearch;
$transaksiPeminjaman = query($sql)->fetch_all(MYSQLI_ASSOC);

$sql = "SELECT buku_transaksi_peminjaman.id as id, buku_transaksi_peminjaman.buku_id, buku_transaksi_peminjaman.transaksi_peminjaman_id, buku_transaksi_peminjaman.jml_buku, buku.judul, buku.id as buku_id FROM buku_transaksi_peminjaman LEFT JOIN buku ON buku.id = buku_transaksi_peminjaman.buku_id";
$bukuTransaksiPeminjaman = query($sql)->fetch_all(MYSQLI_ASSOC);
?>

<div class="container py-5">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">Daftar Data Transaksi Peminjaman Buku</div>
                <div class="card-body">
                    <?php require 'templates/alert.php' ?>
                    <a href="transaksi_tambah.php" class="btn btn-primary mb-3">Buat Transaksi Peminjaman Buku Baru</a>
                    <a href="transaksi_report.php" class="btn btn-success mb-3">Laporan</a>
                    <form action="" class="py-2" method="post">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Cari..." name="keyword" value="<?= $keyword ?>" />
                            <button class="btn btn-outline-primary" type="submit" name="search" value="1">Cari</button>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">ID</th>
                                    <th scope="col">Kode</th>
                                    <th scope="col">Anggota</th>
                                    <th scope="col">Operator</th>
                                    <th scope="col" class="no-wrap">Di pinjam pada</th>
                                    <th scope="col" class="no-wrap">Jatuh tempo pada</th>
                                    <th scope="col" class="no-wrap">Di kembalikan pada</th>
                                    <th scope="col">Denda</th>
                                    <th scope="col">Buku</th>
                                    <th scope="col">Handle</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($transaksiPeminjaman as $tr) : ?>
                                    <tr>
                                        <th scope="row"><?= $no++; ?></th>
                                        <td>
                                            <span class="fw-bold"><?= $tr['tp_id']; ?></span>
                                        </td>
                                        <td class="no-wrap"><?= $tr['tp_kode']; ?></td>
                                        <td>
                                            <a class="badge text-bg-primary" href="anggota_edit.php?id=<?= $tr['anggota_id'] ?>"><?= $tr['an_nama'] ?></a>
                                        </td>
                                        <td>
                                            <a class="badge text-bg-primary" href="users_edit.php?id=<?= $tr['operator_id'] ?>"><?= $tr['op_nama'] ?></a>
                                        </td>
                                        <td class="no-wrap"><?= formatDate($tr['dipinjam_pada'], 'd F Y H:i'); ?></td>
                                        <td class="no-wrap"><?= formatDate($tr['jatuh_tempo_pada'], 'd F Y'); ?></td>
                                        <td class="no-wrap"><?= formatDate($tr['dikembalikan_pada'], 'd F Y H:i'); ?></td>
                                        <td class="no-wrap">Rp. <?= formatRupiah($tr['denda']); ?></td>
                                        <td>
                                            <?php
                                            $currentBuku = array_filter($bukuTransaksiPeminjaman, fn ($item) => $item['transaksi_peminjaman_id'] == $tr['tp_id']);
                                            ?>
                                            <?php foreach ($currentBuku as $bk) : ?>
                                                <a class="badge text-bg-primary" href="buku_edit.php?id=<?= $bk['buku_id'] ?>"><?= $bk['judul'] ?> (<?= $bk['jml_buku'] ?>)</a>
                                            <?php endforeach; ?>
                                        </td>
                                        <td>
                                            <a href="transaksi_edit.php?id=<?= $tr['tp_id']; ?>" class="badge text-bg-primary">edit</a>
                                            <form action="" method="post" class="d-inline-block">
                                                <input type="hidden" name="id" value="<?= $tr['tp_id']; ?>">
                                                <button name="hapus" class="badge text-bg-danger border-0">hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require 'mysql-footer.php';
require 'templates/dashboard/footer.php';
?>