<?php
require_once 'db.php';

try {
    $pdo = getDB();
    
    // Check if menu_items table has data
    $menuCount = $pdo->query("SELECT COUNT(*) FROM menu_items")->fetchColumn();
    echo "Menu items in database: $menuCount\n\n";
    
    if ($menuCount == 0) {
        // If no menu items, execute the menu items part of the schema
        $sql = file_get_contents('database_schema.sql');
        
        // Extract only the menu_items INSERT statement
        if (preg_match('/INSERT INTO menu_items.*?;/s', $sql, $matches)) {
            $insertStatement = $matches[0];
            $pdo->exec($insertStatement);
            echo "Menu items inserted successfully.\n";
            
            // Verify the insertion
            $menuCount = $pdo->query("SELECT COUNT(*) FROM menu_items")->fetchColumn();
            echo "Menu items after insertion: $menuCount\n";
            
            // Show some sample items
            $items = $pdo->query("SELECT item_id, name, price FROM menu_items LIMIT 5")->fetchAll();
            echo "\nSample menu items:\n";
            foreach ($items as $item) {
                echo "{$item['item_id']} - {$item['name']} - ₱{$item['price']}\n";
            }
        }
    } else {
        // Show some existing items
        $items = $pdo->query("SELECT item_id, name, price FROM menu_items LIMIT 5")->fetchAll();
        echo "Sample menu items:\n";
        foreach ($items as $item) {
            echo "{$item['item_id']} - {$item['name']} - ₱{$item['price']}\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}