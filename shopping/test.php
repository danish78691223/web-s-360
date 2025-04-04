<?php
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp-relay.brevo.com';
    $mail->SMTPAuth = true;
    $mail->Username = '864a5e002@smtp-brevo.com';
    $mail->Password = 'Ta7Jym3nrBKdcOSw';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('864a5e002@smtp-brevo.com', 'Test Sender');
    $mail->addAddress('askdnk2523@gmail.com');

    $mail->isHTML(true);
    $mail->Subject = 'Test Email';
    $mail->Body    = '<p>This is a test email.</p>';

    if ($mail->send()) {
        echo "✅ Test email sent successfully!";
    } else {
        echo "❌ Failed to send email: " . $mail->ErrorInfo;
    }
} catch (Exception $e) {
    echo "❌ Error: " . $mail->ErrorInfo;
}
?>