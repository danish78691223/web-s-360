<?php
session_start();

// Check if cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php"); // Redirect to cart if empty
    exit();
}

// Calculate total price
$total_price = 0;
foreach ($_SESSION['cart'] as $item) {
    $total_price += $item['price'] * $item['quantity'];
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['order_details'] = [
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'mobile' => $_POST['mobile'],
        'address' => $_POST['address'],
        'city' => $_POST['city'],
        'state' => $_POST['state'],
        'pincode' => $_POST['pincode'],
        'payment_method' => $_POST['payment_method'],
        'total_price' => $total_price,
        'cart' => $_SESSION['cart']
    ];

    // Generate Order ID (Example: timestamp + random number)
    $_SESSION['order_id'] = time() . rand(1000, 9999);

    // Clear the cart after storing order details
    unset($_SESSION['cart']);

    // Redirect to order_success.php
    header("Location: order_success.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <style>
        /*body { font-family: Arial, sans-serif; padding: 20px; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; }
        .input-group { margin-bottom: 15px; }
        .input-group label { display: block; font-weight: bold; }
        .input-group input, .input-group select { width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 5px; }
        .order-summary { margin-top: 20px; padding: 15px; background: #fff3cd; border-radius: 10px; }
        .payment-options { margin: 15px 0; }
        .btn { background: #28a745; color: white; padding: 10px 15px; border: none; width: 100%; cursor: pointer; border-radius: 5px; }
        .btn:hover { background: #218838; }*/
    </style>
    <style>
        /* Global Styles */
body {
    font-family: 'Poppins', sans-serif;
    background-color: #f8f9fa;
    padding: 40px;
    margin: 0;
}

/* Container */
.container {
    max-width: 500px;
    margin: auto;
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease-in-out;
}

.container:hover {
    transform: translateY(-3px);
}

/* Heading */
h2 {
    text-align: center;
    font-size: 24px;
    margin-bottom: 15px;
    color: #343a40;
}

/* Input Groups */
.input-group {
    margin-bottom: 15px;
}

.input-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 5px;
    color: #495057;
}

.input-group input,
.input-group select {
    width: 100%;
    padding: 12px;
    border: 1px solid #ced4da;
    border-radius: 6px;
    font-size: 14px;
    background-color: #f8f9fa;
    transition: border-color 0.3s ease-in-out;
}

.input-group input:focus,
.input-group select:focus {
    border-color: #28a745;
    outline: none;
    background-color: white;
}

/* Order Summary */
.order-summary {
    padding: 15px;
    background: #fdf8e4;
    border-radius: 8px;
    border-left: 5px solid #ffc107;
    margin-bottom: 15px;
}

.order-summary h3 {
    margin-bottom: 10px;
    font-size: 18px;
    color: #333;
}

/* Payment Options */
.payment-options {
    margin-top: 10px;
}

/* Button */
.btn {
    background: #28a745;
    color: white;
    padding: 12px;
    border: none;
    width: 100%;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    border-radius: 6px;
    transition: background 0.3s ease-in-out;
}

.btn:hover {
    background: #218838;
}

/* Responsive */
@media (max-width: 600px) {
    .container {
        max-width: 100%;
        padding: 20px;
    }
}

    </style>
</head>
<body>
    <div class="container">
        <h2>Proceed to Checkout</h2>
        
        <div class="order-summary">
            <h3>Order Summary</h3>
            <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
                <?php foreach ($_SESSION['cart'] as $item): ?>
                    <p><?php echo htmlspecialchars($item['name']) . " x " . $item['quantity']; ?> - Rs <?php echo number_format($item['price'] * $item['quantity'], 2); ?></p>
                <?php endforeach; ?>
                <h3>Total: Rs <?php echo number_format($total_price, 2); ?></h3>
            <?php else: ?>
                <p>Your cart is empty.</p>
            <?php endif; ?>
        </div>

        <form id="checkoutForm" method="POST">
            <div class="input-group">
                <label>Name:</label>
                <input type="text" name="name" required>
            </div>
            <div class="input-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            <div class="input-group">
                <label>Mobile Number:</label>
                <input type="text" name="mobile" pattern="[0-9]{10}" required>
            </div>
            <div class="input-group">
                <label>Address:</label>
                <input type="text" name="address" required>
            </div>
            <div class="input-group">
                <label>City:</label>
                <input type="text" name="city" required>
            </div>
            <div class="input-group">
                <label>State:</label>
                <input type="text" name="state" required>
            </div>
            <div class="input-group">
                <label>Pincode:</label>
                <input type="text" name="pincode" pattern="[0-9]{6}" required>
            </div>
            
            <div class="payment-options">
                <label>Payment Method:</label>
                <select name="payment_method" required>
                    <option value="COD">Cash on Delivery</option>
                    <option value="UPI">UPI</option>
                    <option value="Card">Credit/Debit Card</option>
                </select>
            </div>
            
            <button type="submit" class="btn">Place Order</button>
        </form>
    </div>
</body>
</html>
