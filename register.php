<?php
session_start();
include 'db.php'; // Ensure this connects to your database
require 'vendor/autoload.php';  
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($name) || empty($email) || empty($password)) {
        echo "<script>alert('All fields are required!'); window.location.href = 'register.php';</script>";
        exit();
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>alert('Email already exists! Try logging in.'); window.location.href = 'register.php';</script>";
        exit();
    }
    $stmt->close();

    // Generate OTP
    $otp = rand(100000, 999999);

    // Store user details in session temporarily
    $_SESSION['user_temp'] = [
        'name' => $name,
        'email' => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'otp' => $otp
    ];

    $_SESSION['otp'] = $otp; // Store OTP separately for verification

    // Send OTP to user email
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp-relay.brevo.com';
        $mail->SMTPAuth = true;
        $mail->Username = '864a5e004@smtp-brevo.com'; // Replace with your Brevo email
        $mail->Password = 'aBvw50gzI2hpmfxE'; // Replace with your Brevo SMTP password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('askdnk2523@gmail.com', 'WEB\'S 360');
        $mail->addAddress($email, $name);

        $mail->isHTML(true);
        $mail->Subject = 'Your OTP for Registration';
        $mail->Body = "<h3>Your OTP is: $otp</h3>";

        $mail->send();

        echo "<script>alert('OTP sent to your email. Verify to complete registration.'); window.location.href = 'verify_otp.php';</script>";
    } catch (Exception $e) {
        echo "<script>alert('Error sending OTP: {$mail->ErrorInfo}'); window.location.href = 'register.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
<div class="register-container">
    <h2>Register</h2>
    <form action="register.php" method="POST">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="register">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Login</a></p>
    <p><a href="login.php">Back</a></p>
</div>

</div>
</body>
</html>
