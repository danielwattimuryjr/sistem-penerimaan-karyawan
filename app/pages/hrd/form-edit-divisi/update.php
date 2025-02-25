<?php
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/init-session.php');
require_once('./../../../functions/page-protection.php');

// // Log request data
// file_put_contents('log_post.txt', print_r($_POST, true)); // Simpan di file untuk debugging
// echo json_encode($_POST);
// exit();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_divisi = $_POST['nama_divisi'] ?? null;
    $jumlah_personil = $_POST['jumlah_personil'] ?? null;
    $id_divisi = $_POST['id_divisi'] ?? null;
    $id_user = $_POST['id_user'] ?? null;

    if (!$nama_divisi && !$jumlah_personil && !$id_divisi && !$id_user) {
        $type = "error";
        $message = "Input tidak valid";
        header("Location: /pages/hrd/form-edit-divisi?id_divisi=$id_divisi&type=$type&message=" . urlencode($message));
        exit();
    } else {
        $query = "UPDATE divisi SET nama_divisi = ?, jumlah_personil = ?, id_user = ? WHERE id_divisi = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param(
            "siii",
            $nama_divisi,
            $jumlah_personil,
            $id_user,
            $id_divisi
        );

        if ($stmt->execute()) {
            $type = "success";
            $message = "Data berhasil diubah";
        } else {
            $type = "error";
            $message = "Gagal mengubah data: " . $stmt->error;
        }

        $stmt->close();
        header("Location: /pages/hrd/data-divisi?type=$type&message=" . urlencode($message));
        exit();
    }
}

// Close connection
$conn->close();
?>