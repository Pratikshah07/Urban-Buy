<?php
require_once 'db_config.php';

class User {
    private $conn;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Register user
    public function register($username, $email, $password, $first_name = null, $last_name = null) {
        // Hash password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        // Check if email or username already exists
        $check_query = "SELECT * FROM users WHERE email = ? OR username = ?";
        $check_stmt = $this->conn->prepare($check_query);
        $check_stmt->bind_param("ss", $email, $username);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        
        if ($result->num_rows > 0) {
            return false; // User already exists
        }
        
        // Insert new user
        $query = "INSERT INTO users (username, email, password, first_name, last_name) 
                  VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssss", $username, $email, $password_hash, $first_name, $last_name);
        
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    // Login user
    public function login($username, $password) {
        $query = "SELECT * FROM users WHERE username = ? OR email = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
                // Password is correct, start session
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['last_name'] = $user['last_name'];
                
                return true;
            }
        }
        
        return false;
    }
    
    // Get user by ID
    public function getUserById($id) {
        $query = "SELECT id, username, email, first_name, last_name, address, city, postal_code, country, phone
                 FROM users WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    // Update user information
    public function updateProfile($id, $first_name, $last_name, $address, $city, $postal_code, $country, $phone) {
        $query = "UPDATE users SET 
                  first_name = ?, 
                  last_name = ?, 
                  address = ?, 
                  city = ?, 
                  postal_code = ?, 
                  country = ?, 
                  phone = ? 
                  WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssssssi", $first_name, $last_name, $address, $city, $postal_code, $country, $phone, $id);
        
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    // Check if user is logged in
    public static function isLoggedIn() {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        return isset($_SESSION['user_id']);
    }
    
    // Logout user
    public static function logout() {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        // Unset all session variables
        $_SESSION = array();
        
        // Destroy the session
        session_destroy();
        
        return true;
    }
}
?> 