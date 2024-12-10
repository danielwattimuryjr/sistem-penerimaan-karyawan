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

