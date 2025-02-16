<?php
require_once('./../../../functions/init-conn.php');
require_once('./../../../vendor/autoload.php');
require_once('./../../../functions/load-env.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$MAIL_MAILER = getenv('MAIL_MAILER');
$MAIL_USERNAME = getenv('MAIL_USERNAME');
$MAIL_PASSWORD = getenv('MAIL_PASSWORD');
$MAIL_PORT = getenv('MAIL_PORT');
$MAIL_EMAIL = getenv('MAIL_EMAIL');

if (!$MAIL_MAILER || !$MAIL_USERNAME || !$MAIL_PASSWORD || !$MAIL_PORT || !$MAIL_EMAIL) {
    $type = 'error';
    $message = 'Mailer belum disetup';
    header("Location: /pages/auth/forget-password?type=$type&message=" . urlencode($message));
    exit();
}

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
            $mail->Host = $MAIL_MAILER;
            $mail->SMTPAuth = true;
            $mail->Username = $MAIL_EMAIL;
            $mail->Password = $MAIL_PASSWORD;
            $mail->SMTPSecure = 'tls';
            $mail->Port = $MAIL_PORT;

            $mail->setFrom($MAIL_EMAIL, $MAIL_USERNAME);
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