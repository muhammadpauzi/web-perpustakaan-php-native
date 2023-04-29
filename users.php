<?php

require 'mysql.php';
require 'global.php';
require 'templates/dashboard/header.php';
require 'templates/dashboard/navbar.php';

guardAuth();
if (isset($_POST['hapus'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM users WHERE id='$id'";
    $result = mysqli_query($conn, $sql);

    setFlash('alert', 'success', 'Data users berhasil dihapus');
}

$sqlQuerySearch = '';

$keyword = isset($_POST['keyword']) ? $_POST['keyword'] : '';

if (isset($_POST['search']) && $keyword) {
    $sqlQuerySearch = " WHERE nama LIKE '%$keyword%' OR username LIKE '%$keyword%'";
}
$sql = "SELECT * FROM users" . $sqlQuerySearch;
$users = query($sql)->fetch_all(MYSQLI_ASSOC);

?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">Daftar Data Users</div>
                <div class="card-body">
                    <?php require 'templates/alert.php' ?>
                    <a href="users_tambah.php" class="btn btn-primary mb-3">Buat User Baru</a>
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
                                <th scope="col">Nama</th>
                                <th scope="col">Username</th>
                                <th scope="col">Handle</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php foreach ($users as $user) : ?>
                                <tr>
                                    <th scope="row"><?= $no++; ?></th>
                                    <td>
                                        <span class="fw-bold"><?= $user['id']; ?></span>
                                    </td>
                                    <td><?= $user['nama']; ?></td>
                                    <td><?= $user['username']; ?></td>
                                    <td>
                                        <a href="users_edit.php?id=<?= $user['id']; ?>" class="badge text-bg-primary">edit</a>
                                        <form action="" method="post" class="d-inline-block">
                                            <input type="hidden" name="id" value="<?= $user['id']; ?>">
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