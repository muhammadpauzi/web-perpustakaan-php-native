<?php

require 'mysql.php';
require 'global.php';
require 'templates/dashboard/header.php';
require 'templates/dashboard/navbar.php';

guardAuth();

function update($id, $transaksiPeminjamanData)
{
    $dipinjam_pada = $_POST['dipinjam_pada'];
    $jatuh_tempo_pada = $_POST['jatuh_tempo_pada'];
    $denda = $_POST['denda'];
    $keterangan = $_POST['keterangan'];
    $anggota_id = $_POST['anggota_id'];
    $buku_ids = $_POST['buku_ids'];

    $new_buku_ids = [];
    foreach ($buku_ids['id'] as $key => $bk_id) {
        if (isset($new_buku_ids[$bk_id])) {
            $new_buku_ids[$bk_id] += intval($buku_ids['jml_buku'][$key]);
        } else {
            $new_buku_ids[$bk_id] = intval($buku_ids['jml_buku'][$key]);
        }
    }

    if (isEmpty([$dipinjam_pada, $jatuh_tempo_pada, $denda, $anggota_id])) {
        return setFlash('error_update', 'danger', 'Semua field wajib di-isi, kecuali keterangan.');
    }

    query("DELETE FROM buku_transaksi_peminjaman WHERE transaksi_peminjaman_id='$id'");

    foreach ($new_buku_ids as $bukuId => $jml_buku) {
        query("INSERT INTO `buku_transaksi_peminjaman` VALUES (null, '$bukuId', '$id', '$jml_buku')");
    }

    query("UPDATE `transaksi_peminjaman` SET dipinjam_pada='$dipinjam_pada', jatuh_tempo_pada='$jatuh_tempo_pada', denda='$denda', keterangan='$keterangan', anggota_id='$anggota_id' WHERE id='$id'");

    setFlash('alert', 'success', 'Data transaksi peminjaman buku berhasil disimpan');
    header("location: transaksi.php");
}

function kembaliBuku($id)
{
    $dikembalikan_pada = $_POST['dikembalikan_pada'];
    $keterangan = $_POST['keterangan'];

    query("UPDATE `transaksi_peminjaman` SET dikembalikan_pada='$dikembalikan_pada', keterangan='$keterangan' WHERE id='$id'");

    setFlash('alert', 'success', 'Data transaksi peminjaman buku berhasil disimpan & dikembalikan');
    header("location: transaksi.php");
}

$id = $_GET['id'];

$transaksiPeminjaman = query("SELECT id, dipinjam_pada, denda, jatuh_tempo_pada, dikembalikan_pada, keterangan, anggota_id, operator_id FROM transaksi_peminjaman WHERE id='$id' LIMIT 1");
$transaksiPeminjamanData = $transaksiPeminjaman->fetch_assoc();

$bukuTransaksiPeminjaman = query("SELECT id, buku_id, transaksi_peminjaman_id, jml_buku FROM buku_transaksi_peminjaman WHERE transaksi_peminjaman_id='$id'");
$bukuTransaksiPeminjamanData = $bukuTransaksiPeminjaman->fetch_all(MYSQLI_ASSOC);

if (isset($_POST['update'])) {
    update($id, $transaksiPeminjamanData);
}

if (isset($_POST['kembali_buku'])) {
    kembaliBuku($id, $transaksiPeminjamanData);
}

$sql = "SELECT * FROM buku ORDER BY id";
$buku = query($sql)->fetch_all(MYSQLI_ASSOC);

