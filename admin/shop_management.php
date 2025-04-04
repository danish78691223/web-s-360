<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "auth_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure required columns exist in the products table
$columns_query = $conn->query("SHOW COLUMNS FROM products");
$columns = [];
while ($col = $columns_query->fetch_assoc()) {
    $columns[] = $col['Field'];
}
$stock_column_exists = in_array('stock', $columns);
$image_column_exists = in_array('image', $columns);

// Fetch product categories and counts
$category_query = "SELECT category, COUNT(*) as count FROM products GROUP BY category";
$category_result = $conn->query($category_query);
$categories = [];
while ($row = $category_result->fetch_assoc()) {
    $categories[$row['category']] = $row['count'];
}

// Fetch all products
$product_query = "SELECT * FROM products";
$product_result = $conn->query($product_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shop Management</title>
    <link rel="stylesheet" href="assets/css/shop_management.css">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <nav class="sidebar">
            <h2>Shop Management</h2>
            <ul>
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="shop_management.php">Manage Products</a></li>
                <li><a href="orders.php">Orders</a></li>
                <li><a href="settings.php">Settings</a></li>
            </ul>
        </nav>
        
        <!-- Main Content -->
        <div class="main-content">
            <h1>Manage Products</h1>
            
            <!-- Category Overview -->
            <div class="category-overview">
                <?php foreach ($categories as $category => $count): ?>
                    <div class="category-box">
                        <h3><?= htmlspecialchars($category) ?></h3>
                        <p><?= $count ?> products</p>
                    </div>
                <?php endforeach; ?>
            </div>
            <a href="add_product.php" class="btn-add-product">➕ Add Product</a>

            <!-- Product List -->
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <?php if ($image_column_exists): ?>
                            <th>Image</th>
                        <?php endif; ?>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <?php if ($stock_column_exists): ?>
                            <th>Stock</th>
                        <?php endif; ?>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($product = $product_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $product['id'] ?></td>
                        <?php if ($image_column_exists): ?>
                            <td>
                                <img src="../uploads/<?= htmlspecialchars($product['image']) ?>" width="50" height="50" alt="Product Image">
                            </td>
                        <?php endif; ?>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td><?= htmlspecialchars($product['category']) ?></td>
                        <td>Rs <?= number_format($product['price'], 2) ?></td>
                        <?php if ($stock_column_exists): ?>
                            <td><?= $product['stock'] ?></td>
                        <?php endif; ?>
                        <td class="action-links">
                            <a href="edit_product.php?id=<?= $product['id'] ?>" class="edit">Edit</a>
                            <a href="delete_product.php?id=<?= $product['id'] ?>" class="delete" onclick="return confirm('Are you sure?');">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
