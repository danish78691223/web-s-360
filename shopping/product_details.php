<?php
session_start();

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$username = $isLoggedIn && isset($_SESSION['username']) ? $_SESSION['username'] : '';

// Database connection
$servername = "localhost";
$username_db = "root";
$password_db = "";
$database = "auth_system";

$conn = new mysqli($servername, $username_db, $password_db, $database);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Fetch product details
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$sql = "SELECT * FROM products WHERE id = $product_id";
$result = $conn->query($sql);
$product = $result->fetch_assoc();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Details</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        .product-container { width: 50%; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px; }
        img { max-width: 100%; height: auto; }
        .btn { padding: 10px; margin: 10px; cursor: pointer; border: none; border-radius: 5px; }
        .like-btn { background-color: #ff4757; color: white; }
        .cart-btn { background-color: #1e90ff; color: white; }
    </style>
    <script>
        function likeProduct() {
            alert("You liked this product!");
        }
    </script>
</head>
<body>
    <div class="product-container">
        <h2><?= htmlspecialchars($product['name']) ?></h2>
        <img src="../uploads/<?= htmlspecialchars($product['image']) ?>" alt="Product Image">
        <p><?= htmlspecialchars($product['description']) ?></p>
        <h3>Rs <?= htmlspecialchars($product['price']) ?></h3>
        <button class="btn like-btn" onclick="likeProduct()">Like</button>
        <form method="POST" action="cart.php">
            <input type="hidden" name="product_id" value="<?= $product_id ?>">
            <button type="submit" class="btn cart-btn" name="add_to_cart">Add to Cart</button>
            <a href="shopping.php">Back</a>
        </form>
    </div>
</body>
</html>
