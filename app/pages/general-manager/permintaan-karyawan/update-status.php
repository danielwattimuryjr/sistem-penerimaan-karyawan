<?php

require_once('./../../../functions/init-conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_permintaan = $_POST['id_permintaan'] ?? null;
    $status_permintaan = $_POST['status_permintaan'] ?? null;

    if ($id_permintaan && $status_permintaan) {
        $query = "UPDATE permintaan SET status_permintaan = ? WHERE id_permintaan = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $status_permintaan, $id_permintaan);

        if ($stmt->execute()) {
            $type = 'success';
            $message = "Status permintaan berhasil diperbarui.";
        } else {
            $type = 'error';
            $message = "Gagal memperbarui status: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $type = 'error';
        $message = "ID permintaan atau status tidak valid.";
    }

    header("Location: /pages/general-manager/permintaan-karyawan?type=$type&message=" . urlencode($message));
}


$conn->close();

