<?php
require_once 'db_config.php';
// Fetch featured products
$featured_products = [];
$sql = "SELECT * FROM products WHERE featured = 1 LIMIT 5";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $featured_products[] = $row;
    }
}
// Fetch categories
$categories = [];
$sql = "SELECT * FROM categories LIMIT 4";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}
// Cart count
session_start();
$cart_count = 0;
if (isset($_SESSION['user_id'])) {
    require_once 'Cart.class.php';
    $cartObj = new Cart($conn);
    $cart_count = $cartObj->getCartCount($_SESSION['user_id']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UrbanBuy - Home</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f7f7f7; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .hero { background-size: cover; background-position: center; color: #fff; padding: 60px 0; border-radius: 12px; margin-bottom: 30px; }
        .hero-content { background: rgba(0,0,0,0.5); padding: 40px; border-radius: 10px; display: inline-block; }
        .carousel-section { margin-bottom: 10px; }
        .carousel-container { display: flex; gap: 20px; overflow-x: auto; }
        .carousel-slide { min-width: 250px; }
        .product-card { background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #0001; padding: 20px; text-align: center; display: flex; flex-direction: column; align-items: center; }
        .product-card img { width: 100%; max-width: 180px; height: 180px; object-fit: cover; border-radius: 6px; margin-bottom: 10px; }
        .product-card h3 { margin: 10px 0 5px; font-size: 1.1em; }
        .product-card p { margin: 0 0 10px; color: #444; }
        .add-cart-form { display: flex; gap: 8px; align-items: center; justify-content: center; }
        .add-cart-form input[type=number] { width: 60px; padding: 6px; border-radius: 4px; border: 1px solid #ccc; text-align: center; }
        .btn { background: #222; color: #fff; border: none; padding: 8px 18px; border-radius: 4px; cursor: pointer; transition: background 0.2s; font-size: 1em; }
        .btn:hover { background: #007bff; }
        .categories { margin-bottom: 40px; margin-top: 0; padding-top: 0; }
        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 36px;
            justify-items: stretch;
            align-items: stretch;
            width: 100%;
        }
        .category-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 2px 16px #0001;
            padding: 32px 0 0 0;
            text-align: center;
            min-width: 220px;
            min-height: 320px;
            transition: box-shadow 0.2s, transform 0.2s;
            cursor: pointer;
            width: 100%;
            max-width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
        }
        .category-card img {
            width: 180px;
            height: 180px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 18px;
        }
        .category-card h3 {
            margin: 0 0 24px 0;
            font-size: 1.5em;
            font-weight: 700;
            color: #222;
            background: rgba(255,255,255,0.9);
            width: 100%;
            padding: 16px 0 12px 0;
            border-radius: 0 0 20px 20px;
        }
        .category-card:hover {
            box-shadow: 0 8px 32px #0002;
            transform: translateY(-6px) scale(1.03);
        }
        .categories h2 { text-align: center; font-size: 2em; margin-bottom: 0; font-weight: 700; color: #222; }
        .categories h2::after { content: ''; display: block; width: 60px; height: 4px; background: #1abc9c; margin: 12px auto 0; border-radius: 2px; }
        .newsletter { background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #0001; padding: 30px 20px; text-align: center; }
        .newsletter input[type=email] { padding: 8px 12px; border-radius: 4px; border: 1px solid #ccc; margin-right: 10px; }
        .footer-content { display: flex; justify-content: center; gap: 60px; background: #232c36; color: #fff; padding: 40px 0 20px; }
        .footer-column { min-width: 180px; }
        .footer-column h3 { margin-bottom: 10px; font-size: 1.1em; font-weight: 700; border-bottom: 2px solid #1abc9c; display: inline-block; padding-bottom: 4px; }
        .footer-column ul { list-style: none; padding: 0; }
        .footer-column ul li { margin-bottom: 8px; }
        .footer-column ul li a { color: #bfc9d1; text-decoration: none; transition: color 0.2s; }
        .footer-column ul li a:hover { color: #1abc9c; }
        .footer-bottom { background: #232c36; color: #bfc9d1; margin-top: 0; text-align: center; padding: 18px 0 8px; font-size: 1em; border-top: 1px solid #2c3e50; }
        .payment-icons i { font-size: 1.5em; margin: 0 6px; color: #bfc9d1; }
        .social-icons a { color: #bfc9d1; margin-right: 10px; font-size: 1.3em; transition: color 0.2s; }
        .social-icons a:hover { color: #1abc9c; }
        .cart-icon { position: relative; }
        .cart-count { background: #007bff; color: #fff; border-radius: 50%; padding: 2px 8px; font-size: 13px; position: absolute; top: -10px; right: -10px; }
        @media (max-width: 900px) {
            .category-grid { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 600px) {
            .category-grid { grid-template-columns: 1fr; }
            .category-card img { width: 100px; height: 100px; }
        }
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
        <!-- Hero Section -->
        <section class="hero" style="background-image: url(https://plus.unsplash.com/premium_photo-1679056835084-7f21e64a3402?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NTd8fGZhc2hpb24lMjBtYW58ZW58MHx8MHx8fDA%3D);">
            <div class="container">
                <div class="hero-content">
                    <h1>Welcome to Our Store</h1>
                    <p>Find the best products for your needs</p>
                    <a href="shop.php" class="btn btn-primary">Shop Now</a>
                </div>
            </div>
        </section>
        <!-- Carousel Section -->
        <section class="carousel-section">
            <div class="container">
                <h2>Featured Products</h2>
                <div class="carousel">
                    <div class="carousel-container">
                        <?php foreach ($featured_products as $product): ?>
                        <div class="carousel-slide">
                            <div class="product-card">
                                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                                <p>$<?php echo htmlspecialchars($product['price']); ?></p>
                                <form class="add-cart-form" method="post" action="cart.php">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <input type="number" name="quantity" value="1" min="1" max="99">
                                    <button class="btn" type="submit" name="add_to_cart">Add to Cart</button>
                                </form>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="carousel-prev">❮</button>
                    <button class="carousel-next">❯</button>
                </div>
            </div>
        </section>
        <!-- Categories Section -->
        <section class="categories">
            <div class="container">
                <h2>Shop by Category</h2>
                <div class="category-grid">
                    <?php foreach ($categories as $cat): ?>
                    <a href="shop.php?category=<?php echo $cat['id']; ?>" class="category-card" style="text-decoration:none; color:inherit;">
                        <img src="<?php echo htmlspecialchars($cat['image']); ?>" alt="<?php echo htmlspecialchars($cat['name']); ?>">
                        <h3><?php echo htmlspecialchars($cat['name']); ?></h3>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <!-- Newsletter Section -->
        <section class="newsletter">
            <div class="container">
                <h2>Subscribe to Our Newsletter</h2>
                <p>Get the latest updates about our products and offers</p>
                <form id="newsletter-form" method="post" action="newsletter.php">
                    <input type="email" name="email" placeholder="Your email address" required>
                    <button type="submit" class="btn">Subscribe</button>
                </form>
            </div>
        </section>
    </main>
    <footer>
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
    </footer>
    <script src="script.js"></script>
</body>
</html> 