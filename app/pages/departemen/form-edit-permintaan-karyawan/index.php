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

$id_permintaan = isset($_GET['id_permintaan']) ? $_GET['id_permintaan'] : null;

if (!$id_permintaan) {
    $type = 'error';
    $message = 'Data permintaan tidak ditemukan';
    header("Location: /pages/departemen/beranda?type=$type&message=" . urlencode($message));
    exit();
}

$getPermintaanQueryStr = "SELECT id_permintaan, tanggal_permintaan, p.id_user, u.name, d.id_divisi, jumlah_permintaan, p.jenis_kelamin, status_kerja, tanggal_mulai, tanggal_selesai, status_permintaan FROM permintaan p JOIN user u ON p.id_user = u.id_user JOIN divisi d ON p.id_divisi = d.id_divisi WHERE id_permintaan = ? LIMIT 1";
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
                                <form action="update-permintaan-request.php" method="POST">
                                    <input type="hidden" name="id_permintaan" value="<?= $id_permintaan ?>">

                                    <div class="mb-3">
                                        <label class="form-label">Tanggal Permintaan</label>
                                        <input type="date" class="form-control" name="tanggal_permintaan"
                                            value="<?= $getPermintaanResult['tanggal_permintaan'] ?>" readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Department</label>
                                        <input type="hidden" name="id_user" value="<?= $_SESSION['user']['id_user'] ?>">
                                        <input type="text" class="form-control" readonly disabled
                                            value="<?= $_SESSION['user']['name'] ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Posisi</label>
                                        <select id="id_divisi" name="id_divisi" class="form-control">
                                            <option selected>-- PILIH POSISI --</option>
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
                                            <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" required
                                                min="<?= $today; ?>" max="<?= $lastDayOfMonth; ?>" value="<?= $getPermintaanResult['tanggal_mulai'] ?>">
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                                            <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control" required
                                                min="<?= $today; ?>" max="<?= $lastDayOfMonth; ?>" value="<?= $getPermintaanResult['tanggal_selesai'] ?>">
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

            // This should be populated from the PHP variable $getPermintaanResult['id_divisi']
            const storedIdDivisi = <?= json_encode($getPermintaanResult['id_divisi']); ?>;

            function updateJumlahPermintaan(selectedId) {
                const selectedDivisi = divisiData.find(divisi => divisi.id_divisi === selectedId);

                if (selectedDivisi) {
                    const availableSlots = selectedDivisi.jumlah_personil - selectedDivisi.current_karyawan;

                    inputPermintaan.value = Math.max(0, availableSlots);
                    hiddenJumlahPermintaanInput.value = Math.max(0, availableSlots);
                }
            }

            // Set the selected divisi based on the stored value from the database
            selectDivisi.value = storedIdDivisi;

            // Update the jumlah permintaan based on the initial divisi
            updateJumlahPermintaan(storedIdDivisi);

            // Add an event listener to update jumlah permintaan when the selection changes
            selectDivisi.addEventListener("change", function () {
                updateJumlahPermintaan(parseInt(this.value, 10));
            });
        });

    </script>
</body>

</html>