<?php
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/init-session.php');

function redirectWithMessage($type, $message, $page = '/pages/public/forget-password')
{
    header("Location: $page?type=$type&message=" . urlencode($message));
    exit();
}

if (!isset($_GET['token'])) {
    redirectWithMessage('error', 'Token tidak ada');
}

$token = $_GET['token'];

$query = "SELECT id_user, expiry FROM password_resets WHERE token = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();

    // Cek apakah token sudah kadaluarsa
    if (strtotime($row['expiry']) < time()) {
        redirectWithMessage('error', 'Token kadaluarsa');
    }
} else {
    redirectWithMessage('error', 'Token tidak valid');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Grand Pasundan Careers</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Bootstrap Knowledge Base and Help Centre Template">
    <meta name="author" content="Xiaoying Riley at 3rd Wave Media">
    <link rel="shortcut icon" href="favicon.ico">

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500&display=swap"
        rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/scss/pages/sweetalert2.scss">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/sweetalert2/sweetalert2.min.css">
</head>

<body>
    <div class="container">

        <div class="d-flex flex-column justify-content-center align-items-center" style="min-height: 100vh ">
            <img src="/assets/images/app-logo.png" alt="Logo" style="width: 200px; height: 200px; object-fit: cover;">

            <h5 class="text-center">RESET PASSWORD</h5>

            <form action="post.php" method="POST" class="mt-3" style="width: 50%">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                <div class="mb-3">
                    <label for="username" class="form-label">Password Baru</label>
                    <input type="password" name="password" id="" class="form-control" autofocus required>
                </div>

                <div class="d-flex justify-content-center flex-column" style="width: 100%">
                    <button type="submit" class="btn btn-primary m-auto" style="width: 10rem">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script
        src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/sweetalert2/sweetalert2.min.js"></script>
    <script src="/assets/js/sweet-alert.js"></script>
</body>

</html>
