<?php
require_once('./../../../functions/init-conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_divisi = isset($_POST['id_divisi']) ? intval($_POST['id_divisi']) : null;
    $jumlah_permintaan = isset($_POST['jumlah_permintaan']) ? intval($_POST['jumlah_permintaan']) : null;

    if ($id_divisi && $jumlah_permintaan >= 0) {
        $query = "INSERT INTO permintaan (id_divisi, jumlah_permintaan) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $id_divisi, $jumlah_permintaan);

        if ($stmt->execute()) {
            $type = "success";
            $message = "Data berhasil disimpan";
        } else {
            $type = "error";
            $message = "Gagal menyimpan data";
        }

        // Redirect with parameters
        header("Location: /sistem-penerimaan-karyawan/pages/departemen/permintaan-karyawan?type=$type&message=" . urlencode($message));
        exit();
    } else {
        $type = "error";
        $message = "Input tidak valid";
        header("Location: /sistem-penerimaan-karyawan/pages/departemen/permintaan-karyawan?type=$type&message=" . urlencode($message));
        exit();
    }
}

// Close connection
$conn->close();
?>