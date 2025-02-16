<?php
require_once('./../../../functions/init-conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $password = $_POST['password'];

    // Validasi token
    $query = "SELECT id_user FROM password_resets WHERE token = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $id_user = $row['id_user'];

        $query = "UPDATE user SET password = ? WHERE id_user = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $password, $id_user);
        $stmt->execute();

        $query = "DELETE FROM password_resets WHERE token = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $token);
        $stmt->execute();

        $type = 'success';
        $message = "Password berhasil diubah";
    } else {
        $type = 'error';
        $message = "Token tidak valid";
    }

    header("Location: /pages/auth/sign-in?type=$type&message=" . urlencode($message));
    exit();
} else {
    $type = 'error';
    $message = "Metode request tidak valid";

    header("Location: /pages/auth/forget-password/reset-password.php?type=$type&message=" . urlencode($message));
    exit();
}

?>
