<?php
include '../db.php'; // Ensure database connection

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM products WHERE id='$id'");
    $product = $result->fetch_assoc();
    
    if (!$product) {
        echo "Product not found!";
        exit;
    }
} else {
    echo "Product ID not found!";
    exit;
}

// Handle update form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $stock = $_POST['stock']; // Added stock field

    $conn->query("UPDATE products SET name='$name', price='$price', category='$category', stock='$stock' WHERE id='$id'");
    
    header("Location: shop_management.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link rel="stylesheet" href="assets/css/style.css"> <!-- Ensure you have a CSS file -->
</head>
<body>
    <div class="edit-container">
        <h2>Edit Product</h2>
        <form method="POST">
            <label for="name">Product Name:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($product['name']); ?>" required>

            <label for="price">Price:</label>
            <input type="text" name="price" value="<?= htmlspecialchars($product['price']); ?>" required>

            <label for="category">Category:</label>
            <select name="category">
                <option value="electronics" <?= $product['category'] == "electronics" ? "selected" : ""; ?>>Electronics</option>
                <option value="clothing" <?= $product['category'] == "clothing" ? "selected" : ""; ?>>Fashion</option>
                <option value="home" <?= $product['category'] == "home" ? "selected" : ""; ?>>Home</option>
                <option value="Grocery" <?= $product['category'] == "Grocery" ? "selected" : ""; ?>>Grocery</option>
                <option value="Gadgets" <?= $product['category'] == "Gadgets" ? "selected" : ""; ?>>Gadgets</option>
                <option value="Beauty" <?= $product['category'] == "Beauty" ? "selected" : ""; ?>>Beauty</option>
            </select>

            <label for="stock">Stock:</label>
            <input type="number" name="stock" value="<?= htmlspecialchars($product['stock']); ?>" required>

            <button type="submit">Update</button>
        </form>
    </div>
</body>
</html>
