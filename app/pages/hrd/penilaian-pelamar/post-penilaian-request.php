<?php
require_once('./../../../functions/init-session.php');
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/page-protection.php');

// if (!empty($_POST)) {
//     // Convert $_POST to JSON
//     header('Content-Type: application/json');
//     echo json_encode($_POST, JSON_PRETTY_PRINT);
// } else {
//     echo 'No POST data received.';
// }

if (!isset($_POST['id_pelamaran']) || empty($_POST['id_pelamaran'])) {
    $type = 'error';
    $message = "ID pelamar tidak ditemukan";
    header('Location: /pages/hrd/data-pelamar?type=error&message=' . urlencode($message));
    exit();
}

$id_pelamaran = $_POST['id_pelamaran'];

$tes_tertulis = $_POST['tes_tertulis'];
$tes_wawancara = $_POST['tes_wawancara'];
$tes_praktek = $_POST['tes_praktek'];
$tes_psikotes = $_POST['tes_psikotes'];
$tes_kesehatan = $_POST['tes_kesehatan'];
$pendidikan = $_POST['pendidikan'];
$umur = $_POST['umur'];
$pengalaman_kerja = $_POST['pengalaman_kerja'];

$getIdLowonganQuery = "SELECT
    id_lowongan
FROM pelamaran
WHERE id_pelamaran = ?";
$getIdLowonganStmt = $conn->prepare($getIdLowonganQuery);
$getIdLowonganStmt->bind_param('i', $id_pelamaran);
$getIdLowonganStmt->execute();
$getIdLowonganResult = $getIdLowonganStmt->get_result();
$getIdLowongan = $getIdLowonganResult->fetch_assoc();
$getIdLowonganStmt->close();

$fpArray = [
    'tes_tertulis' => $tes_tertulis,
    'tes_wawancara' => $tes_wawancara,
    'tes_praktek' => $tes_praktek,
    'tes_psikotes' => $tes_psikotes,
    'tes_kesehatan' => $tes_kesehatan,
    'pendidikan' => $pendidikan,
    'umur' => $umur,
    'pengalaman_kerja' => $pengalaman_kerja
];

try {
    $conn->begin_transaction();

    $queryStr = "
        INSERT INTO penilaian (
            id_pelamaran,
            id_faktor,
            nilai
        ) VALUES (?, (SELECT id_faktor FROM faktor_penilaian WHERE id_lowongan = ? AND nama_faktor = ?), ?)
        ON DUPLICATE KEY UPDATE nilai = VALUES(nilai)
    ";
    $stmt = $conn->prepare($queryStr);

    foreach ($fpArray as $fp => $nilai) {
        $stmt->bind_param('iisi', $id_pelamaran, $getIdLowongan['id_lowongan'], $fp, $nilai);
        $stmt->execute();
    }
    $stmt->close();

    $insertHasilQuery = "
        INSERT INTO hasil (id_pelamaran, status)
        VALUES (?, NULL)
        ON DUPLICATE KEY UPDATE status = VALUES(status)
    ";
    $stmtHasil = $conn->prepare($insertHasilQuery);
    $stmtHasil->bind_param('i', $id_pelamaran);
    $stmtHasil->execute();
    $stmtHasil->close();

    $conn->commit();
    $conn->close();

    $type = 'success';
    $message = 'Penilaian berhasil disimpan';

    header('Location: /pages/hrd/data-pelamar?type=' . $type . '&message=' . urlencode($message));
    exit();
} catch (Exception $e) {
    $conn->rollback();

    $type = 'error';
    $message = "Terjadi kesalahan: " . $e->getMessage();
    header('Location: /pages/hrd/data-pelamar?type=error&message=' . urlencode($message));
    exit();
}
