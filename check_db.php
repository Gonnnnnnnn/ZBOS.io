<?php
require_once 'db.php';

try {
    $pdo = getDB();
    
    // Check users
    $userCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    echo "Users in database: $userCount\n";
    
    // Check orders
    $orderCount = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
    echo "Orders in database: $orderCount\n";
    
    // Check order items
    $itemCount = $pdo->query("SELECT COUNT(*) FROM order_items")->fetchColumn();
    echo "Order items in database: $itemCount\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}