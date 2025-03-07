<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Corrected path to vendor/autoload.php
require __DIR__ . '/../../vendor/autoload.php';

function sendEmail($to, $subject, $message) {
    $mail = new PHPMailer(true);

    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';  
        $mail->SMTPAuth   = true;
        $mail->Username   = 'askdnk2523@gmail.com'; // Your email
        $mail->Password   = 'hhxn xmis qhrx sejr';  // Use an App Password instead of your real password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Sender and Recipient
        $mail->setFrom('askdnk2523@gmail.com', 'Webs360 Admin');
        $mail->addAddress($to);

        // Email Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = nl2br($message);

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email sending failed: " . $mail->ErrorInfo);
        return false;
    }
}
?>
