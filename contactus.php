<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - WEB'S 360</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            background: #0f0f0f;
            color: #fff;
            line-height: 1.8;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .navbar .logo {
            font-size: 1.8rem;
            font-weight: 600;
            color: #fff;
            text-decoration: none;
        }

        .navbar .nav-links {
            display: flex;
            gap: 30px;
        }

        .navbar .nav-links a {
            color: #fff;
            text-decoration: none;
            font-size: 1rem;
            transition: 0.3s ease;
        }

        .navbar .nav-links a:hover {
            text-decoration: underline;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 50px 20px;
            text-align: center;
        }

        .contact-header h1 {
            font-size: 3rem;
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .contact-form {
            max-width: 600px;
            margin: 40px auto;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .contact-form input, .contact-form textarea {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 8px;
            background: #1f1f1f;
            color: #fff;
            font-size: 1rem;
        }

        .contact-form button {
            padding: 12px 24px;
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .contact-form button:hover {
            transform: scale(1.1);
        }

        .btn-back {
            display: inline-block;
            margin-top: 40px;
            padding: 12px 24px;
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: 0.3s;
        }

        .btn-back:hover {
            background: linear-gradient(135deg, #0072ff, #00c6ff);
            transform: scale(1.1);
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="index.php" class="logo">WEB'S 360</a>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="aboutus.html">About Us</a>
            <a href="help.html">Help</a>
        </div>
    </div>

    <div class="container">
        <div class="contact-header">
            <h1>Contact Us</h1>
            <p>We would love to hear from you! Fill out the form below and we'll get back to you shortly.</p>
        </div>

        <form id="contactForm" class="contact-form" action="submit_contact.php" method="POST">
            <input type="text" name="user_name" placeholder="Your Name" required>
            <input type="email" name="email" placeholder="Your Email" required>
            <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
            <button type="submit">Send Message</button>
        </form>

        <a href="aboutus.html" class="btn-back">Back to About Us</a>
    </div>
</body>
</html>
