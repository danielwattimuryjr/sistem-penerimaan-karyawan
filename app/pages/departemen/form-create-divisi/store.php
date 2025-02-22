<?php
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/init-session.php');
require_once('./../../../functions/page-protection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama_divisi = $_POST['nama_divisi'] ?? null;
  $jumlah_personil = $_POST['jumlah_personil'] ?? null;
  $id_user = $_SESSION['user']['id_user'];

  if (!$nama_divisi && !$jumlah_personil && !$id_user) {
    $type = "error";
    $message = "Input tidak valid";
    header("Location: /pages/departemen/form-create-divisi?type=$type&message=" . urlencode($message));
    exit();
  } else {
    $query = "INSERT INTO divisi (nama_divisi, jumlah_personil, id_user) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param(
      "sii",
      $nama_divisi,
      $jumlah_personil,
      $id_user
    );

    if ($stmt->execute()) {
      $type = "success";
      $message = "Data berhasil disimpan";
    } else {
      $type = "error";
      $message = "Gagal menyimpan data: " . $stmt->error;
    }

    $stmt->close();
    header("Location: /pages/departemen/daftar-divisi?type=$type&message=" . urlencode($message));
    exit();
  }
}

// Close connection
$conn->close();
?>