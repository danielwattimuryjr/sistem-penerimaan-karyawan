<?php
require_once('./../../../functions/init-conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_divisi = $_POST['nama_divisi'] ?? null;

    if ($nama_divisi) {
        $query = "INSERT INTO divisi (nama_divisi) VALUES (?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $nama_divisi);

        if ($stmt->execute()) {
            $type = "success";
            $message = "Data berhasil disimpan";
        } else {
            $type = "error";
            $message = "Gagal menyimpan data";
        }

        // Redirect with parameters
        header("Location: /sistem-penerimaan-karyawan/pages/hrd/data-department?type=$type&message=" . urlencode($message));
        exit();
    } else {
        $type = "error";
        $message = "Input tidak valid";
        header("Location: /sistem-penerimaan-karyawan/pages/hrd/data-department?type=$type&message=" . urlencode($message));
        exit();
    }
}

// Close connection
$conn->close();
?>