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
    <title>Form Department</title>

    <?php require_once ('./../_components/styles.php'); ?>
</head>
<body>
<?php require_once('./../_components/navbar.php'); ?>

<div class="container-sm mt-3 mt-lg-5">
    <div class="card" style="width: 100%;">
        <div class="card-body">
            <h5 class="card-title text-center mb-3">Formulir Department</h5>

            <form action="store-department-request.php" method="POST">
                <div class="mb-3">
                    <label class="form-label">Nama Departement</label>
                    <input type="text" class="form-control" min="0" name="nama_divisi" required>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>

<?php require_once ('./../_components/scripts.php'); ?>
</body>
</html>
