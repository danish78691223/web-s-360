<?php
include '../db.php'; // Ensure this file connects to the database

$query = "SELECT orders.id, products.name AS product_name, orders.quantity, 
                 orders.total_price, orders.status, users.name, users.email, users.address 
          FROM orders 
          JOIN products ON orders.product_id = products.id
          JOIN users ON orders.user_id = users.id";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <link rel="stylesheet" href="assets/css/orders.css"> <!-- Ensure this file exists -->
</head>
<body>
    <div class="container">
        <h2>Orders List</h2>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Customer Name</th>
                <th>Email</th>
                <th>Address</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['product_name']; ?></td>
                <td><?php echo $row['quantity']; ?></td>
                <td>₹<?php echo number_format($row['total_price'], 2); ?></td>
                <td class="status <?php echo strtolower($row['status']); ?>"><?php echo ucfirst($row['status']); ?></td>
                <td><?php echo $row['full_name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['address']; ?></td>
            </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
