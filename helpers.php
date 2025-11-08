<?php
// Helper functions for common operations

function logError($type, $message, $details = null) {
    try {
        $pdo = getDB();
        $stmt = $pdo->prepare("INSERT INTO error_logs (error_type, error_message, error_details) VALUES (?, ?, ?)");
        $stmt->execute([$type, $message, $details ? json_encode($details) : null]);
    } catch (Exception $e) {
        error_log("Failed to log error: $message");
    }
}

function formatPrice($price) {
    return number_format($price, 2);
}

function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function generateOrderNumber() {
    return 'ZAP' . date('Ymd') . rand(1000, 9999);
}

function validateContactNumber($number) {
    return preg_match('/^09\d{9}$/', $number);
}

function getOrderDetails($orderNumber) {
    try {
        $pdo = getDB();
        
        // Get order information
        $stmt = $pdo->prepare("
            SELECT o.*, u.username, u.email 
            FROM orders o 
            LEFT JOIN users u ON o.user_id = u.id 
            WHERE o.order_number = ?
        ");
        $stmt->execute([$orderNumber]);
        $order = $stmt->fetch();
        
        if (!$order) {
            return null;
        }
        
        // Get order items
        $stmt = $pdo->prepare("
            SELECT oi.*, mi.name as menu_item_name 
            FROM order_items oi 
            LEFT JOIN menu_items mi ON oi.item_id = mi.item_id 
            WHERE oi.order_id = ?
        ");
        $stmt->execute([$order['id']]);
        $order['items'] = $stmt->fetchAll();
        
        return $order;
    } catch (Exception $e) {
        logError('DATABASE', 'Failed to get order details', [
            'order_number' => $orderNumber,
            'error' => $e->getMessage()
        ]);
        return null;
    }
}

function updateOrderStatus($orderNumber, $status) {
    try {
        $pdo = getDB();
        $stmt = $pdo->prepare("
            UPDATE orders 
            SET status = ?, updated_at = CURRENT_TIMESTAMP 
            WHERE order_number = ?
        ");
        return $stmt->execute([$status, $orderNumber]);
    } catch (Exception $e) {
        logError('DATABASE', 'Failed to update order status', [
            'order_number' => $orderNumber,
            'status' => $status,
            'error' => $e->getMessage()
        ]);
        return false;
    }
}

function getUserOrders($userId) {
    try {
        $pdo = getDB();
        $stmt = $pdo->prepare("
            SELECT o.*, 
                   COUNT(oi.id) as total_items,
                   GROUP_CONCAT(oi.item_name SEPARATOR ', ') as items_list
            FROM orders o 
            LEFT JOIN order_items oi ON o.id = oi.order_id
            WHERE o.user_id = ?
            GROUP BY o.id
            ORDER BY o.order_date DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        logError('DATABASE', 'Failed to get user orders', [
            'user_id' => $userId,
            'error' => $e->getMessage()
        ]);
        return [];
    }
}

function getMenuItems($category = null) {
    try {
        $pdo = getDB();
        $sql = "SELECT * FROM menu_items WHERE is_available = TRUE";
        $params = [];
        
        if ($category) {
            $sql .= " AND category = ?";
            $params[] = $category;
        }
        
        $sql .= " ORDER BY name ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        logError('DATABASE', 'Failed to get menu items', [
            'category' => $category,
            'error' => $e->getMessage()
        ]);
        return [];
    }
}

// Session helper functions
function setUserSession($user) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['full_name'] = $user['full_name'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['contact_number'] = $user['contact_number'] ?? '';
    $_SESSION['address'] = $user['address'] ?? '';
}

function clearUserSession() {
    unset(
        $_SESSION['user_id'],
        $_SESSION['username'],
        $_SESSION['full_name'],
        $_SESSION['email'],
        $_SESSION['contact_number'],
        $_SESSION['address']
    );
}