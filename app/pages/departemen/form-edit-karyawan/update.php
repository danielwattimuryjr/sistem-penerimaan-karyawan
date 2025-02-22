<?php
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/init-session.php');
require_once('./../../../functions/page-protection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id_karyawan = $_POST['id_karyawan'] ?? null;
  $name = $_POST['name'] ?? null;
  $email = $_POST['email'] ?? null;
  $tempat_lahir = $_POST['tempat_lahir'] ?? null;
  $tanggal_lahir = $_POST['tanggal_lahir'] ?? null;
  $nomor_telepon = $_POST['nomor_telepon'] ?? null;
  $jenis_kelamin = $_POST['jenis_kelamin'] ?? null;
  $pendidikan_terakhir = $_POST['pendidikan_terakhir'] ?? null;
  $alamat = $_POST['alamat'] ?? null;
  $id_divisi = $_POST['id_divisi'] ?? null;

  if (!$id_karyawan && !$name && !$email && !$tempat_lahir && !$tanggal_lahir && !$nomor_telepon && !$jenis_kelamin && !$pendidikan_terakhir && !$alamat && !$id_divisi) {
    $type = "error";
    $message = "Input tidak valid";
    header("Location: /pages/departemen/form-edit-karyawan?id_karyawan=$id_karyawan&type=$type&message=" . urlencode($message));
    exit();
  } else {
    $query = "UPDATE karyawan SET name = ?, email = ?, tempat_lahir = ?, tanggal_lahir = ?, nomor_telepon = ?, jenis_kelamin = ?, pendidikan_terakhir = ?, alamat = ?, id_divisi = ? WHERE id_karyawan = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param(
      "sssssissii",
      $name,
      $email,
      $tempat_lahir,
      $tanggal_lahir,
      $nomor_telepon,
      $jenis_kelamin,
      $pendidikan_terakhir,
      $alamat,
      $id_divisi,
      $id_karyawan
    );

    if ($stmt->execute()) {
      $type = "success";
      $message = "Data berhasil diubah";
    } else {
      $type = "error";
      $message = "Gagal mengubah data: " . $stmt->error;
    }

    $stmt->close();
    header("Location: /pages/departemen/daftar-karyawan?type=$type&message=" . urlencode($message));
    exit();
  }
}

// Close connection
$conn->close();
?>