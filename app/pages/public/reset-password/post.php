<?php
require_once('./../../../functions/init-conn.php');

function redirectWithMessage($type, $message, $page = '/pages/public/forget-password')
{
    header("Location: $page?type=$type&message=" . urlencode($message));
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? null;
    $password = $_POST['password'] ?? null;

    if (!$token || !$password) {
        redirectWithMessage('error', 'Input tidak valid');
    }

    $conn->begin_transaction();
    try {
        $query = "SELECT id_user FROM password_resets WHERE token = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $id_user = $row['id_user'];
            $stmt->close();

            $query = "UPDATE user SET password = ? WHERE id_user = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $password, $id_user);
            $stmt->execute();
            $stmt->close();

            $query = "DELETE FROM password_resets WHERE token = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $stmt->close();

            $conn->commit();

            redirectWithMessage('success', 'Password berhasil diubah', "/pages/public/sign-in");
        } else {
            $stmt->close();
            $conn->rollback();
            redirectWithMessage('error', 'Token tidak valid');
        }
    } catch (\Throwable $th) {
        $conn->rollback();
        redirectWithMessage('error', 'Terjadi kesalahan saat memperbarui password. Silakan coba lagi.');
    }
} else {
    redirectWithMessage('error', 'Method tidak valid');
}
