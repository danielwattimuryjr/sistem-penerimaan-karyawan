<?php
require_once('./../../../functions/init-session.php');
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/page-protection.php');

$id_permintaan = isset($_GET['id_permintaan']) ? $_GET['id_permintaan'] : null;

if (!$id_permintaan) {
    $type = 'error';
    $message = 'Data permintaan tidak ditemukan';
    header("Location: /pages/departemen/beranda?type=$type&message=" . urlencode($message));
    exit();
}

$getPermintaanQueryStr = "SELECT id_permintaan, tanggal_permintaan, p.id_user, u.name, posisi, jumlah_permintaan, p.jenis_kelamin, status_kerja, tanggal_mulai, tanggal_selesai status_permintaan FROM permintaan p JOIN user u ON p.id_user = u.id_user WHERE id_permintaan = ? LIMIT 1";
$getPermintaanStmt = $conn->prepare($getPermintaanQueryStr);
$getPermintaanStmt->bind_param("i", $id_permintaan);
$getPermintaanStmt->execute();
$getPermintaanResult = $getPermintaanStmt->get_result()->fetch_assoc();

if ($getPermintaanResult['status_permintaan'] !== 'Pending') {
    $type = 'error';
    $message = 'Data yang telah disetujui atau ditolak, tidak bisa diubah atau dihapus';
    header("Location: /pages/departemen/permintaan-karyawan?type=$type&message=" . urlencode($message));
    exit();
}

$storedJenisKelamin = $getPermintaanResult['jenis_kelamin'];
$selectedJenisKelamin = explode(',', $storedJenisKelamin);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Permintaan Karyawan</title>

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
                <h5 class="card-title">Formulir Permintaan Karyawan</h5>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="update-permintaan-request.php" method="POST">
                                    <input type="hidden" name="id_permintaan" value="<?= $id_permintaan ?>">

                                    <div class="mb-3">
                                        <label class="form-label">Tanggal Permintaan</label>
                                        <input type="date" class="form-control" name="tanggal_permintaan"
                                            value="<?= $getPermintaanResult['tanggal_permintaan'] ?>" readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Department</label>
                                        <input type="hidden" name="id_divisi"
                                            value="<?= $_SESSION['user']['id_user'] ?>">
                                        <input type="text" class="form-control" readonly
                                            value="<?= $_SESSION['user']['name'] ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Posisi</label>
                                        <input type="text" class="form-control" name="posisi"
                                            value="<?= $getPermintaanResult['posisi'] ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Jumlah Permintaan</label>
                                        <input type="number" class="form-control" min="0" name="jumlah_permintaan"
                                            value="<?= $getPermintaanResult['jumlah_permintaan'] ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Jenis Kelamin</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="Laki-laki"
                                                name="jenis_kelamin[]" id="laki-laki" <?php echo in_array('Laki-laki', $selectedJenisKelamin) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="laki-laki">
                                                Laki-laki
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="Perempuan"
                                                name="jenis_kelamin[]" id="perempuan" <?php echo in_array('Perempuan', $selectedJenisKelamin) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="perempuan">
                                                Perempuan
                                            </label>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="" class="form-label">Status Kerja</label>
                                        <select name="status_kerja" id="" class="form-control" required>
                                            <option selected disabled>-- PILIH STATUS KERJA --</option>
                                            <option value="daily-worker"
                                                <?= $getPermintaanResult['status_kerja'] === 'daily-worker' ? 'selected' : '' ?>>Daily Worker</option>
                                            <option value="karyawan-kontrak"
                                                <?= $getPermintaanResult['status_kerja'] === 'karyawan-kontrak' ? 'selected' : '' ?>>
                                                Karyawan Kontrak</option>
                                        </select>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-12 col-lg-6">
                                            <label for="" class="form-label">Tanggal Mulai</label>
                                            <input type="date" name="tanggal_mulai" id="" class="form-control"
                                                value="<?= $getPermintaanResult['tanggal_mulai'] ?>"
                                                min="<?php echo date('Y-m-d'); ?>" required>
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <label for="" class="form-label">Tanggal Selesai</label>
                                            <input type="date" name="tanggal_selesai"
                                                value="<?= $getPermintaanResult['tanggal_selesai'] ?>"
                                                class="form-control" min="<?php echo date('Y-m-d'); ?>">
                                            <div class="form-text">Opsional</div>
                                        </div>
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