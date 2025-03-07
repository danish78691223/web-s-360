<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WEB'S 360 - Home</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=home" />
</head>

<body>
    <header>
        <div class="container">
            <div class="navbar">
                <div class="logo">WEB'S 360</div>
                <div class="hamburger" id="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <nav id="side-nav">
                    <ul class="nav-links">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="shopping/shopping.php">Shopping</a></li>
                        <li><a href="entertainment/entertainment.php">Entertainment</a></li>
                        <li><a href="aboutus.html">About Us</a></li>

                        <?php
                        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
                        echo '<li class="dropdown">';
                        echo '<a href="#" id="profile-toggle" class="dropdown-toggle">' . htmlspecialchars($_SESSION['user_name']) . ' ▼</a>';
                        echo '<ul id="profile-menu" class="dropdown-menu hidden">';

                        echo '<li><a href="profile.php">Profile</a></li>';
                        echo '<li><a href="logout.php">Logout</a></li>';
                        echo '</ul>';
                        echo '</li>';
                            } else {
                        echo '<li><a href="login.php">Login</a></li>';
                            }
                        ?>

                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <section class="hero-section">
        <div class="hero-content">
            <h1>Welcome to WEB'S 360</h1>
            <p>Your one-stop platform for buying, selling, and managing your business.</p>
            <a href="login.php" class="cta-btnn">Get Started</a>
        </div>
    </section>
    <section class="info-section">
        <div class="container">
            <h2>About WEB'S 360</h2>
            <p>WEB'S 360 is designed to simplify your business and lifestyle. Explore our features for shopping, selling, and managing operations efficiently.</p>
            <div class="features">
                <div class="feature">
                    <a href="shopping/shopping.php"><h3>Shop & Sell</h3></a>
                    <p>Browse and buy the latest products with ease. Reach a wide audience and grow your sales.</p>
                </div>
                <div class="feature">
                    <a href="entertainment/entertainment.php"><h3>Entertain yourself</h3></a>
                    <p>"Unleash the thrill of entertainment—where fun meets excitement, and every moment is an experience to remember!" 🎭✨</p>
                </div>
                <div class="feature">
                    <a href="./admin/business_management/users/register.php"><h3>Manage Business</h3></a>
                    <p>Admins and employees can track operations and analytics.</p>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Toggle mobile menu
        const hamburger = document.querySelector('.hamburger');
        const navLinks = document.querySelector('.nav-links');

        hamburger.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });
    </script>

    <footer class="footer">
  <div class="footer-container">
    <div class="footer-section">
      <h4>ABOUT</h4>
      <a href="contactus.html">Contact Us</a>
      <a href="aboutus.html">About Us</a>
      <a href="#">Corporate Information</a>
    </div>

    <div class="footer-section">
      <h4>HELP</h4>
      <a href="#">Cancellation & Returns</a>
    </div>

    <div class="footer-section">
      <h4>CONSUMER POLICY</h4>
      <a href="#">Cancellation & Returns</a>
      <a href="#">Terms Of Use</a>
      <a href="#">Security</a>
      <a href="#">Privacy</a>
    </div>

    <div class="footer-section contact-info">
      <h4>Mail Us:</h4>
      <p>WEB'S 360 Private Limited<br>
        07, Sangam Park<br>
        Behind S.G.R.G. Shinde Collage <br>
        Bawachi Road Paranda<br>
        Osmanabad(Dharashiv), 413502,<br>
        Maharashtra, India
      </p>
      <h4>Social:</h4>
      <div class="social-icons">
        <a href="https://www.facebook.com/profile.php?id=61573501899192"><img src="images/facebook.png"><i class="fab fa-facebook"></i></a>
        <a href="https://x.com/ALLROUNDER75430"><img src="images/twitter.png"><i class="fab fa-x-twitter"></i></a>
        <a href="https://www.instagram.com/webs_360/?hl=en"><img src="images/instagram.png"></a>
      </div>
    </div>

    <div class="footer-section contact-info">
      <h4>Registered Office Address:</h4>
      <p>WEB'S 360 Private Limited<br>
        07, Sangam Park<br>
        Behind S.G.R.G. Shinde Collage <br>
        Bawachi Road Paranda<br>
        Osmanabad(Dharashiv), 413502,<br>
        Maharashtra, India<br>
        CIN : U51109KA2012PTC066107<br>
        Telephone: <a href="7709201779">7709201779</a> / <a href="9511749510">9511749510</a>
      </p>
    </div>
  </div>

  <div class="footer-bottom">
    <a href=".\admin\admin_login.php">Admin Login</a>
    <a href="admin_register.php">Become a Seller</a>
    <a href="help.html">Help Center</a>
    <span>© 2024-2025 Webs'360.com</span>

    <div class="payment-icons">
      <a href="https://www.visa.co.in/pay-with-visa/visa-affluent.html?utm_source=Google-GDN/Search/Adwords&utm_medium=Search&utm_campaign=India-Brand-B2C-FY25Q2-FY25-India-Brand-Site-Traffic/Clicks/Consideration&utm_term=NA&utm_content=Search-Search-English-Brand---Visa-Brand---Visa---Keywords-NA-NA-NA-NA-NA-NA-FY25-India-Brand-NA&gclid=Cj0KCQiAwtu9BhC8ARIsAI9JHakytJLEy76RP5dWX7_GTzTRF8vUKXEk3lAk2Pl0iMawxvtoH8RtI2IaAkQWEALw_wcB" target="_blank"><img src="images/visa.png" alt="Visa"></a>
      <a href="https://www.mastercard.co.in/en-in.html" target="_blank"><img src="images/mastercard.png" alt="Mastercard">
      <a href="https://www.rupay.co.in/" target="_blank"><img src="images/rupay.png" alt="RuPay">
      <img src="images/cashondelivery.png" alt="Cash On Delivery">
      <img src="images/service.png" alt="Net Banking">
    </div>
  </div>
</footer>
<style>
    .social-icons {
  display: flex;
  gap: 15px; /* Space between icons */
  align-items: center;
}

.social-icons img {
  width: 30px; /* Adjust size as needed */
  height: 30px;
  object-fit: cover;
  border-radius: 50%; /* Makes icons circular */
  transition: transform 0.3s ease-in-out;
}

.social-icons a {
  display: flex;
  align-items: center;
  justify-content: center;
  text-decoration: none;
  position: relative;
}

.social-icons a i {
  position: absolute;
  font-size: 18px;
  color: white;
  opacity: 0;
  transition: opacity 0.3s ease-in-out;
}

.social-icons a:hover i {
  opacity: 1;
}

.social-icons a:hover img {
  transform: scale(1.1); /* Slight zoom effect */
  filter: brightness(0.8);
}

</style>
</body>
</html>
