<?php
require_once('./../../../functions/init-conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        $query = "INSERT INTO user (name, email, password, user_name, nomor_telepon, tempat_lahir, tanggal_lahir,jenis_kelamin, alamat, role) VALUES (?, ?, ?, ?, ?, ?, ?,?, ?, 'General Manager')";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssssis", $name, $email, $password, $user_name, $nomor_telepon, $tempat_lahir, $tanggal_lahir, $jenis_kelamin, $alamat);

        if ($stmt->execute()) {
            $type = "success";
            $message = "Data berhasil disimpan";
        } else {
            $type = "error";
            $message = "Gagal menyimpan data";
        }

        // Redirect with parameters
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