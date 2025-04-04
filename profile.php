<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'];

// Fetch user details
if ($user_role == 'user') {
    $sql = "SELECT name AS full_name, email FROM users WHERE id = ?";
} elseif ($user_role == 'admin') {
    $sql = "SELECT full_name, email FROM admins WHERE id = ?";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Fetch user orders
$order_query = "SELECT id, product_name, price, order_date FROM orders WHERE user_id = ? ORDER BY order_date DESC";
$order_stmt = $conn->prepare($order_query);
$order_stmt->bind_param("i", $user_id);
$order_stmt->execute();
$orders = $order_stmt->get_result();
$order_stmt->close();

// Fetch notifications
$notif_query = "SELECT id, message, status, created_at FROM notifications WHERE user_id = ? ORDER BY created_at DESC";
$notif_stmt = $conn->prepare($notif_query);
$notif_stmt->bind_param("i", $user_id);
$notif_stmt->execute();
$notifications = $notif_stmt->get_result();
$notif_stmt->close();

// Fetch unread notification count
$unread_query = "SELECT COUNT(*) AS unread_count FROM notifications WHERE user_id = ? AND status = 'unread'";
$unread_stmt = $conn->prepare($unread_query);
$unread_stmt->bind_param("i", $user_id);
$unread_stmt->execute();
$unread_result = $unread_stmt->get_result()->fetch_assoc();
$unread_count = $unread_result['unread_count'];
$unread_stmt->close();

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="profile.css">
</head>
<body>
    <div class="top-bar">
        <div class="notifications">
            <div class="notification-icon" onclick="toggleNotifications()">
                🔔 <span id="notification-count"><?php echo $unread_count > 0 ? $unread_count : ''; ?></span>
            </div>
            <div class="dropdown" id="notif-dropdown" style="display: none;">
                <?php if ($notifications->num_rows > 0): ?>
                    <ul>
                        <?php while ($notif = $notifications->fetch_assoc()): ?>
                            <li class="<?php echo $notif['status'] == 'unread' ? 'unread' : ''; ?>">
                                <?php echo htmlspecialchars($notif['message']); ?>
                                <small><?php echo $notif['created_at']; ?></small>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <p>No notifications</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="sidebar">
            <h2>Profile</h2>
            <ul>
                <li><a href="profile.php" class="active">Profile</a></li>
                <li><a href="edit_profile.php">Edit Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
                <li><a href="user_orders.php">Orders</a>
                <li><a href="index.php">Home</a></li>
            </ul>
        </div>

        <div class="main-content">
            <h1>Welcome, <?php echo htmlspecialchars($user['full_name']); ?>!</h1>

            <div class="profile-section">
                <h2>Your Profile</h2>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($user['full_name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <a href="edit_profile.php" class="edit-profile-btn">Edit Profile</a>
            </div>

            <div class="orders-section">
                <h2>Your Orders</h2>
                <?php if ($orders->num_rows > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Order Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($order = $orders->fetch_assoc()): ?>
                                <tr>
                                    <td>#<?php echo htmlspecialchars($order['id']); ?></td>
                                    <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                                    <td>₹<?php echo number_format($order['price'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>You have not placed any orders yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script>
        function toggleNotifications() {
            let dropdown = document.getElementById("notif-dropdown");
            dropdown.style.display = (dropdown.style.display === "block") ? "none" : "block";
        }
    </script>
</body>
</html>