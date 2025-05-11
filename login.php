<?php
require_once 'db_config.php';
require_once 'users.php';

if (!isset($_SESSION)) {
    session_start();
}

// Guest login functionality only
$check_guest = $conn->query("SELECT id FROM users WHERE id = 9999");
if ($check_guest->num_rows == 0) {
    $conn->query("INSERT INTO users (id, username, email, password) VALUES (9999, 'guest', 'guest@demo.com', 'guest')");
}
$_SESSION['user_id'] = 9999;
$_SESSION['guest'] = true;
header('Location: shop.php');
exit(); 