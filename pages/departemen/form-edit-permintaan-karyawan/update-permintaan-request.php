<?php
require_once('./../../../functions/init-conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form values
    $id_permintaan = isset($_POST['id_permintaan']) ? intval($_POST['id_permintaan']) : null;
    $id_divisi = isset($_POST['id_divisi']) ? intval($_POST['id_divisi']) : null;
    $jumlah_permintaan = isset($_POST['jumlah_permintaan']) ? intval($_POST['jumlah_permintaan']) : null;

    if ($id_permintaan && $id_divisi && $jumlah_permintaan >= 0) {
        $query = "UPDATE permintaan SET id_divisi = ?, jumlah_permintaan = ? WHERE id_permintaan = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iii", $id_divisi, $jumlah_permintaan, $id_permintaan);

        // Execute and redirect with message
        if ($stmt->execute()) {
            $type = "success";
            $message = "Data permintaan berhasil diperbaharui";
        } else {
            $type = "error";
            $message = "Gagal memperbaharui data permintaan";
        }

        header("Location: /sistem-penerimaan-karyawan/pages/departemen/permintaan-karyawan?type=$type&message=" . urlencode($message));
        exit();
    } else {
        $type = "error";
        $message = "Input tidak valid";
        header("Location: /sistem-penerimaan-karyawan/pages/departemen/permintaan-karyawan?type=$type&message=" . urlencode($message));
        exit();
    }
} else {
    header("Location: /sistem-penerimaan-karyawan/pages/departemen/permintaan-karyawan");
    exit();
}

$conn->close();
