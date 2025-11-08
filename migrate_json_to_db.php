<?php
require_once 'db.php';

function migrateUsers() {
    $users_json = file_get_contents('users.json');
    $users = json_decode($users_json, true) ?: [];
    $pdo = getDB();

    $stmt = $pdo->prepare("INSERT INTO users 
        (id, username, email, password_hash, full_name, contact_number, address, created_at) 
        VALUES (:id, :username, :email, :password_hash, :full_name, :contact_number, :address, :created_at)
        ON DUPLICATE KEY UPDATE 
        username = VALUES(username),
        email = VALUES(email),
        password_hash = VALUES(password_hash),
        full_name = VALUES(full_name),
        contact_number = VALUES(contact_number),
        address = VALUES(address)");

    foreach ($users as $user) {
        $stmt->execute([
            ':id' => $user['id'],
            ':username' => $user['username'],
            ':email' => $user['email'],
            ':password_hash' => $user['password_hash'],
            ':full_name' => $user['full_name'],
            ':contact_number' => $user['contact_number'] ?? null,
            ':address' => $user['address'] ?? null,
            ':created_at' => $user['created_at']
        ]);
    }
    return count($users);
}

function migrateOrders() {
    $orders_json = file_get_contents('orders.json');
    $orders = json_decode($orders_json, true) ?: [];
    $pdo = getDB();

    // Prepare statements for orders and order items
    $orderStmt = $pdo->prepare("INSERT INTO orders 
        (order_number, user_id, customer_name, contact_number, delivery_address, 
         payment_method, total_amount, status, order_date) 
        VALUES (:order_number, :user_id, :customer_name, :contact_number, :delivery_address, 
                :payment_method, :total, :status, :order_date)
        ON DUPLICATE KEY UPDATE 
        customer_name = VALUES(customer_name),
        contact_number = VALUES(contact_number),
        delivery_address = VALUES(delivery_address),
        status = VALUES(status)");

    $itemStmt = $pdo->prepare("INSERT INTO order_items 
        (order_id, item_id, item_name, size, sugar_level, ice_level, quantity, unit_price, total_price) 
        VALUES (:order_id, :item_id, :item_name, :size, :sugar_level, :ice_level, :quantity, 
                :unit_price, :total_price)
        ON DUPLICATE KEY UPDATE 
        quantity = VALUES(quantity),
        unit_price = VALUES(unit_price),
        total_price = VALUES(total_price)");

    try {
        $pdo->beginTransaction();

        foreach ($orders as $order) {
            $orderStmt->execute([
                ':order_number' => $order['order_number'],
                ':user_id' => $order['user_id'] ?? null,
                ':customer_name' => $order['customer_name'],
                ':contact_number' => $order['contact_number'],
                ':delivery_address' => $order['delivery_address'],
                ':payment_method' => $order['payment_method'],
                ':total' => $order['total'],
                ':status' => $order['status'] ?? 'pending',
                ':order_date' => $order['order_date']
            ]);

            $order_id = $pdo->lastInsertId();

            foreach ($order['items'] as $item) {
                $itemStmt->execute([
                    ':order_id' => $order_id,
                    ':item_id' => $item['item_id'],
                    ':item_name' => $item['name'],
                    ':size' => $item['size'],
                    ':sugar_level' => $item['sugar_level'],
                    ':ice_level' => $item['ice_level'],
                    ':quantity' => $item['quantity'],
                    ':unit_price' => $item['price'] / $item['quantity'], // Calculate unit price
                    ':total_price' => $item['price']
                ]);
            }
        }

        $pdo->commit();
        return count($orders);
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}

// Run migrations
try {
    $userCount = migrateUsers();
    $orderCount = migrateOrders();
    echo "Migration completed successfully!\n";
    echo "Migrated $userCount users and $orderCount orders.\n";
    
    // Verify migration with some basic counts
    $pdo = getDB();
    $userDbCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $orderDbCount = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
    echo "\nVerification counts from database:\n";
    echo "Users in database: $userDbCount\n";
    echo "Orders in database: $orderDbCount\n";
} catch (Exception $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}