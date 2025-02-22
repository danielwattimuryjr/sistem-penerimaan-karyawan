<?php
require_once('./../../../functions/init-conn.php');
require_once('./../../../vendor/autoload.php');
require_once('./../../../functions/load-env.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emailTo = trim($_POST['email']);

    $query = "SELECT id_user FROM user WHERE email = ? AND role != 'Pelamar'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $emailTo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $token = bin2hex(random_bytes(32));
        $expiry = date("Y-m-d H:i:s", strtotime('+1 hour'));
        $row = $result->fetch_assoc();
        $id_user = $row['id_user'];

        $query = "INSERT INTO password_resets (id_user, token, expiry) VALUES (?, ?, ?)
                  ON DUPLICATE KEY UPDATE token = VALUES(token), expiry = VALUES(expiry)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iss", $id_user, $token, $expiry);
        $stmt->execute();

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = MAIL_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = MAIL_ADDRESS;
            $mail->Password = MAIL_PASSWORD;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = MAIL_PORT;

            $mail->setFrom(MAIL_ADDRESS, MAIL_USERNAME);
            $mail->addAddress($emailTo);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body = "Klik tautan berikut untuk mereset password Anda:
                <a href='http://localhost/pages/auth/forget-password/reset-password.php?token=$token'>Reset Password</a>";
            if ($mail->send()) {
                $type = 'success';
                $message = "Silahkan cek email untuk mendapatkan link reset password";
            } else {
                $type = 'error';
                $message = "Terjadi kesalahan saat mengirim email";
            }
        } catch (Exception $e) {
            $type = 'error';
            $message = "Gagal mengirim email: {$mail->ErrorInfo}";
        }
    } else {
        $type = 'error';
        $message = "Email tidak ditemukan";
    }
} else {
    $type = 'error';
    $message = "Mwtode request tidak valid";
}
header("Location: /pages/auth/forget-password?type=$type&message=" . urlencode($message));
exit();
?>