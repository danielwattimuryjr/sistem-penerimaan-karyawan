<?php
require_once('./../../../functions/init-session.php');
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/page-protection.php');

// Get id_lowongan
$id_lowongan = isset($_GET['id_lowongan']) ? $_GET['id_lowongan'] : null;
//
if (!$id_lowongan) {
    header("Location: /sistem-penerimaan-karyawan/pages/departemen/beranda");
}

$getLowonganQueryStr = "SELECT nama_lowongan, deskripsi FROM lowongan LIMIT 1";
$getLowonganResult = $conn->query($getLowonganQueryStr);
$lowongan = $getLowonganResult->fetch_assoc();

$getPersyaratanStr = "SELECT pengalaman_kerja, umur, pendidikan FROM persyaratan WHERE id_lowongan = ? LIMIT 1";
$stmt = $conn->prepare($getPersyaratanStr);
$stmt->bind_param("i", $id_lowongan);
$stmt->execute();
$getPersyaratanResult = $stmt->get_result();
$persyaratan = $getPersyaratanResult->fetch_assoc();


$user = $_SESSION['user'];
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
    <title><?= $lowongan['nama_lowongan'] . ' | Form Pelamaran' ?></title>

    <link rel="shortcut icon" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/svg/favicon.svg"
        type="image/x-icon">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/app.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/app-dark.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/iconly.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/scss/pages/sweetalert2.scss">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/sweetalert2/sweetalert2.min.css">
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
                <h3>Formulir Pengajuan Lamaran Pekerjaan</h3>
                <p class="text-subtitle text-muted">Data yang dimiliki salah? <a
                        href="/sistem-penerimaan-karyawan/pages/pelamar/profile">Update di sini</a></p>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="post-lamaran-request.php" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="id_lowongan" value="<?= $id_lowongan ?>">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Lengkap</label>
                                        <input type="text" class="form-control" value="<?= $userData['nama_lengkap'] ?>"
                                            disabled>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Tempat, Tanggal Lahir</label>
                                        <div class="row">
                                            <div class="col">
                                                <input type="text" class="form-control"
                                                    value="<?= $profile['tempat_lahir'] ?>" disabled>
                                            </div>
                                            <div class="col">
                                                <input type="date" value="<?= $profile['tanggal_lahir'] ?>"
                                                    class="form-control" disabled>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Nomor HP</label>
                                        <input type="text" class="form-control" value="<?= $profile['nomor_telepon'] ?>"
                                            disabled>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Jenis Kelamin</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" value="1" disabled
                                                <?= $profile['jenis_kelamin'] ? 'checked' : '' ?>>
                                            <label class="form-check-label">
                                                Laki-laki
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" value="0" disabled
                                                <?= !is_null(['jenis_kelamin']) && !$profile['jenis_kelamin'] ? 'checked' : '' ?>>
                                            <label class="form-check-label">
                                                Perempuan
                                            </label>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Pendidikan Terkahir</label>
                                        <select class="form-select" disabled>
                                            <option selected disabled>-- PILIH PENDIDIKAN TERAKHIR --</option>
                                            <option <?= isset($profile['pendidikan_terakhir']) && $profile['pendidikan_terakhir'] === 'SMA/SMK' ? 'selected' : '' ?>>
                                                SMA/SMK
                                            </option>
                                            <option <?= isset($profile['pendidikan_terakhir']) && $profile['pendidikan_terakhir'] === 'Diploma' ? 'selected' : '' ?>>
                                                Diploma
                                            </option>
                                            <option <?= isset($profile['pendidikan_terakhir']) && $profile['pendidikan_terakhir'] === 'Sarjana' ? 'selected' : '' ?>>
                                                Sarjana
                                            </option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Alamat</label>
                                        <textarea cols="30" rows="5" class="form-control"
                                            disabled><?= $profile['alamat'] ?></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Pengalaman Kerja</label>
                                        <textarea name="pengalaman_kerja" id="default"></textarea>
                                        <div class="form-text">Deskripsikan pengalaman kerja mu di sini</div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Curiculum Vitae (CV)</label>
                                        <input type="file" name="curiculum_vitae" id="" class="form-control" required>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>
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
    <script src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/tinymce/tinymce.min.js"></script>
    <script src="/sistem-penerimaan-karyawan/assets/js/tiny-mce.js"></script>
    <script
        src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/sweetalert2/sweetalert2.min.js"></script>
    <script src="/sistem-penerimaan-karyawan/assets/js/sweet-alert.js"></script>
</body>

</html>