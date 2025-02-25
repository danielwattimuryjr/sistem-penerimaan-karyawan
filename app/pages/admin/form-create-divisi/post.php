<?php
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/init-session.php');
require_once('./../../../functions/page-protection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_divisi = $_POST['nama_divisi'] ?? null;
    $jumlah_personil = $_POST['jumlah_personil'] ?? null;
    $id_user = $_POST['id_user'] ?? null;

    if (!$nama_divisi && !$jumlah_personil && !$id_user) {
        $type = "error";
        $message = "Input tidak valid";
        header("Location: /pages/admin/form-create-divisi?type=$type&message=" . urlencode($message));
        exit();
    } else {
        $query = "INSERT INTO divisi (nama_divisi, jumlah_personil, id_user) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param(
            "sii",
            $nama_divisi,
            $jumlah_personil,
            $id_user
        );

        if ($stmt->execute()) {
            $type = "success";
            $message = "Data berhasil disimpan";
        } else {
            $type = "error";
            $message = "Gagal menyimpan data: " . $stmt->error;
        }

        $stmt->close();
        header("Location: /pages/admin/data-divisi?type=$type&message=" . urlencode($message));
        exit();
    }
}

// Close connection
$conn->close();
?>
