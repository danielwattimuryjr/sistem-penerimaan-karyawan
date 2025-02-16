<?php
require_once('./../../../functions/init-conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user = $_POST['id_user'] ?? null;
    $name = $_POST['name'] ?? null;
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;
    $user_name = $_POST['user_name'] ?? null;
    $nomor_telepon = $_POST['nomor_telepon'] ?? null;
    $tempat_lahir = $_POST['tempat_lahir'] ?? null;
    $tanggal_lahir = $_POST['tanggal_lahir'] ?? null;
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? null;
    $alamat = $_POST['alamat'] ?? null;

    if ($name) {
        $query = "UPDATE user SET name = ?, email = ?, user_name = ?, nomor_telepon = ?, tempat_lahir = ?, tanggal_lahir = ?, jenis_kelamin = ?, alamat = ? WHERE id_user = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssssisi", $name, $email, $user_name, $nomor_telepon, $tempat_lahir, $tanggal_lahir, $jenis_kelamin, $alamat, $id_user);

        if ($stmt->execute()) {
            $type = "success";
            $message = "Data General Manager berhasil diperbaharui";
        } else {
            $type = "error";
            $message = "Gagal memperbaharui data General Manager";
        }

        header("Location: /pages/admin/data-general-manager?type=$type&message=" . urlencode($message));
        exit();
    } else {
        $type = "error";
        $message = "Input tidak valid";
        header("Location: /pages/admin/data-general-manager?type=$type&message=" . urlencode($message));
        exit();
    }
}

// Close connection
$conn->close();
?>