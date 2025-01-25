<?php

require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/init-session.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['user_name'];
    $email = $_POST['email'];
    $namaLengkap = $_POST['name'];
    $password = $_POST['password'];

    $queryStr = "INSERT INTO user (user_name, name, email, password, role) VALUES (?, ?, ?, ?, 'Pelamar')";
    $stmt = $conn->prepare($queryStr);

    $stmt->bind_param('ssss', $username, $namaLengkap, $email, $password);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header("Location: /sistem-penerimaan-karyawan/pages/public/sign-in?type=success&message=" . urlencode("Register Berhasil"));
        exit;
    } else {
        // Redirect ke halaman registrasi jika gagal
        header("Location: /sistem-penerimaan-karyawan/pages/public/register?type=error&message=" . urlencode("Registrasi gagal, coba lagi."));
        exit;
    }
}