<?php
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/init-session.php');
require_once('./../../../functions/page-protection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $tempatLahir = $_POST['tempat_lahir'];
    $tanggalLahir = $_POST['tanggal_lahir'];
    $nomorTelepon = $_POST['nomor_telepon'];
    $jenisKelamin = $_POST['jenis_kelamin'];
    $pendidikanTerakhir = $_POST['pendidikan_terakhir'];
    $alamat = $_POST['alamat'];
    $idDivisi = $_POST['id_divisi'];

    if (!$name || !$email || !$tempatLahir || !$tanggalLahir || !$nomorTelepon || !$jenisKelamin || !$pendidikanTerakhir || !$alamat || !$idDivisi) {
        $type = "error";
        $message = "Input tidak valid";
        header("Location: /pages/admin/form-create-karyawan?type=$type&message=" . urlencode($message));
        exit();
    } else {
        $query = "INSERT INTO karyawan (id_divisi, name, email, tempat_lahir, tanggal_lahir, nomor_telepon, jenis_kelamin, pendidikan_terakhir, alamat) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param(
            "isssssiss",
            $idDivisi,
            $name,
            $email,
            $tempatLahir,
            $tanggalLahir,
            $nomorTelepon,
            $jenisKelamin,
            $pendidikanTerakhir,
            $alamat
        );

        if ($stmt->execute()) {
            $type = "success";
            $message = "Data berhasil disimpan";
        } else {
            $type = "error";
            $message = "Gagal menyimpan data: " . $stmt->error;
        }

        $stmt->close();
        header("Location: /pages/admin/data-karyawan?type=$type&message=" . urlencode($message));
        exit();
    }
}

// Close connection
$conn->close();
?>
