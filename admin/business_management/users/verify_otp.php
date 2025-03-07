<?php
session_start();
require_once '../../../includes/db.php'; // Ensure the correct path

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $entered_otp = trim($_POST['otp']);

    // Check if OTP session exists
    if (!isset($_SESSION['otp']) || $entered_otp != $_SESSION['otp']) {
        $_SESSION['error'] = "Invalid OTP. Please try again.";
        header("Location: verify_otp.php");
        exit();
    }

    // Check if registration data exists in session
    if (!isset($_SESSION['register_data'])) {
        $_SESSION['error'] = "Session expired. Please register again.";
        header("Location: register.php");
        exit();
    }

    // Extract stored registration details
    $business_name = $_SESSION['register_data']['business_name'];
    $owner_name = $_SESSION['register_data']['owner_name'];
    $email = $_SESSION['register_data']['email'];
    $phone = $_SESSION['register_data']['phone'];
    $address = $_SESSION['register_data']['address'];
    $business_type = $_SESSION['register_data']['business_type'];

    // Insert into 'pending_businesses' table
    $sql = "INSERT INTO pending_businesses (business_name, owner_name, email, phone, address, business_type, status) 
            VALUES (?, ?, ?, ?, ?, ?, 'pending')
            ON DUPLICATE KEY UPDATE status = 'pending'"; // Prevent duplicate email errors
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        $_SESSION['error'] = "Database error: " . $conn->error;
        header("Location: verify_otp.php");
        exit();
    }

    $stmt->bind_param("ssssss", $business_name, $owner_name, $email, $phone, $address, $business_type);

    if ($stmt->execute()) {
        // Clear session data after successful registration
        unset($_SESSION['otp']);
        unset($_SESSION['register_data']);

        $_SESSION['success'] = "Registration request sent to admin for approval.";
        header("Location: registration_success.php");
        exit();
    } else {
        $_SESSION['error'] = "Database error. Please try again.";
        header("Location: verify_otp.php");
        exit();
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            width: 100%;
            background: #28a745;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background: #218838;
        }
        p {
            font-size: 14px;
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Enter OTP</h2>
        <?php if (isset($_SESSION['success'])): ?>
            <p style="color: green;"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <p><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
        <?php endif; ?>
        <form action="verify_otp.php" method="POST">
            <input type="text" name="otp" placeholder="Enter OTP" required>
            <button type="submit">Verify</button>
        </form>
    </div>
</body>
</html>
