<?php
session_start();
require_once 'auth.php';
require_once 'db.php';

// Check if user is logged in
requireLogin('login.php?redirect=index.php&error=login_required');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_SESSION['cart'])) {
    $customer_name = $_POST['customer_name'];
    // Normalize contact number input to a trimmed string (avoid null/array warnings)
    $contact_number = isset($_POST['contact_number']) ? trim((string)$_POST['contact_number']) : '';
    $delivery_address = $_POST['delivery_address'];
    $payment_method = $_POST['payment_method'];
    
   
   
    // Validation: must be exactly 11 digits (numbers only) and start with '09'
    if (!is_string($contact_number) || !ctype_digit($contact_number) || strlen($contact_number) !== 11 || strpos($contact_number, '09') !== 0) {
        header("Location: index.php?error=invalid_contact");
        exit;
    }
    
    
    $total = 0;
    foreach ($_SESSION['cart'] as $item) 
        {
        $total += $item['price'];
        }
    
    
    $order_number = 'ZAP' . date('Ymd') . rand(1000, 9999);
    
    // Get user_id from session (user is logged in at this point)
    $user_id = $_SESSION['user_id'] ?? null;
    
    // Save order to MySQL database
    try {
        $pdo = getDB();
        $pdo->beginTransaction();
        
        // Insert into orders table
        $orderStmt = $pdo->prepare("INSERT INTO orders 
            (order_number, user_id, customer_name, contact_number, delivery_address, 
             payment_method, total_amount, status, order_date) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
        $orderStmt->execute([
            $order_number,
            $user_id,
            $customer_name,
            $contact_number,
            $delivery_address,
            $payment_method,
            $total,
            'pending',
            date('Y-m-d H:i:s')
        ]);
        
        $order_id = $pdo->lastInsertId();
        
        // Insert order items
        $itemStmt = $pdo->prepare("INSERT INTO order_items 
            (order_id, item_id, item_name, size, sugar_level, ice_level, 
             quantity, unit_price, total_price) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
        foreach ($_SESSION['cart'] as $item) {
            $unit_price = $item['price'] / $item['quantity'];
            $itemStmt->execute([
                $order_id,
                $item['item_id'],
                $item['name'],
                $item['size'],
                $item['sugar_level'],
                $item['ice_level'],
                $item['quantity'],
                $unit_price,
                $item['price']
            ]);
        }
        
        $pdo->commit();
    } catch (Exception $e) {
        // Log detailed error information
        error_log("Failed to save order to database: " . $e->getMessage());
        error_log("Error details: " . print_r([
            'order_number' => $order_number,
            'user_id' => $user_id,
            'customer_name' => $customer_name,
            'contact_number' => $contact_number,
            'delivery_address' => $delivery_address,
            'payment_method' => $payment_method,
            'total' => $total,
            'items' => $_SESSION['cart']
        ], true));
        
        if (isset($pdo)) {
            $pdo->rollBack();
        }
        
        // For debugging, show error on screen (remove in production)
        die("Order failed: " . $e->getMessage());
        
        header("Location: index.php?error=order_failed");
        exit;
    }
    
    $_SESSION['cart'] = [];
    
   
    header("Location: order_confirmation.php?order_number=" . $order_number);
    exit;
} else {
    header("Location: index.php");
    exit;
}
?>
