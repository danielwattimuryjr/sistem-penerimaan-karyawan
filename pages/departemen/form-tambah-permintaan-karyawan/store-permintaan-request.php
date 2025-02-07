<?php
require_once('./../../../functions/init-conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal_permintaan = $_POST['tanggal_permintaan'] ?? null;
    $id_divisi = $_POST['id_divisi'] ?? null;
    $posisi = $_POST['posisi'] ?? null;
    $jumlah_permintaan = $_POST['jumlah_permintaan'] ?? null;
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? null;
    $status_kerja = $_POST['status_kerja'] ?? null;
    $tanggal_mulai = $_POST['tanggal_mulai'] ?? null;
    $tanggal_selesai = $_POST['tanggal_Selesai'] ?? null;
    $keperluan = $_POST['keperluan'] ?? null;

    if (
        !$tanggal_permintaan &&
        (!$id_divisi || is_null($id_divisi) || !is_numeric($id_divisi)) &&
        !$posisi &&
        (!$jumlah_permintaan || $jumlah_permintaan < 1 || !is_numeric($jumlah_permintaan)) &&
        is_null($jenis_kelamin) &&
        !$status_kerja &&
        !$tanggal_mulai &&
        !$keperluan
    ) {
        $type = "error";
        $message = "Input tidak valid";
        header("Location: /sistem-penerimaan-karyawan/pages/departemen/permintaan-karyawan?type=$type&message=" . urlencode($message));
        exit();
    } else {
        $jenis_kelamin = implode(',', $jenis_kelamin);
        // Menyiapkan query dasar dengan semua field yang diperlukan
        if (!empty($tanggal_selesai)) {
            // Query dengan tanggal_selesai jika ada
            $query = "INSERT INTO permintaan (
                tanggal_permintaan,
                id_user,
                posisi,
                jumlah_permintaan,
                jenis_kelamin,
                status_kerja,
                tanggal_mulai,
                tanggal_selesai,
                keperluan
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param(
                "sisississs",
                $tanggal_permintaan,
                $id_divisi,
                $posisi,
                $jumlah_permintaan,
                $jenis_kelamin,
                $status_kerja,
                $tanggal_mulai,
                $tanggal_selesai,
                $keperluan
            );
        } else {
            // Query tanpa tanggal_selesai
            $query = "INSERT INTO permintaan (
                tanggal_permintaan,
                id_user,
                posisi,
                jumlah_permintaan,
                jenis_kelamin,
                status_kerja,
                tanggal_mulai,
                keperluan
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param(
                "sisissss",
                $tanggal_permintaan,
                $id_divisi,
                $posisi,
                $jumlah_permintaan,
                $jenis_kelamin,
                $status_kerja,
                $tanggal_mulai,
                $keperluan
            );
        }

        if ($stmt->execute()) {
            $type = "success";
            $message = "Data berhasil disimpan";
        } else {
            $type = "error";
            $message = "Gagal menyimpan data: " . $stmt->error;
        }

        $stmt->close();
        header("Location: /sistem-penerimaan-karyawan/pages/departemen/permintaan-karyawan?type=$type&message=" . urlencode($message));
        exit();
    }
}

// Close connection
$conn->close();
?>