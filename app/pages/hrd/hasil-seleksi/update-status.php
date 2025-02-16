<?php

require_once('./../../../functions/init-conn.php');
require_once('./../../../vendor/autoload.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$MAIL_MAILER = getenv('MAIL_MAILER');
$MAIL_USERNAME = getenv('MAIL_USERNAME');
$MAIL_PASSWORD = getenv('MAIL_PASSWORD');
$MAIL_PORT = getenv('MAIL_PORT');
$MAIL_EMAIL = getenv('MAIL_EMAIL');

function redirect($type, $message)
{
    header("Location: /pages/hrd/hasil-seleksi?type=$type&message=" . urlencode($message));
    exit();
}

if (!$MAIL_MAILER || !$MAIL_USERNAME || !$MAIL_PASSWORD || !$MAIL_PORT || !$MAIL_EMAIL) {
    $type = 'error';
    $message = 'Mailer belum disetup';
    redirect($type, $message);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_hasil = $_POST['id_hasil'] ?? null;
    $status = $_POST['status'] ?? null;
    $mail = new PHPMailer(true);

    if ($id_hasil && $status) {
        $query = "UPDATE hasil SET status = ? WHERE id_hasil = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $status, $id_hasil);

        if ($stmt->execute()) {
            // Get Email Pengguna
            $pelamaranDataQuery = "SELECT
                p.email,
                l.nama_lowongan
            FROM hasil h
            JOIN pelamaran p ON h.id_pelamaran = p.id_pelamaran
            JOIN lowongan l ON p.id_lowongan = l.id_lowongan   -- Perbaiki ini
            WHERE h.id_hasil = ?;";
            $pelamaranDataStmt = $conn->prepare($pelamaranDataQuery);
            $pelamaranDataStmt->bind_param('i', $id_hasil);
            $pelamaranDataStmt->execute();
            $pelamaranData = $pelamaranDataStmt->get_result()->fetch_assoc();
            $pelamaranDataStmt->execute();
            if ($pelamaranData['email'] !== null) {
                $emailTo = $pelamaranData['email'];
                $lowonganName = $pelamaranData['nama_lowongan'];
                try {
                    $mail->isSMTP();
                    $mail->Host = $MAIL_MAILER;
                    $mail->SMTPAuth = true;
                    $mail->Username = $MAIL_EMAIL;
                    $mail->Password = $MAIL_PASSWORD;
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = $MAIL_PORT;

                    $mail->setFrom($MAIL_EMAIL, $MAIL_USERNAME);
                    $mail->addAddress($emailTo);

                    $mail->isHTML(true);
                    $mail->Subject = 'Hasil Penilaian';
                    $mail->Body = $status === 'Diterima'
                        ? "<p><b>Selamat!</b> Anda telah <b>diterima</b> dalam lowongan <i>$lowonganName</i></p>"
                        : "<p><b>Maaf,</b> Anda telah <b>ditolak</b> dalam lowongan <i>$lowonganName</i></p>";

                    if ($mail->send()) {
                        $type = 'success';
                        $message = "Status hasil berhasil diperbarui.";
                    } else {
                        $type = 'error';
                        $message = "Terjadi kesalahan saat mengirim email";
                    }
                } catch (Exception $e) {
                    $type = 'error';
                    $message = "Gagal mengirim email: {$mail->ErrorInfo}";
                }
            }
        } else {
            $type = 'error';
            $message = "Gagal memperbarui status: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $type = 'error';
        $message = "ID hasil atau status tidak valid.";
    }

    redirect($type, $message);
}


$conn->close();
