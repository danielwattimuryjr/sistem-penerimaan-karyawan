<?php
require_once('./../../../functions/init-session.php');
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/page-protection.php');

$id_permintaan = isset($_GET['id_permintaan']) ? $_GET['id_permintaan'] : null;

if (!$id_permintaan) {
    $type = 'error';
    $message = 'Data permintaan tidak ditemukan';
    header("Location: /sistem-penerimaan-karyawan/pages/departemen/beranda?type=$type&message=".urlencode($message));
    exit();
}

$getPermintaanQueryStr = "SELECT id_permintaan, id_divisi, jumlah_permintaan, status_permintaan FROM permintaan WHERE id_permintaan = ? LIMIT 1";
$getPermintaanStmt = $conn->prepare($getPermintaanQueryStr);
$getPermintaanStmt->bind_param("i", $id_permintaan);
$getPermintaanStmt->execute();
$getPermintaanResult = $getPermintaanStmt->get_result()->fetch_assoc();

if ($getPermintaanResult['status_permintaan'] !== 'Pending') {
    $type = 'error';
    $message = 'Data yang telah disetujui atau dihapus, tidak bisa diubah';
    header("Location: /sistem-penerimaan-karyawan/pages/departemen/beranda?type=$type&message=".urlencode($message));
    exit();
}
$getDivisiQueryStr = "SELECT id_divisi, nama_divisi FROM divisi";

$getDivisiStmt = $conn->prepare($getDivisiQueryStr);
$getDivisiStmt->execute();
$getDivisiResult = $getDivisiStmt->get_result();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Form Permintaan Karyawan</title>

    <!--  Bootstrap 5.3 CSS  -->
    <link rel="stylesheet" href="/sistem-penerimaan-karyawan/assets/css/bootstrap.min.css" crossorigin="anonymous">

    <!--  Data Table CSS  -->
    <link rel="stylesheet" href="/sistem-penerimaan-karyawan/assets/css/datatables.min.css" crossorigin="anonymous">

    <style>
        body {
            background-color: #f1f1f1f1;
        }
    </style>
</head>
<body>
    <?php require_once('./../_components/navbar.php'); ?>

    <div class="container-sm mt-3 mt-lg-5">
        <div class="card" style="width: 100%;">
            <div class="card-body">
                <h5 class="card-title text-center mb-3">Formulir Permintaan Karyawan</h5>

                <form action="update-permintaan-request.php" method="POST">
                    <input type="hidden" name="id_permintaan" value="<?= $id_permintaan ?>">
                    <div class="mb-3">
                        <label class="form-label">Divisi</label>
                        <select class="form-select" name="id_divisi">
                            <option selected disabled>-- PILIH DIVISI --</option>
                            <?php foreach ($getDivisiResult as $divisi) {?>
                                <option value="<?= $divisi['id_divisi'] ?>"
                                    <?= $getPermintaanResult['id_divisi'] === $divisi['id_divisi'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($divisi['nama_divisi']) ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jumlah Permintaan</label>
                        <input type="number" class="form-control" min="0" name="jumlah_permintaan" value="<?= $getPermintaanResult['jumlah_permintaan'] ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>


    <!--  Bootstrap 5.3 JS  -->
    <script src="/sistem-penerimaan-karyawan/assets/js/popper.min.js" crossorigin="anonymous"></script>
    <script src="/sistem-penerimaan-karyawan/assets/js/bootstrap.min.js" crossorigin="anonymous"></script>
</body>
</html>