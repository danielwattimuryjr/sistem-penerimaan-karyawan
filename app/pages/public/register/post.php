<?php

require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/init-session.php');

function redirectWithMessage($type, $message, $page = '/pages/public/register')
{
    header("Location: $page?type=$type&message=" . urlencode($message));
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? null;
    $password = $_POST['password'] ?? null;
    $email = $_POST['email'] ?? null;
    $name = $_POST['name'] ?? null;
    $role = 'Pelamar';

    if (!$username || !$password || !$email || !$name) {
        redirectWithMessage('error', 'Input tidak lengkap');
    }

    $conn->begin_transaction();
    try {
        $sql = "INSERT INTO user (name, email, user_name, password, role) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssss', $name, $email, $username, $password, $role);
        $result = $stmt->get_result();

        if ($stmt->execute()) {
            $conn->commit();
            redirectWithMessage('success', 'Registrasi berhasil', '/pages/public/sign-in');
        } else {
            throw new Exception('Gagal menyimpan data');
        }
    } catch (\Throwable $th) {
        $conn->rollback();
        redirectWithMessage('error', 'Terjadi kesalahan: ' . $th->getMessage());
    }
} else {
    redirectWithMessage('error', 'Method tidak valid');
}
