<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

// Include Database Configuration
include '../db.php';

// Fetch Real-time Data Counts
$users_count = $conn->query("SELECT COUNT(*) AS count FROM users")->fetch_assoc()['count'];
$products_count = $conn->query("SELECT COUNT(*) AS count FROM products")->fetch_assoc()['count'];
$businesses_count = $conn->query("SELECT COUNT(*) AS count FROM approved_businesses")->fetch_assoc()['count'];

// Fetch Queries from Database
$query_sql = "SELECT id, user_name, email, message, created_at FROM queries ORDER BY created_at DESC";
$query_result = $conn->query($query_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - WEB'S 360</title>
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <script src="assets/js/dashboard.js" defer></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="sidebar">
        <h2>WEB'S 360</h2>
        <ul>
            <li><a href="#"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="shop_management.php"><i class="fas fa-shopping-cart"></i> Shop Management</a></li>
            <li><a href="business_register.php"><i class="fas fa-building"></i> Business Registration</a></li>
            <li><a href="send_review.php"><i class="fas fa-question-circle"></i> User Queries</a></li>
            <li><a href="admin_logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <header>
            <h2><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h2>
            <button id="toggle-btn"><i class="fas fa-bars"></i></button>
        </header>

        <section class="dashboard-cards">
            <div class="card">
                <i class="fas fa-users"></i>
                <h3>Users</h3>
                <p id="users-count"><?php echo $users_count; ?></p>
            </div>
            <div class="card">
                <i class="fas fa-shopping-bag"></i>
                <h3>Products</h3>
                <p id="products-count"><?php echo $products_count; ?></p>
            </div>
            <div class="card">
                <i class="fas fa-building"></i>
                <h3>Businesses</h3>
                <p id="businesses-count"><?php echo $businesses_count; ?></p>
            </div>
        </section>

        <!-- Queries Section -->
        <section class="queries-section">
            <h2><i class="fas fa-question-circle"></i> User Queries</h2>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Message</th>
                        <th>Submitted On</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($query_result->num_rows > 0) {
                        while ($row = $query_result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['user_name']}</td>
                                    <td>{$row['email']}</td>
                                    <td>{$row['message']}</td>
                                    <td>{$row['created_at']}</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No queries found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </div>

    <script>
    function fetchCounts() {
        $.ajax({
            url: 'fetch_counts.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                $('#users-count').text(response.users);
                $('#products-count').text(response.products);
                $('#businesses-count').text(response.businesses);
            }
        });
    }

    setInterval(fetchCounts, 5000); // Update every 5 seconds
    </script>
</body>
</html>
<?php
$conn->close();
?>
