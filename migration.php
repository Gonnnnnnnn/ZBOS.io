<?php
require_once 'config.php';

function migrateDatabase() {
    $pdo = getPDO();
    
    // Create tables if they don't exist
    $schema = file_get_contents('database_schema.sql');
    try {
        $pdo->exec($schema);
        echo "Database schema created successfully.\n";
    } catch (PDOException $e) {
        echo "Note: Tables may already exist. Continuing with data migration.\n";
    }

    // Migrate users
    $users = json_decode(file_get_contents('users.json'), true) ?: [];
    $userStmt = $pdo->prepare("INSERT INTO users 
        (id, username, email, password_hash, full_name, contact_number, address, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?) 
        ON DUPLICATE KEY UPDATE 
        username = VALUES(username),
        email = VALUES(email),
        password_hash = VALUES(password_hash),
        full_name = VALUES(full_name),
        contact_number = VALUES(contact_number),
        address = VALUES(address)");

    foreach ($users as $user) {
        try {
            $userStmt->execute([
                $user['id'],
                $user['username'],
                $user['email'],
                $user['password_hash'],
                $user['full_name'],
                $user['contact_number'] ?? '',
                $user['address'] ?? '',
                $user['created_at']
            ]);
            echo "Migrated user: {$user['username']}\n";
        } catch (PDOException $e) {
            echo "Error migrating user {$user['username']}: {$e->getMessage()}\n";
        }
    }

    // Migrate orders
    $orders = json_decode(file_get_contents('orders.json'), true) ?: [];
    $orderStmt = $pdo->prepare("INSERT INTO orders 
        (order_number, user_id, customer_name, contact_number, delivery_address, 
         payment_method, total_amount, status, order_date) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?) 
        ON DUPLICATE KEY UPDATE 
        customer_name = VALUES(customer_name),
        contact_number = VALUES(contact_number),
        delivery_address = VALUES(delivery_address),
        payment_method = VALUES(payment_method),
        total_amount = VALUES(total_amount),
        status = VALUES(status)");

    $itemStmt = $pdo->prepare("INSERT INTO order_items 
        (order_id, item_id, item_name, size, sugar_level, ice_level, 
         quantity, unit_price, total_price) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    foreach ($orders as $order) {
        try {
            $pdo->beginTransaction();
            
            $orderStmt->execute([
                $order['order_number'],
                $order['user_id'] ?? null,
                $order['customer_name'],
                $order['contact_number'],
                $order['delivery_address'],
                $order['payment_method'],
                $order['total'],
                $order['status'] ?? 'pending',
                $order['order_date']
            ]);
            
            $orderId = $pdo->lastInsertId();
            
            foreach ($order['items'] as $item) {
                $itemStmt->execute([
                    $orderId,
                    $item['item_id'],
                    $item['name'],
                    $item['size'],
                    $item['sugar_level'],
                    $item['ice_level'],
                    $item['quantity'],
                    $item['price'] / $item['quantity'], // Calculate unit price
                    $item['price']
                ]);
            }
            
            $pdo->commit();
            echo "Migrated order: {$order['order_number']}\n";
        } catch (PDOException $e) {
            $pdo->rollBack();
            echo "Error migrating order {$order['order_number']}: {$e->getMessage()}\n";
        }
    }

    // Verify migration
    $userCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $orderCount = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
    $itemCount = $pdo->query("SELECT COUNT(*) FROM order_items")->fetchColumn();

    echo "\nMigration Summary:\n";
    echo "Users migrated: $userCount\n";
    echo "Orders migrated: $orderCount\n";
    echo "Order items migrated: $itemCount\n";
}

// Run migration
try {
    migrateDatabase();
    echo "\nMigration completed successfully!\n";
} catch (Exception $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}