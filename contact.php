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
    <title>Contact Us - UrbanBuy</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f7f7f7; }
        .container { max-width: 900px; margin: 0 auto; padding: 20px; }
        .contact-section { background: #fff; border-radius: 10px; box-shadow: 0 2px 8px #0001; padding: 40px 30px; margin: 40px 0; }
        .contact-section h1 { margin-bottom: 20px; }
        .contact-form { display: flex; flex-direction: column; gap: 15px; }
        .contact-form input, .contact-form textarea { padding: 10px; border-radius: 4px; border: 1px solid #ccc; }
        .contact-form button { width: 150px; align-self: flex-start; background: #222; color: #fff; border: none; padding: 10px 0; border-radius: 4px; cursor: pointer; transition: background 0.2s; }
        .contact-form button:hover { background: #007bff; }
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
                    <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
                    <li class="nav-item"><a href="contact.php" class="nav-link active">Contact</a></li>
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
        <section class="contact-section">
            <div class="container">
                <h1>Contact Us</h1>
                <form class="contact-form">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required>
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="5" required></textarea>
                    <button type="submit" class="btn">Send Message</button>
                </form>
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