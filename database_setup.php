<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'db_config.php';

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

// Select the database
$conn->select_db($dbname);

// Create users table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    address TEXT,
    city VARCHAR(50),
    postal_code VARCHAR(20),
    country VARCHAR(50),
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Users table created successfully<br>";
} else {
    echo "Error creating users table: " . $conn->error . "<br>";
}

// Create categories table
$sql = "CREATE TABLE IF NOT EXISTS categories (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    image VARCHAR(255)
)";

if ($conn->query($sql) === TRUE) {
    echo "Categories table created successfully<br>";
} else {
    echo "Error creating categories table: " . $conn->error . "<br>";
}

// Create products table
$sql = "CREATE TABLE IF NOT EXISTS products (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    category_id INT(11),
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    stock INT(11) DEFAULT 0,
    featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "Products table created successfully<br>";
} else {
    echo "Error creating products table: " . $conn->error . "<br>";
}

// Create orders table
$sql = "CREATE TABLE IF NOT EXISTS orders (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11),
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    shipping_address TEXT,
    payment_method VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "Orders table created successfully<br>";
} else {
    echo "Error creating orders table: " . $conn->error . "<br>";
}

// Create order_items table
$sql = "CREATE TABLE IF NOT EXISTS order_items (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    order_id INT(11) NOT NULL,
    product_id INT(11),
    quantity INT(11) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "Order items table created successfully<br>";
} else {
    echo "Error creating order items table: " . $conn->error . "<br>";
}

// Create cart table
$sql = "CREATE TABLE IF NOT EXISTS cart (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11),
    product_id INT(11),
    quantity INT(11) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "Cart table created successfully<br>";
} else {
    echo "Error creating cart table: " . $conn->error . "<br>";
}

// Create newsletter table
$sql = "CREATE TABLE IF NOT EXISTS newsletter (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Newsletter table created successfully<br>";
} else {
    echo "Error creating newsletter table: " . $conn->error . "<br>";
}

// Insert sample categories
$sql = "INSERT INTO categories (name, description, image) VALUES 
('Shoes', 'All types of footwear', 'https://images.unsplash.com/photo-1612015670817-0127d21628d4'),
('T-shirts', 'Casual and formal t-shirts', 'https://plus.unsplash.com/premium_photo-1673356302067-aac3b545a362'),
('Watches', 'Luxury and sports watches', 'https://images.unsplash.com/photo-1594534475808-b18fc33b045e'),
('Glasses', 'Sunglasses and eyewear', 'https://images.unsplash.com/photo-1732139637220-15346842a9fd')";

if ($conn->query($sql) === TRUE) {
    echo "Sample categories added successfully<br>";
} else {
    echo "Error adding sample categories: " . $conn->error . "<br>";
}

// Insert sample products
$sql = "INSERT INTO products (category_id, name, description, price, image, stock, featured) VALUES 
(1, 'Running Shoes', 'Comfortable shoes for running and jogging', 99.99, 'https://images.unsplash.com/photo-1635205383450-e0fee6fe73c4', 50, TRUE),
(2, 'Casual Tee', 'Casual t-shirt for everyday wear', 89.99, 'https://images.unsplash.com/photo-1636590416469-af0828ebbf27', 100, TRUE),
(3, 'Classic Watch', 'Elegant watch for all occasions', 79.99, 'https://images.unsplash.com/photo-1644621596488-25d519a1a8ed', 30, TRUE),
(4, 'Designer Sunglasses', 'Stylish sunglasses for the summer', 69.99, 'https://images.unsplash.com/photo-1596518997680-7f15c4b7543d', 40, TRUE),
(2, 'Premium Tee', 'Premium quality cotton t-shirt', 59.99, 'https://images.unsplash.com/photo-1618001789159-ffffe6f96ef2', 80, TRUE)";

if ($conn->query($sql) === TRUE) {
    echo "Sample products added successfully<br>";
} else {
    echo "Error adding sample products: " . $conn->error . "<br>";
}

echo "Database setup completed!";
?> 