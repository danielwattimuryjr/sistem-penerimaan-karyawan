<?php
require_once('./../../../functions/init-session.php');
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/page-protection.php');

$id_lowongan = $_GET['id_lowongan'] ?? null;
$id_user = $_SESSION['user']['id_user'];
if (!$id_lowongan) {
    header("Location: /sistem-penerimaan-karyawan/pages/departemen/beranda");
}

$checkPelamaranQueryStr = "
    SELECT COUNT(*) as total
    FROM pelamaran
    WHERE id_user = ? AND id_lowongan = ?
";
$checkPelamaranStmt = $conn->prepare($checkPelamaranQueryStr);
$checkPelamaranStmt->bind_param('ii', $id_user, $id_lowongan);
$checkPelamaranStmt->execute();
$checkPelamaranResult = $checkPelamaranStmt->get_result();
$checkPelamaran = $checkPelamaranResult->fetch_assoc();

$getLowonganQueryStr = "SELECT nama_lowongan, deskripsi FROM lowongan LIMIT 1";
$getLowonganResult = $conn->query($getLowonganQueryStr);
$lowongan = $getLowonganResult->fetch_assoc();

$getPersyaratanStr = "SELECT pengalaman_kerja, umur, pendidikan FROM persyaratan WHERE id_lowongan = ? LIMIT 1";
$stmt = $conn->prepare($getPersyaratanStr);
$stmt->bind_param("i", $id_lowongan);
$stmt->execute();
$getPersyaratanResult = $stmt->get_result();
$persyaratan = $getPersyaratanResult->fetch_assoc();

$isApplied = ($checkPelamaran['total'] > 0);
$formPelamaranUrl = "/sistem-penerimaan-karyawan/pages/pelamar/form-pelamaran?id_lowongan=$id_lowongan";
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $lowongan['nama_lowongan'] ?? 'Detail Lowongan'?></title>

    <?php require_once ('./../_components/styles.php'); ?>
</head>
<body>
    <?php require_once('./../_components/navbar.php'); ?>

    <div class="container-sm mt-3 mt-lg-5">
        <div class="card" style="width: 100%;">
            <div class="card-body">
                <div class="d-flex flex-column flex-lg-row gap-3 align-items-center align-items-lg-start">
                    <img src="https://placehold.co/300" alt="" style="width: 300px">

                    <div class="d-flex flex-column text-start">
                        <h2><?= $lowongan['nama_lowongan'] ?></h2>
                        <h5>Deskripsi Pekerjaan :</h5>
                        <p><?= $lowongan['deskripsi'] ?></p>

                        <h5>Persyaratan :</h5>
                        <ul>
                            <li>Pria/Wanita usia maksimal <?= $persyaratan['umur'] ?> tahun</li>
                            <li>Pendidikan minimal <?= $persyaratan['pendidikan'] ?></li>
                            <li><?= $persyaratan['pengalaman_kerja'] ?></li>
                        </ul>

                        <a href="<?= $formPelamaranUrl ?>" class="btn btn-primary <?= $isApplied ? 'disabled' : '' ?>">Ajukan Lamaran</a>
                        <p class="form-text <?= $isApplied ? '' : 'disabled' ?>">Kamu sudah mendaftar</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <?php require_once ('./../_components/scripts.php'); ?>
</body>
</html>
