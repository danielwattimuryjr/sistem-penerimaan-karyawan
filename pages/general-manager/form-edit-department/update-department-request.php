<?php
require_once('./../../../functions/init-conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_divisi = $_POST['nama_divisi'] ?? null;
    $id_divisi = $_POST['id_divisi'] ?? null;

    if ($nama_divisi && $id_divisi) {
        $query = "UPDATE divisi SET nama_divisi = ? WHERE id_divisi = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $nama_divisi, $id_divisi);

        // Execute and redirect with message
        if ($stmt->execute()) {
            $type = "success";
            $message = "Data divisi berhasil diperbaharui";
        } else {
            $type = "error";
            $message = "Gagal memperbaharui data divisi";
        }

        header("Location: /sistem-penerimaan-karyawan/pages/general-manager/data-department?type=$type&message=" . urlencode($message));
        exit();
    } else {
        $type = "error";
        $message = "Input tidak valid";
        header("Location: /sistem-penerimaan-karyawan/pages/general-manager/data-department?type=$type&message=" . urlencode($message));
        exit();
    }
} else {
    header("Location: /sistem-penerimaan-karyawan/pages/departemen/permintaan-karyawan");
    exit();
}

$conn->close();
