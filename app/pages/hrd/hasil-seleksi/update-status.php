<?php

require_once('./../../../functions/init-conn.php');
require_once('./../../../vendor/autoload.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function redirect($type, $message)
{
    header("Location: /pages/hrd/hasil-seleksi?type=$type&message=" . urlencode($message));
    exit();
}

function kirimEmail($toEmail, $subject, $message)
{
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = MAIL_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = MAIL_ADDRESS;
        $mail->Password = MAIL_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = MAIL_PORT;

        //Recipients
        $mail->setFrom(MAIL_ADDRESS, MAIL_USERNAME); // Sender's email
        $mail->addAddress($toEmail); // Add recipient

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;

        // Send email
        $mail->send();
    } catch (Exception $e) {
        // Log error if needed
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_hasil = $_POST['id_hasil'] ?? null;
    $status = $_POST['status'] ?? null;

    if ($id_hasil && $status) {
        $conn->begin_transaction();

        try {
            // Ambil ID divisi dari hasil pelamaran terkait
            $sqlDivisi = "SELECT per.id_divisi
                  FROM hasil h
                  JOIN pelamaran pel ON h.id_pelamaran = pel.id_pelamaran
                  JOIN lowongan l ON pel.id_lowongan = l.id_lowongan
                  JOIN permintaan per ON l.id_permintaan = per.id_permintaan
                  WHERE h.id_hasil = ?";
            $stmt = $conn->prepare($sqlDivisi);
            $stmt->bind_param("i", $id_hasil);
            $stmt->execute();
            $resultDivisi = $stmt->get_result();
            $divisi = $resultDivisi->fetch_assoc();
            $idDivisi = $divisi['id_divisi'];

            // Ambil data pelamaran untuk dipindahkan ke karyawan
            $sqlPelamaran = "SELECT * FROM pelamaran WHERE id_pelamaran = (SELECT id_pelamaran FROM hasil WHERE id_hasil = ?)";
            $stmt = $conn->prepare($sqlPelamaran);
            $stmt->bind_param("i", $id_hasil);
            $stmt->execute();
            $resultPelamaran = $stmt->get_result();
            $pelamaran = $resultPelamaran->fetch_assoc();

            // Ambil status pelamaran (misalnya "Diterima" atau "Ditolak")
            $sqlStatus = "UPDATE hasil SET status = ? WHERE id_hasil = ?";
            $stmt = $conn->prepare($sqlStatus);
            $stmt->bind_param("si", $status, $id_hasil);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                if ($status === 'Diterima') {
                    // Jika status diterima, pindahkan ke tabel karyawan
                    $sqlInsertKaryawan = "INSERT INTO karyawan (id_divisi, name, email, tempat_lahir, tanggal_lahir, nomor_telepon, jenis_kelamin, pendidikan_terakhir, alamat)
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sqlInsertKaryawan);
                    $stmt->bind_param("issssssss", $idDivisi, $pelamaran['name'], $pelamaran['email'], $pelamaran['tempat_lahir'], $pelamaran['tanggal_lahir'], $pelamaran['nomor_telepon'], $pelamaran['jenis_kelamin'], $pelamaran['pendidikan_terakhir'], $pelamaran['alamat']);
                    $stmt->execute();

                    // Kirim email (contoh email sukses)
                    kirimEmail($pelamaran['email'], "Selamat, Anda Diterima", "Anda telah diterima sebagai karyawan. Selamat bergabung!");
                } elseif ($status === 'Ditolak') {
                    // Kirim email (contoh email ditolak)
                    kirimEmail($pelamaran['email'], "Mohon Maaf, Anda Ditolak", "Kami mohon maaf, Anda tidak diterima untuk posisi yang Anda lamar.");
                }
            }

            // Commit transaksi
            $conn->commit();

            redirect('success', 'Status berhasil diubah.');
        } catch (Exception $e) {
            // Jika terjadi error, rollback transaksi
            $conn->rollback();
            redirect('error', "Terjadi kesalahan: " . $e->getMessage());
        }
    } else {
        $type = 'error';
        $message = "ID hasil atau status tidak valid.";
    }

    redirect($type, $message);
}


$conn->close();
