<?php
require_once('./../../../functions/init-session.php');
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/page-protection.php');

$user = $_SESSION['user'];
if ($user['role'] !== 'Pelamar') {
    $type = "error";
    $message = "Anda bukan seorang pelamar";
    header("Location: /sistem-penerimaan-karyawan/pages/pelamar/beranda?type=$type&message=" . urlencode($message));
    exit();
}

$queryProfile = "SELECT * FROM profile WHERE id_user = ?";
$stmtProfile = $conn->prepare($queryProfile);
$stmtProfile->bind_param('i', $user['id_user']);
$stmtProfile->execute();
$resultProfile = $stmtProfile->get_result();
$profile = $resultProfile->fetch_assoc();

$queryUser = "SELECT nama_lengkap FROM user WHERE id_user = ?";
$stmtUser = $conn->prepare($queryUser);
$stmtUser->bind_param('i', $user['id_user']);
$stmtUser->execute();
$resultUser = $stmtUser->get_result();
$userData = $resultUser->fetch_assoc();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profile</title>

    <!--  Bootstrap 5.3 CSS  -->
    <link rel="stylesheet" href="/sistem-penerimaan-karyawan/assets/css/bootstrap.min.css" crossorigin="anonymous">

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
            <div class="d-flex justify-content-between flex-column flex-md-row align-items-start align-items-lg-ccenter">
                <h5 class="card-title text-center">Profile</h5>

                <a href="/sistem-penerimaan-karyawan/pages/pelamar/edit-profile" class="btn btn-sm btn-warning">Update</a>
            </div>

            <dl class="row mt-4">
                <dt class="col-sm-3">Nama Lengkap</dt>
                <dd class="col-sm-9"><?= $userData['nama_lengkap'] ?? '-' ?></dd>


                <dt class="col-sm-3">Nomor Telepon</dt>
                <dd class="col-sm-9">
                        <?= $profile['nomor_telepon'] ?? '-' ?>
                </dd>

                <dt class="col-sm-3 text-truncate">Jenis Kelamin</dt>
                <dd class="col-sm-9">
                    <?=
                    is_null($profile['jenis_kelamin']) ? '-' :
                        ($profile['jenis_kelamin'] ? 'Laki- laki' : 'Perempuan')
                    ?>
                </dd>

                <dt class="col-sm-3">Pendidikan Terakhir</dt>
                <dd class="col-sm-9"><?= $profile['pendidikan_terakhir'] ?? '-' ?></dd>

                <dt class="col-sm-3">Tempat, Tgl. Lahir</dt>
                <dd class="col-sm-9">
                    <?= ($profile['tempat_lahir'] ?? '-') . ', ' . ($profile['tanggal_lahir'] ?? '-') ?>
                </dd>

                <dt class="col-sm-3">Alamat</dt>
                <dd class="col-sm-9"><?= $profile['alamat'] ?? '-' ?></dd>
            </dl>
        </div>
    </div>
</div>


<!--  Bootstrap 5.3 JS  -->
<script src="/sistem-penerimaan-karyawan/assets/js/popper.min.js" crossorigin="anonymous"></script>
<script src="/sistem-penerimaan-karyawan/assets/js/bootstrap.min.js" crossorigin="anonymous"></script>

<!--  SweetAlert2  -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>

