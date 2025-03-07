<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Success</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            padding: 50px;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            display: inline-block;
        }
        h2 {
            color: #4CAF50;
        }
        p {
            color: #333;
        }
        a {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            text-decoration: none;
            color: white;
            background-color: #4CAF50;
            border-radius: 5px;
        }
        a:hover {
            background-color: #45a049;
        }
    </style>
    <script>
        alert("Registration request sent to admin for approval.");
        setTimeout(function() {
            window.location.href = "buser_login.php"; // Redirect to login page after 5 seconds
        }, 5000);
    </script>
</head>
<body>
    <div class="container">
        <h2>Registration Request Sent</h2>
        <p>Your request has been sent to the admin for approval.</p>
        <p>You will receive an email once it is approved.</p>
        <a href="login.php">Go to Login</a>
    </div>
</body>
</html>
