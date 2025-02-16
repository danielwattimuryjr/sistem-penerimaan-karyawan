<?php
require_once('./../../../functions/init-conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? null;
    $id_user = $_POST['id_user'] ?? null;
    $email = $_POST['email'] ?? null;
    $user_name = $_POST['user_name'] ?? null;

    if ($name && $id_user) {
        $query = "UPDATE user SET name = ?, email = ?, user_name = ? WHERE id_user = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssi", $name, $email, $user_name, $id_user);

        // Execute and redirect with message
        if ($stmt->execute()) {
            $type = "success";
            $message = "Data divisi berhasil diperbaharui";
        } else {
            $type = "error";
            $message = "Gagal memperbaharui data divisi";
        }

        header("Location: /pages/admin/data-departemen?type=$type&message=" . urlencode($message));
        exit();
    } else {
        $type = "error";
        $message = "Input tidak valid";
        header("Location: /pages/admin/data-departemen?type=$type&message=" . urlencode($message));
        exit();
    }
} else {
    header("Location: /pages/admin/data-departemen");
    exit();
}

$conn->close();
