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
            echo "Status permintaan berhasil diperbarui.";
        } else {
            echo "Gagal memperbarui status: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "ID permintaan atau status tidak valid.";
    }
}

$conn->close();

