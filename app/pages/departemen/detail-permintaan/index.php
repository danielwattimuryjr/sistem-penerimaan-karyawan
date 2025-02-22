<?php
require_once('./../../../functions/init-session.php');
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/string-helpers.php');
require_once('./../../../functions/page-protection.php');

$id_permintaan = isset($_GET['id_permintaan']) ? $_GET['id_permintaan'] : null;

if (!$id_permintaan) {
    $type = 'error';
    $message = 'Data permintaan tidak ditemukan';
    header("Location: /pages/departemen/beranda?type=$type&message=" . urlencode($message));
    exit();
}
$getPermintaanQueryStr = "SELECT p.id_permintaan, p.tanggal_permintaan, u.name, d.nama_divisi AS posisi, p.jumlah_permintaan, p.jenis_kelamin, p.status_kerja, p.tanggal_mulai, p.tanggal_selesai, p.status_permintaan
FROM permintaan p
JOIN user u ON p.id_user = u.id_user
JOIN divisi d ON p.id_divisi = d.id_divisi
WHERE p.id_permintaan = ?
LIMIT 1";

$getPermintaanStmt = $conn->prepare($getPermintaanQueryStr);
$getPermintaanStmt->bind_param("i", $id_permintaan);
$getPermintaanStmt->execute();
$permintaan = $getPermintaanStmt->get_result()->fetch_assoc();

$storedJenisKelamin = $permintaan['jenis_kelamin'];
$selectedJenisKelamin = explode(',', $storedJenisKelamin);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Permintaan Karyawan</title>

    <link rel="shortcut icon" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/svg/favicon.svg"
        type="image/x-icon">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/app.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/iconly.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/scss/pages/sweetalert2.scss">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/sweetalert2/sweetalert2.min.css">
</head>

<body>

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
                <h3>Permintaan Karyawan</h3>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Details</h5>
                            </div>
                            <div class="card-body">
                                <dl class="row">
                                    <dt class="col-sm-3">Tanggal Permintaan</dt>
                                    <dd class="col-sm-9"><?= $permintaan['tanggal_permintaan'] ?></dd>

                                    <dt class="col-sm-3">Status</dt>
                                    <dd class="col-sm-9"><span
                                            class="badge
                                                                    <?= $permintaan['status_permintaan'] === 'Disetujui' ? 'bg-success' : 'bg-danger'; ?>">
                                            <?= htmlspecialchars(ucfirst($permintaan['status_permintaan'])); ?>
                                        </span></dd>

                                    <dt class="col-sm-3">Departement</dt>
                                    <dd class="col-sm-9"><?= $permintaan['name'] ?></dd>

                                    <dt class="col-sm-3">Untuk Posisi</dt>
                                    <dd class="col-sm-9"><?= $permintaan['posisi'] ?></dd>

                                    <dt class="col-sm-3">Jumlah</dt>
                                    <dd class="col-sm-9"><?= $permintaan['jumlah_permintaan'] ?></dd>

                                    <dt class="col-sm-3">Jenis Kelamin</dt>
                                    <dd class="col-sm-9">
                                        <?= $permintaan['jenis_kelamin'] ?>
                                    </dd>

                                    <dt class="col-sm-3">Status Kerja</dt>
                                    <dd class="col-sm-9"><?= toTitleCase($permintaan['status_kerja']) ?> </dd>

                                    <dt class="col-sm-3">Tanggal Mulai</dt>
                                    <dd class="col-sm-9"><?= $permintaan['tanggal_mulai'] ?> </dd>

                                    <dt class="col-sm-3">Tanggal Selesai</dt>
                                    <dd class="col-sm-9"><?= $permintaan['tanggal_selesai'] ?? '-' ?> </dd>
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

    <script
        src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/js/app.js"></script>
    <script
        src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/sweetalert2/sweetalert2.min.js"></script>
    <script src="/assets/js/sweet-alert.js"></script>
</body>

</html>