<?php
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/page-protection.php');

$idLowongan = $_GET['id_lowongan'];
if (!isset($idLowongan)) {
    $message = 'id lowongan tidak valid';
    header("Location: /sistem-penerimaan-karyawan/pages/hrd/beranda?type=error&message=" . urlencode($message));
    exit();
}

$lowonganQuery = "SELECT
    l.id_lowongan,
    l.nama_lowongan,
    l.poster_lowongan,
    l.deskripsi,
    l.tgl_mulai,
    l.tgl_selesai,
    l.id_permintaan
FROM lowongan l
JOIN permintaan p ON l.id_permintaan = p.id_permintaan
WHERE l.id_lowongan = ?";
$lowonganStmt = $conn->prepare($lowonganQuery);
$lowonganStmt->bind_param('i', $idLowongan);
$lowonganStmt->execute();
$lowonganResult = $lowonganStmt->get_result();
$lowonganData = $lowonganResult->fetch_assoc();
if (!$lowonganResult->num_rows) {
    $message = 'Lowongan tidak ditemukan';
    header("Location: /sistem-penerimaan-karyawan/pages/hrd/beranda?type=error&message=" . urlencode($message));
    exit();
}

$permintaanQuery = "SELECT
    p.id_permintaan,
    p.jumlah_permintaan,
    u.name
FROM permintaan p
LEFT JOIN lowongan l ON p.id_permintaan = l.id_permintaan
INNER JOIN user u ON p.id_user = u.id_user
WHERE p.status_permintaan = ?
AND (l.id_lowongan IS NULL OR l.id_lowongan = ?)";
$permintaanStmt = $conn->prepare($permintaanQuery);
$status = 'Disetujui';
$permintaanStmt->bind_param('si', $status, $idLowongan);
$permintaanStmt->execute();
$permintaanResult = $permintaanStmt->get_result();
$permintaanData = $permintaanResult->fetch_all(MYSQLI_ASSOC);

$persyaratanQuery = "SELECT
    pengalaman_kerja,
    umur,
    pendidikan
FROM persyaratan
WHERE id_lowongan = ?";
$persyaratanStmt = $conn->prepare($persyaratanQuery);
$persyaratanStmt->bind_param('i', $idLowongan);
$persyaratanStmt->execute();
$persyaratanResult = $persyaratanStmt->get_result();
$persyaratanData = $persyaratanResult->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Lowongan Pekerjaan</title>

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

<>
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
                <h3>Beranda</h3>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Edit Lowongan Pekerjaan</h5>
                            </div>
                            <div class="card-body">
                                <form action="edit-lowongan-request.php" method="post" class="mt-4"
                                    enctype="multipart/form-data">
                                    <input type="hidden" name="id_lowongan" value="<?= $lowonganData['id_lowongan'] ?>">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Nama Lowongan</label>
                                        <input type="text" name="nama_lowongan" id="" class="form-control"
                                            value="<?= $lowonganData['nama_lowongan'] ?>" required>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-12 col-lg-6">
                                            <label for="" class="form-label">Tanggal Mulai</label>
                                            <input type="date" name="tanggal_mulai" id="" class="form-control"
                                                value="<?= $lowonganData['tgl_mulai'] ?>" required
                                                min="<?php echo date('Y-m-d'); ?>">
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <label for="" class="form-label">Tanggal Selesai</label>
                                            <input type="date" name="tanggal_selesai" id="" class="form-control"
                                                value="<?= $lowonganData['tgl_selesai'] ?>" required
                                                min="<?php echo date('Y-m-d'); ?>">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="" class="form-label">Permintaan</label>
                                        <select name="id_permintaan" id="" class="form-control">
                                            <option value="" selected disabled>-- PILIH PERMINTAAN --</option>
                                            <?php if (!empty($permintaanData)) { ?>
                                                <?php foreach ($permintaanData as $pd) { ?>
                                                    <option value="<?= $pd['id_permintaan']; ?>"
                                                        <?= ($pd['id_permintaan'] === $lowonganData['id_permintaan']) ? 'selected' : ''; ?>>
                                                        [<?= htmlspecialchars($pd['name']); ?>] -
                                                        (<?= $pd['jumlah_permintaan']; ?>
                                                        orang)
                                                    </option>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <option value="">Tidak ada data permintaan</option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="" class="form-label">Poster Lowongan</label>
                                        <input type="file" name="poster_lowongan" id="" class="form-control">
                                        <div class="form-text">Poster saat ini: <a
                                                href="/sistem-penerimaan-karyawan/assets/uploads/poster/<?= $lowonganData['poster_lowongan'] ?>"
                                                target="_blank"><?= $lowonganData['poster_lowongan'] ?></a></div>
                                    </div>
                                    <div class="mb-4">
                                        <label for="" class="form-label">Deskripsi Pekerjaan</label>
                                        <textarea name="deskripsi" id="" cols="30" rows="5" class="form-control"
                                            required><?= $lowonganData['deskripsi'] ?></textarea>
                                    </div>

                                    <p>Isi persyaratan untuk lowongan, pada bagian di bawah ini:</p>

                                    <div class="row mb-3">
                                        <div class="col-12 col-lg-6">
                                            <label for="" class="form-label">Umur</label>
                                            <input type="number" name="umur" id="" class="form-control" required
                                                value="<?= $persyaratanData['umur'] ?>">
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <label for="" class="form-label">Pendidikan Terakhir</label>
                                            <select name="pendidikan" class="form-select" required>
                                                <option selected disabled>-- PILIH PENDIDIKAN TERAKHIR --</option>
                                                <option value="SMA/SMK" <?php echo ($persyaratanData['pendidikan'] === 'SMA/SMK') ? 'selected' : ''; ?>>
                                                    SMA/SMK
                                                </option>
                                                <option value="Diploma" <?php echo ($persyaratanData['pendidikan'] === 'Diploma') ? 'selected' : ''; ?>>
                                                    Diploma
                                                </option>
                                                <option value="Sarjana" <?php echo ($persyaratanData['pendidikan'] === 'Sarjana') ? 'selected' : ''; ?>>
                                                    Sarjana
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="" class="form-label">Pengalaman Kerja</label>
                                        <textarea name="pengalaman_kerja"
                                            id="default"><?= $persyaratanData['pengalaman_kerja'] ?></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-warning">Update</button>
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