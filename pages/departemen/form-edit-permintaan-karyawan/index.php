<?php
require_once('./../../../functions/init-session.php');
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/page-protection.php');

$id_permintaan = isset($_GET['id_permintaan']) ? $_GET['id_permintaan'] : null;

if (!$id_permintaan) {
    $type = 'error';
    $message = 'Data permintaan tidak ditemukan';
    header("Location: /sistem-penerimaan-karyawan/pages/departemen/beranda?type=$type&message=" . urlencode($message));
    exit();
}

$getPermintaanQueryStr = "SELECT id_permintaan, tanggal_permintaan, id_divisi, posisi, jumlah_permintaan, jenis_kelamin, status_kerja, tanggal_mulai, tanggal_selesai, keperluan, status_permintaan FROM permintaan WHERE id_permintaan = ? LIMIT 1";
$getPermintaanStmt = $conn->prepare($getPermintaanQueryStr);
$getPermintaanStmt->bind_param("i", $id_permintaan);
$getPermintaanStmt->execute();
$getPermintaanResult = $getPermintaanStmt->get_result()->fetch_assoc();

if ($getPermintaanResult['status_permintaan'] !== 'Pending') {
    $type = 'error';
    $message = 'Data yang telah disetujui atau ditolak, tidak bisa diubah atau dihapus';
    header("Location: /sistem-penerimaan-karyawan/pages/departemen/permintaan-karyawan?type=$type&message=" . urlencode($message));
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

    <?php require_once('./../_components/styles.php'); ?>
    <script src="https://cdn.tiny.cloud/1/weuk5gq9uk3b6yfox67jdajpmljl7u042vnu0zhqus3u0dqg/tinymce/7/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: 'textarea#tiny',
            plugins: 'lists, link, image, media',
            toolbar: 'h1 h2 bold italic strikethrough blockquote bullist numlist backcolor | link ',
            menubar: false,
        });
    </script>
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
                        <label class="form-label">Tanggal Permintaan</label>
                        <input type="date" class="form-control" name="tanggal_permintaan"
                            value="<?= $getPermintaanResult['tanggal_permintaan'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Divisi</label>
                        <select class="form-select" name="id_divisi">
                            <option selected disabled>-- PILIH DIVISI --</option>
                            <?php foreach ($getDivisiResult as $divisi) { ?>
                                <option value="<?= $divisi['id_divisi'] ?>"
                                    <?= $getPermintaanResult['id_divisi'] === $divisi['id_divisi'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($divisi['nama_divisi']) ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Posisi</label>
                        <input type="text" class="form-control" name="posisi"
                            value="<?= $getPermintaanResult['posisi'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah Permintaan</label>
                        <input type="number" class="form-control" min="0" name="jumlah_permintaan"
                            value="<?= $getPermintaanResult['jumlah_permintaan'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jenis Kelamin</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="1" name="jenis_kelamin"
                                <?= $getPermintaanResult['jenis_kelamin'] ? 'checked' : '' ?>>
                            <label class="form-check-label">
                                Laki-laki
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="0" name="jenis_kelamin"
                                <?= !is_null(['jenis_kelamin']) && !$getPermintaanResult['jenis_kelamin'] ? 'checked' : '' ?>>
                            <label class="form-check-label">
                                Perempuan
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">Status Kerja</label>
                        <select name="status_kerja" id="" class="form-control" required>
                            <option selected disabled>-- PILIH STATUS KERJA --</option>
                            <option value="daily-worker" <?= $getPermintaanResult['status_kerja'] === 'daily-worker' ? 'selected' : '' ?>>Daily Worker</option>
                            <option value="karyawan-kontrak"
                                <?= $getPermintaanResult['status_kerja'] === 'karyawan-kontrak' ? 'selected' : '' ?>>
                                Karyawan Kontrak</option>
                        </select>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12 col-lg-6">
                            <label for="" class="form-label">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" id="" class="form-control"
                                value="<?= $getPermintaanResult['tanggal_mulai'] ?>" required>
                        </div>
                        <div class="col-12 col-lg-6">
                            <label for="" class="form-label">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai"
                                value="<?= $getPermintaanResult['tanggal_selesai'] ?>" class="form-control">
                            <div class="form-text">Opsional</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">Keperluan</label>
                        <textarea id="tiny" name="keperluan"><?= $getPermintaanResult['keperluan'] ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <?php require_once('./../_components/scripts.php'); ?>
</body>

</html>