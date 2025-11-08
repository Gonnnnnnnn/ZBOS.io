<?php
require_once 'db.php';

try {
    $pdo = getDB();

    // Check if menu_items table exists and is not empty
    $stmt = $pdo->query("SELECT COUNT(*) FROM menu_items");
    $count = $stmt->fetchColumn();
    
    if ($count == 0) {
        // Execute the menu items part of the SQL schema
        $schema = file_get_contents('database_schema.sql');
        
        // Extract the INSERT INTO menu_items statement and execute it
        if (preg_match('/INSERT INTO menu_items.*?;/s', $schema, $matches)) {
            echo "Inserting menu items into database...\n";
            $pdo->exec($matches[0]);
            echo "Menu items inserted successfully.\n";
            
            // Verify insertion
            $stmt = $pdo->query("SELECT COUNT(*) FROM menu_items");
            $newCount = $stmt->fetchColumn();
            echo "Total menu items in database: $newCount\n";
            
            // Show some sample items
            echo "\nSample items:\n";
            $items = $pdo->query("SELECT item_id, name, price FROM menu_items LIMIT 5")->fetchAll();
            foreach ($items as $item) {
                echo "{$item['item_id']} - {$item['name']} - ₱{$item['price']}\n";
            }
        }
    } else {
        echo "Menu items already exist in database. Count: $count\n";
        echo "\nSample items:\n";
        $items = $pdo->query("SELECT item_id, name, price FROM menu_items LIMIT 5")->fetchAll();
        foreach ($items as $item) {
            echo "{$item['item_id']} - {$item['name']} - ₱{$item['price']}\n";
        }
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    
    // Show more detailed error info
    if ($e instanceof PDOException) {
        echo "\nSQL State: " . $e->errorInfo[0] . "\n";
        echo "Error Code: " . $e->errorInfo[1] . "\n";
        echo "Message: " . $e->errorInfo[2] . "\n";
    }
}