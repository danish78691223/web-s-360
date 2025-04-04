<?php 
session_start();

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$username = $isLoggedIn && isset($_SESSION['username']) ? $_SESSION['username'] : ''; // Prevent undefined index warning

// Redirect to original page after login
if (!$isLoggedIn && basename($_SERVER['PHP_SELF']) !== 'login.php') {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
}

// Database connection
$servername = "localhost";
$username_db = "root";  
$password_db = "";  
$database = "auth_system"; 

$conn = new mysqli($servername, $username_db, $password_db, $database);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Fetch products from both tables
$sql = "
    SELECT id, product_name AS name, price, category, image, description, 'seller' AS source FROM seller_products
    UNION
    SELECT id, name, price, category, image, description, 'regular' AS source FROM products
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Page</title>
    <link rel="stylesheet" href="shopping.css">
    <script defer src="shopping.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg custom-navbar">
        <div class="container">
            <a class="navbar-brand" href="#">WEB'S 360</a>
            <div class="search-bar">
                <input type="text" class="form-control" id="searchInput" placeholder="Search for Products, Brands and More" onkeyup="filterProducts()">
                <button class="btn btn-outline-secondary" onclick="filterProducts()"><i class="fas fa-search"></i></button>
            </div>
            <div class="nav-links">
                <span id="login-section">
                    <?php if ($isLoggedIn): ?>
                        <a href="../profile.php">Welcome, <?= htmlspecialchars($username) ?></a>
                    <?php else: ?>
                        <a href="../login.php">Login</a>
                    <?php endif; ?>
                </span>
                <a href="cart.php">Cart</a>
                <a href="seller/seller_register_login.php">Become a Seller</a>
                <a href="liked_products.php" class="active">Liked Products</a>
            </div>
        </div>
    </nav>

    <!-- Mini Header for Categories -->
    <div class="mini-header">
        <nav>
            <a href="#" onclick="filterCategory('All')">All</a>
            <a href="#" onclick="filterCategory('clothing')">Fashion</a>
            <a href="#" onclick="filterCategory('Grocery')">Grocery</a>
            <a href="#" onclick="filterCategory('home')">Home</a>
            <a href="#" onclick="filterCategory('electronics')">Electronics</a>
            <a href="#" onclick="filterCategory('Gadgets')">Gadgets</a>
            <a href="#" onclick="filterCategory('Beauty')">Beauty</a>
        </nav>
    </div>

    <!-- Products Section -->
    <section class="products">
        <div class="container">
            <div class="row" id="productContainer">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Determine image path based on source
                        $imagePath = ($row["source"] === 'seller') ? "shopping/seller/uploads/" . htmlspecialchars($row["image"]) : "../uploads/" . htmlspecialchars($row["image"]);

                        echo '<div class="col-md-4 product-card" data-category="' . htmlspecialchars($row["category"]) . '" data-name="' . htmlspecialchars($row["name"]) . '">';
                        echo '<span class="heart-icon" onclick="toggleLike(this)">&#x2661;</span>'; 
                        echo '<a href="product_details.php?id=' . $row["id"] . '">';
                        echo '<img src="' . $imagePath . '" alt="Product Image">';
                        echo '<h4>' . htmlspecialchars($row["name"]) . '</h4>';
                        echo '<p>' . htmlspecialchars($row["description"]) . '</p>'; // Added description field
                        echo '<h5>Rs ' . htmlspecialchars($row["price"]) . '</h5>'; 
                        echo '</a>';
                        echo '<form method="POST" action="cart.php">';
                        echo '<input type="hidden" name="product_id" value="' . $row['id'] . '">';
                        echo '<input type="hidden" name="name" value="' . htmlspecialchars($row['name']) . '">';
                        echo '<input type="hidden" name="price" value="' . htmlspecialchars($row['price']) . '">';
                        echo '<input type="hidden" name="image" value="' . $imagePath . '">'; 

                        echo '<button type="submit" class="btn btn-primary" name="add_to_cart">Add to Cart</button>';
                        echo '</form>';
                        echo '</div>';
                    }
                } else {
                    echo "<p class='text-center'>No products available.</p>";
                }
                ?>
            </div>
        </div>
    </section>

    <script>
        function filterProducts() {
            let input = document.getElementById("searchInput").value.toLowerCase();
            let products = document.querySelectorAll(".product-card");
            products.forEach(product => {
                let name = product.getAttribute("data-name").toLowerCase();
                product.style.display = name.includes(input) ? "block" : "none";
            });
        }

        function filterCategory(category) {
            let products = document.querySelectorAll(".product-card");
            products.forEach(product => {
                let productCategory = product.getAttribute("data-category");
                product.style.display = (productCategory === category || category === "All") ? "block" : "none";
            });
        }
    </script>

    <footer class="footer">
        <div class="footer-container">
            <div class="footer-section">
                <h4>ABOUT</h4>
                <a href="../contactus.html">Contact Us</a>
                <a href="../aboutus.html">About Us</a>
            </div>
            <div class="footer-section">
                <h4>GROUP COMPANIES</h4>
                <a href="#">Flipkart</a>
                <a href="#">Air India</a>
                <a href="#">Shopsy</a>
            </div>
            <div class="footer-section">
                <h4>HELP</h4>
                <a href="#">Payments</a>
                <a href="#">Shipping</a>
                <a href="#">Cancellation & Returns</a>
                <a href="#">FAQ</a>
            </div>
        </div>
        <div class="footer-bottom">
            <a href="../admin_register.php">Become a Seller</a>
            <a href="../help.html">Help Center</a>
            <span>© 2024-2025 Webs'360.com</span>
        </div>
    </footer>

</body>
</html>

<?php 
// Ensure connection is closed only once
if ($conn instanceof mysqli && $conn->ping()) {
    $conn->close();
}
?>
