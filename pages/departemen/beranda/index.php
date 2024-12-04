<?php
    require_once ('./../../../functions/init-session.php');
    if (!$_SESSION['user']) {
        header("Location: /sistem-penerimaan-karyawan/pages/auth/sign-in");
    }
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Beranda</title>

    <!--  Bootstrap 5.3 CSS  -->
    <link rel="stylesheet" href="/sistem-penerimaan-karyawan/assets/css/bootstrap.min.css" crossorigin="anonymous">

    <style>
        body {
            background-color: #f1f1f1f1;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                <a class="navbar-brand" href="#">GrandPasundan</a>
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="#">Berandan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Penerimaan Karyawan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-disabled="true">Hasil Seleksi</a>
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
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-sm mt-3 mt-lg-5">
        <div class="card p-3" style="width: 100%;">
            Departemen
        </div>
    </div>


    <!--  Bootstrap 5.3 JS  -->
    <script src="/sistem-penerimaan-karyawan/assets/js/popper.min.js" crossorigin="anonymous"></script>
    <script src="/sistem-penerimaan-karyawan/assets/js/bootstrap.min.js" crossorigin="anonymous"></script>

    <!--  SweetAlert2  -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>