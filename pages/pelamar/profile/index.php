<?php
require_once('./../../../functions/init-session.php');
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/page-protection.php');

$user = $_SESSION['user'];
$queryProfile = "SELECT name, nomor_telepon, pendidikan_terakhir, jenis_kelamin, tempat_lahir, tanggal_lahir, alamat FROM user WHERE id_user = ?";
$stmtProfile = $conn->prepare($queryProfile);
$stmtProfile->bind_param('i', $user['id_user']);
$stmtProfile->execute();
$resultProfile = $stmtProfile->get_result();
$profile = $resultProfile->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Grand Pasundan Careers</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Bootstrap Knowledge Base and Help Centre Template">
    <meta name="author" content="Xiaoying Riley at 3rd Wave Media">
    <link rel="shortcut icon" href="favicon.ico">

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500&display=swap"
        rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- Plugin CSS -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark-reasonable.min.css">

    <!-- Theme CSS -->
    <link id="theme-style" rel="stylesheet" href="/sistem-penerimaan-karyawan/assets/css/public.styles.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/scss/pages/sweetalert2.scss">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/sweetalert2/sweetalert2.min.css">
</head>

<body>
    <div class="page-header-wrapper">
        <div class="page-header-bg-pattern-holder">
            <div class="bg-pattern-top"></div>
            <div class="bg-pattern-bottom"></div>
        </div><!--//page-header-bg-pattern-holder-->

        <header class="header">
            <div class="container">
                <nav class="navbar navbar-expand-lg">
                    <div class="site-logo me-3">
                        <a class="navbar-brand" href="/sistem-penerimaan-karyawan">
                            <img src="/sistem-penerimaan-karyawan/assets/images/app-logo.png" alt="logo"
                                style="width: 100px;">
                        </a>
                    </div><!--//site-logo-->


                    <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span> </span>
                        <span> </span>
                        <span> </span>
                    </button>

                    <div class="collapse navbar-collapse ms-auto" id="navigation">
                        <ul class="navbar-nav ms-auto align-items-lg-center">
                            <?php if (isset($_SESSION['user'])): ?>
                                <li class="nav-item dropdown me-lg-4">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                        aria-expanded="false"><?= $_SESSION['user']['name'] ?? 'name' ?></a>
                                    <ul class="dropdown-menu dropdown-menu-lg-end rounded shadow">
                                        <li><a class="dropdown-item"
                                                href="/sistem-penerimaan-karyawan/pages/pelamar/profile">Profile</a></li>
                                        <li><a class="dropdown-item text-danger"
                                                href="/sistem-penerimaan-karyawan/pages/public/landing-page/logout.php">Logout</a>
                                        </li>
                                    </ul>
                                </li>
                            <?php else: ?>
                                <li class="nav-item me-lg-4">
                                    <a class="nav-link" href="/sistem-penerimaan-karyawan/pages/public/sign-in">Sign In</a>
                                </li>
                                <li class="nav-item pt-3 pt-lg-0">
                                    <a class="nav-link"
                                        href="/sistem-penerimaan-karyawan/pages/public/register">Register</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </nav>
            </div><!--//container-->

        </header><!--//header-->
    </div><!--//page-header-wrapper-->

    <div class="help-content-wrapper theme-section pt-4">
        <div class="container">
            <div class="row">
                <div class="col">
                    <section class="main-section order-lg-last">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Profile Pelamar</h3>
                            </div>
                            <div class="card-body">
                                <dl class="row mt-4">
                                    <p class="col-12">Profil tidak sesuai? <a
                                            href="/sistem-penerimaan-karyawan/pages/pelamar/edit-profile">Edit di
                                            sini</a></p>
                                    <dt class="col-sm-3">Nama Lengkap</dt>
                                    <dd class="col-sm-9"><?= $profile['name'] ?? '-' ?></dd>

                                    <dt class="col-sm-3">Nomor Telepon</dt>
                                    <dd class="col-sm-9">
                                        <?= $profile['nomor_telepon'] ?? '-' ?>
                                    </dd>

                                    <dt class="col-sm-3 text-truncate">Jenis Kelamin</dt>
                                    <dd class="col-sm-9">
                                        <?=
                                            is_null($profile['jenis_kelamin']) ? '-' :
                                            ($profile['jenis_kelamin'] ? 'Laki- laki' : 'Perempuan')
                                            ?>
                                    </dd>

                                    <dt class="col-sm-3">Pendidikan Terakhir</dt>
                                    <dd class="col-sm-9"><?= $profile['pendidikan_terakhir'] ?? '-' ?></dd>

                                    <dt class="col-sm-3">Tempat, Tgl. Lahir</dt>
                                    <dd class="col-sm-9">
                                        <?= ($profile['tempat_lahir'] ?? '-') . ', ' . ($profile['tanggal_lahir'] ?? '-') ?>
                                    </dd>

                                    <dt class="col-sm-3">Alamat</dt>
                                    <dd class="col-sm-9"><?= $profile['alamat'] ?? '-' ?></dd>
                                </dl>
                            </div>
                        </div>

                    </section><!--//main-section-->
                </div><!--//col-->
            </div><!--//row-->

        </div><!--//container-->
    </div><!--//help-content-wrapper-->

    <!-- Javascript -->
    <script src="/sistem-penerimaan-karyawan/assets/plugins/popper.min.js"></script>
    <script src="/sistem-penerimaan-karyawan/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/tinymce/tinymce.min.js"></script>
    <script src="/sistem-penerimaan-karyawan/assets/js/tiny-mce.js"></script>
    <script
        src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/sweetalert2/sweetalert2.min.js"></script>
    <script src="/sistem-penerimaan-karyawan/assets/js/sweet-alert.js"></script>
</body>

</html>