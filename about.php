<?php
session_start();
$cart_count = 0;
if (isset($_SESSION['user_id'])) {
    require_once 'Cart.class.php';
    require_once 'db_config.php';
    $cartObj = new Cart($conn);
    $cart_count = $cartObj->getCartCount($_SESSION['user_id']);
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - UrbanBuy</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f7f7f7; }
        .container { max-width: 900px; margin: 0 auto; padding: 20px; }
        .about-section { background: #fff; border-radius: 10px; box-shadow: 0 2px 8px #0001; padding: 40px 30px; margin: 40px 0; }
        .about-section h1 { margin-bottom: 20px; }
        .about-section ul { margin-top: 20px; }
        .about-section ul li { margin-bottom: 8px; }
        .footer-content { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 20px; }
        .footer-column h3 { margin-bottom: 10px; }
        .footer-column ul { list-style: none; padding: 0; }
        .footer-column ul li { margin-bottom: 8px; }
        .footer-column ul li a { color: #222; text-decoration: none; }
        .footer-column ul li a:hover { color: #007bff; }
        .footer-bottom { margin-top: 20px; text-align: center; color: #888; }
        .cart-icon { position: relative; }
        .cart-count { background: #007bff; color: #fff; border-radius: 50%; padding: 2px 8px; font-size: 13px; position: absolute; top: -10px; right: -10px; }
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <div class="logo">
                    <a href="index.php">UrbanBuy</a>
                </div>
                <ul class="nav-menu">
                    <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
                    <li class="nav-item"><a href="shop.php" class="nav-link">Products</a></li>
                    <li class="nav-item"><a href="about.php" class="nav-link active">About</a></li>
                    <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
                </ul>
                <div class="cart-icon">
                    <a href="cart.php"><i class="fas fa-shopping-cart"></i>
                    <span class="cart-count"><?php echo $cart_count; ?></span></a>
                </div>
                <div class="hamburger">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </div>
            </nav>
        </div>
    </header>
    <main>
        <section class="about-section">
            <div class="container">
                <h1>About UrbanBuy</h1>
                <p>UrbanBuy is your one-stop shop for the latest in fashion, footwear, accessories, and more. We are dedicated to bringing you the best products at unbeatable prices, with a focus on quality, customer service, and uniqueness.</p>
                <p>Founded in 2025, UrbanBuy has come a long way from its beginnings. When we first started out, our passion for stylish and affordable products drove us to start our own business.</p>
                <p>We hope you enjoy our products as much as we enjoy offering them to you. If you have any questions or comments, please don't hesitate to contact us!</p>
                <h2>Our Mission</h2>
                <ul>
                    <li>Deliver high-quality products at affordable prices</li>
                    <li>Provide excellent customer service</li>
                    <li>Offer a wide variety of trendy and classic items</li>
                </ul>
            </div>
        </section>
    </main>
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>Shop</h3>
                    <ul>
                        <li><a href="shop.php">All Products</a></li>
                        <li><a href="#">New Arrivals</a></li>
                        <li><a href="#">Featured</a></li>
                        <li><a href="#">Sale</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Information</h3>
                    <ul>
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="contact.php">Contact</a></li>
                        <li><a href="#">Terms & Conditions</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Customer Service</h3>
                    <ul>
                        <li><a href="#">FAQs</a></li>
                        <li><a href="#">Shipping</a></li>
                        <li><a href="#">Returns</a></li>
                        <li><a href="#">Track Order</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Connect With Us</h3>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-pinterest"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Urban-Buy Store. All rights reserved.</p>
                <div class="payment-icons">
                    <i class="fab fa-cc-visa"></i>
                    <i class="fab fa-cc-mastercard"></i>
                    <i class="fab fa-cc-amex"></i>
                    <i class="fab fa-cc-paypal"></i>
                </div>
            </div>
        </div>
    </footer>
    <script src="script.js"></script>
</body>
</html> 