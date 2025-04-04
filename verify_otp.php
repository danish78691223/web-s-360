<?php
session_start();
include 'db.php';

// Check if OTP is set in session
if (!isset($_SESSION['user_temp']['otp'])) {
    echo "<script>alert('Session expired! Please register again.'); window.location.href = 'register.php';</script>";
    exit();
}

// Handle OTP verification
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_otp = trim($_POST["otp"]);
    $session_otp = $_SESSION['user_temp']['otp']; // Get OTP from session

    if ($user_otp == $session_otp) {
        // OTP Matched - Save user to database
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $_SESSION['user_temp']['name'], $_SESSION['user_temp']['email'], $_SESSION['user_temp']['password']);
        $stmt->execute();
        $stmt->close();

        // Clear session data
        unset($_SESSION['user_temp'], $_SESSION['otp']);

        echo "<script>alert('✅ OTP Verified! Your account is created.'); window.location.href = 'login.php';</script>";
        exit();
    } else {
        // Invalid OTP
        echo "<script>alert('❌ Invalid OTP! Try again.'); window.location.href = 'verify_otp.php';</script>";
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
            text-align: center;
            padding: 50px;
        }
        .container {
            background: white;
            padding: 20px;
            box-shadow: 0px 0px 10px gray;
            width: 300px;
            margin: auto;
            border-radius: 10px;
        }
        input {
            width: 80%;
            padding: 8px;
            margin: 10px;
        }
        button {
            background-color: green;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: darkgreen;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Verify OTP</h2>
    <form method="POST" action="">
        <input type="text" name="otp" placeholder="Enter OTP" required>
        <button type="submit">Verify OTP</button>
    </form>
</div>

</body>
</html>
