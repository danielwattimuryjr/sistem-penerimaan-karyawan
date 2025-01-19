<?php
require_once('./../../../functions/init-session.php');
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/page-protection.php');

$user = $_SESSION['user'];
if ($user['role'] !== 'Pelamar') {
    $type = "error";
    $message = "Anda bukan seorang pelamar";
    header("Location: /sistem-penerimaan-karyawan/pages/pelamar/beranda?type=$type&message=" . urlencode($message));
    exit();
}

$queryProfile = "SELECT * FROM profile WHERE id_user = ?";
$stmtProfile = $conn->prepare($queryProfile);
$stmtProfile->bind_param('i', $user['id_user']);
$stmtProfile->execute();
$resultProfile = $stmtProfile->get_result();
$profile = $resultProfile->fetch_assoc();

$queryUser = "SELECT nama_lengkap FROM user WHERE id_user = ?";
$stmtUser = $conn->prepare($queryUser);
$stmtUser->bind_param('i', $user['id_user']);
$stmtUser->execute();
$resultUser = $stmtUser->get_result();
$userData = $resultUser->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>

    <link rel="shortcut icon" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/svg/favicon.svg"
        type="image/x-icon">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/app.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/app-dark.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/iconly.css">
</head>

<body>
    <script src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/static/js/initTheme.js"></script>
    <!-- Start content here -->

    <div id="app">
        <div id="sidebar">
            <?php require_once('./../_components/sidebar.php'); ?>
        </div>
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>
            <!-- Content -->
            <div class="page-heading">
                <h3>Profile</h3>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div
                                    class="d-flex justify-content-between flex-column flex-md-row align-items-start align-items-lg-ccenter">
                                    <h5 class="card-title text-center">Profile</h5>

                                    <a href="/sistem-penerimaan-karyawan/pages/pelamar/edit-profile"
                                        class="btn btn-sm btn-warning">Update</a>
                                </div>

                                <dl class="row mt-4">
                                    <dt class="col-sm-3">Nama Lengkap</dt>
                                    <dd class="col-sm-9"><?= $userData['nama_lengkap'] ?? '-' ?></dd>

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
                    </div>
                </section>
            </div>
            <!-- End Content -->
        </div>
    </div>

    <!-- End content -->
    <script src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/static/js/components/dark.js"></script>
    <script
        src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/js/app.js"></script>
</body>

</html>