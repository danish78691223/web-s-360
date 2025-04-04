<?php
session_start();
require_once(__DIR__ . '/../includes/db.php');

if (!isset($_SESSION['business_id'])) {
    header("Location: /../users/buser_login.php");
    exit();
}

$business_id = $_SESSION['business_id'];

$query = "SELECT * FROM approved_businesses WHERE business_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $business_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found!");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            height: 100vh;
            background-color: #f4f4f4;
        }

        .sidebar {
            width: 250px;
            background: #333;
            color: white;
            padding: 20px;
            transition: width 0.3s;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar ul li {
            padding: 10px;
            border-bottom: 1px solid #444;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            display: block;
        }

        .sidebar ul li:hover {
            background: #555;
        }

        .content {
            flex: 1;
            padding: 20px;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            background: #222;
            color: white;
            padding: 10px;
        }

        .profile {
            text-align: right;
        }

        .dashboard-box {
            background: white;
            padding: 20px;
            border-radius: 5px;
            margin-top: 20px;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.1);
        }

        .menu-toggle {
            cursor: pointer;
            font-size: 20px;
            color: white;
            margin-right: 10px;
        }
    </style>
</head>
<body>

<div class="sidebar" id="sidebar">
    <h2>Dashboard</h2>
    <ul>
        <li><a href="#">Home</a></li>
        <li><a href="#">Profile</a></li>
        <li><a href="#">Settings</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>

<div class="content">
    <div class="top-bar">
        <span class="menu-toggle" onclick="toggleMenu()">☰</span>
        <div class="profile">
            <strong><?php echo htmlspecialchars($user['owner_name']); ?></strong>
        </div>
    </div>

    <div class="dashboard-box">
        <h3>Welcome, <?php echo htmlspecialchars($user['owner_name']); ?>!</h3>
        <p>Business Name: <?php echo htmlspecialchars($user['business_name']); ?></p>
        <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
        <p>Phone: <?php echo htmlspecialchars($user['phone']); ?></p>
        <p>Address: <?php echo htmlspecialchars($user['address']); ?></p>
        <p>Business Type: <?php echo htmlspecialchars($user['business_type']); ?></p>
    </div>
</div>

<script>
    function toggleMenu() {
        let sidebar = document.getElementById("sidebar");
        if (sidebar.style.width === "250px") {
            sidebar.style.width = "0px";
        } else {
            sidebar.style.width = "250px";
        }
    }
</script>

</body>
</html>
