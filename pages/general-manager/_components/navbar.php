<?php
require_once ('./../../../functions/init-session.php');
if (!$_SESSION['user']) {
    header("Location: /sistem-penerimaan-karyawan/pages/auth/sign-in");
}
?>

<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
            <a class="navbar-brand" href="#">GrandPasundan</a>
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="/sistem-penerimaan-karyawan/pages/general-manager/beranda">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/sistem-penerimaan-karyawan/pages/general-manager/permintaan-karyawan">Permintaan Karyawan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/sistem-penerimaan-karyawan/pages/general-manager/hasil-seleksi">Hasil Seleksi</a>
                </li>
            </ul>
        </div>

        <div class="d-flex justify-content-between align-items-center gap-1 ms-lg-3">
            <p class="mb-0"><?= $_SESSION['user']['user_name'] ?></p>
            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle p-0 border-0 d-flex align-items-center" type="button" id="avatarDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="https://placehold.co/100" alt="Avatar" class="rounded-circle" style="width: 40px; height: 40px;">
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="avatarDropdown">
                    <li><a class="dropdown-item" href="#">Profile</a></li>
                    <li><a class="dropdown-item" href="#">Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="../beranda/logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>