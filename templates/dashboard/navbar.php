<nav class="navbar navbar-expand-md bg-body-tertiary py-3 border-bottom">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">Web Perpustakaan</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="<?= url() . '/index.php'; ?>">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="<?= url() . '/anggota.php'; ?>">Data Anggota</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="<?= url() . '/buku.php'; ?>">Data Buku</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="<?= url() . '/transaksi.php'; ?>">Transaksi Buku</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="<?= url() . '/users.php'; ?>">Data Users</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-danger text-white py-2 px-3 ms-0 ms-md-3 btn-sm" aria-current="page" href="<?= url() . '/logout.php'; ?>">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>