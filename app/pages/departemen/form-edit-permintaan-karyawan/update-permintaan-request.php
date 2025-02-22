<?php
require_once('./../../../functions/init-conn.php');

// Log request data
// file_put_contents('log_post.txt', print_r($_POST, true)); // Simpan di file untuk debugging
// echo json_encode($_POST);
// exit();

function redirect($type, $message)
{
    if ($type === 'success') {
        header("Location: /pages/departemen/permintaan-karyawan?type=success&message=" . urlencode($message));
        exit();
    } else {
        header("Location: /pages/departemen/form-edit-permintaan-karyawan?type=error&message=" . urlencode($message));
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_permintaan = $_POST['id_permintaan'] ?? null;
    $tanggal_permintaan = $_POST['tanggal_permintaan'] ?? null;
    $id_user = $_POST['id_user'] ?? null;
    $id_divisi = $_POST['id_divisi'] ?? null;
    $jumlah_permintaan = $_POST['jumlah_permintaan'] ?? null;
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? null;
    $status_kerja = $_POST['status_kerja'] ?? null;
    $tanggal_mulai = $_POST['tanggal_mulai'] ?? null;
    $tanggal_selesai = $_POST['tanggal_selesai'] ?? null;

    if (is_null($id_permintaan)) {
        redirect('error', 'ID permintaan tidak boleh kosong');
    } else if (
        !$tanggal_permintaan &&
        (!$id_user || is_null($id_user) || !is_numeric($id_user)) &&
        !$id_divisi &&
        (!$jumlah_permintaan || $jumlah_permintaan < 1 || !is_numeric($jumlah_permintaan)) &&
        is_null($jenis_kelamin) &&
        !$status_kerja &&
        !$tanggal_mulai
    ) {
        redirect('error', 'Input tidak valid');
    } else {
        $jenis_kelamin = implode(',', $jenis_kelamin);

        $query = "UPDATE permintaan SET
            tanggal_permintaan = ?,
            id_user = ?,
            id_divisi = ?,
            jumlah_permintaan = ?,
            jenis_kelamin = ?,
            status_kerja = ?,
            tanggal_mulai = ?,
            tanggal_selesai = ?
            WHERE id_permintaan = ?";

        $stmt = $conn->prepare($query);
        $stmt->bind_param(
            "siiissssi",
            $tanggal_permintaan,
            $id_user,
            $id_divisi,
            $jumlah_permintaan,
            $jenis_kelamin,
            $status_kerja,
            $tanggal_mulai,
            $tanggal_selesai,
            $id_permintaan
        );

        if ($stmt->execute()) {
            $type = "success";
            $message = "Data permintaan berhasil diperbaharui";
        } else {
            $type = "error";
            $message = "Gagal memperbaharui data permintaan: " . $stmt->error;
        }

        $stmt->close();
        redirect($type, $message);
    }
} else {
    redirect('error', 'Method tidak valid');
}

$conn->close();
