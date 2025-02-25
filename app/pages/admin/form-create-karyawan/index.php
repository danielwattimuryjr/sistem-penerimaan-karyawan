<?php
require_once('./../../../functions/init-session.php');
require_once('./../../../functions/init-conn.php');

$sql = "
    SELECT
        id_divisi,
        nama_divisi,
        nama_department,
        jumlah_personil AS jumlah_max,
        current_karyawan AS jumlah_saat_ini,
        isInNeed
    FROM divisi_status
    HAVING jumlah_saat_ini < jumlah_max
";

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$divisi = [];
while ($row = $result->fetch_assoc()) {
    $divisi[] = $row;
}
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Karyawan</title>

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
                <h5 class="card-title">Formulir Tambah Karyawan</h5>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="post.php" method="POST">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Lengkap</label>
                                        <input type="text" class="form-control" name="name" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Tempat, Tanggal Lahir</label>
                                        <div class="row">
                                            <div class="col">
                                                <input type="text" class="form-control" name="tempat_lahir" required>
                                            </div>
                                            <div class="col">
                                                <input type="date" name="tanggal_lahir" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Nomor HP</label>
                                        <input type="text" class="form-control" name="nomor_telepon" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Jenis Kelamin</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" value="1" name="jenis_kelamin"
                                                required>
                                            <label class="form-check-label">
                                                Laki-laki
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" value="0" name="jenis_kelamin"
                                                required>
                                            <label class="form-check-label">
                                                Perempuan
                                            </label>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Pendidikan Terkahir</label>
                                        <select class="form-select" name="pendidikan_terakhir" required>
                                            <option selected disabled>-- PILIH PENDIDIKAN TERAKHIR --</option>
                                            <option value="SMA/SMK">
                                                SMA/SMK
                                            </option>
                                            <option value="Diploma">
                                                Diploma
                                            </option>
                                            <option value="Sarjana">
                                                Sarjana
                                            </option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Alamat</label>
                                        <textarea cols="30" rows="5" class="form-control" name="alamat"
                                            required></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Divisi</label>
                                        <select class="form-select" name="id_divisi" required>
                                            <option selected disabled>-- PILIH DIVISI --
                                            </option>
                                            <?php foreach ($divisi as $d): ?>
                                                <option value="<?= $d['id_divisi'] ?>">
                                                    <?= htmlspecialchars($d['nama_department'] . ' - ' . $d['nama_divisi']) ?>
                                                    (Max: <?= $d['jumlah_max'] ?>; Current <?= $d['jumlah_saat_ini'] ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
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
