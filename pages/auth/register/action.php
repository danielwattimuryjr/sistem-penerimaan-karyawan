<?php

require_once ('./../../../functions/init-conn.php');
require_once ('./../../../functions/init-session.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $namaLengkap = $_POST['nama_lengkap'];
    $password = $_POST['password'];
    $role = 'Pelamar';

    $queryStr = "INSERT INTO user (user_name, nama_lengkap, email, role, password) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($queryStr);

    $stmt->bind_param('sssss', $username, $namaLengkap, $email, $role, $password);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header("Location: ../sign-in?type=success&message=" . urlencode("Register Berhasil"));
        exit;
    } else {
        // Redirect ke halaman registrasi jika gagal
        header("Location: ../register?type=error&message=" . urlencode("Registrasi gagal, coba lagi."));
        exit;
    }
}