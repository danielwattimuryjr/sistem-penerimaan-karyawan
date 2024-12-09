<?php
require_once('./../../../functions/init-session.php');
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/page-protection.php');
//
//// Get id_lowongan
//$id_lowongan = isset($_GET['id_lowongan']) ? $_GET['id_lowongan'] : null;
////
//if (!$id_lowongan) {
//    header("Location: /sistem-penerimaan-karyawan/pages/departemen/beranda");
//}
//
//$getLowonganQueryStr = "SELECT nama_lowongan, deskripsi FROM lowongan LIMIT 1";
//$getLowonganResult = $conn->query($getLowonganQueryStr);
//$lowongan = $getLowonganResult->fetch_assoc();
//
//$getPersyaratanStr = "SELECT pengalaman_kerja, umur, pendidikan FROM persyaratan WHERE id_lowongan = ? LIMIT 1";
//$stmt = $conn->prepare($getPersyaratanStr);
//$stmt->bind_param("i", $id_lowongan);
//$stmt->execute();
//$getPersyaratanResult = $stmt->get_result();
//$persyaratan = $getPersyaratanResult->fetch_assoc();

$persyaratan = [
    'umur' => 37,
    'pendidikan' => 'SMA',
    'pengalaman_kerja' => '3 tahun sebagai office boy'
];

$lowongan = [
    'nama_lowongan' => 'office boy',
    'deskripsi' => 'test'
];
$id_lowongan = 1;

$user = $_SESSION['user'];
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
    <title><?= $lowongan['nama_lowongan']  . ' | Form Pelamaran' ?></title>

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
            <h5 class="card-title text-center">Formulir Pengajuan Lamaran Pekerjaan</h5>
            <h5 class="card-title text-center"><?= $lowongan['nama_lowongan'] ?></h5>

            <p class="mt-3">
                Data yang dimiliki salah? <a href="/sistem-penerimaan-karyawan/pages/pelamar/profile">Update di sini</a>
            </p>

            <form action="post-lamaran-request.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_lowongan" value="<?= $id_lowongan?>">
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" value="<?= $userData['nama_lengkap'] ?>" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tempat, Tanggal Lahir</label>
                    <div class="row">
                        <div class="col">
                            <input type="text" class="form-control" value="<?= $profile['tempat_lahir'] ?>" disabled>
                        </div>
                        <div class="col">
                            <input type="date" value="<?= $profile['tanggal_lahir'] ?>" class="form-control" disabled>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nomor HP</label>
                    <input type="text" class="form-control" value="<?= $profile['nomor_telepon'] ?>" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jenis Kelamin</label>
                    <div class="form-check">
                        <input
                                class="form-check-input"
                                type="radio"
                                value="1"
                                disabled
                            <?= $profile['jenis_kelamin'] ? 'checked' : '' ?>
                        >
                        <label class="form-check-label">
                            Laki-laki
                        </label>
                    </div>
                    <div class="form-check">
                        <input
                                class="form-check-input"
                                type="radio"
                                value="0"
                            <?= !is_null(['jenis_kelamin']) && !$profile['jenis_kelamin'] ? 'checked' : '' ?>
                                disabled
                        >
                        <label class="form-check-label">
                            Perempuan
                        </label>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Pendidikan Terkahir</label>
                    <select class="form-select" disabled>
                        <option selected disabled>-- PILIH PENDIDIKAN TERAKHIR --</option>
                        <option <?= isset($profile['pendidikan_terakhir']) && $profile['pendidikan_terakhir'] === 'SMA/SMK' ? 'selected' : '' ?>>
                            SMA/SMK
                        </option>
                        <option <?= isset($profile['pendidikan_terakhir']) && $profile['pendidikan_terakhir'] === 'Diploma' ? 'selected' : '' ?>>
                            Diploma
                        </option>
                        <option <?= isset($profile['pendidikan_terakhir']) && $profile['pendidikan_terakhir'] === 'Sarjana' ? 'selected' : '' ?>>
                            Sarjana
                        </option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea cols="30" rows="5" class="form-control" disabled><?= $profile['alamat'] ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Pengalaman Kerja</label>
                    <textarea name="pengalaman_kerja" id="" cols="30" rows="5" class="form-control" required></textarea>
                    <div class="form-text">Deskripsikan pengalaman kerja mu di sini</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Curiculum Vitae (CV)</label>
                    <input type="file" name="curiculum_vitae" id="" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
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
