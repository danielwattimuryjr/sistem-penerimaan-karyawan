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
    id_lowongan,
    name,
    FLOOR(DATEDIFF(CURDATE(), tanggal_lahir) / 365.25) as umur,
    pendidikan_terakhir,
    pengalaman_kerja
FROM pelamaran
WHERE id_pelamaran = ?";
$stmt = $conn->prepare($queryStr);
$stmt->bind_param("i", $id_pelamaran);
$stmt->execute();
$result = $stmt->get_result();
$dataPelamar = $result->fetch_assoc();

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $dataPelamar['name'] . ' | Penilaian Pelamar' ?></title>

    <link rel="shortcut icon" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/svg/favicon.svg"
        type="image/x-icon">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/app.css">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/iconly.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/scss/pages/sweetalert2.scss">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/sweetalert2/sweetalert2.min.css">
</head>


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
            <h3>Penilaian Pelamar</h3>
        </div>
        <div class="page-content">
            <section class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="post-penilaian-request.php">
                                <input type="hidden" name="id_pelamaran" value="<?= $id_pelamaran ?>">
                                <div class="mb-3">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" disabled
                                        value="<?= $dataPelamar['name'] ?>">
                                </div>

                                <div class="mb-5">
                                    <label for="" class="form-label">Tes Tertulis</label>
                                    <div class="d-flex justify-content-between align-items-center gap-2 mx-5 mt-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="tes_tertulis"
                                                    value="1" required>
                                            </div>
                                            <label class="form-check-label small">0 - 20</label>
                                        </div>
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="tes_tertulis"
                                                    value="2" required>
                                            </div>
                                            <label class="form-check-label small">21 - 40</label>
                                        </div>
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="tes_tertulis"
                                                    value="3" required>
                                            </div>
                                            <label class="form-check-label small">41 - 60</label>
                                        </div>
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="tes_tertulis"
                                                    value="4" required>
                                            </div>
                                            <label class="form-check-label small">61 - 80</label>
                                        </div>
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="tes_tertulis"
                                                    value="5" required>
                                            </div>
                                            <label class="form-check-label small">81 - 100</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-5">
                                    <label class="form-label">Tes Wawancara</label>
                                    <div class="d-flex justify-content-between align-items-center gap-2 mx-5 mt-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="tes_wawancara"
                                                    value="1" required>
                                            </div>
                                            <label class="form-check-label small">Sangat Kurang</label>
                                        </div>
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="tes_wawancara"
                                                    value="2" required>
                                            </div>
                                            <label class="form-check-label small">Kurang</label>
                                        </div>
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="tes_wawancara"
                                                    value="3" required>
                                            </div>
                                            <label class="form-check-label small">Cukup</label>
                                        </div>
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="tes_wawancara"
                                                    value="4" required>
                                            </div>
                                            <label class="form-check-label small">Baik</label>
                                        </div>
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="tes_wawancara"
                                                    value="5" required>
                                            </div>
                                            <label class="form-check-label small">Sangat Baik</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-5">
                                    <label class="form-label">Tes Praktek</label>
                                    <div class="d-flex justify-content-between align-items-center gap-2 mx-5 mt-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="tes_praktek"
                                                    value="1" required>
                                            </div>
                                            <label class="form-check-label small">Sangat Kurang</label>
                                        </div>
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="tes_praktek"
                                                    value="2" required>
                                            </div>
                                            <label class="form-check-label small">Kurang</label>
                                        </div>
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="tes_praktek"
                                                    value="3" required>
                                            </div>
                                            <label class="form-check-label small">Cukup</label>
                                        </div>
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="tes_praktek"
                                                    value="4" required>
                                            </div>
                                            <label class="form-check-label small">Baik</label>
                                        </div>
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="tes_praktek"
                                                    value="5" required>
                                            </div>
                                            <label class="form-check-label small">Sangat Baik</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-5">
                                    <label class="form-label">Tes Psikotes</label>
                                    <div class="d-flex justify-content-between align-items-center gap-2 mx-5 mt-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="tes_psikotes"
                                                    value="1" required>
                                            </div>
                                            <label class="form-check-label small">0 - 20</label>
                                        </div>
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="tes_psikotes"
                                                    value="2" required>
                                            </div>
                                            <label class="form-check-label small">21 - 40</label>
                                        </div>
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="tes_psikotes"
                                                    value="3" required>
                                            </div>
                                            <label class="form-check-label small">41 - 60</label>
                                        </div>
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="tes_psikotes"
                                                    value="4" required>
                                            </div>
                                            <label class="form-check-label small">61 - 80</label>
                                        </div>
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="tes_psikotes"
                                                    value="5" required>
                                            </div>
                                            <label class="form-check-label small">81 - 100</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-5">
                                    <label class="form-label">Tes Kesehatan</label>
                                    <div class="d-flex justify-content-between align-items-center gap-2 mx-5 mt-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="tes_kesehatan"
                                                    value="1" required>
                                            </div>
                                            <label class="form-check-label small">Sangat Kurang</label>
                                        </div>
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="tes_kesehatan"
                                                    value="2" required>
                                            </div>
                                            <label class="form-check-label small">Kurang</label>
                                        </div>
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="tes_kesehatan"
                                                    value="3" required>
                                            </div>
                                            <label class="form-check-label small">Cukup</label>
                                        </div>
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="tes_kesehatan"
                                                    value="4" required>
                                            </div>
                                            <label class="form-check-label small">Baik</label>
                                        </div>
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="tes_kesehatan"
                                                    value="5" required>
                                            </div>
                                            <label class="form-check-label small">Sangat Baik</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-5">
                                    <label class="form-label">Pendidikan</label>
                                    <div class="d-flex justify-content-between align-items-center gap-2 mx-5 mt-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="pendidikan" value="1"
                                                    required <?= $dataPelamar['pendidikan_terakhir'] === 'SMA/SMK' ? 'checked' : '' ?>>
                                            </div>
                                            <label class="form-check-label small">SMA/SMK</label>
                                        </div>
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="pendidikan" value="2"
                                                    required <?= $dataPelamar['pendidikan_terakhir'] === 'Diploma' ? 'checked' : '' ?>>
                                            </div>
                                            <label class="form-check-label small">D1/D2</label>
                                        </div>
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="pendidikan" value="3"
                                                    required>
                                            </div>
                                            <label class="form-check-label small">D3</label>
                                        </div>
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="pendidikan" value="4"
                                                    required <?= $dataPelamar['pendidikan_terakhir'] === 'Sarjana' ? 'checked' : '' ?>>
                                            </div>
                                            <label class="form-check-label small">S1</label>
                                        </div>
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="pendidikan" value="5"
                                                    required>
                                            </div>
                                            <label class="form-check-label small">S2</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-5">
                                    <label class="form-label">Umur</label>
                                    <div class="d-flex justify-content-between align-items-center gap-2 mx-5 mt-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="umur" value="1"
                                                    required <?= ($dataPelamar['umur'] >= 18 && $dataPelamar['umur'] <= 21) ? 'checked' : '' ?>>
                                            </div>
                                            <label class="form-check-label small">18 - 21</label>
                                        </div>
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="umur" value="2"
                                                    required <?= ($dataPelamar['umur'] >= 22 && $dataPelamar['umur'] <= 24) ? 'checked' : '' ?>>
                                            </div>
                                            <label class="form-check-label small">22 - 24</label>
                                        </div>
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="umur" value="3"
                                                    required <?= ($dataPelamar['umur'] >= 25 && $dataPelamar['umur'] <= 26) ? 'checked' : '' ?>>
                                            </div>
                                            <label class="form-check-label small">25 - 26</label>
                                        </div>
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="umur" value="4"
                                                    required <?= ($dataPelamar['umur'] >= 27 && $dataPelamar['umur'] <= 28) ? 'checked' : '' ?>>
                                            </div>
                                            <label class="form-check-label small">27 - 28</label>
                                        </div>
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="umur" value="5"
                                                    required <?= ($dataPelamar['umur'] >= 29) ? 'checked' : '' ?>>
                                            </div>
                                            <label class="form-check-label small">> 29</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Pengalaman Kerja (Tahun)</label>
                                    <div class="d-flex justify-content-between align-items-center gap-2 mx-5 mt-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="pengalaman_kerja"
                                                    value="1" required <?= ($dataPelamar['pengalaman_kerja'] === 1) ? 'checked' : '' ?>>
                                            </div>
                                            <label class="form-check-label small">1</label>
                                        </div>
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="pengalaman_kerja"
                                                    value="2" required <?= ($dataPelamar['pengalaman_kerja'] >= 1 && $dataPelamar['pengalaman_kerja'] <= 2) ? 'checked' : '' ?>>
                                            </div>
                                            <label class="form-check-label small">1 - 2</label>
                                        </div>
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="pengalaman_kerja"
                                                    value="3" required <?= ($dataPelamar['pengalaman_kerja'] >= 2 && $dataPelamar['pengalaman_kerja'] <= 4) ? 'checked' : '' ?>>
                                            </div>
                                            <label class="form-check-label small">2 - 4</label>
                                        </div>
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="pengalaman_kerja"
                                                    value="4" required <?= ($dataPelamar['pengalaman_kerja'] >= 4 && $dataPelamar['pengalaman_kerja'] <= 6) ? 'checked' : '' ?>>
                                            </div>
                                            <label class="form-check-label small">4 - 6</label>
                                        </div>
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="pengalaman_kerja"
                                                    value="5" required <?= ($dataPelamar['pengalaman_kerja'] >= 6) ? 'checked' : '' ?>>
                                            </div>
                                            <label class="form-check-label small">> 6</label>
                                        </div>
                                    </div>
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