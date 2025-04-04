<?php
include '../db.php'; // Ensure database connection

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $conn->query("DELETE FROM products WHERE id='$id'");
    header("Location: shop_management.php");
    exit;
} else {
    echo "Product ID not found!";
}
?>
