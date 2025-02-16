<?php
require_once('./../../../functions/init-conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? null;
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;
    $user_name = $_POST['user_name'] ?? null;

    if ($name) {
        $query = "INSERT INTO user (name, email, password, user_name, role) VALUES (?, ?, ?, ?, 'Departement')";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $name, $email, $password, $user_name);

        if ($stmt->execute()) {
            $type = "success";
            $message = "Data berhasil disimpan";
        } else {
            $type = "error";
            $message = "Gagal menyimpan data";
        }

        // Redirect with parameters
        header("Location: /pages/admin/data-departemen?type=$type&message=" . urlencode($message));
        exit();
    } else {
        $type = "error";
        $message = "Input tidak valid";
        header("Location: /pages/admin/data-departemen?type=$type&message=" . urlencode($message));
        exit();
    }
}

// Close connection
$conn->close();
?>