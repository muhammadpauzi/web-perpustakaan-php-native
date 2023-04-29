<?php

require 'mysql.php';
require 'global.php';
require 'templates/dashboard/header.php';
require 'templates/dashboard/navbar.php';

guardAuth();

function add()
{
    $file = $_FILES['image'];
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $penerbit = $_POST['penerbit'];
    $keterangan = $_POST['keterangan'];

    if (isEmpty([$judul, $penulis, $penerbit])) {
        return setFlash('error_tambah', 'danger', 'Semua field wajib di-isi, kecuali keterangan.');
    }
    $path = uploadImage($file);
    if (!$path) return setFlash('error_tambah', 'danger', 'Gagal menyimpan.');

    query("INSERT INTO `buku` VALUES (null, '$judul', '$penulis', '$penerbit', '$path', '$keterangan')");
    setFlash('alert', 'success', 'Data buku berhasil disimpan');
    header("location: buku.php");
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
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="judul" name="judul" placeholder="-" value="<?= $_POST['judul'] ?? ''; ?>">
                            <label for="judul">Judul</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="penulis" name="penulis" placeholder="-" value="<?= $_POST['penulis'] ?? ''; ?>">
                            <label for="penulis">Penulis</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="penerbit" name="penerbit" placeholder="-" value="<?= $_POST['penerbit'] ?? ''; ?>">
                            <label for="penerbit">Penerbit</label>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Cover</label>
                            <input type="file" class="form-control" id="image" name="image">
                            <?php if ($error = getFlash('message_file')) : ?>
                                <div class="alert alert-<?= $error['type']; ?> " role="alert">
                                    <div><?= $error['message']; ?></div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="form-floating mb-3">
                            <textarea rows="4" class="form-control" id="keterangan" name="keterangan" placeholder="-"><?= $_POST['keterangan'] ?? ''; ?></textarea>
                            <label for="keterangan">Keterangan</label>
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