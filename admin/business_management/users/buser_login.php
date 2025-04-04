<?php
session_start();
require_once('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $business_id = trim($_POST['business_id']);
    $password = trim($_POST['password']);

    // Fetch business details
    $sql = "SELECT * FROM approved_businesses WHERE business_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $business_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $business = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $business['password'])) {
            $_SESSION['business_id'] = $business['business_id'];
            $_SESSION['business_name'] = $business['business_name'];

            // Check if dashboard_url exists
            $dashboard_url = !empty($business['dashboard_url']) ? $business['dashboard_url'] : 'dashboard.php';

            // Debugging
            error_log("Redirecting to: " . $dashboard_url);

            // Redirect to assigned dashboard
            header("Location: " . $dashboard_url);
            exit();
        } else {
            $_SESSION['error'] = "Invalid Business ID or Password.";
        }
    } else {
        $_SESSION['error'] = "Business not found. Please check your credentials.";
    }
    header("Location: buser_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Login</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Container */
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 300px;
        }

        h2 {
            margin-bottom: 20px;
        }

        /* Form Styles */
        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        label {
            font-weight: bold;
            text-align: left;
        }

        input {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Error Message */
        .error {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Business Login</h2>

        <?php if (isset($_SESSION['error'])): ?>
            <p class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
        <?php endif; ?>

        <form action="buser_login.php" method="POST">
            <label for="business_id">Business ID:</label>
            <input type="text" name="business_id" required>

            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <button type="submit">Login</button>
        </form>
    </div>

</body>
</html>
