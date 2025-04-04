<?php
session_start();

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle adding a product to the cart (from shopping.php)
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $price = isset($_POST['price']) ? $_POST['price'] : '';
    $image = isset($_POST['image']) ? $_POST['image'] : '';

    // If product already exists, increment quantity
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += 1;
    } else {
        $_SESSION['cart'][$product_id] = [
            'name' => htmlspecialchars($name),
            'price' => floatval($price),
            'image' => htmlspecialchars($image), // Store image URL
            'quantity' => 1
        ];
    }
}

// Remove item from cart
if (isset($_POST['remove'])) {
    $product_id = $_POST['product_id'];
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Update quantity
if (isset($_POST['update'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = intval($_POST['quantity']);
    if ($new_quantity > 0) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// Calculate total price
$total_price = 0;
foreach ($_SESSION['cart'] as $item) {
    $total_price += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="cart.css">
</head>
<body>
    <h2>Your Cart</h2>
    
    <?php if (empty($_SESSION['cart'])): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <table border="1" cellpadding="10">
            <tr>
                <th>Image</th>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
            <?php foreach ($_SESSION['cart'] as $id => $item): ?>
                <tr>
                    <td><img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" width="50"></td>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td>Rs <?php echo number_format($item['price'], 2); ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($id); ?>">
                            <input type="number" name="quantity" value="<?php echo htmlspecialchars($item['quantity']); ?>" min="1">
                            <button type="submit" name="update">Update</button>
                        </form>
                    </td>
                    <td>Rs <?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($id); ?>">
                            <button type="submit" name="remove">Remove</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        
        <h3>Total Price: Rs <?php echo number_format($total_price, 2); ?></h3>

        <!-- Check if user is logged in before showing checkout button -->
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="checkout.php"><button>Proceed to Checkout</button></a>
        <?php else: ?>
            <p style="color: red;">You must <a href="../login.php">log in</a> to proceed to checkout.</p>
        <?php endif; ?>

    <?php endif; ?>
</body>
</html>
