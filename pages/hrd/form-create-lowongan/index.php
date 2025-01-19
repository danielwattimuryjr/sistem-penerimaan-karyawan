<?php
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/page-protection.php');

$permintaanQuery = "
    SELECT p.id_permintaan, p.jumlah_permintaan, d.nama_divisi
    FROM permintaan p
    LEFT JOIN lowongan l ON p.id_permintaan = l.id_permintaan
    INNER JOIN divisi d ON p.id_divisi = d.id_divisi
    WHERE p.status_permintaan = ? AND l.id_lowongan IS NULL
";
$stmt = $conn->prepare($permintaanQuery);
$status = 'Disetujui';
$stmt->bind_param('s', $status);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Lowongan Pekerjaan</title>

    <link rel="shortcut icon" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/svg/favicon.svg"
        type="image/x-icon">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/app.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/app-dark.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/iconly.css">
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
                <h3>Form Lowongan Pekerjaan</h3>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Tambah Lowongan Pekerjaan</h5>
                            </div>
                            <div class="card-body">
                                <form action="post-lowongan-request.php" method="post" class="mt-4"
                                    enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Nama Lowongan</label>
                                        <input type="text" name="nama_lowongan" id="" class="form-control" required>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-12 col-lg-6">
                                            <label for="" class="form-label">Tanggal Mulai</label>
                                            <input type="date" name="tanggal_mulai" id="" class="form-control" required>
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <label for="" class="form-label">Tanggal Selesai</label>
                                            <input type="date" name="tanggal_selesai" id="" class="form-control"
                                                required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="" class="form-label">Permintaan</label>
                                        <select name="id_permintaan" id="" class="form-control">
                                            <option value="" selected disabled>-- PILIH PERMINTAAN --</option>
                                            <?php if ($result && $result->num_rows > 0): ?>
                                                <?php while ($row = $result->fetch_assoc()): ?>
                                                    <option value="<?= $row['id_permintaan']; ?>">
                                                        [<?= htmlspecialchars($row['nama_divisi']); ?>] -
                                                        (<?= $row['jumlah_permintaan']; ?>
                                                        orang)
                                                    </option>
                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <option value="" disabled>Tidak ada permintaan yang tersedia</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="" class="form-label">Poster Lowongan</label>
                                        <input type="file" name="poster_lowongan" id="" class="form-control" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="" class="form-label">Deskripsi Pekerjaan</label>
                                        <textarea name="deskripsi" id="" cols="30" rows="5" class="form-control"
                                            required></textarea>
                                    </div>

                                    <p>Isi persyaratan untuk lowongan, pada bagian di bawah ini:</p>

                                    <div class="row mb-3">
                                        <div class="col-12 col-lg-6">
                                            <label for="" class="form-label">Umur</label>
                                            <input type="number" name="umur" id="" class="form-control" required>
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <label for="" class="form-label">Pendidikan Terakhir</label>
                                            <select name="pendidikan" class="form-select" required>
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
                                    </div>

                                    <div class="mb-3">
                                        <label for="" class="form-label">Pengalaman Kerja</label>
                                        <textarea name="pengalaman_kerja" id="default"></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Simpan</button>
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
    </body>

</html>