<?php

require 'mysql.php';
require 'global.php';
require 'templates/dashboard/header.php';
require 'templates/dashboard/navbar.php';

guardAuth();


function update($id)
{
    $username = $_POST['username'];
    $nama = $_POST['nama'];

    if (isEmpty([$username, $nama])) {
        return setFlash('error_edit', 'danger', 'Semua field wajib di-isi.');
    }
    $user = query("SELECT id, username, nama FROM users WHERE id='$id' LIMIT 1");
    $userData = $user->fetch_assoc();
    if ($userData['username'] != $username & $user->num_rows > 0) return setFlash('error_edit', 'danger', 'Username sudah terdaftar, coba username lain!');

    query("UPDATE `users` SET nama='$nama', username='$username' WHERE id='$id'");
    setFlash('alert', 'success', 'Data users berhasil disimpan');
    header("location: users.php");
}

$id = $_GET['id'];

if (isset($_POST['edit'])) {
    update($id);
}

$user = query("SELECT id, username, nama FROM users WHERE id='$id' LIMIT 1");
$userData = $user->fetch_assoc();
?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    Form Edit Data
                </div>
                <div class="card-body">
                    <?php if ($error = getFlash('error_edit')) : ?>
                        <div class="alert alert-<?= $error['type']; ?> " role="alert">
                            <div><?= $error['message']; ?></div>
                        </div>
                    <?php endif; ?>
                    <form action="" method="POST">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="nama" name="nama" placeholder="-" value="<?= $_POST['nama'] ?? $userData['nama']; ?>">
                            <label for="nama">Nama</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="username" name="username" placeholder="-" value="<?= $_POST['username'] ?? $userData['username']; ?>">
                            <label for="username">Username</label>
                        </div>
                        <button name="edit" class="btn btn-primary d-block mt-3 w-100 py-3 d-block fw-bold">Simpan</button>
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