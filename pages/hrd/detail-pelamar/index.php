<?php
require_once('./../../../functions/init-session.php');
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/page-protection.php');

if (!isset($_GET['id_pelamaran']) || empty($_GET['id_pelamaran'])) {
    $type = 'error';
    $message = "ID pelamar tidak ditemukan";
    header('Location: /sistem-penerimaan-karyawan/pages/hrd/data-pelamar?type=error&message=' . urlencode($message));
    exit();
}
$id_pelamaran = $_GET['id_pelamaran'];

$queryStr = "SELECT 
    u.nama_lengkap,
    p.tempat_lahir,
    p.tanggal_lahir,
    p.nomor_telepon,
    p.jenis_kelamin,
    p.pendidikan_terakhir,
    p.alamat,
    pel.curiculum_vitae,
    pel.pengalaman_kerja
FROM 
    user u
JOIN 
    profile p ON u.id_user = p.id_user
JOIN 
    pelamaran pel ON u.id_user = pel.id_user
WHERE 
    pel.id_pelamaran = ?";
$stmt = $conn->prepare($queryStr);
$stmt->bind_param("i", $id_pelamaran);
$stmt->execute();
$result = $stmt->get_result();
$dataPelamar = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Data Pelamar</title>

    <?php require_once('./../_components/styles.php'); ?>
</head>
<body>
<?php require_once('./../_components/navbar.php');?>

<div class="container-sm mt-3 mt-lg-5">
    <div class="card" style="width: 100%;">
        <div class="card-body">
            <h5 class="card-title text-center">Detail Pelamar</h5>

            <div>
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" value="<?= $dataPelamar['nama_lengkap'] ?>" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tempat, Tanggal Lahir</label>
                    <div class="row">
                        <div class="col">
                            <input type="text" class="form-control" value="<?= $dataPelamar['tempat_lahir'] ?>" disabled>
                        </div>
                        <div class="col">
                            <input type="date" value="<?= $dataPelamar['tanggal_lahir'] ?>" class="form-control" disabled>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nomor HP</label>
                    <input type="text" class="form-control" value="<?= $dataPelamar['nomor_telepon'] ?>" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jenis Kelamin</label>
                    <div class="form-check">
                        <input
                                class="form-check-input"
                                type="radio"
                                value="1"
                                disabled
                            <?= $dataPelamar['jenis_kelamin'] ? 'checked' : '' ?>
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
                            <?= !is_null(['jenis_kelamin']) && !$dataPelamar['jenis_kelamin'] ? 'checked' : '' ?>
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
                        <option <?= isset($dataPelamar['pendidikan_terakhir']) && $dataPelamar['pendidikan_terakhir'] === 'SMA/SMK' ? 'selected' : '' ?>>
                            SMA/SMK
                        </option>
                        <option <?= isset($dataPelamar['pendidikan_terakhir']) && $dataPelamar['pendidikan_terakhir'] === 'Diploma' ? 'selected' : '' ?>>
                            Diploma
                        </option>
                        <option <?= isset($dataPelamar['pendidikan_terakhir']) && $dataPelamar['pendidikan_terakhir'] === 'Sarjana' ? 'selected' : '' ?>>
                            Sarjana
                        </option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea cols="30" rows="5" class="form-control" disabled><?= $dataPelamar['alamat'] ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Pengalaman Kerja</label>
                    <textarea name="pengalaman_kerja" id="" cols="30" rows="5" class="form-control" disabled required><?= $dataPelamar['pengalaman_kerja'] ?>
                    </textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Curiculum Vitae (CV)</label>
                    <div class="input-group">
                        <input type="text" class="form-control" value="<?= $dataPelamar['curiculum_vitae'] ?>" disabled>
                        <a class="btn btn-primary" href="/sistem-penerimaan-karyawan/assets/uploads/cv/<?= $dataPelamar['curiculum_vitae'] ?>" target="_blank">Lihat CV</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once ('./../_components/scripts.php'); ?>
</body>
</html>