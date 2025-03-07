<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Adjust if using Composer

function sendEmail($to, $subject, $message) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';  // Change if using a different provider
        $mail->SMTPAuth   = true;
        $mail->Username   = 'askdnk2523@gmail.com'; // Set your email
        $mail->Password   = 'hhxn xmis qhrx sejr'; // Set your email password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('askdnk2523@gmail.com', 'Webs360 Admin');
        $mail->addAddress($to);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = nl2br($message);

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
