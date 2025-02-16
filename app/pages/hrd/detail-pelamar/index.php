<?php
require_once('./../../../functions/init-session.php');
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/page-protection.php');

if (!isset($_GET['id_pelamaran']) || empty($_GET['id_pelamaran'])) {
    $type = 'error';
    $message = "ID pelamar tidak ditemukan";
    header('Location: /pages/hrd/data-pelamar?type=error&message=' . urlencode($message));
    exit();
}
$id_pelamaran = $_GET['id_pelamaran'];

$queryStr = "SELECT
    name,
    tempat_lahir,
    tanggal_lahir,
    nomor_telepon,
    jenis_kelamin,
    pendidikan_terakhir,
    alamat,
    curiculum_vitae,
    pengalaman_kerja
FROM pelamaran
WHERE
    id_pelamaran = ?";
$stmt = $conn->prepare($queryStr);
$stmt->bind_param("i", $id_pelamaran);
$stmt->execute();
$result = $stmt->get_result();
$dataPelamar = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pelamar</title>

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
                <h3>Detail Pelamar</h3>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div>
                                    <div class="mb-3">
                                        <label class="form-label">Nama Lengkap</label>
                                        <input type="text" class="form-control"
                                            value="<?= $dataPelamar['name'] ?>" disabled>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Tempat, Tanggal Lahir</label>
                                        <div class="row">
                                            <div class="col">
                                                <input type="text" class="form-control"
                                                    value="<?= $dataPelamar['tempat_lahir'] ?>" disabled>
                                            </div>
                                            <div class="col">
                                                <input type="date" value="<?= $dataPelamar['tanggal_lahir'] ?>"
                                                    class="form-control" disabled>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Nomor HP</label>
                                        <input type="text" class="form-control"
                                            value="<?= $dataPelamar['nomor_telepon'] ?>" disabled>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Jenis Kelamin</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" value="1" disabled
                                                <?= $dataPelamar['jenis_kelamin'] ? 'checked' : '' ?>>
                                            <label class="form-check-label">
                                                Laki-laki
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" value="0" disabled
                                                <?= !is_null(['jenis_kelamin']) && !$dataPelamar['jenis_kelamin'] ? 'checked' : '' ?>>
                                            <label class="form-check-label">
                                                Perempuan
                                            </label>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Pendidikan Terkahir</label>
                                        <select class="form-select" disabled>
                                            <option selected disabled>-- PILIH PENDIDIKAN TERAKHIR --</option>
                                            <option <?= isset($dataPelamar['pendidikan_terakhir']) && $dataPelamar['pendidikan_terakhir'] === 'SMA/SMK' ? 'selected' : '' ?>>
                                                SMA/SMK
                                            </option>
                                            <option <?= isset($dataPelamar['pendidikan_terakhir']) && $dataPelamar['pendidikan_terakhir'] === 'Diploma' ? 'selected' : '' ?>>
                                                Diploma
                                            </option>
                                            <option <?= isset($dataPelamar['pendidikan_terakhir']) && $dataPelamar['pendidikan_terakhir'] === 'Sarjana' ? 'selected' : '' ?>>
                                                Sarjana
                                            </option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Alamat</label>
                                        <textarea cols="30" rows="5" class="form-control"
                                            disabled><?= $dataPelamar['alamat'] ?></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Pengalaman Kerja</label>
                                        <textarea name="pengalaman_kerja" id="test" cols="30" rows="5"
                                            class="form-control" required><?= $dataPelamar['pengalaman_kerja'] ?>
                    </textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Curiculum Vitae (CV)</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control"
                                                value="<?= $dataPelamar['curiculum_vitae'] ?>" disabled>
                                            <a class="btn btn-primary"
                                                href="/assets/uploads/cv/<?= $dataPelamar['curiculum_vitae'] ?>"
                                                target="_blank">Lihat CV</a>
                                        </div>
                                    </div>
                                </div>
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
    <script src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/tinymce/tinymce.min.js"></script>
    <script src="/assets/js/tiny-mce.js"></script>
    <script
        src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/sweetalert2/sweetalert2.min.js"></script>
    <script src="/assets/js/sweet-alert.js"></script>
</body>

</html>