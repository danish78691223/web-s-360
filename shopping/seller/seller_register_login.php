<?php
session_start();
include '../../db.php'; // Ensure correct path to database connection file

// Check Database Connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Handle Seller Registration
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $store_name = trim($_POST['store_name']);
    $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('❌ Invalid email format!');</script>";
        exit;
    }

    // Insert into sellers table
    $query = "INSERT INTO sellers (name, email, phone, store_name, password) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("sssss", $name, $email, $phone, $store_name, $password);
        if ($stmt->execute()) {
            echo "<script>alert('✅ Seller registered successfully!');</script>";
        } else {
            echo "<script>alert('❌ Error registering seller. Try again later.');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('❌ SQL Error: " . $conn->error . "');</script>";
    }
}

// Handle Seller Login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $query = "SELECT id, password FROM sellers WHERE email = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($seller_id, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION['seller_id'] = $seller_id;
                echo "<script>alert('✅ Login successful!');</script>";
                header("Location: seller_dashboard.php");
                exit;
            } else {
                echo "<script>alert('❌ Incorrect password. Try again.');</script>";
            }
        } else {
            echo "<script>alert('❌ No account found with this email.');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('❌ SQL Error: " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Become a Seller</title>
</head>
<style>
    /* General Styling */
body {
    font-family: Arial, sans-serif;
    background: ##20B2AA;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh;
    margin: 0;
}

/* Form Container */
form {
    background: rgba(255, 255, 255, 0.9);
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    width: 300px;
    text-align: center;
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
}

form:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

/* Input Fields */
input {
    width: 90%;
    padding: 10px;
    margin: 8px 0;
    border: 1px solid #ccc;
    border-radius: 8px;
    transition: all 0.3s ease-in-out;
    font-size: 16px;
}

input:focus {
    border-color: #ff758c;
    box-shadow: 0 0 8px rgba(255, 117, 140, 0.5);
    outline: none;
}

/* Buttons */
button {
    background: linear-gradient(90deg, #ff758c, #ff7eb3);
    border: none;
    color: white;
    padding: 10px 15px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 16px;
    width: 100%;
    transition: all 0.3s ease-in-out;
}

button:hover {
    background: linear-gradient(90deg, #ff4b6f, #ff6584);
    transform: scale(1.05);
}

/* Headings */
h2 {
    text-align: center;
    color: black;
    text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.2);
    margin-bottom: 10px;
}
</style>
<body>
    <h2>Become a Seller</h2>
    <form method="POST">
        <input type="text" name="name" placeholder="Full Name" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="text" name="phone" placeholder="Phone Number" required><br>
        <input type="text" name="store_name" placeholder="Store Name" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit" name="register">Register</button>
    </form>

    <h2>Seller Login</h2>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit" name="login">Login</button>
    </form>
</body>
</html>
