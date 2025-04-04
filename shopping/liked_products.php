<?php
session_start();
// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$username = $isLoggedIn && isset($_SESSION['username']) ? $_SESSION['username'] : ''; // Prevent undefined index warning
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Liked Products</title>
    <link rel="stylesheet" href="styles.css">
    <script defer src="liked_products.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg custom-navbar">
    <div class="container">
        <a class="navbar-brand" href="shopping.php">WEB'S 360</a>
        <div class="nav-links">
            <span id="login-section">
                <?php if ($isLoggedIn): ?>
                    <a href="../profile.php">Welcome, <?= htmlspecialchars($username) ?></a>
                <?php else: ?>
                    <a href="../login.php">Login</a>
                <?php endif; ?>
            </span>
            <a href="cart.php">Cart</a>
            <a href="liked_products.php" class="active">Liked Products</a>
        </div>
    </div>
</nav>

<!-- Liked Products Section -->
<section class="products">
    <h2 class="text-center">Liked Products</h2>
    <div class="container">
        <div class="row" id="liked-products-list">
            <!-- Liked Products will be loaded here -->
        </div>
    </div>
</section>

<footer class="footer">
    <div class="footer-bottom">
        <a href="../admin_register.php">Become a Seller</a>
        <span>© 2024-2025 Webs'360.com</span>
    </div>
</footer>

</body>
</html>
