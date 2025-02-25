<?php
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/send-mail.php');

function redirectWithMessage($type, $message, $page = '/pages/auth/forget-password')
{
    header("Location: $page?type=$type&message=" . urlencode($message));
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emailTo = trim($_POST['email']);

    $query = "SELECT id_user FROM user WHERE email = ? AND role != 'Pelamar'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $emailTo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $token = bin2hex(random_bytes(32));
        $expiry = date("Y-m-d H:i:s", strtotime('+1 hour'));
        $row = $result->fetch_assoc();
        $id_user = $row['id_user'];

        $query = "INSERT INTO password_resets (id_user, token, expiry) VALUES (?, ?, ?)
                  ON DUPLICATE KEY UPDATE token = VALUES(token), expiry = VALUES(expiry)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iss", $id_user, $token, $expiry);
        $stmt->execute();

        try {
            send($emailTo, 'Password Reset Request', "Klik tautan berikut untuk mereset password Anda:
                <a href='" . SITE_URL . "/pages/auth/forget-password/reset-password.php?token=$token'>Reset Password</a>");
            redirectWithMessage('success', 'Silahkan cek email untuk mendapatkan link reset password');
        } catch (\Throwable $th) {
            redirectWithMessage('error', 'Terjadi kesalahan saat mencoba mengirim email');
        }
    } else {
        redirectWithMessage('error', 'Email tidak ditemukan');
    }
} else {
    redirectWithMessage('error', 'Metode tidak valid');
}
