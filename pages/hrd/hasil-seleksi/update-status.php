<?php

require_once('./../../../functions/init-conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_hasil = $_POST['id_hasil'] ?? null;
    $status = $_POST['status'] ?? null;

    if ($id_hasil && $status) {
        $query = "UPDATE hasil SET status = ? WHERE id_hasil = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $status, $id_hasil);

        if ($stmt->execute()) {
            $type = 'success';
            $message = "Status hasil berhasil diperbarui.";
        } else {
            $type = 'error';
            $message = "Gagal memperbarui status: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $type = 'error';
        $message = "ID hasil atau status tidak valid.";
    }

    header("Location: /sistem-penerimaan-karyawan/pages/hrd/hasil-seleksi?type=$type&message=" . urlencode($message));
}


$conn->close();
