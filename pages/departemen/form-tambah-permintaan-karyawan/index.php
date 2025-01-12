<?php
require_once('./../../../functions/init-session.php');
require_once('./../../../functions/init-conn.php');
if (!$_SESSION['user']) {
    header("Location: /sistem-penerimaan-karyawan/pages/auth/sign-in");
}

$queryStr = "SELECT id_divisi, nama_divisi FROM divisi";

$stmt = $conn->prepare($queryStr);
$stmt->execute();
$result = $stmt->get_result();
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

                <form action="store-permintaan-request.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Tanggal Permintaan</label>
                        <input type="date" class="form-control" name="tanggal_permintaan" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Divisi</label>
                        <select class="form-select" name="id_divisi">
                            <option selected disabled>-- PILIH DIVISI --</option>
                            <?php foreach ($result as $res) { ?>
                                <option value="<?= $res['id_divisi'] ?>">
                                    <?= $res['nama_divisi'] ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Posisi</label>
                        <input type="text" class="form-control" name="posisi" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah Permintaan</label>
                        <input type="number" class="form-control" min="0" name="jumlah_permintaan" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jenis Kelamin</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="1" name="jenis_kelamin">
                            <label class="form-check-label">
                                Laki-laki
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="0" name="jenis_kelamin">
                            <label class="form-check-label">
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
                            <label for="" class="form-label">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" id="" class="form-control" required>
                        </div>
                        <div class="col-12 col-lg-6">
                            <label for="" class="form-label">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" id="" class="form-control">
                            <div class="form-text">Opsional</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">Keperluan</label>
                        <textarea id="tiny" name="keperluan"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <?php require_once('./../_components/scripts.php'); ?>
</body>

</html>