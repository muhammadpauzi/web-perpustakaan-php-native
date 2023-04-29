<?php

require 'mysql.php';
require 'global.php';
require 'templates/dashboard/header.php';
require 'templates/dashboard/navbar.php';

guardAuth();

if (isset($_POST['tambah'])) {
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $noHP = $_POST['no_hp'];

    if (isEmpty([$kode, $nama, $alamat, $noHP])) {
        setFlash('error_tambah', 'danger', 'Semua field wajib di-isi.');
    } else if (strlen($kode) > 3) {
        setFlash('error_tambah', 'danger', 'Panjang kode max. 3.');
    } else {
        $sql = "SELECT * FROM anggota WHERE kode='$kode'";
        $result = query($sql);
        $anggota = $result->fetch_assoc();
        if ($anggota) {
            mysqli_query($conn, "UPDATE `anggota` SET kode='$kode', nama='$nama', alamat='$alamat', no_hp='$noHP' WHERE kode='$kode'");
            setFlash('alert', 'success', 'Data anggota berhasil diedit');
        } else {
            mysqli_query($conn, "INSERT INTO `anggota` VALUES (null, '$kode', '$nama', '$alamat', '$noHP')");
            setFlash('alert', 'success', 'Data anggota berhasil disimpan');

            $_POST = []; // reset input
        }
    }
}


if (isset($_POST['hapus'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM anggota WHERE id='$id'";
    $result = mysqli_query($conn, $sql);

    setFlash('alert', 'success', 'Data anggota berhasil dihapus');
}

$sqlQuerySearch = '';

$keyword = isset($_POST['keyword']) ? $_POST['keyword'] : '';

if (isset($_POST['search']) && $keyword) {
    $sqlQuerySearch = " WHERE kode LIKE '%$keyword%' OR nama LIKE '%$keyword%' OR alamat LIKE '%$keyword%'";
}

$sql = "SELECT * FROM anggota" . $sqlQuerySearch;
$anggotas = query($sql)->fetch_all(MYSQLI_ASSOC);

?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">Daftar Data Anggota</div>
                <div class="card-body">
                    <?php require 'templates/alert.php' ?>
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
                                <th scope="col">Kode</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Alamat</th>
                                <th scope="col">NO. HP</th>
                                <th scope="col">Handle</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php foreach ($anggotas as $anggota) : ?>
                                <tr>
                                    <th scope="row"><?= $no++; ?></th>
                                    <td>
                                        <span class="fw-bold"><?= $anggota['kode']; ?></span>
                                    </td>
                                    <td><?= $anggota['nama']; ?></td>
                                    <td><?= $anggota['alamat']; ?></td>
                                    <td><?= $anggota['no_hp']; ?></td>
                                    <td>
                                        <a href="#" onclick="
                                        document.getElementById('kode').value = '<?= $anggota['kode']; ?>';
                                        document.getElementById('nama').value = '<?= $anggota['nama']; ?>';
                                        document.getElementById('alamat').value = '<?= $anggota['alamat']; ?>';
                                        document.getElementById('no_hp').value = '<?= $anggota['no_hp']; ?>';
                                        " class="badge text-bg-primary">edit</a>
                                        <form action="" method="post" class="d-inline-block">
                                            <input type="hidden" name="id" value="<?= $anggota['id']; ?>">
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
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    Form Tambah & Edit Data
                </div>
                <div class="card-body">
                    <?php if ($error = getFlash('error_tambah')) : ?>
                        <div class="alert alert-<?= $error['type']; ?> " role="alert">
                            <div><?= $error['message']; ?></div>
                        </div>
                    <?php endif; ?>
                    <form action="" method="POST">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="kode" name="kode" placeholder="-" value="<?= $_POST['kode'] ?? ''; ?>" maxlength="3">
                            <label for="kode">Kode</label>
                        </div>
                        <small class="text-muted mb-3 d-block mt-2">Masukan Kode yang sudah ada untuk mengedit.</small>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="nama" name="nama" placeholder="-" value="<?= $_POST['nama'] ?? ''; ?>">
                            <label for="nama">Nama</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="alamat" name="alamat" placeholder="-" value="<?= $_POST['alamat'] ?? ''; ?>">
                            <label for="alamat">Alamat</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="no_hp" name="no_hp" placeholder="-" value="<?= $_POST['no_hp'] ?? ''; ?>">
                            <label for="no_hp">No. HP</label>
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