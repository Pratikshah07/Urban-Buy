<?php
require_once 'db_config.php';

class Product {
    private $conn;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Get all products
    public function getAllProducts() {
        $query = "SELECT p.*, c.name as category_name 
                 FROM products p
                 LEFT JOIN categories c ON p.category_id = c.id
                 ORDER BY p.created_at DESC";
        
        $result = $this->conn->query($query);
        return $result;
    }
    
    // Get featured products
    public function getFeaturedProducts() {
        $query = "SELECT p.*, c.name as category_name 
                 FROM products p
                 LEFT JOIN categories c ON p.category_id = c.id
                 WHERE p.featured = TRUE
                 ORDER BY p.created_at DESC";
        
        $result = $this->conn->query($query);
        return $result;
    }
    
    // Get products by category
    public function getProductsByCategory($category_id) {
        $query = "SELECT p.*, c.name as category_name 
                 FROM products p
                 LEFT JOIN categories c ON p.category_id = c.id
                 WHERE p.category_id = ?
                 ORDER BY p.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $category_id);
        $stmt->execute();
        return $stmt->get_result();
    }
    
    // Get single product
    public function getProductById($id) {
        $query = "SELECT p.*, c.name as category_name 
                 FROM products p
                 LEFT JOIN categories c ON p.category_id = c.id
                 WHERE p.id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();
    }
    
    // Search products
    public function searchProducts($keyword) {
        $keyword = "%{$keyword}%";
        $query = "SELECT p.*, c.name as category_name 
                 FROM products p
                 LEFT JOIN categories c ON p.category_id = c.id
                 WHERE p.name LIKE ? OR p.description LIKE ?
                 ORDER BY p.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss", $keyword, $keyword);
        $stmt->execute();
        return $stmt->get_result();
    }
}

// Get all categories
function getAllCategories($conn) {
    $query = "SELECT * FROM categories ORDER BY name";
    $result = $conn->query($query);
    return $result;
}

// Function to get featured products (used in index.php)
function getFeaturedProductsHTML() {
    global $conn;
    
    $product = new Product($conn);
    $result = $product->getFeaturedProducts();
    
    $output = '';
    
    while ($row = $result->fetch_assoc()) {
        $output .= '<div class="carousel-slide">
            <div class="product-card">
                <div class="product-badge">Featured</div>
                <div class="product-image">
                    <img src="' . $row['image'] . '" alt="' . $row['name'] . '">
                    <div class="product-actions">
                        <button class="quick-view" data-id="' . $row['id'] . '"><i class="fas fa-eye"></i></button>
                        <button class="add-to-cart" data-id="' . $row['id'] . '"><i class="fas fa-shopping-cart"></i></button>
                        <button class="add-to-wishlist" data-id="' . $row['id'] . '"><i class="fas fa-heart"></i></button>
                    </div>
                </div>
                <div class="product-info">
                    <span class="product-category">' . $row['category_name'] . '</span>
                    <h3>' . $row['name'] . '</h3>
                    <p class="product-price">$' . number_format($row['price'], 2) . '</p>
                    <button class="btn add-to-cart-btn" data-id="' . $row['id'] . '">Add to Cart</button>
                </div>
            </div>
        </div>';
    }
    
    return $output;
}

// Function to get category cards (used in index.php)
function getCategoriesHTML() {
    global $conn;
    
    $result = getAllCategories($conn);
    
    $output = '';
    
    while ($row = $result->fetch_assoc()) {
        $output .= '<div class="category-card">
            <a href="shop.php?category=' . $row['id'] . '">
                <img src="' . $row['image'] . '" alt="' . $row['name'] . '">
                <h3>' . $row['name'] . '</h3>
            </a>
        </div>';
    }
    
    return $output;
}
?> 