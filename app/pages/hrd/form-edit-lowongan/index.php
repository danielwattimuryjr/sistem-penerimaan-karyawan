<?php
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/page-protection.php');
require_once('./../../../functions/string-helpers.php');

$idLowongan = $_GET['id_lowongan'];
if (!isset($idLowongan)) {
    $message = 'id lowongan tidak valid';
    header("Location: /pages/hrd/beranda?type=error&message=" . urlencode($message));
    exit();
}

$lowonganQuery = "SELECT
    l.id_lowongan,
    l.nama_lowongan,
    l.poster_lowongan,
    l.id_permintaan,
    l.deskripsi,
    l.tgl_mulai,
    l.tgl_selesai,
    l.id_permintaan,
    l.closed,
    p.jumlah_permintaan,
    u.name
FROM lowongan l
JOIN permintaan p ON l.id_permintaan = p.id_permintaan
JOIN user u ON p.id_user = u.id_user
WHERE l.id_lowongan = ?";
$lowonganStmt = $conn->prepare($lowonganQuery);
$lowonganStmt->bind_param('i', $idLowongan);
$lowonganStmt->execute();
$lowonganResult = $lowonganStmt->get_result();
$lowonganData = $lowonganResult->fetch_assoc();

if ($lowonganData['closed']) {
    $message = 'Lowongan ini sudah ditutup. Tidak bisa diubah lagi';
    header("Location: /pages/hrd/beranda?type=error&message=" . urlencode($message));
    exit();
}

if (!$lowonganResult->num_rows) {
    $message = 'Lowongan tidak ditemukan';
    header("Location: /pages/hrd/beranda?type=error&message=" . urlencode($message));
    exit();
}

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

$faktorPenilaianQuery = "SELECT
    nama_faktor,
    bobot
FROM faktor_penilaian
WHERE id_lowongan = ?";
$faktorPenilaianStmt = $conn->prepare($faktorPenilaianQuery);
$faktorPenilaianStmt->bind_param('i', $idLowongan);
$faktorPenilaianStmt->execute();
$faktorPenilaianResult = $faktorPenilaianStmt->get_result();

$fpData = [];
while ($row = $faktorPenilaianResult->fetch_assoc()) {
    $fpData[$row['nama_faktor']] = $row['bobot'];
}

$faktorPenilaian = [
    'tes_tertulis',
    'tes_wawancara',
    'tes_praktek',
    'tes_psikotes',
    'tes_kesehatan',
    'pendidikan',
    'umur',
    'pengalaman_kerja'
];
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
                <h3>Beranda</h3>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12">
                        <form action="edit-lowongan-request.php" method="post" class="mt-4"
                            enctype="multipart/form-data">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Edit Lowongan Pekerjaan</h5>
                                </div>
                                <div class="card-body">
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
                                        <input type="hidden" name="id_permintaan" value="<?= $lowonganData['id_permintaan'] ?>">
                                        <input type="text" class="form-control" disabled required value="<?= $lowonganData['name'] . ' - ' . $lowonganData['jumlah_permintaan'] ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="" class="form-label">Poster Lowongan</label>
                                        <input type="file" name="poster_lowongan" id="" class="form-control">
                                        <div class="form-text">Poster saat ini: <a
                                                href="/assets/uploads/poster/<?= $lowonganData['poster_lowongan'] ?>"
                                                target="_blank"><?= $lowonganData['poster_lowongan'] ?></a></div>
                                    </div>
                                    <div class="mb-4">
                                        <label for="" class="form-label">Deskripsi Pekerjaan</label>
                                        <textarea name="deskripsi" id="default" cols="30" rows="5"
                                            class="form-control"><?= $lowonganData['deskripsi'] ?></textarea>
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
                                        <label class="form-label">Pengalaman Kerja</label>
                                        <div class="input-group">
                                            <input type="number" value="<?= $persyaratanData['pengalaman_kerja'] ?>"
                                                class="form-control" name="pengalaman_kerja">
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
                                                <?php foreach ($faktorPenilaian as $namaFaktor): ?>
                                                                                <tr>
                                                                                    <td><?= toTitleCase($namaFaktor) ?></td>
                                                                                    <td>
                                                                                        <input type="number" name="<?= "fp_$namaFaktor" ?>"
                                                                                            class="form-control bobot-input" required
                                                                                            data-index="<?= $index ?>"
                                                                                            value="<?= $fpData[$namaFaktor] ?>" min="0" max="1"
                                                                                            step="0.01">
                                                                                    </td>
                                                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td>Total Bobot</td>
                                                    <td>
                                                        <input type="number" id="total-bobot" class="form-control"
                                                            disabled>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-warning">Update</button>
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