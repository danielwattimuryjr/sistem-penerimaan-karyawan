<?php
require_once('./../../../functions/init-conn.php');

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

    <link rel="shortcut icon" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/svg/favicon.svg"
        type="image/x-icon">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/app.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/iconly.css">
</head>

<body>

    <!-- Start content here -->

    <div id="app">
        <div class="container-sm d-flex justify-content-center align-items-center" style="min-height: 100vh">
            <div class="card" style="width: 50%;">
                <div class="card-header">
                    <div class="d-flex flex-column align-items-center">
                        <div class="logo">
                            <img src="/assets/images/app-logo.png" alt="Logo"
                                style="width: 200px; height: 200px; object-fit: cover;">
                        </div>
                        <h5 class="card-title text-center">Reset Password</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form action="process-reset-password.php" method="POST">
                        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                        <div class="mb-3">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="password" id="password" class="form-control" required
                                autofocus>
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
    </div>

    <!-- End content -->

    <script
        src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/js/app.js"></script>
</body>

</html>
