<?php
require_once('./../../../functions/init-conn.php');

function redirect($type, $message)
{
    if ($type === 'success') {
        header("Location: /sistem-penerimaan-karyawan/pages/departemen/permintaan-karyawan?type=success&message=" . urlencode($message));
        exit();
    } else {
        header("Location: /sistem-penerimaan-karyawan/pages/departemen/form-edit-permintaan-karyawan?type=error&message=" . urlencode($message));
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_permintaan = $_POST['id_permintaan'] ?? null;
    $tanggal_permintaan = $_POST['tanggal_permintaan'] ?? null;
    $id_divisi = $_POST['id_divisi'] ?? null;
    $posisi = $_POST['posisi'] ?? null;
    $jumlah_permintaan = $_POST['jumlah_permintaan'] ?? null;
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? null;
    $status_kerja = $_POST['status_kerja'] ?? null;
    $tanggal_mulai = $_POST['tanggal_mulai'] ?? null;
    $tanggal_selesai = $_POST['tanggal_Selesai'] ?? null;
    $keperluan = $_POST['keperluan'] ?? null;

    if (is_null($id_permintaan)) {
        redirect('error', 'ID permintaan tidak boleh kosong');
    } else if (
        !$tanggal_permintaan &&
        (!$id_divisi || is_null($id_divisi) || !is_numeric($id_divisi)) &&
        !$posisi &&
        (!$jumlah_permintaan || $jumlah_permintaan < 1 || !is_numeric($jumlah_permintaan)) &&
        is_null($jenis_kelamin) &&
        !$status_kerja &&
        !$tanggal_mulai &&
        !$keperluan
    ) {
        redirect('error', 'Input tidak valid');
    } else {
        if (!empty($tanggal_selesai)) {
            // Query dengan tanggal_selesai
            $query = "UPDATE permintaan SET
                tanggal_permintaan = ?,
                id_divisi = ?,
                posisi = ?,
                jumlah_permintaan = ?,
                jenis_kelamin = ?,
                status_kerja = ?,
                tanggal_mulai = ?,
                tanggal_selesai = ?,
                keperluan = ?
                WHERE id_permintaan = ?";

            $stmt = $conn->prepare($query);
            $stmt->bind_param(
                "sisssssssi",
                $tanggal_permintaan,
                $id_divisi,
                $posisi,
                $jumlah_permintaan,
                $jenis_kelamin,
                $status_kerja,
                $tanggal_mulai,
                $tanggal_selesai,
                $keperluan,
                $id_permintaan
            );
        } else {
            // Query tanpa tanggal_selesai
            $query = "UPDATE permintaan SET
                tanggal_permintaan = ?,
                id_divisi = ?,
                posisi = ?,
                jumlah_permintaan = ?,
                jenis_kelamin = ?,
                status_kerja = ?,
                tanggal_mulai = ?,
                tanggal_selesai = NULL,
                keperluan = ?
                WHERE id_permintaan = ?";

            $stmt = $conn->prepare($query);
            $stmt->bind_param(
                "sissssssi",
                $tanggal_permintaan,
                $id_divisi,
                $posisi,
                $jumlah_permintaan,
                $jenis_kelamin,
                $status_kerja,
                $tanggal_mulai,
                $keperluan,
                $id_permintaan
            );
        }

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
