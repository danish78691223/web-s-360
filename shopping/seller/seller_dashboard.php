<?php
session_start();
include('../../db.php');

if (!isset($_SESSION['seller_id'])) {
    die("❌ Access Denied. Please <a href='become_seller.php'>Login</a>.");
}

$seller_id = $_SESSION['seller_id'];

// Handle product deletion
if (isset($_GET['delete'])) {
    $product_id = intval($_GET['delete']);
    $deleteQuery = "DELETE FROM seller_products WHERE id = ? AND seller_id = ?";
    $stmt = mysqli_prepare($conn, $deleteQuery);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $product_id, $seller_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    } else {
        die("Error in deletion: " . mysqli_error($conn));
    }

    header("Location: seller_dashboard.php");
    exit();
}

// Fetch seller's products
$query = "SELECT * FROM seller_products WHERE seller_id = ?";
$stmt = mysqli_prepare($conn, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $seller_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    die("Query Failed: " . mysqli_error($conn));
}
?>
s
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        /* Global Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f8f9fa;
            color: #333;
        }

        /* Navbar */
        .navbar {
            background: #28a745;
            padding: 15px;
            text-align: center;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
            margin: 0 15px;
            transition: color 0.3s ease-in-out;
        }

        .navbar a:hover {
            color: #d4edda;
        }

        /* Dashboard Container */
        .container {
            max-width: 900px;
            margin: 40px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        /* Cards */
        .dashboard-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 20px;
        }

        .card {
            width: 200px;
            padding: 15px;
            text-align: center;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }

        .card:hover {
            transform: translateY(-3px);
        }

        .card a {
            display: block;
            text-decoration: none;
            color: #28a745;
            font-weight: bold;
            margin-top: 10px;
        }

        .card a:hover {
            text-decoration: underline;
        }

        .icon {
            font-size: 40px;
            color: #28a745;
            margin-bottom: 10px;
        }

        /* Product List */
        .product-list {
            margin-top: 20px;
        }

        .product-list table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .product-list th, .product-list td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        .product-list th {
            background: #28a745;
            color: white;
        }

        .delete-btn {
            background: #dc3545;
            color: white;
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            transition: background 0.3s;
        }

        .delete-btn:hover {
            background: #bd2130;
        }

        /* Logout Button */
        .logout-btn {
            display: block;
            width: 100%;
            text-align: center;
            padding: 10px;
            margin-top: 20px;
            background: #dc3545;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s ease-in-out;
        }

        .logout-btn:hover {
            background: #bd2130;
        }

        /* Responsive */
        @media (max-width: 600px) {
            .dashboard-links {
                flex-direction: column;
                align-items: center;
            }

            .product-list table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>

    <div class="navbar">
        <a href="#">Seller Dashboard</a>
        <a href="add_product.php">Add Product</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="container">
        <h2>Welcome, Seller!</h2>

        <div class="dashboard-links">
            <div class="card">
                <i class="fas fa-box icon"></i>
                <a href="add_product.php">Add Product</a>
            </div>
            <div class="card">
                <i class="fas fa-list icon"></i>
                <a href="manage_products.php">Manage Products</a>
            </div>
            <div class="card">
                <i class="fas fa-chart-line icon"></i>
                <a href="sales_report.php">Sales Report</a>
            </div>
        </div>

        <!-- Product List Section -->
        <div class="product-list">
            <h3>My Products</h3>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><img src="../uploads/<?php echo $row['image']; ?>" alt="Product" width="50"></td>
                        <td><?php echo $row['product_name']; ?></td>
                        <td>$<?php echo number_format($row['price'], 2); ?></td>
                        <td>
                            <a href="?delete=<?php echo $row['id']; ?>" class="delete-btn"
                               onclick="return confirm('Are you sure you want to delete this product?')">
                                Delete
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>

        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

</body>
</html>
