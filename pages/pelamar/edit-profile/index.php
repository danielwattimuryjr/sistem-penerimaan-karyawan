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
    <title>Edit Profile</title>

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
            <h5 class="card-title text-center">Edit Profile</h5>

            <form action="update-profile-request.php" method="POST">
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" placeholder="Nama Lengkap" name="nama_lengkap" value="<?= $userData['nama_lengkap'] ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nomor Telepon</label>
                    <input type="text" class="form-control" name="nomor_telepon" placeholder="Nomor HP Aktif" value="<?= $profile['nomor_telepon'] ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tempat, Tanggal Lahir</label>
                    <div class="row">
                        <div class="col">
                            <input type="text" class="form-control" name="tempat_lahir" placeholder="Tempat" value="<?= $profile['tempat_lahir'] ?>" required>
                        </div>
                        <div class="col">
                            <input type="date" name="tanggal_lahir" id="" class="form-control" value="<?= $profile['tanggal_lahir'] ?>" required>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jenis Kelamin</label>
                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="radio"
                            value="1"
                            name="jenis_kelamin"
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
                            name="jenis_kelamin"
                            <?= !is_null(['jenis_kelamin']) && !$profile['jenis_kelamin'] ? 'checked' : '' ?>
                        >
                        <label class="form-check-label">
                            Perempuan
                        </label>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Pendidikan Terkahir</label>
                    <select class="form-select" name="pendidikan_terakhir" required>
                        <option selected disabled>-- PILIH PENDIDIKAN TERAKHIR --</option>
                        <option value="SMA/SMK" <?= isset($profile['pendidikan_terakhir']) && $profile['pendidikan_terakhir'] === 'SMA/SMK' ? 'selected' : '' ?>>
                            SMA/SMK
                        </option>
                        <option value="Diploma" <?= isset($profile['pendidikan_terakhir']) && $profile['pendidikan_terakhir'] === 'Diploma' ? 'selected' : '' ?>>
                            Diploma
                        </option>
                        <option value="Sarjana" <?= isset($profile['pendidikan_terakhir']) && $profile['pendidikan_terakhir'] === 'Sarjana' ? 'selected' : '' ?>>
                            Sarjana
                        </option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" id="" cols="30" rows="5" class="form-control" required><?= $profile['alamat'] ?></textarea>
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
