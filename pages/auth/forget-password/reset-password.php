<?php
require_once ('./../../../functions/init-conn.php');

if (!isset($_GET['token'])) {
    echo "Token tidak valid.";
    exit();
}

$token = $_GET['token'];

// Validasi token
$query = "SELECT id_user, expiry FROM password_resets WHERE token = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();

    // Cek apakah token sudah kadaluarsa
    if (strtotime($row['expiry']) < time()) {
        echo "Token telah kadaluarsa.";
        exit();
    }
} else {
    echo "Token tidak valid.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>

    <?php
    require_once ('./../_components/styles.php');
    ?>
</head>
<body>

<div class="container-sm d-flex justify-content-center align-items-center" style="min-height: 100vh">
    <div class="card" style="width: 60%;">
        <div class="card-body">
            <h5 class="card-title text-center">Reset Password Anda</h5>

            <form action="process-reset-password.php" method="POST">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                <div class="mb-3">
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="password" id="password" class="form-control" required autofocus>
                </div>

                <div class="d-flex justify-content-center flex-column" style="width: 100%">
                    <button type="submit" class="btn btn-primary m-auto" style="width: 10rem">
                        Lupa Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
require_once ('./../_components/scripts.php');
?>
</body>
</html>
