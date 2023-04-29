<?php

require 'mysql.php';
require 'global.php';
require 'templates/dashboard/header.php';
require 'templates/dashboard/navbar.php';

guardAuth();

function update($id, $bukuData)
{
    $file = $_FILES['image'];
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $penerbit = $_POST['penerbit'];
    $keterangan = $_POST['keterangan'];

    if (isEmpty([$judul, $penulis, $penerbit])) {
        return setFlash('error_edit', 'danger', 'Semua field wajib di-isi, kecuali keterangan.');
    }

    $path = $bukuData['cover'];
    if ($file && $file['error'] == UPLOAD_ERR_OK) {
        $path = uploadImage($file);
        if (!$path) {
            return setFlash('error_edit', 'danger', 'Gagal menyimpan.');
        }
    }

    query("UPDATE `buku` SET judul='$judul', penulis='$penulis', penerbit='$penerbit', keterangan='$keterangan', cover='$path' WHERE id='$id'");
    setFlash('alert', 'success', 'Data buku berhasil disimpan');
    header("location: buku.php");
}

$id = $_GET['id'];

$buku = query("SELECT id, judul, penerbit, penulis, keterangan, cover FROM buku WHERE id='$id' LIMIT 1");
$bukuData = $buku->fetch_assoc();

if (isset($_POST['edit'])) {
    update($id, $bukuData);
}
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
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="judul" name="judul" placeholder="-" value="<?= $_POST['judul'] ?? $bukuData['judul']; ?>">
                            <label for="judul">Judul</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="penulis" name="penulis" placeholder="-" value="<?= $_POST['penulis'] ?? $bukuData['penulis']; ?>">
                            <label for="penulis">Penulis</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="penerbit" name="penerbit" placeholder="-" value="<?= $_POST['penerbit'] ?? $bukuData['penerbit']; ?>">
                            <label for="penerbit">Penerbit</label>
                        </div>
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-sm-3">
                                    <img src="<?= $bukuData['cover'] ?>" alt="Cover Buku" class="img-thumbnail d-block w-100" />
                                </div>
                                <div class="col-sm-9">
                                    <label for="image" class="form-label">Cover</label>
                                    <input type="file" class="form-control" id="image" name="image">
                                    <?php if ($error = getFlash('message_file')) : ?>
                                        <div class="alert alert-<?= $error['type']; ?> mt-2" role="alert">
                                            <div><?= $error['message']; ?></div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-floating mb-3">
                            <textarea rows="4" class="form-control" id="keterangan" name="keterangan" placeholder="-"><?= $_POST['keterangan'] ?? $bukuData['keterangan']; ?></textarea>
                            <label for="keterangan">Keterangan</label>
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