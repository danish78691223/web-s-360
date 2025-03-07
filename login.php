<?php
session_start();
include 'db.php';  // Ensure db.php is correctly included

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $foundUser = false;

    function checkLogin($conn, $table, $email, $password, $role, $nameField) {
        global $foundUser;

        $stmt = $conn->prepare("SELECT id, $nameField, password FROM $table WHERE email = ?");
        if (!$stmt) {
            die("SQL Error: " . $conn->error);
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $user_name, $hashed_password);
            $stmt->fetch();
            $stmt->close();

            $foundUser = true;

            // Debugging output
            echo "Entered Password: " . htmlspecialchars($password) . "<br>";
            echo "Stored Hashed Password: " . htmlspecialchars($hashed_password) . "<br>";

            if (password_verify($password, $hashed_password)) {
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = $id;
                $_SESSION['user_name'] = $user_name;
                $_SESSION['role'] = $role;

                echo "<script>alert('Login Successful!'); window.location.href='index.php';</script>";
                exit();
            } else {
                echo "<script>alert('Incorrect password! Try again.'); window.location.href='login.php';</script>";
                exit();
            }
        }
        $stmt->close();
    }

    // Check users table
    checkLogin($conn, 'users', $email, $password, 'user', 'name');

    // If not found in `users`, check admins table
    if (!$foundUser) {
        checkLogin($conn, 'admins', $email, $password, 'admin', 'full_name');
    }

    // If still not found, show error
    echo "<script>alert('No account found with this email!'); window.location.href='login.php';</script>";

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>

<body>
    <div class="container">
        <div class="form-box">
            <h2>Login</h2>
            <form action="login.php" method="POST">
                <div class="input-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" class="btn">Login</button>
                <p>Don't have an account? <a href="register.php">Register</a></p>
                <a href="index.php" class="btn-back">Back to Home</a>
            </form>
        </div>
    </div>
</body>

</html>
