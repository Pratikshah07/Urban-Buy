<?php
require_once 'db_config.php';
require_once 'products.php';
require_once 'Cart.class.php';

if (!isset($_SESSION)) {
    session_start();
}

// Guest login functionality
if (isset($_POST['guest_login'])) {
    // Check if guest user exists, if not, create it
    $check_guest = $conn->query("SELECT id FROM users WHERE id = 9999");
    if ($check_guest->num_rows == 0) {
        $conn->query("INSERT INTO users (id, username, email, password) VALUES (9999, 'guest', 'guest@demo.com', 'guest')");
    }
    $_SESSION['user_id'] = 9999;
    $_SESSION['guest'] = true;
    header('Location: shop.php');
    exit();
}

$cart_count = 0;
if (isset($_SESSION['user_id'])) {
    $cartObj = new Cart($conn);
    $cart_count = $cartObj->getCartCount($_SESSION['user_id']);
}

$productObj = new Product($conn);
$category_id = isset($_GET['category']) ? (int)$_GET['category'] : null;
$search_query = isset($_GET['search']) ? $_GET['search'] : null;
$featured = isset($_GET['featured']) ? true : null;

if ($category_id) {
    $products = $productObj->getProductsByCategory($category_id);
    $category_result = $conn->query("SELECT name FROM categories WHERE id = $category_id");
    $category_name = $category_result->fetch_assoc()['name'];
    $page_title = "Shop " . $category_name;
} elseif ($search_query) {
    $products = $productObj->searchProducts($search_query);
    $page_title = "Search Results for \"$search_query\"";
} elseif ($featured) {
    $products = $productObj->getFeaturedProducts();
    $page_title = "Featured Products";
} else {
    $products = $productObj->getAllProducts();
    $page_title = "All Products";
}

$categories = getAllCategories($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - UrbanBuy</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f7f7f7; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .shop-container { display: flex; gap: 30px; margin-top: 30px; }
        .shop-sidebar { width: 250px; flex-shrink: 0; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #0001; padding: 20px; }
        .shop-main { flex-grow: 1; }
        .product-grid { display: flex; flex-wrap: wrap; justify-content: center; gap: 32px; }
        .product-card { background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #0001; padding: 24px 18px; text-align: center; width: 320px; min-height: 420px; display: flex; flex-direction: column; align-items: center; justify-content: space-between; }
        .product-card img { width: 100%; max-width: 220px; height: 220px; object-fit: cover; border-radius: 6px; margin-bottom: 16px; }
        .product-card h3 { margin: 10px 0 5px; font-size: 1.2em; font-weight: 600; }
        .product-card p { margin: 0 0 10px; color: #444; }
        .btn { background: #222; color: #fff; border: none; padding: 10px 22px; border-radius: 4px; cursor: pointer; transition: background 0.2s; font-size: 1em; }
        .btn:hover { background: #007bff; }
        .filter-section { margin-bottom: 20px; }
        .filter-section h3 { margin-bottom: 10px; font-size: 1.1em; }
        .filter-list { list-style: none; padding: 0; }
        .filter-list li { margin-bottom: 8px; }
        .filter-list a { color: #222; text-decoration: none; }
        .filter-list a:hover { color: #007bff; }
        .shop-header { margin-bottom: 20px; }
        .shop-header h1 { font-size: 1.5em; }
        .guest-login-box { background: #fff3cd; border: 1px solid #ffeeba; padding: 15px; border-radius: 6px; margin-bottom: 20px; text-align: center; }
        @media (max-width: 900px) { .shop-container { flex-direction: column; } .shop-sidebar { width: 100%; margin-bottom: 20px; } .product-grid { justify-content: flex-start; } }
        @media (max-width: 600px) { .product-card { width: 100%; min-width: 0; } }
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
                    <li class="nav-item"><a href="shop.php" class="nav-link active">Products</a></li>
                    <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
                    <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
                </ul>
                <div class="cart-icon">
                    <a href="cart.php"><i class="fas fa-shopping-cart"></i>
                    <span class="cart-count"><?php echo $cart_count; ?></span></a>
                </div>
            </nav>
        </div>
    </header>
    <main>
        <div class="container">
            <?php if (!isset($_SESSION['user_id'])): ?>
                <div class="guest-login-box">
                    <form method="post" action="shop.php">
                        <button type="submit" name="guest_login" class="btn">Continue as Guest (Demo)</button>
                    </form>
                    <p style="margin-top:10px; color:#856404;">You are not logged in. Use guest mode to test cart functionality.</p>
                </div>
            <?php elseif (isset($_SESSION['guest'])): ?>
                <div class="guest-login-box">
                    <p>You are browsing as a <strong>Guest</strong>. <a href="logout.php">Logout</a> to switch user.</p>
                </div>
            <?php endif; ?>
            <div class="shop-container">
                <aside class="shop-sidebar">
                    <div class="filter-section">
                        <h3>Categories</h3>
                        <ul class="filter-list">
                            <li><a href="shop.php">All</a></li>
                            <?php while ($cat = $categories->fetch_assoc()): ?>
                            <li><a href="shop.php?category=<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></a></li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                    <div class="filter-section">
                        <h3>Search</h3>
                        <form class="search-form" method="get" action="shop.php">
                            <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search_query ?? ''); ?>" style="width:100%;padding:6px 10px;">
                        </form>
                    </div>
                </aside>
                <section class="shop-main">
                    <div class="shop-header">
                        <h1><?php echo $page_title; ?></h1>
                    </div>
                    <div class="product-grid">
                        <?php if ($products && $products->num_rows > 0): ?>
                            <?php while ($product = $products->fetch_assoc()): ?>
                            <div class="product-card">
                                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                                <p class="product-category"><?php echo htmlspecialchars($product['category_name']); ?></p>
                                <p class="product-price">$<?php echo htmlspecialchars($product['price']); ?></p>
                                <form method="post" action="cart.php">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <button class="btn" type="submit" name="add_to_cart">Add to Cart</button>
                                </form>
                            </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p>No products found.</p>
                        <?php endif; ?>
                    </div>
                </section>
            </div>
        </div>
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