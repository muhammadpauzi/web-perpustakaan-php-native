<?php

require 'mysql.php';
require 'global.php';
require 'templates/dashboard/header.php';
require 'templates/dashboard/navbar.php';

guardAuth();

function add()
{
    $username = $_POST['username'];
    $nama = $_POST['nama'];
    $password = $_POST['password'];

    if (isEmpty([$username, $nama, $password])) {
        return setFlash('error_tambah', 'danger', 'Semua field wajib di-isi.');
    }
    $user = query("SELECT id FROM users WHERE username='$username'");
    if ($user->num_rows > 0) return setFlash('error_tambah', 'danger', 'Username sudah terdaftar, coba username lain!');

    $password = password_hash($password, PASSWORD_DEFAULT);
    query("INSERT INTO `users` VALUES (null, '$nama', '$password', '$username')");
    setFlash('alert', 'success', 'Data users berhasil disimpan');
    header("location: users.php");
}

if (isset($_POST['tambah'])) {
    add();
}
?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    Form Tambah Data
                </div>
                <div class="card-body">
                    <?php if ($error = getFlash('error_tambah')) : ?>
                        <div class="alert alert-<?= $error['type']; ?> " role="alert">
                            <div><?= $error['message']; ?></div>
                        </div>
                    <?php endif; ?>
                    <form action="" method="POST">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="nama" name="nama" placeholder="-" value="<?= $_POST['nama'] ?? ''; ?>">
                            <label for="nama">Nama</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="username" name="username" placeholder="-" value="<?= $_POST['username'] ?? ''; ?>">
                            <label for="username">Username</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="password" name="password" placeholder="-" value="<?= $_POST['password'] ?? ''; ?>">
                            <label for="password">Password</label>
                        </div>
                        <button name="tambah" class="btn btn-primary d-block mt-3 w-100 py-3 d-block fw-bold">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require 'mysql-footer.php';
require 'templates/dashboard/footer.php';
?>