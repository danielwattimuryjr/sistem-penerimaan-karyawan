<?php
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/page-protection.php');
require_once('./../../../functions/string-helpers.php');

$idPermintaan = $_GET['id_permintaan'] ?? null;

if (!$idPermintaan) {
    header('Location: /pages/hrd/permintaan-karyawan?type=error&message=' . urlencode('Data permintaan tidak ditemukan'));
}

$permintaanQuery = "
    SELECT
        p.id_permintaan,
        p.jumlah_permintaan,
        u.name,
        d.nama_divisi
    FROM permintaan p
    INNER JOIN user u ON p.id_user = u.id_user
    JOIN divisi d ON p.id_divisi = d.id_divisi
    WHERE p.id_permintaan = ? AND p.status_permintaan = ?
";
$stmt = $conn->prepare($permintaanQuery);
$status = 'Disetujui';
$stmt->bind_param('is', $idPermintaan, $status);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

$faktorPenilaian = [
    'tes_tertulis' => 0.05,
    'tes_wawancara' => 0.1,
    'tes_praktek' => 0.3,
    'tes_psikotes' => 0.1,
    'tes_kesehatan' => 0.05,
    'pendidikan' => 0.1,
    'umur' => 0.1,
    'pengalaman_kerja' => 0.2
];

$today = date('Y-m-d'); // Current date
$lastDayOfMonth = date('Y-m-t'); // Last day of the current month
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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/iconly.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/scss/pages/sweetalert2.scss">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/sweetalert2/sweetalert2.min.css">
</head>

<>

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
                        <form action="post-lowongan-request.php" method="post" class="mt-4"
                            enctype="multipart/form-data">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Tambah Lowongan Pekerjaan</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Nama Lowongan</label>
                                        <input type="hidden" name="nama_lowongan" value="<?= $result['nama_divisi'] ?>">
                                        <input type="text" name="" id="" class="form-control"
                                            value="<?= $result['nama_divisi'] ?>" disabled required>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-12 col-lg-6">
                                            <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                                            <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                                                class="form-control" min="<?= $today; ?>" max="<?= $lastDayOfMonth; ?>"
                                                required>
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                                            <input type="date" name="tanggal_selesai" id="tanggal_selesai"
                                                class="form-control" min="<?= $today; ?>" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="" class="form-label">Permintaan</label>
                                        <input type="hidden" name="id_permintaan" value="<?= $idPermintaan ?>">
                                        <input type="text" class="form-control"
                                            value="Department: <?= $result['name'] ?>; Divisi: <?= $result['nama_divisi'] ?>; Jumlah: <?= $result['jumlah_permintaan'] ?>"
                                            required disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label for="" class="form-label">Poster Lowongan</label>
                                        <input type="file" name="poster_lowongan" id="" class="form-control" required>
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
                                        <label class="form-label">Pengalaman Kerja</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="pengalaman_kerja">
                                            <span class="input-group-text" id="basic-addon2">Tahun</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Faktor Penilaian</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Nama Kriteria</th>
                                                    <th>Bobot</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($faktorPenilaian as $namaFaktor => $defaultBobot): ?>
                                                    <tr>
                                                        <td><?= toTitleCase($namaFaktor) ?></td>
                                                        <td>
                                                            <input type="number" name="<?= "fp_$namaFaktor" ?>"
                                                                class="form-control bobot-input" required
                                                                data-index="<?= $index ?>" min="0"
                                                                value="<?= $defaultBobot ?>" max="1" step="0.01">
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td>Total Bobot</td>
                                                    <td>
                                                        <input type="number" id="total-bobot" class="form-control"
                                                            value="1" disabled>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
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
    <script>
        const bobotInputs = document.querySelectorAll('.bobot-input');
        const totalBobotField = document.getElementById('total-bobot');

        function calculateTotal() {
            let total = 0;
            bobotInputs.forEach(input => {
                const value = parseFloat(input.value) || 0;
                total += value;
            });
            return total;
        }

        function updateTotalBobot() {
            const total = calculateTotal();
            totalBobotField.value = total;

            if (total > 1) {
                alert('Total bobot tidak boleh lebih dari 1!');
                this.value = '';
                updateTotalBobot();
            }
        }

        // Add event listeners to all inputs
        bobotInputs.forEach(input => {
            input.addEventListener('input', updateTotalBobot);
        });
    </script>

    </body>

</html>
