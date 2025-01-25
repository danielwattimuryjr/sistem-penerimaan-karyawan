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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>

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
                <h3>Edit Profile</h3>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">

                                <form action="update-profile-request.php" method="POST">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Lengkap</label>
                                        <input type="text" class="form-control" placeholder="Nama Lengkap"
                                            name="nama_lengkap" value="<?= $profile['name'] ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Nomor Telepon</label>
                                        <input type="text" class="form-control" name="nomor_telepon"
                                            placeholder="Nomor HP Aktif" value="<?= $profile['nomor_telepon'] ?>"
                                            required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Tempat, Tanggal Lahir</label>
                                        <div class="row">
                                            <div class="col">
                                                <input type="text" class="form-control" name="tempat_lahir"
                                                    placeholder="Tempat" value="<?= $profile['tempat_lahir'] ?>"
                                                    required>
                                            </div>
                                            <div class="col">
                                                <input type="date" name="tanggal_lahir" id="" class="form-control"
                                                    value="<?= $profile['tanggal_lahir'] ?>" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Jenis Kelamin</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" value="1" name="jenis_kelamin"
                                                <?= $profile['jenis_kelamin'] ? 'checked' : '' ?>>
                                            <label class="form-check-label">
                                                Laki-laki
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" value="0" name="jenis_kelamin"
                                                <?= !is_null(['jenis_kelamin']) && !$profile['jenis_kelamin'] ? 'checked' : '' ?>>
                                            <label class="form-check-label">
                                                Perempuan
                                            </label>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Pendidikan Terkahir</label>
                                        <select class="form-select" name="pendidikan_terakhir" required>
                                            <option selected disabled>-- PILIH PENDIDIKAN TERAKHIR --</option>
                                            <option value="SMA/SMK" <?= isset($profile['pendidikan_terakhir']) && $profile['pendidikan_terakhir'] === 'SMA/SMK' ? 'selected' : '' ?>>
                                                SMA/SMK
                                            </option>
                                            <option value="Diploma" <?= isset($profile['pendidikan_terakhir']) && $profile['pendidikan_terakhir'] === 'Diploma' ? 'selected' : '' ?>>
                                                Diploma
                                            </option>
                                            <option value="Sarjana" <?= isset($profile['pendidikan_terakhir']) && $profile['pendidikan_terakhir'] === 'Sarjana' ? 'selected' : '' ?>>
                                                Sarjana
                                            </option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Alamat</label>
                                        <textarea name="alamat" id="" cols="30" rows="5" class="form-control"
                                            required><?= $profile['alamat'] ?></textarea>
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
    <script
        src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/sweetalert2/sweetalert2.min.js"></script>
    <script src="/sistem-penerimaan-karyawan/assets/js/sweet-alert.js"></script>
</body>

</html>