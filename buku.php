<?php

require 'mysql.php';
require 'global.php';
require 'templates/dashboard/header.php';
require 'templates/dashboard/navbar.php';

guardAuth();
if (isset($_POST['hapus'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM buku WHERE id='$id'";
    $result = mysqli_query($conn, $sql);

    setFlash('alert', 'success', 'Data buku berhasil dihapus');
}

$sqlQuerySearch = '';

$keyword = isset($_POST['keyword']) ? $_POST['keyword'] : '';

if (isset($_POST['search']) && $keyword) {
    $sqlQuerySearch = " WHERE judul LIKE '%$keyword%' OR penulis LIKE '%$keyword%' OR penerbit LIKE '%$keyword%'";
}

$sql = "SELECT * FROM buku" . $sqlQuerySearch;
$buku = query($sql)->fetch_all(MYSQLI_ASSOC);

?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Daftar Data Buku</div>
                <div class="card-body">
                    <?php require 'templates/alert.php' ?>
                    <a href="buku_tambah.php" class="btn btn-primary mb-3">Buat Buku Baru</a>
                    <form action="" class="py-2" method="post">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Cari..." name="keyword" value="<?= $keyword ?>" />
                            <button class="btn btn-outline-primary" type="submit" name="search" value="1">Cari</button>
                        </div>
                    </form>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">ID</th>
                                <th scope="col">Judul</th>
                                <th scope="col">Penulis</th>
                                <th scope="col">Penerbit</th>
                                <th scope="col">Handle</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php foreach ($buku as $bk) : ?>
                                <tr>
                                    <th scope="row"><?= $no++; ?></th>
                                    <td>
                                        <span class="fw-bold"><?= $bk['id']; ?></span>
                                    </td>
                                    <td><?= $bk['judul']; ?></td>
                                    <td><?= $bk['penulis']; ?></td>
                                    <td><?= $bk['penerbit']; ?></td>
                                    <td>
                                        <a href="buku_edit.php?id=<?= $bk['id']; ?>" class="badge text-bg-primary">edit</a>
                                        <form action="" method="post" class="d-inline-block">
                                            <input type="hidden" name="id" value="<?= $bk['id']; ?>">
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

<?php
require 'mysql-footer.php';
require 'templates/dashboard/footer.php';
?>