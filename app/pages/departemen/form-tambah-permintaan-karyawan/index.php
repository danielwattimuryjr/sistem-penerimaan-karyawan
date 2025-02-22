<?php
require_once('./../../../functions/init-session.php');
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/page-protection.php');

$sql = "SELECT * FROM divisi_status WHERE id_department = ? AND isInNeed = 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user']['id_user']);
$stmt->execute();
$divisiData = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$queryStr = "SELECT id_user, name FROM user WHERE role = 'Departement'";

$stmt = $conn->prepare($queryStr);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
$conn->close();

$today = date('Y-m-d'); // Current date
$lastDayOfMonth = date('Y-m-t'); // Last day of the current month
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
                                <form action="store-permintaan-request.php" method="POST">
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal Permintaan</label>
                                        <input type="hidden" name="tanggal_permintaan" value="<?= date('Y-m-d'); ?>">
                                        <input type="date" class="form-control" required readonly
                                            value="<?= date('Y-m-d'); ?>" disabled>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Department</label>
                                        <input type="hidden" name="id_user" value="<?= $_SESSION['user']['id_user'] ?>">
                                        <input type="text" class="form-control" readonly disabled
                                            value="<?= $_SESSION['user']['name'] ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Posisi</label>
                                        <input type="hidden" name="id_divisi" value="<?= $_GET['id_divisi'] ?? null ?>">
                                        <select id="id_divisi" name="id_divisi" class="form-control">
                                            <option selected disabled>-- PILIH POSISI --</option>
                                            <?php foreach ($divisiData as $dd): ?>
                                                <option value="<?= $dd['id_divisi'] ?>"><?= $dd['nama_divisi'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Jumlah Permintaan</label>
                                        <input type="hidden" name="jumlah_permintaan">
                                        <input type="number" class="form-control" id="jumlah_permintaan" value="0"
                                            required readonly disabled>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Jenis Kelamin</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="Laki-laki"
                                                name="jenis_kelamin[]" id="laki-laki">
                                            <label class="form-check-label" for="laki-laki">
                                                Laki-laki
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="Perempuan"
                                                name="jenis_kelamin[]" id="perempuan">
                                            <label class="form-check-label" for="perempuan">
                                                Perempuan
                                            </label>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="" class="form-label">Status Kerja</label>
                                        <select name="status_kerja" id="" class="form-control" required>
                                            <option selected disabled>-- PILIH STATUS KERJA --</option>
                                            <option value="daily-worker">Daily Worker</option>
                                            <option value="karyawan-kontrak">Karyawan Kontrak</option>
                                        </select>
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
    <script>
        const divisiData = <?= json_encode($divisiData, JSON_PRETTY_PRINT) ?>;

        document.addEventListener("DOMContentLoaded", function () {
            const selectDivisi = document.getElementById("id_divisi");
            const inputPermintaan = document.getElementById("jumlah_permintaan");
            const hiddenJumlahPermintaanInput = document.querySelector("input[name='jumlah_permintaan']");

            function getQueryParam(param) {
                const urlParams = new URLSearchParams(window.location.search);
                return urlParams.get(param);
            }

            const urlIdDivisi = getQueryParam("id_divisi");

            function updateJumlahPermintaan(selectedId) {
                const selectedDivisi = divisiData.find(divisi => divisi.id_divisi === selectedId);

                if (selectedDivisi) {
                    const availableSlots = selectedDivisi.jumlah_personil - selectedDivisi.current_karyawan;

                    inputPermintaan.value = Math.max(0, availableSlots);
                    hiddenJumlahPermintaanInput.value = Math.max(0, availableSlots);
                }
            }

            if (urlIdDivisi) {
                const selectedId = parseInt(urlIdDivisi, 10);

                selectDivisi.value = selectedId;

                selectDivisi.setAttribute("disabled", "disabled");

                updateJumlahPermintaan(selectedId);
            } else {
                selectDivisi.addEventListener("change", function () {
                    updateJumlahPermintaan(parseInt(this.value, 10));
                });
            }
        });
    </script>
</body>

</html>