$sql = "SELECT * FROM anggota ORDER BY id";
$anggota = query($sql)->fetch_all(MYSQLI_ASSOC);
?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Form Edit Data
                </div>
                <div class="card-body">
                    <?php if ($error = getFlash('error_update')) : ?>
                        <div class="alert alert-<?= $error['type']; ?> " role="alert">
                            <div><?= $error['message']; ?></div>
                        </div>
                    <?php endif; ?>
                    <form action="" method="POST">
                        <div class="form-floating mb-3">
                            <input type="datetime-local" class="form-control" id="dipinjam_pada" name="dipinjam_pada" placeholder="-" value="<?= $_POST['dipinjam_pada'] ?? $transaksiPeminjamanData['dipinjam_pada']; ?>">
                            <label for="dipinjam_pada">Di pinjam pada</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="date" class="form-control" id="jatuh_tempo_pada" name="jatuh_tempo_pada" placeholder="-" value="<?= $_POST['jatuh_tempo_pada'] ?? $transaksiPeminjamanData['jatuh_tempo_pada']; ?>">
                            <label for="jatuh_tempo_pada">Jatuh tempo pada</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" id="denda" name="denda" placeholder="-" value="<?= $_POST['denda'] ?? $transaksiPeminjamanData['denda']; ?>">
                            <label for="denda">Denda</label>
                        </div>
                        <div class="form-floating mb-3">
                            <textarea rows="4" class="form-control" id="keterangan" name="keterangan" placeholder="-"><?= $_POST['keterangan'] ?? $transaksiPeminjamanData['keterangan']; ?></textarea>
                            <label for="keterangan">Keterangan</label>
                        </div>
                        <div class="form-floating mb-3">
                            <select class="form-select" aria-label="Default select example" name="anggota_id">
                                <?php foreach ($anggota as $ang) : ?>
                                    <option value="<?= $ang['id'] ?>" <?= $ang['id'] == $transaksiPeminjamanData['anggota_id'] ? 'selected' : '' ?>><?= $ang['id'] . ' - ' . $ang['nama']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <label for="anggota_id">Anggota</label>
                        </div>
                        <div class="mb-3">
                            <label for="buku_id" class="form-label">Buku</label>
                            <div id="item-group">
                                <?php foreach ($bukuTransaksiPeminjamanData as $btp) : ?>
                                    <div class="input-group mb-2">
                                        <select class="form-select" aria-label="Default select example" name="buku_ids[id][]">
                                            <?php foreach ($buku as $bk) : ?>
                                                <option value="<?= $bk['id'] ?>" <?= $btp['buku_id'] == $bk['id'] ? 'selected' : '' ?>><?= $bk['id'] . ' - ' . $bk['judul']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <input type="number" class="form-control" placeholder="Jumlah buku" value="<?= $btp['jml_buku'] ?>" min="1" name="buku_ids[jml_buku][]" />
                                        <button class="btn btn-outline-danger fw-bold remove-item-group-btn" type="button">🗑</button>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="d-flex align-items-center justify-content-end">
                                <button class="btn btn-primary btn-sm fw-bold mt-2" id="add-item-group-btn" type="button">Tambah</button>
                            </div>
                        </div>

                        <button name="update" class="btn btn-primary d-block mt-3 w-100 py-3 d-block fw-bold">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    Form Kembali Buku
                </div>
                <div class="card-body">
                    <?php if ($error = getFlash('error_kembali')) : ?>
                        <div class="alert alert-<?= $error['type']; ?> " role="alert">
                            <div><?= $error['message']; ?></div>
                        </div>
                    <?php endif; ?>
                    <form action="" method="POST">
                        <div class="form-floating mb-3">
                            <input type="datetime-local" class="form-control" id="dikembalikan_pada" name="dikembalikan_pada" placeholder="-" value="<?= $_POST['dikembalikan_pada'] ?? $transaksiPeminjamanData['dikembalikan_pada']; ?>">
                            <label for="dikembalikan_pada">Di kembalikan pada</label>
                        </div>
                        <div class="form-floating mb-3">
                            <textarea rows="4" class="form-control" id="keterangan" name="keterangan" placeholder="-"><?= $_POST['keterangan'] ?? $transaksiPeminjamanData['keterangan']; ?></textarea>
                            <label for="keterangan">Keterangan</label>
                        </div>
                        <button name="kembali_buku" class="btn btn-primary d-block mt-3 w-100 py-3 d-block fw-bold">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const itemGroup = document.getElementById('item-group');
    const addItemGroupBtn = document.getElementById('add-item-group-btn');

    addItemGroupBtn.addEventListener('click', function() {
        itemGroup.append(itemGroup.firstElementChild.cloneNode(true));
    });

    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-item-group-btn')) {
            if (document.querySelectorAll('.remove-item-group-btn').length > 1) {
                e.target.parentElement.remove();
            }
        }
    })
</script>

<?php
require 'mysql-footer.php';
require 'templates/dashboard/footer.php';
?>