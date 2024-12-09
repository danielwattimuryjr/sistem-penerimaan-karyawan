<?php

require_once('./../../../functions/init-session.php');
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/page-protection.php');

function redirectWithMessage($type, $message) {
    header("Location: /sistem-penerimaan-karyawan/pages/pelamar/profile?type=$type&message=" . urlencode($message));
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectWithMessage('error', 'Invalid Method');
}

$user = $_SESSION['user'] ?? null;

if (empty($user['id_user'])) {
    redirectWithMessage('error', 'Id Pelamar tidak ditemukan');
}

if ($user['role'] !== 'Pelamar') {
    redirectWithMessage('error', 'User bukan seorang pelamar');
}

$namaLengkap = $_POST['nama_lengkap'];
$nomorTelepon = $_POST['nomor_telepon'];
$tempatLahir = $_POST['tempat_lahir'];
$tanggalLahir = $_POST['tanggal_lahir'];
$jenisKelamin = $_POST['jenis_kelamin'];
$pendidikanTerakhir = $_POST['pendidikan_terakhir'];
$alamat = $_POST['alamat'];

if (!$namaLengkap
    || !$nomorTelepon
    || !$tempatLahir
    || !$tanggalLahir
    || !$jenisKelamin
    || !$pendidikanTerakhir
    || !$alamat
) {
    redirectWithMessage('error', 'Data tidak lengkap');
}

$idUser = $user['id_user'];

try {
    $conn->begin_transaction();

    $updateUserTableQuery = "UPDATE user SET nama_lengkap = ? WHERE id_user = ?";
    $stmtUser = $conn->prepare($updateUserTableQuery);
    $stmtUser->bind_param('si', $namaLengkap, $idUser);
    $stmtUser->execute();

    $updateProfileTableQuery = "
        UPDATE profile
        SET jenis_kelamin = ?, pendidikan_terakhir = ?, nomor_telepon = ?, alamat = ?, tempat_lahir = ?, tanggal_lahir = ?
        WHERE id_user = ?";
    $stmtProfile = $conn->prepare($updateProfileTableQuery);
    $stmtProfile->bind_param(
        'isssssi',
        $jenisKelamin,
        $pendidikanTerakhir,
        $nomorTelepon,
        $alamat,
        $tempatLahir,
        $tanggalLahir,
        $idUser
    );
    $stmtProfile->execute();

    $conn->commit();

    redirectWithMessage('success', 'Data berhasil diperbarui');
} catch (Exception $e) {
    $conn->rollback();
    redirectWithMessage('error', 'Terjadi kesalahan: ' . $e->getMessage());
} finally {
    $stmtUser->close();
    $stmtProfile->close();
    $conn->close();
}