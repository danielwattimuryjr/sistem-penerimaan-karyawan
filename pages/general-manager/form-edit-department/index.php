<?php
require_once('./../../../functions/init-session.php');
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/page-protection.php');

$id_divisi = isset($_GET['id_divisi']) ? $_GET['id_divisi'] : null;

if (!$id_divisi) {
    $type = 'error';
    $message = 'Data divisi tidak ditemukan';
    header("Location: /sistem-penerimaan-karyawan/pages/general-manager/data-department?type=$type&message=".urlencode($message));
    exit();
}
$getDivisiQueryStr = "SELECT id_divisi, nama_divisi FROM divisi WHERE id_divisi = ?";

$getDivisiStmt = $conn->prepare($getDivisiQueryStr);
$getDivisiStmt->bind_param('i', $id_divisi);
$getDivisiStmt->execute();
$getDivisiResult = $getDivisiStmt->get_result()->fetch_assoc();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Form Update Data Department</title>

    <?php require_once ('./../_components/styles.php'); ?>
</head>
<body>
<?php require_once('./../_components/navbar.php'); ?>

<div class="container-sm mt-3 mt-lg-5">
    <div class="card" style="width: 100%;">
        <div class="card-body">
            <h5 class="card-title text-center mb-3">Formulir Department</h5>

            <form action="update-department-request.php" method="POST">
                <input type="hidden" name="id_divisi" value="<?= $id_divisi ?>">
                <div class="mb-3">
                    <label class="form-label">Nama Departement</label>
                    <input type="text" class="form-control" min="0" name="nama_divisi" value="<?= $getDivisiResult['nama_divisi'] ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>

<?php require_once ('./../_components/scripts.php'); ?>
</body>
</html>