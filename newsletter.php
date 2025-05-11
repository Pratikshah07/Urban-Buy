<?php
require_once 'db_config.php';

// Add subscriber to newsletter
function addNewsletterSubscriber($email) {
    global $conn;
    
    // Check if email already exists
    $check_query = "SELECT * FROM newsletter WHERE email = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows > 0) {
        return ['status' => 'error', 'message' => 'Email already subscribed to our newsletter'];
    }
    
    // Insert new subscriber
    $query = "INSERT INTO newsletter (email) VALUES (?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    
    if ($stmt->execute()) {
        return ['status' => 'success', 'message' => 'Thank you for subscribing to our newsletter!'];
    } else {
        return ['status' => 'error', 'message' => 'Failed to subscribe. Please try again later.'];
    }
}

// Process newsletter form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['newsletter_email'])) {
    $email = filter_var($_POST['newsletter_email'], FILTER_SANITIZE_EMAIL);
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Please enter a valid email address']);
        exit();
    }
    
    $result = addNewsletterSubscriber($email);
    echo json_encode($result);
    exit();
}
?> 