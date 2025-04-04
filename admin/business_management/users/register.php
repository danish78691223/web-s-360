<?php
session_start();
require_once '../../../vendor/autoload.php'; // Ensure this path is correct
require_once '../../includes/db.php'; // Adjust the path as needed
require_once '../../includes/send_email.php'; // Ensure the email function is properly set up

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $business_name = trim($_POST['business_name']);
    $owner_name = trim($_POST['owner_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $business_type = trim($_POST['business_type']);

    // Validate input fields
    if (empty($business_name) || empty($owner_name) || empty($email) || empty($phone) || empty($address) || empty($business_type)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: register.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format.";
        header("Location: register.php");
        exit();
    }

    // Generate OTP
    $otp = rand(100000, 999999);
    $_SESSION['otp'] = $otp;
    $_SESSION['register_data'] = $_POST; // Store user data to use after OTP verification

    // Email Content
    $subject = "Verify Your Email - Business Registration";
    $message = "Your OTP for business registration is: <b>$otp</b>. Please enter this OTP to verify your email.";

    // Send email and handle response
    $email_result = sendEmail($email, $subject, $message);
    
    if ($email_result === true) {
        $_SESSION['success'] = "OTP sent successfully! Please check your email.";
        echo "<script>alert('OTP sent successfully! Redirecting to verify OTP page...');</script>";
        echo "<script>window.location.href = 'verify_otp.php';</script>";
        exit();
    } else {
        $_SESSION['error'] = "Failed to send OTP. Error: " . $email_result;
        header("Location: register.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Registration</title>
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
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
        }
        h2 {
            margin-bottom: 15px;
        }
        input, select, button {
            width: 100%;
            padding: 5px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #28a745;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
        .success {
            color: green;
            margin-bottom: 10px;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Register Your Business</h2>

        <?php if (isset($_SESSION['error'])): ?>
            <p class="error"> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?> </p>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <p class="success"> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?> </p>
        <?php endif; ?>

        <form action="register.php" method="POST">
            <input type="text" name="business_name" placeholder="Business Name" required>
            <input type="text" name="owner_name" placeholder="Owner Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="phone" placeholder="Phone Number" required>
            <input type="text" name="address" placeholder="Business Address" required>
            <select name="business_type" required>
                <option value="">Select Business Type</option>
                <option value="E-commerce">E-commerce</option>
                <option value="Education">Education</option>
                <option value="Healthcare">Healthcare</option>
                <option value="Retail">Retail</option>
            </select>
            <button type="submit">Register</button>
            <p>Already Have An Account? <a href="buser_login.php">Login</a></p>
        </form>
    </div>
</body>
</html>
