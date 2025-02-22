<?php
require_once('./../../../functions/init-conn.php');

// Log request data
// file_put_contents('log_post.txt', print_r($_POST, true)); // Simpan di file untuk debugging
// echo json_encode($_POST);
// exit();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal_permintaan = $_POST['tanggal_permintaan'] ?? null;
    $id_user = $_POST['id_user'] ?? null;
    $id_divisi = $_POST['id_divisi'] ?? null;
    $jumlah_permintaan = $_POST['jumlah_permintaan'] ?? null;
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? null;
    $status_kerja = $_POST['status_kerja'] ?? null;
    $tanggal_mulai = $_POST['tanggal_mulai'] ?? null;
    $tanggal_selesai = $_POST['tanggal_selesai'] ?? null;

    if (
        !$tanggal_permintaan &&
        (!$id_user || is_null($id_user) || !is_numeric($id_user)) &&
        !$id_divisi &&
        (!$jumlah_permintaan || $jumlah_permintaan < 1 || !is_numeric($jumlah_permintaan)) &&
        is_null($jenis_kelamin) &&
        !$status_kerja &&
        !$tanggal_mulai
    ) {
        $type = "error";
        $message = "Input tidak valid";
        header("Location: /pages/departemen/permintaan-karyawan?type=$type&message=" . urlencode($message));
        exit();
    } else {
        $jenis_kelamin = implode(',', $jenis_kelamin);
        // Menyiapkan query dasar dengan semua field yang diperlukan
        $query = "INSERT INTO permintaan (
            tanggal_permintaan,
            id_user,
            id_divisi,
            jumlah_permintaan,
            jenis_kelamin,
            status_kerja,
            tanggal_mulai,
            tanggal_selesai
        ) VALUES (?,?,?,?,?,?,?,?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param(
            "siiissss",
            $tanggal_permintaan,
            $id_user,
            $id_divisi,
            $jumlah_permintaan,
            $jenis_kelamin,
            $status_kerja,
            $tanggal_mulai,
            $tanggal_selesai
        );

        if ($stmt->execute()) {
            $type = "success";
            $message = "Data berhasil disimpan";
        } else {
            $type = "error";
            $message = "Gagal menyimpan data: " . $stmt->error;
        }

        $stmt->close();
        header("Location: /pages/departemen/permintaan-karyawan?type=$type&message=" . urlencode($message));
        exit();
    }
}

// Close connection
$conn->close();
?>