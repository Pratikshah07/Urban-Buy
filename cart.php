<?php
require_once 'db_config.php';
require_once 'users.php';
require_once 'Cart.class.php';

// Start session if not already started
if (!isset($_SESSION)) {
    session_start();
}

// Ensure guest user exists if using guest mode
if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == 9999) {
    $check_guest = $conn->query("SELECT id FROM users WHERE id = 9999");
    if ($check_guest->num_rows == 0) {
        $conn->query("INSERT INTO users (id, username, email, password) VALUES (9999, 'guest', 'guest@demo.com', 'guest')");
    }
}

$user_id = $_SESSION['user_id'] ?? null;
$cartObj = new Cart($conn);
$cart_items = [];
$cart_total = 0;
$cart_count = 0;

if ($user_id) {
    $cart_items = $cartObj->getCartItems($user_id);
    $cart_total = $cartObj->getCartTotal($user_id);
    $cart_count = $cartObj->getCartCount($user_id);
    // Handle update quantity
    if (isset($_POST['update_cart']) && isset($_POST['cart_id']) && isset($_POST['quantity'])) {
        $cartObj->updateCartItem($_POST['cart_id'], $_POST['quantity']);
        header('Location: cart.php');
        exit();
    }
    // Handle remove item
    if (isset($_POST['remove_cart']) && isset($_POST['cart_id'])) {
        $cartObj->removeFromCart($_POST['cart_id']);
        header('Location: cart.php');
        exit();
    }
    // Handle add to cart from product/shop page
    if (isset($_POST['add_to_cart']) && isset($_POST['product_id'])) {
        $cartObj->addToCart($user_id, $_POST['product_id'], $_POST['quantity'] ?? 1);
        header('Location: cart.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - UrbanBuy</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Additional styles for cart page */
        .cart-page {
            padding: 60px 0;
        }
        
        .cart-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: var(--box-shadow);
            overflow: hidden;
        }
        
        .cart-header {
            padding: 20px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .cart-header h1 {
            margin: 0;
            font-size: 24px;
        }
        
        .cart-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .cart-table th {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-muted);
            font-weight: 500;
            background-color: var(--light-color);
        }
        
        .cart-table td {
            padding: 15px;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }
        
        .cart-product {
            display: flex;
            align-items: center;
        }
        
        .cart-product img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
            margin-right: 15px;
        }
        
        .cart-product-info h3 {
            margin: 0 0 5px 0;
        }
        
        .cart-product-info p {
            margin: 0;
            color: var(--text-muted);
            font-size: 14px;
        }
        
        .quantity-control {
            display: flex;
            align-items: center;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            width: fit-content;
        }
        
        .quantity-control button {
            background: none;
            border: none;
            width: 30px;
            height: 30px;
            font-size: 16px;
            cursor: pointer;
            background-color: var(--light-color);
        }
        
        .quantity-control input {
            width: 40px;
            border: none;
            text-align: center;
            font-size: 14px;
            padding: 5px 0;
        }
        
        .cart-remove {
            color: var(--danger-color);
            border: none;
            background: none;
            cursor: pointer;
            font-size: 16px;
        }
        
        .cart-totals {
            padding: 20px;
            background-color: var(--light-color);
        }
        
        .cart-summary {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid var(--border-color);
        }
        
        .cart-summary:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 18px;
            padding-top: 15px;
        }
        
        .cart-actions {
            display: flex;
            justify-content: space-between;
            padding: 20px;
            border-top: 1px solid var(--border-color);
        }
        
        .empty-cart {
            text-align: center;
            padding: 40px 20px;
        }
        
        .empty-cart i {
            font-size: 60px;
            color: var(--text-muted);
            margin-bottom: 20px;
        }
        
        .empty-cart h2 {
            margin-bottom: 15px;
        }
        
        .empty-cart p {
            margin-bottom: 30px;
            color: var(--text-muted);
        }
        
        @media (max-width: 768px) {
            .cart-table thead {
                display: none;
            }
            
            .cart-table tbody tr {
                display: block;
                padding: 15px;
                position: relative;
            }
            
            .cart-table td {
                display: block;
                border: none;
                padding: 5px 0;
                text-align: right;
            }
            
            .cart-table td:before {
                content: attr(data-label);
                float: left;
                font-weight: bold;
            }
            
            .cart-product {
                text-align: left;
            }
            
            .cart-remove {
                position: absolute;
                top: 15px;
                right: 15px;
            }
            
            .quantity-control {
                margin-left: auto;
            }
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
                    <?php if (User::isLoggedIn()): ?>
                        <li class="nav-item"><a href="account.php" class="nav-link">My Account</a></li>
                        <li class="nav-item"><a href="logout.php" class="nav-link">Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a href="login.php" class="nav-link">Login</a></li>
                        <li class="nav-item"><a href="register.php" class="nav-link">Register</a></li>
                    <?php endif; ?>
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
        <!-- Page Header -->
        <section class="page-header" style="background-color: var(--light-color); padding: 40px 0;">
            <div class="container">
                <h1>Shopping Cart</h1>
                <div class="breadcrumbs">
                    <a href="index.php">Home</a> / Shopping Cart
                </div>
            </div>
        </section>
        
        <!-- Cart Section -->
        <section class="cart-page">
            <div class="container">
                <div class="cart-container">
                    <?php if (User::isLoggedIn() && $cart_count > 0): ?>
                        <div class="cart-header">
                            <h1>Your Shopping Cart (<?php echo $cart_count; ?> items)</h1>
                        </div>
                        <table class="cart-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($item = $cart_items->fetch_assoc()): ?>
                                    <tr data-cart-id="<?php echo $item['id']; ?>">
                                        <td data-label="Product">
                                            <div class="cart-product">
                                                <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                                <div class="cart-product-info">
                                                    <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                                                    <p>SKU: PROD-<?php echo htmlspecialchars($item['product_id']); ?></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-label="Price">$<?php echo htmlspecialchars($item['price']); ?></td>
                                        <td data-label="Quantity">
                                            <div class="quantity-control">
                                                <form method="post" action="cart.php" style="display:inline;">
                                                    <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">
                                                    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" style="width:50px;">
                                                    <button type="submit" name="update_cart" class="btn">Update</button>
                                                </form>
                                            </div>
                                        </td>
                                        <td data-label="Total">$<?php echo number_format($item['total_price'], 2); ?></td>
                                        <td>
                                            <form method="post" action="cart.php" style="display:inline;">
                                                <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">
                                                <button type="submit" name="remove_cart" class="btn btn-danger">Remove</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                        <div class="cart-totals">
                            <div class="cart-summary">
                                <span>Subtotal</span>
                                <span>$<?php echo number_format($cart_total, 2); ?></span>
                            </div>
                            <div class="cart-summary">
                                <span>Shipping</span>
                                <span>Free</span>
                            </div>
                            <div class="cart-summary">
                                <span>Total</span>
                                <span>$<?php echo number_format($cart_total, 2); ?></span>
                            </div>
                        </div>
                        <div class="cart-actions">
                            <a href="shop.php" class="btn btn-secondary">Continue Shopping</a>
                            <a href="checkout.php" class="btn">Proceed to Checkout</a>
                        </div>
                    <?php else: ?>
                        <div class="empty-cart">
                            <i class="fas fa-shopping-cart"></i>
                            <h2>Your cart is empty</h2>
                            <p>Looks like you haven't added any products to your cart yet.</p>
                            <a href="shop.php" class="btn">Start Shopping</a>
                        </div>
                    <?php endif; ?>
                </div>
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
                        <li><a href="shop.php?new=1">New Arrivals</a></li>
                        <li><a href="shop.php?featured=1">Featured</a></li>
                        <li><a href="shop.php?sale=1">Sale</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Information</h3>
                    <ul>
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="contact.php">Contact</a></li>
                        <li><a href="terms.php">Terms & Conditions</a></li>
                        <li><a href="privacy.php">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Customer Service</h3>
                    <ul>
                        <li><a href="faq.php">FAQs</a></li>
                        <li><a href="shipping.php">Shipping</a></li>
                        <li><a href="returns.php">Returns</a></li>
                        <li><a href="track-order.php">Track Order</a></li>
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
                <p>&copy; <?php echo date('Y'); ?> UrbanBuy. All rights reserved.</p>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Quantity controls
            const decreaseButtons = document.querySelectorAll('.quantity-decrease');
            const increaseButtons = document.querySelectorAll('.quantity-increase');
            const removeButtons = document.querySelectorAll('.cart-remove');
            
            function updateCartItem(cartId, quantity) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'cart.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (this.status === 200) {
                        const response = JSON.parse(this.responseText);
                        if (response.status === 'success') {
                            // Update cart count and total
                            document.querySelector('.cart-count').textContent = response.cart_count;
                            
                            // Update row total
                            const row = document.querySelector(`tr[data-cart-id="${cartId}"]`);
                            const price = parseFloat(row.querySelector('[data-label="Price"]').textContent.replace('$', ''));
                            const total = price * quantity;
                            row.querySelector('[data-label="Total"]').textContent = '$' + total.toFixed(2);
                            
                            // Update cart total
                            const cartTotals = document.querySelectorAll('.cart-summary span:last-child');
                            cartTotals[0].textContent = '$' + response.cart_total.toFixed(2);
                            cartTotals[2].textContent = '$' + response.cart_total.toFixed(2);
                        } else {
                            alert(response.message);
                        }
                    }
                };
                xhr.send(`action=update_cart&cart_id=${cartId}&quantity=${quantity}`);
            }
            
            function removeCartItem(cartId) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'cart.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (this.status === 200) {
                        const response = JSON.parse(this.responseText);
                        if (response.status === 'success') {
                            // Update cart count
                            document.querySelector('.cart-count').textContent = response.cart_count;
                            
                            // Remove the row
                            const row = document.querySelector(`tr[data-cart-id="${cartId}"]`);
                            row.remove();
                            
                            // Update cart total
                            const cartTotals = document.querySelectorAll('.cart-summary span:last-child');
                            cartTotals[0].textContent = '$' + response.cart_total.toFixed(2);
                            cartTotals[2].textContent = '$' + response.cart_total.toFixed(2);
                            
                            // If cart is empty, reload page
                            if (response.cart_count == 0) {
                                window.location.reload();
                            }
                        } else {
                            alert(response.message);
                        }
                    }
                };
                xhr.send(`action=remove_from_cart&cart_id=${cartId}`);
            }
            
            decreaseButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const input = this.nextElementSibling;
                    let value = parseInt(input.value);
                    if (value > 1) {
                        value--;
                        input.value = value;
                        
                        // Update cart
                        const row = this.closest('tr');
                        const cartId = row.getAttribute('data-cart-id');
                        updateCartItem(cartId, value);
                    }
                });
            });
            
            increaseButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const input = this.previousElementSibling;
                    let value = parseInt(input.value);
                    value++;
                    input.value = value;
                    
                    // Update cart
                    const row = this.closest('tr');
                    const cartId = row.getAttribute('data-cart-id');
                    updateCartItem(cartId, value);
                });
            });
            
            removeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    if (confirm('Are you sure you want to remove this item from your cart?')) {
                        const cartId = this.getAttribute('data-cart-id');
                        removeCartItem(cartId);
                    }
                });
            });
        });
    </script>
</body>
</html> 