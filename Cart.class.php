<?php
class Cart {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function addToCart($user_id, $product_id, $quantity = 1) {
        $check_query = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
        $check_stmt = $this->conn->prepare($check_query);
        $check_stmt->bind_param("ii", $user_id, $product_id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            $cart_item = $result->fetch_assoc();
            $new_quantity = $cart_item['quantity'] + $quantity;
            $query = "UPDATE cart SET quantity = ? WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ii", $new_quantity, $cart_item['id']);
        } else {
            $query = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("iii", $user_id, $product_id, $quantity);
        }
        return $stmt->execute();
    }

    public function updateCartItem($cart_id, $quantity) {
        $query = "UPDATE cart SET quantity = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $quantity, $cart_id);
        return $stmt->execute();
    }

    public function removeFromCart($cart_id) {
        $query = "DELETE FROM cart WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $cart_id);
        return $stmt->execute();
    }

    public function getCartItems($user_id) {
        $query = "SELECT c.id, c.quantity, p.id as product_id, p.name, p.price, p.image, (c.quantity * p.price) as total_price 
                  FROM cart c
                  JOIN products p ON c.product_id = p.id
                  WHERE c.user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getCartTotal($user_id) {
        $query = "SELECT SUM(c.quantity * p.price) as cart_total 
                  FROM cart c
                  JOIN products p ON c.product_id = p.id
                  WHERE c.user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['cart_total'] ?? 0;
    }

    public function getCartCount($user_id) {
        $query = "SELECT SUM(quantity) as item_count FROM cart WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['item_count'] ?? 0;
    }

    public function clearCart($user_id) {
        $query = "DELETE FROM cart WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        return $stmt->execute();
    }
}
?> 