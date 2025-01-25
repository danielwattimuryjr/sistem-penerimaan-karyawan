<?php

require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/init-session.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['user_name'];
    $password = $_POST['password'];

    $queryStr = "SELECT id_user, name FROM user WHERE user_name = ? AND password = ? AND role = 'Pelamar'";
    $stmt = $conn->prepare($queryStr);

    $stmt->bind_param('ss', $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user'] = $user;

        header("Location: /sistem-penerimaan-karyawan/pages/public/landing-page?type=success&message=" . urlencode("Login berhasil!"));
        exit;
    } else {
        header("Location: /sistem-penerimaan-karyawan/pages/public/sign-in?type=error&message=" . urlencode("Username atau password salah."));
        exit;
    }
}