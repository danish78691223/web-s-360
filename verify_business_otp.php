<?php
session_start();
include 'db.php'; // Ensure this file connects to your database

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['verify'])) {
    $entered_otp = trim($_POST['otp']);

    if (!isset($_SESSION['business_register'])) {
        echo "<script>alert('Session expired! Register again.'); window.location.href = 'business_register.php';</script>";
        exit();
    }

    $stored_otp = $_SESSION['business_register']['otp'];

    if ($entered_otp == $stored_otp) {
        // Retrieve business details from session
        $full_name = $_SESSION['business_register']['full_name']; // Assuming business_name is used for full_name
        $email = $_SESSION['business_register']['email'];
        $mobile_no = $_SESSION['business_register']['mobile_no'];
        $address = $_SESSION['business_register']['address'];
        $business_type = $_SESSION['business_register']['business_type'];
        $password = $_SESSION['business_register']['password'];

        // Hash the password before storing it
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Prepare the SQL statement
        $stmt = $conn->prepare("INSERT INTO admins (full_name, email, mobile_no, address, business_type, password) VALUES (?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            die("Error preparing statement: " . $conn->error);
        }

        $stmt->bind_param("ssssss", $full_name, $email, $mobile_no, $address, $business_type, $hashed_password);

        // Execute the statement
        if ($stmt->execute()) {
            unset($_SESSION['business_register']); // Remove session data after successful registration
            echo "<script>alert('Your business is registered successfully!'); window.location.href = 'login.php';</script>";
        } else {
            echo "<script>alert('Error registering business! Try again.'); window.location.href = 'business_register.php';</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Invalid OTP! Try again.'); window.location.href = 'verify_business_otp.php';</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP | WEB'S 360</title>
</head>
<body>
    <h2>Enter OTP to Verify Business</h2>
    <form action="verify_business_otp.php" method="POST">
        <label>Enter OTP:</label>
        <input type="text" name="otp" required pattern="[0-9]{6}" title="Enter a 6-digit OTP">
        <button type="submit" name="verify">Verify OTP</button>
    </form>
</body>
</html>
