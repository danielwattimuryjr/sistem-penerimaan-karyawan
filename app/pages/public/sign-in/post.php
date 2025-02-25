<?php

require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/init-session.php');

function redirectWithMessage($type, $message, $page = '/pages/public/sign-in')
{
    header("Location: $page?type=$type&message=" . urlencode($message));
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? null;
    $password = $_POST['password'] ?? null;

    if (!$username || !$password) {
        redirectWithMessage('error', 'Input tidak lengkap');
    }

    try {
        $sql = "SELECT id_user, name, role FROM user WHERE user_name = ? AND password = ? AND role = 'Pelamar'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($stmt->execute()) {
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                $_SESSION['user'] = $user;
                $redirectUrl = '/pages/public/landing-page';
                redirectWithMessage('success', 'Login berhasil', $redirectUrl);
            } else {
                redirectWithMessage('error', 'Username atau password salah');
            }
        } else {
            throw new Exception('Gagal menyimpan data');
        }
    } catch (\Throwable $th) {
        redirectWithMessage('error', 'Terjadi kesalahan: ' . $th->getMessage());
    }
} else {
    redirectWithMessage('error', 'Method tidak valid');
}
