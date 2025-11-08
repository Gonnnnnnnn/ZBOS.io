<?php
session_start();

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}


// Menu items
$menu_items = [
    'classic' => [
        'name' => 'Classic Milk Tea',
        'price' => 50,
        'description' => 'Traditional milk tea with chewy tapioca pearls',
        'image' => 'images/classic milk tea.jpg',
        'category' => 'classic',
        'popular' => true,
        'rating' => 4.8,
        'prep_time' => '5-7 min'
    ],
    'taro' => [
        'name' => 'Taro Milk Tea',
        'price' => 50,
        'description' => 'Creamy taro flavor with milk and pearls',
        'image' => 'images/taro milk tea.jpg',
        'category' => 'classic',
        'popular' => false,
        'rating' => 4.6,
        'prep_time' => '5-7 min'
    ],
    'matcha' => [
        'name' => 'Matcha Milk Tea',
        'price' => 50,
        'description' => 'Premium matcha green tea with milk',
        'image' => 'images/matcha milk tea.jpg',
        'category' => 'premium',
        'popular' => true,
        'rating' => 4.9,
        'prep_time' => '6-8 min'
    ],
    'chocolate' => [
        'name' => 'Chocolate Milk Tea',
        'price' => 50,
        'description' => 'Rich chocolate flavor with milk tea',
        'image' => 'images/chocolate milk tea.jpg',
        'category' => 'classic',
        'popular' => false,
        'rating' => 4.5,
        'prep_time' => '5-7 min'
    ],
    'strawberry' => [
        'name' => 'Strawberry Milk Tea',
        'price' => 50,
        'description' => 'Sweet strawberry with milk tea',
        'image' => 'images/strawberry milk tea.jpg',
        'category' => 'classic',
        'popular' => false,
        'rating' => 4.4,
        'prep_time' => '5-7 min'
    ],
    'brown_sugar' => [
        'name' => 'Brown Sugar Milk Tea',
        'price' => 50,
        'description' => 'Caramelized brown sugar with fresh milk',
        'image' => 'images/brown sugar milk tea.jpg',
        'category' => 'premium',
        'popular' => true,
        'rating' => 4.9,
        'prep_time' => '7-9 min'
    ],

    // New items
    'oolong' => [
        'name' => 'Oolong Milk Tea',
        'price' => 50,
        'description' => 'Delicate oolong tea blended with creamy milk',
        'image' => 'images/oolong milk tea.jpg',
        'category' => 'classic',
        'popular' => false,
        'rating' => 4.5,
        'prep_time' => '5-7 min'
    ],
    'honeydew' => [
        'name' => 'Honeydew Milk Tea',
        'price' => 50,
        'description' => 'Fragrant honeydew melon paired with smooth milk tea',
        'image' => 'images/honeydew milk tea.jpg',
        'category' => 'premium',
        'popular' => true,
        'rating' => 4.7,
        'prep_time' => '6-8 min'
    ],
    'mango' => [
        'name' => 'Mango Fruit Tea',
        'price' => 80,
        'description' => 'Fresh mango chunks and tropical fruit tea, refreshing and bright',
        'image' => 'images/mango fruit tea.jpg',
        'category' => 'fruit',
        'popular' => true,
        'rating' => 4.8,
        'prep_time' => '4-6 min'
    ],
    'thai_tea' => [
        'name' => 'Thai Milk Tea',
        'price' => 50,
        'description' => 'Strong brewed Thai tea with sweetened condensed milk',
        'image' => 'images/thai milk tea.jpg',
        'category' => 'premium',
        'popular' => true,
        'rating' => 4.8,
        'prep_time' => '6-8 min'
    ],
    'lavender' => [
        'name' => 'Lavender Milk Tea',
        'price' => 50,
        'description' => 'Light floral notes of lavender with creamy milk tea',
        'image' => 'images/lavender milk tea.jpg',
        'category' => 'specialty',
        'popular' => false,
        'rating' => 4.6,
        'prep_time' => '7-9 min'
    ],
    'coffee_latte' => [
        'name' => 'Coffee Milk Latte',
        'price' => 95,
        'description' => 'Espresso blended with milk for a coffee-forward latte',
        'image' => 'images/hot coffee milk latte.jpg',
        'category' => 'coffee',
        'popular' => true,
        'rating' => 4.7,
        'prep_time' => '3-5 min'
    ],
    'oat_milk' => [
        'name' => 'Oat Milk Tea',
        'price' => 50,
        'description' => 'Creamy oat milk with a smooth tea base, dairy-free option',
        'image' => 'images/oat milk tea.jpg',
        'category' => 'specialty',
        'popular' => false,
        'rating' => 4.5,
        'prep_time' => '5-7 min'
    ]
    ,
    'cookie_choc_chip' => [
        'name' => 'Chocolate Chip Cookie',
        'price' => 55,
        'description' => 'Classic chewy chocolate chip cookie, baked fresh.',
        'image' => 'images/chocolate chip cookie.jpg',
        'category' => 'cookies',
        'popular' => true,
        'rating' => 4.7,
        'prep_time' => '2-3 min'
    ],
    'cookie_double_choco' => [
        'name' => 'Double Chocolate Cookie',
        'price' => 65,
        'description' => 'Intensely chocolatey cookie with gooey chunks.',
        'image' => 'images/double chocolate cookie.jpg',
        'category' => 'cookies',
        'popular' => false,
        'rating' => 4.8,
        'prep_time' => '2-4 min'
    ],
    'cookie_oatmeal_raisin' => [
        'name' => 'Oatmeal Raisin Cookie',
        'price' => 50,
        'description' => 'Warm, spiced oatmeal cookie with plump raisins.',
        'image' => 'images/oatmeal raisin cookie.jpg',
        'category' => 'cookies',
        'popular' => false,
        'rating' => 4.5,
        'prep_time' => '2-3 min'
    ],
    'cookie_shortbread' => [
        'name' => 'Classic Shortbread',
        'price' => 45,
        'description' => 'Buttery, crumbly shortbread — simple and perfect.',
        'image' => 'images/classic shortbread cookie.jpg',
        'category' => 'cookies',
        'popular' => false,
        'rating' => 4.4,
        'prep_time' => '2-3 min'
    ],
    'cookie_matcha' => [
        'name' => 'Matcha Cookie',
        'price' => 60,
        'description' => 'Delicate matcha-flavored cookie with a subtle tea aroma.',
        'image' => 'images/matcha cookie.jpg',
        'category' => 'cookies',
        'popular' => true,
        'rating' => 4.6,
        'prep_time' => '2-4 min'
    ],

    // Coffee (Hot)
    'coffee_hot_americano' => [
        'name' => 'Hot Americano',
        'price' => 70,
        'description' => 'Rich espresso shots with hot water, bold and smooth',
        'image' => 'images/hot americano.jpg',
        'category' => 'coffee_hot',
        'popular' => true,
        'rating' => 4.7,
        'prep_time' => '3-5 min'
    ],
    'coffee_hot_cappuccino' => [
        'name' => 'Hot Cappuccino',
        'price' => 70,
        'description' => 'Espresso with steamed milk and velvety foam',
        'image' => 'images/hot cappuccino.jpg',
        'category' => 'coffee_hot',
        'popular' => true,
        'rating' => 4.8,
        'prep_time' => '4-6 min'
    ],
    'coffee_hot_latte' => [
        'name' => 'Hot Latte',
        'price' => 70,
        'description' => 'Smooth espresso with steamed milk, creamy and comforting',
        'image' => 'images/hot latte.jpg',
        'category' => 'coffee_hot',
        'popular' => true,
        'rating' => 4.9,
        'prep_time' => '4-6 min'
    ],
    'coffee_hot_mocha' => [
        'name' => 'Hot Mocha',
        'price' => 70,
        'description' => 'Espresso with chocolate and steamed milk, indulgent and rich',
        'image' => 'images/hot mocha.jpg',
        'category' => 'coffee_hot',
        'popular' => false,
        'rating' => 4.6,
        'prep_time' => '5-7 min'
    ],
    'coffee_hot_espresso' => [
        'name' => 'Hot Espresso',
        'price' => 60,
        'description' => 'Intense and concentrated coffee shot, pure and bold',
        'image' => 'images/hot espresso.jpg',
        'category' => 'coffee_hot',
        'popular' => false,
        'rating' => 4.5,
        'prep_time' => '2-3 min'
    ],

    // Coffee (Cold)
    'coffee_cold_iced_americano' => [
        'name' => 'Iced Americano',
        'price' => 75,
        'description' => 'Refreshing espresso with cold water and ice',
        'image' => 'images/iced americano.jpg',
        'category' => 'coffee_cold',
        'popular' => true,
        'rating' => 4.7,
        'prep_time' => '3-5 min'
    ],
    'coffee_cold_iced_latte' => [
        'name' => 'Iced Latte',
        'price' => 60,
        'description' => 'Smooth espresso with cold milk over ice',
        'image' => 'images/iced latte.jpg',
        'category' => 'coffee_cold',
        'popular' => true,
        'rating' => 4.8,
        'prep_time' => '4-6 min'
    ],
    'coffee_cold_iced_mocha' => [
        'name' => 'Iced Mocha',
        'price' => 60,
        'description' => 'Chilled espresso with chocolate and cold milk',
        'image' => 'images/iced mocha.jpg',
        'category' => 'coffee_cold',
        'popular' => false,
        'rating' => 4.6,
        'prep_time' => '5-7 min'
    ],
    'coffee_cold_cold_brew' => [
        'name' => 'Cold Brew',
        'price' => 60,
        'description' => 'Smooth, naturally sweet cold-brewed coffee',
        'image' => 'images/cold brew.jpg',
        'category' => 'coffee_cold',
        'popular' => true,
        'rating' => 4.9,
        'prep_time' => '5-7 min'
    ],
    'coffee_cold_iced_cappuccino' => [
        'name' => 'Iced Cappuccino',
        'price' => 60,
        'description' => 'Espresso with cold milk and foam, refreshing',
        'image' => 'images/iced cappuccino.jpg',
        'category' => 'coffee_cold',
        'popular' => false,
        'rating' => 4.7,
        'prep_time' => '4-6 min'
    ],

    // Frappes
    'frappe_mocha' => [
        'name' => 'Mocha Frappe',
        'price' => 95,
        'description' => 'Blended mocha coffee with ice, topped with whipped cream',
        'image' => 'images/mocha frappe.jpg',
        'category' => 'frappes',
        'popular' => true,
        'rating' => 4.8,
        'prep_time' => '6-8 min'
    ],
    'frappe_caramel' => [
        'name' => 'Caramel Frappe',
        'price' => 95,
        'description' => 'Rich caramel flavor blended with coffee and ice',
        'image' => 'images/caramel frappe.jpg',
        'category' => 'frappes',
        'popular' => true,
        'rating' => 4.9,
        'prep_time' => '6-8 min'
    ],
    'frappe_vanilla' => [
        'name' => 'Vanilla Frappe',
        'price' => 90,
        'description' => 'Smooth vanilla coffee frappe, creamy and sweet',
        'image' => 'images/vanilla frappe.jpg',
        'category' => 'frappes',
        'popular' => false,
        'rating' => 4.6,
        'prep_time' => '6-8 min'
    ],
    'frappe_chocolate' => [
        'name' => 'Chocolate Frappe',
        'price' => 95,
        'description' => 'Decadent chocolate coffee frappe, rich and indulgent',
        'image' => 'images/chocolate frappe.jpg',
        'category' => 'frappes',
        'popular' => true,
        'rating' => 4.7,
        'prep_time' => '6-8 min'
    ],
    'frappe_matcha' => [
        'name' => 'Matcha Frappe',
        'price' => 95,
        'description' => 'Premium matcha green tea blended with ice, refreshing',
        'image' => 'images/matcha frappe.jpg',
        'category' => 'frappes',
        'popular' => false,
        'rating' => 4.8,
        'prep_time' => '6-8 min'
    ],
    'frappe_strawberry' => [
        'name' => 'Strawberry Frappe',
        'price' => 95,
        'description' => 'Sweet strawberry flavor blended to perfection',
        'image' => 'images/strawberry frappe.jpg',
        'category' => 'frappes',
        'popular' => true,
        'rating' => 4.7,
        'prep_time' => '6-8 min'
    ],

    // Fruit Teas
    'fruit_tea_strawberry' => [
        'name' => 'Strawberry Fruit Tea',
        'price' => 75,
        'description' => 'Fresh strawberries with fruity tea blend, naturally sweet',
        'image' => 'images/strawberry fruit tea.jpg',
        'category' => 'fruit_tea',
        'popular' => true,
        'rating' => 4.8,
        'prep_time' => '4-6 min'
    ],
    'fruit_tea_peach' => [
        'name' => 'Peach Fruit Tea',
        'price' => 80,
        'description' => 'Juicy peach chunks with refreshing fruit tea',
        'image' => 'images/peach fruit tea.jpg',
        'category' => 'fruit_tea',
        'popular' => true,
        'rating' => 4.7,
        'prep_time' => '4-6 min'
    ],
    'fruit_tea_lychee' => [
        'name' => 'Lychee Fruit Tea',
        'price' => 85,
        'description' => 'Exotic lychee with tropical fruit tea blend',
        'image' => 'images/lychee fruit tea.jpg',
        'category' => 'fruit_tea',
        'popular' => false,
        'rating' => 4.6,
        'prep_time' => '4-6 min'
    ],
    'fruit_tea_passion_fruit' => [
        'name' => 'Passion Fruit Tea',
        'price' => 85,
        'description' => 'Tart and tangy passion fruit with fruity tea',
        'image' => 'images/passion fruit tea.jpg',
        'category' => 'fruit_tea',
        'popular' => true,
        'rating' => 4.9,
        'prep_time' => '4-6 min'
    ],
    'fruit_tea_grape' => [
        'name' => 'Grape Fruit Tea',
        'price' => 80,
        'description' => 'Sweet grape flavor with refreshing fruit tea',
        'image' => 'images/grape fruit tea.jpg',
        'category' => 'fruit_tea',
        'popular' => false,
        'rating' => 4.5,
        'prep_time' => '4-6 min'
    ],
    'fruit_tea_watermelon' => [
        'name' => 'Watermelon Fruit Tea',
        'price' => 75,
        'description' => 'Refreshing watermelon with light fruit tea',
        'image' => 'images/watermelon fruit tea.jpg',
        'category' => 'fruit_tea',
        'popular' => true,
        'rating' => 4.7,
        'prep_time' => '4-6 min'
    ],
    'fruit_tea_blueberry' => [
        'name' => 'Blueberry Fruit Tea',
        'price' => 85,
        'description' => 'Antioxidant-rich blueberries with fruity tea',
        'image' => 'images/blueberry fruit tea.jpg',
        'category' => 'fruit_tea',
        'popular' => false,
        'rating' => 4.6,
        'prep_time' => '4-6 min'
    ]
];

// Handle add to cart
if (isset($_POST['action']) && $_POST['action'] === 'add_to_cart') {
    $item_id = $_POST['item_id'];
    $size = $_POST['size'];
    $sugar_level = $_POST['sugar_level'];
    $ice_level = $_POST['ice_level'];
    $quantity = (int)$_POST['quantity'];
    // Validate that the item exists
    if (isset($menu_items[$item_id])) {
        // Special pricing for cold coffee large size
        if (strpos($item_id, 'coffee_cold_') === 0 && $size === 'large') {
            $total_price = 75 * $quantity;  // Fixed price for large cold coffee
        } else {
            $price_multiplier = $size === 'large' ? 1.3 : 1.0;
            $total_price = $menu_items[$item_id]['price'] * $price_multiplier * $quantity;
        }
        
        $cart_item = [
            'item_id' => $item_id,
            'name' => $menu_items[$item_id]['name'],
            'size' => $size,
            'sugar_level' => $sugar_level,
            'ice_level' => $ice_level,
            'quantity' => $quantity,
            'price' => $total_price
        ];
        
        $_SESSION['cart'][] = $cart_item;
        $success_message = "Item added to cart successfully!";
    } else {
        $error_message = "Invalid item selected.";
    }
}

// Handle remove from cart
if (isset($_POST['action']) && $_POST['action'] === 'remove_from_cart') {
    $index = (int)$_POST['cart_index'];
    unset($_SESSION['cart'][$index]);
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}

// Handle clear cart
if (isset($_POST['action']) && $_POST['action'] === 'clear_cart') {
    $_SESSION['cart'] = [];
    $success_message = 'Cart cleared.';
}

// Calculate total
$cart_total = 0;
foreach ($_SESSION['cart'] as $item) {
    $cart_total += $item['price'];
}

$sql = "UPDATE `order_items` SET `id`=\'[value-1]\',`order_id`=\'[value-2]\',`item_id`=\'[value-3]\',`item_name`=\'[value-4]\',
`size`=\'[value-5]\',`sugar_level`=\'[value-6]\',`ice_level`=\'[value-7]\',`quantity`=\'[value-8]\',`unit_price`=\'[value-9]\',
`total_price`=\'[value-10]\' WHERE 1;";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZapBrew Ordering System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .menu-card {
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            background: white;
            overflow: hidden;
        }
        .menu-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        .cart-item {
            border-left: 4px solid #007bff;
        }
        .navbar-brand {
            font-weight: bold;
            color: #8B4513 !important;
            font-size: 1.5rem;
        }
        .navbar {
            backdrop-filter: blur(10px);
            background: rgba(0, 0, 0, 0.9) !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }
        .btn-primary {
            background: linear-gradient(135deg, #8B4513, #D2691E);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #A0522D, #CD853F);
        }
        .price-tag {
            background: linear-gradient(135deg, #FF6B6B, #FF8E53);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: bold;
        }
        .card-img-top {
        }
        .menu-card:hover .card-img-top {
            opacity: 0.9;
        }
        .menu-card {
            overflow: hidden;
        }
        .hero-content {
            z-index: 2;
        }
        .jumbotron {
            background-attachment: fixed;
            position: relative;
            overflow: hidden;
        }
        .jumbotron::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, #8B4513, #D2691E);
        }
        .hero-content {
            position: relative;
            z-index: 2;
        }
        html {
            scroll-behavior: smooth;
        }
        body {
            background: #f5f5f5;
        }
        .btn-light:hover {
            opacity: 0.9;
        }
        .btn-outline-light:hover {
            opacity: 0.9;
        }
        
        /* Menu Filter Styles */
        .menu-filters .btn-group {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
        }
        .filter-btn {
            margin: 5px;
            padding: 12px 24px;
            font-weight: 600;
            border-radius: 25px;
            white-space: nowrap;
        }
        .filter-btn.active {
            background: linear-gradient(135deg, #8B4513, #D2691E);
            border-color: #8B4513;
            color: white;
        }
        .filter-btn:hover {
            background: rgba(139, 69, 19, 0.1);
        }
        .filter-btn:not(.active) {
            border: 2px solid #8B4513;
            color: #8B4513;
        }
        .filter-btn:not(.active):hover {
            background: rgba(139, 69, 19, 0.1);
        }
        @media (max-width: 768px) {
            .filter-btn {
                padding: 10px 16px;
                font-size: 0.9rem;
            }
        }
        
        .menu-item {
        }
        .menu-item.hidden {
            display: none;
        }
        
        
        /* Image Overlay */
        .image-container {
            overflow: hidden;
        }
        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
        }
        .image-container:hover .image-overlay {
            opacity: 1;
        }
        .quick-view-btn {
            background: white !important;
        }
        
        /* Rating Styles */
        .rating {
            display: flex;
            align-items: center;
            gap: 3px;
        }
        .rating i {
            color: #FFD700;
        }
        
        /* Category Badge */
        .badge {
            font-size: 0.7rem;
            padding: 4px 8px;
        }
        .badge.bg-warning {
        }
        
        /* Order Button */
        .order-btn {
        }
        .order-btn:hover {
            opacity: 0.9;
        }
        
        
        /* New Cart Design Styles */
        .cart-container {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            position: sticky;
            top: 20px;
        }
        
        .cart-header {
            background: linear-gradient(135deg, #8B4513, #D2691E);
            color: white;
            padding: 20px;
        }
        
        .cart-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.2rem;
            font-weight: bold;
        }
        
        .cart-count {
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            padding: 5px 10px;
            font-size: 0.9rem;
            margin-left: auto;
        }
        
        .cart-body {
            padding: 20px;
            max-height: 500px;
            overflow-y: auto;
        }
        
        .empty-cart {
            text-align: center;
            padding: 40px 20px;
        }
        
        .empty-cart-icon {
            font-size: 3rem;
            color: #ccc;
            margin-bottom: 20px;
        }
        
        .cart-items {
            margin-bottom: 20px;
        }
        
        .cart-item-wrapper {
            margin-bottom: 15px;
        }
        
        .cart-item-wrapper.removing {
            margin-bottom: 0;
            height: 0;
            overflow: hidden;
        }
        
        .cart-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background: white;
            border-radius: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .cart-item:hover {
            box-shadow: 0 3px 12px rgba(0,0,0,0.1);
        }
        
        .cart-item.removing {
            display: none;
        }
        
        .item-image {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #8B4513, #D2691E);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }
        
        .item-details {
            flex: 1;
        }
        
        .item-name {
            font-weight: bold;
            margin-bottom: 8px;
            color: #333;
        }
        
        .item-options {
            display: flex;
            gap: 5px;
            margin-bottom: 5px;
            flex-wrap: wrap;
        }
        
        .option-tag {
            background: #e9ecef;
            color: #6c757d;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 0.75rem;
        }
        
        .quantity-label {
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .item-actions {
            text-align: right;
        }
        
        .item-price {
            font-weight: bold;
            color: #28a745;
            margin-bottom: 5px;
        }
        
        .btn-remove {
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        
        .btn-remove:hover {
            background: #c82333;
        }
        
        .cart-summary {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #f8f9fa;
        }
        
        .summary-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .summary-row.total {
            font-weight: bold;
            font-size: 1.1rem;
            color: #28a745;
            border-top: 2px solid #28a745;
            padding-top: 10px;
            margin-top: 10px;
        }
        
        .cart-actions {
            margin-top: 20px;
        }
        
        .btn-checkout {
            width: 100%;
            padding: 15px;
            border-radius: 15px;
            font-weight: bold;
            font-size: 1.1rem;
            background: linear-gradient(135deg, #28a745, #20c997);
            border: none;
        }
        
        .btn-checkout:hover {
            opacity: 0.9;
        }
        
        /* Custom scrollbar for cart */
        .cart-body::-webkit-scrollbar {
            width: 6px;
        }
        
        .cart-body::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }
        
        .cart-body::-webkit-scrollbar-thumb {
            background: #8B4513;
            border-radius: 3px;
        }
        
        .cart-body::-webkit-scrollbar-thumb:hover {
            background: #D2691E;
        }


    /* Maximize dead space: roomy/airy layout */
    body { padding-top: 0; padding-bottom: 64px; }
    .container { max-width: 1600px; }
    .jumbotron { min-height: 920px; padding-top: 160px; padding-bottom: 160px; }
    .hero-content h1 { font-size: 5rem; line-height: 1.02; }

    /* Compact card spacing for more items on screen */
    .menu-item { padding: 8px; }
    .menu-card { margin-bottom: 20px; }
    .menu-card .card-body { padding: 12px; }
    .price-tag { padding: 6px 12px; border-radius: 20px; font-size: 0.9rem; }
    .order-btn { padding: 8px 12px; font-size: 0.85rem; }

    /* Tighter filter spacing */
    .menu-filters { margin-bottom: 30px; }

    /* Modal and offcanvas: roomy interior */
    .modal-dialog { max-width: 1140px; }
    .modal-content .modal-body { padding: 48px; }
    .offcanvas.offcanvas-end { width: 720px; }
    .offcanvas .offcanvas-body { padding: 40px; }

    /* Spacious cart items */
    .cart-item { padding: 28px; border-radius: 24px; }

    /* Smaller images for compact layout */
    .card-img-top { height: 180px !important; }
    
    /* Reduced font sizes for compact cards */
    .menu-card .card-title { font-size: 1rem; margin-bottom: 6px; line-height: 1.3; }
    .menu-card .card-text { font-size: 0.8rem; margin-bottom: 8px; line-height: 1.4; }
    .menu-card .badge { font-size: 0.7rem; padding: 3px 6px; }
    .menu-card .rating { font-size: 0.85rem; }
    .menu-card .order-btn i { font-size: 0.75rem; }
    .menu-card .quick-view-btn { font-size: 0.85rem; padding: 6px 12px; }

    /* Compact card variant: smaller padding/image/text to show more items */
    .menu-card.compact .card-body { padding: 6px; }
    .menu-card.compact .card-title { font-size: 0.9rem; margin-bottom: 3px; }
    .menu-card.compact .card-text { font-size: 0.75rem; }
    .menu-card.compact .price-tag { padding: 3px 6px; font-size: 0.78rem; border-radius: 12px; }
    .menu-card.compact .card-img-top { height: 80px !important; }
    .menu-item.compact { padding: 4px; }
    .order-btn.compact { padding: 5px 6px; font-size: 0.8rem; }

    /* List-view compact overrides (tighter list-only presentation) */
    #menu-list .menu-item { padding: 6px 8px; margin-bottom: 6px; }
    #menu-list img { width: 96px; height: 72px; object-fit: cover; border-radius: 6px; margin-right: 12px; }
    #menu-list h5 { font-size: 0.98rem; margin-bottom: 4px; }
    #menu-list .text-muted.small { font-size: 0.75rem; }
    #menu-list .price-tag { padding: 4px 8px; font-size: 0.85rem; border-radius: 12px; }
    #menu-list .btn-sm { padding: 4px 8px; font-size: 0.85rem; }

    </style>
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="home.php">
                <i class="fas fa-coffee"></i> ZapBrew
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#menu">Menu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas">
                            <i class="fas fa-shopping-cart"></i> Cart 
                            <span class="badge bg-danger"><?php echo count($_SESSION['cart']); ?></span>
                        </a>
                    </li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                            <a class="nav-link" href="logout.php">
                                <i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['full_name'] ?? 'Account'); ?> | Logout
                            </a>
                    </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="signup.php">Sign Up</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="jumbotron bg-gradient text-white text-center py-5 position-relative" style="background: linear-gradient(135deg, #8B4513, #D2691E, #CD853F); min-height: 400px; display: flex; align-items: center;">
        <div class="container position-relative">
            <div class="hero-content">
                <h1 class="display-2 fw-bold mb-4" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">
                    <i class="fas fa-coffee me-3"></i>Welcome to ZapBrew
                </h1>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert" id="successAlert">
                <?php echo $success_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if (isset($_GET['logged_out'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                You have been logged out.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['error']) && $_GET['error'] === 'invalid_contact'): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle"></i> Please enter a valid contact number starting with 09.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle"></i> <?php echo $error_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
                <!-- Menu Section -->
            <div class="col-lg-12">
                <div class="text-center mb-5">
                    <h2 id="menu" class="display-4 fw-bold mb-3" style="color: #8B4513;">
                        <i class="fas fa-coffee me-3"></i>Our Delicious Menu
                    </h2>
                    <p class="lead text-muted">Choose from our premium selection of milk teas, coffee, frappes, fruit teas, and cookies</p>
                </div>

                <!-- Menu Filter Tabs -->
                <div class="text-center mb-5 menu-filters">
                    <div class="btn-group flex-wrap" role="group" aria-label="Menu categories" style="gap: 10px;">
                        <button type="button" class="btn btn-outline-primary filter-btn active" data-filter="milkteas">
                            <i class="fas fa-glass-whiskey me-2"></i>Milkteas
                        </button>
                        <button type="button" class="btn btn-outline-primary filter-btn" data-filter="coffee_hot">
                            <i class="fas fa-coffee me-2"></i>Coffee (Hot)
                        </button>
                        <button type="button" class="btn btn-outline-primary filter-btn" data-filter="coffee_cold">
                            <i class="fas fa-coffee me-2"></i>Coffee (Cold)
                        </button>
                        <button type="button" class="btn btn-outline-primary filter-btn" data-filter="frappes">
                            <i class="fas fa-blender me-2"></i>Frappes
                        </button>
                        <button type="button" class="btn btn-outline-primary filter-btn" data-filter="fruit_tea">
                            <i class="fas fa-leaf me-2"></i>Fruit Teas
                        </button>
                        <button type="button" class="btn btn-outline-primary filter-btn" data-filter="cookies">
                            <i class="fas fa-cookie me-2"></i>Cookies
                        </button>
                        <button type="button" class="btn btn-outline-primary filter-btn" data-filter="all">
                            <i class="fas fa-list me-2"></i>All Items
                        </button>
                    </div>
                </div>

                <!-- Menu Items Grid -->
                <div class="row" id="menu-container">
                    <?php 
                    // Separate items into categories
                    $milktea_categories = ['classic', 'premium', 'specialty'];
                    foreach ($menu_items as $item_id => $item): 
                        $category = $item['category'];
                        
                        // Determine item class based on category
                        if (in_array($category, $milktea_categories)) {
                            $item_class = 'milkteas';
                        } elseif ($category === 'cookies') {
                            $item_class = 'cookies';
                        } elseif ($category === 'coffee_hot') {
                            $item_class = 'coffee_hot';
                        } elseif ($category === 'coffee_cold') {
                            $item_class = 'coffee_cold';
                        } elseif ($category === 'frappes') {
                            $item_class = 'frappes';
                        } elseif ($category === 'fruit_tea' || $category === 'fruit') {
                            // Handle both 'fruit_tea' and legacy 'fruit' category
                            $item_class = 'fruit_tea';
                        } elseif ($category === 'coffee') {
                            // Legacy coffee category - treat as hot coffee
                            $item_class = 'coffee_hot';
                        } else {
                            $item_class = 'all';
                        }
                    ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-3 menu-item" data-category="<?php echo $item_class; ?>">
                        <div class="card menu-card h-100">
                            <div class="image-container position-relative">
                                <img src="<?php echo htmlspecialchars($item['image']); ?>" 
                                     class="card-img-top" 
                                     alt="<?php echo htmlspecialchars($item['name']); ?>"
                                     style="object-fit: cover;">
                                <div class="image-overlay">
                                    <button class="btn btn-light quick-view-btn" data-bs-toggle="modal" data-bs-target="#itemModal<?php echo $item_id; ?>">
                                        <i class="fas fa-eye me-2"></i>Quick View
                                    </button>
                                </div>
                                <?php if ($item['popular']): ?>
                                <span class="badge bg-warning position-absolute top-0 end-0 m-1" style="font-size: 0.65rem; padding: 2px 6px;">
                                    <i class="fas fa-star"></i> Popular
                                </span>
                                <?php endif; ?>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($item['name']); ?></h5>
                                <p class="card-text text-muted small"><?php echo htmlspecialchars($item['description']); ?></p>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="rating">
                                        <i class="fas fa-star text-warning"></i>
                                        <span class="ms-1"><?php echo $item['rating']; ?></span>
                                    </div>
                                    <span class="badge bg-secondary"><?php echo $item['prep_time']; ?></span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <span class="price-tag">₱<?php echo number_format($item['price'], 2); ?></span>
                                    <button class="btn btn-primary order-btn" data-bs-toggle="modal" data-bs-target="#itemModal<?php echo $item_id; ?>">
                                        <i class="fas fa-shopping-cart me-2"></i>Order Now
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Item Modal -->
                    <div class="modal fade" id="itemModal<?php echo $item_id; ?>" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title"><?php echo htmlspecialchars($item['name']); ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <img src="<?php echo htmlspecialchars($item['image']); ?>" 
                                                 class="img-fluid rounded" 
                                                 alt="<?php echo htmlspecialchars($item['name']); ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                                            <p class="text-muted"><?php echo htmlspecialchars($item['description']); ?></p>
                                            <div class="mb-3">
                                                <span class="badge bg-primary">Rating: <?php echo $item['rating']; ?> ⭐</span>
                                                <span class="badge bg-secondary ms-2"><?php echo $item['prep_time']; ?></span>
                                            </div>
                                            <h5 class="text-primary mb-4">₱<?php echo number_format($item['price'], 2); ?></h5>
                                            
                                            <form method="POST" action="">
                                                <input type="hidden" name="action" value="add_to_cart">
                                                <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
                                                
                                                <?php 
                                                // Determine if item needs customization options
                                                $needs_size = in_array($item_class, ['milkteas', 'coffee_hot', 'coffee_cold', 'frappes', 'fruit_tea']);
                                                $needs_sugar = in_array($item_class, ['milkteas', 'coffee_hot', 'coffee_cold', 'frappes', 'fruit_tea']);
                                                $needs_ice = in_array($item_class, ['milkteas', 'coffee_cold', 'frappes', 'fruit_tea']);
                                                // Hot coffee doesn't need ice level
                                                if ($item_class === 'coffee_hot') {
                                                    $needs_ice = false;
                                                }
                                                ?>
                                                
                                                <?php if ($needs_size): ?>
                                                <div class="mb-3">
                                                    <label class="form-label">Size</label>
                                                    <select class="form-select" name="size" required>
                                                        <option value="regular">Regular</option>
                                                        <option value="large">Large (+30%)</option>
                                                    </select>
                                                </div>
                                                <?php else: ?>
                                                <input type="hidden" name="size" value="regular">
                                                <?php endif; ?>
                                                
                                                <?php if ($needs_sugar): ?>
                                                <div class="mb-3">
                                                    <label class="form-label">Sugar Level</label>
                                                    <select class="form-select" name="sugar_level" required>
                                                        <option value="no_sugar">No Sugar</option>
                                                        <option value="less_sugar">Less Sugar</option>
                                                        <option value="regular" selected>Regular</option>
                                                        <option value="more_sugar">More Sugar</option>
                                                    </select>
                                                </div>
                                                <?php else: ?>
                                                <input type="hidden" name="sugar_level" value="regular">
                                                <?php endif; ?>
                                                
                                                <?php if ($needs_ice): ?>
                                                <div class="mb-3">
                                                    <label class="form-label">Ice Level</label>
                                                    <select class="form-select" name="ice_level" required>
                                                        <option value="no_ice">No Ice</option>
                                                        <option value="less_ice">Less Ice</option>
                                                        <option value="regular" selected>Regular</option>
                                                        <option value="more_ice">More Ice</option>
                                                    </select>
                                                </div>
                                                <?php else: ?>
                                                <input type="hidden" name="ice_level" value="<?php echo $item_class === 'coffee_hot' ? 'no_ice' : 'regular'; ?>">
                                                <?php endif; ?>
                                                
                                                <div class="mb-3">
                                                    <label class="form-label">Quantity</label>
                                                    <input type="number" class="form-control" name="quantity" value="1" min="1" required>
                                                </div>
                                                
                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-success btn-lg">
                                                        <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Cart: replaced with floating button + offcanvas -->
            <div class="col-lg-5 d-none d-lg-block">
                <!-- on large screens keep a small placeholder to preserve layout spacing -->
            </div>

            <!-- Floating Cart Button -->
            <button class="floating-cart-btn" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas" aria-label="Open Cart">
                <i class="fas fa-shopping-cart"></i>
                <span class="cart-badge <?php echo empty($_SESSION['cart']) ? 'empty' : ''; ?>" id="floatingCartBadge">
                    <?php echo count($_SESSION['cart']); ?>
                </span>
            </button>

            <!-- Offcanvas Cart -->
            <div class="offcanvas offcanvas-end" tabindex="-1" id="cartOffcanvas" aria-labelledby="cartOffcanvasLabel">
                <div class="offcanvas-header">
                    <h5 id="cartOffcanvasLabel"><i class="fas fa-shopping-cart"></i> Your Cart</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <?php if (empty($_SESSION['cart'])): ?>
                        <p class="text-muted">Your cart is empty</p>
                    <?php else: ?>
                        <?php if (!isset($_SESSION['user_id'])): ?>
                            <div class="alert alert-warning mb-3" style="font-size: 0.9rem;">
                                <i class="fas fa-exclamation-triangle"></i> <strong>Login Required:</strong> You must be logged in to checkout. 
                                <a href="login.php?redirect=index.php" class="alert-link">Login here</a> or 
                                <a href="signup.php" class="alert-link">create an account</a>.
                            </div>
                        <?php endif; ?>
                        <?php foreach ($_SESSION['cart'] as $index => $item): ?>
                        <div class="cart-item p-3 mb-3 bg-light rounded">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1"><?php echo $item['name']; ?></h6>
                                    <small class="text-muted">
                                        <?php echo ucfirst($item['size']); ?> | 
                                        <?php echo ucfirst(str_replace('_', ' ', $item['sugar_level'])); ?> | 
                                        <?php echo ucfirst(str_replace('_', ' ', $item['ice_level'])); ?>
                                    </small>
                                    <br>
                                    <small class="text-muted">Qty: <?php echo $item['quantity']; ?></small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold">₱<?php echo number_format($item['price'], 2); ?></div>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="action" value="remove_from_cart">
                                        <input type="hidden" name="cart_index" value="<?php echo $index; ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>

                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total:</strong>
                            <strong>₱<?php echo number_format($cart_total, 2); ?></strong>
                        </div>

                        <div class="d-grid">
                            <?php if (isset($_SESSION['user_id'])): ?>
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#checkoutModal" data-bs-dismiss="offcanvas">
                                <i class="fas fa-credit-card"></i> Checkout
                            </button>
                            <?php else: ?>
                                <a href="login.php?redirect=index.php" class="btn btn-warning">
                                    <i class="fas fa-sign-in-alt"></i> Login to Checkout
                                </a>
                                <div class="alert alert-info mt-2 mb-0" style="font-size: 0.85rem;">
                                    <i class="fas fa-info-circle"></i> Please login to proceed with checkout
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="d-grid mt-2">
                            <form method="POST">
                                <input type="hidden" name="action" value="clear_cart">
                                <button type="submit" class="btn btn-outline-secondary">
                                    <i class="fas fa-trash-alt"></i> Clear Cart
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Checkout Modal -->
    <div class="modal fade" id="checkoutModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Order Summary</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> You must be logged in to place an order.
                        </div>
                        <div class="text-center">
                            <a href="login.php?redirect=index.php" class="btn btn-primary me-2">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                            <a href="signup.php" class="btn btn-outline-primary">
                                <i class="fas fa-user-plus"></i> Sign Up
                            </a>
                        </div>
                    <?php elseif (!empty($_SESSION['cart'])): ?>
                        <div class="mb-3">
                            <h6>Order Details:</h6>
                            <?php foreach ($_SESSION['cart'] as $item): ?>
                                <div class="d-flex justify-content-between">
                                    <span><?php echo $item['name']; ?> (<?php echo $item['quantity']; ?>x)</span>
                                    <span>₱<?php echo number_format($item['price'], 2); ?></span>
                                </div>
                            <?php endforeach; ?>
                            <hr>
                            <div class="d-flex justify-content-between fw-bold">
                                <span>Total:</span>
                                <span>₱<?php echo number_format($cart_total, 2); ?></span>
                            </div>
                        </div>
                        
                        <form method="POST" action="process_order.php" id="checkoutForm">
                            <div class="mb-3">
                                <label class="form-label">Customer Name</label>
                                <input type="text" class="form-control" name="customer_name" id="customer_name" 
                                       value="<?php echo htmlspecialchars($_SESSION['full_name'] ?? ''); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Contact Number</label>
                    <div class="input-group">
                        <input type="tel" class="form-control" name="contact_number" id="contact_number"
                           pattern="09[0-9]{9}" maxlength="11" required
                           placeholder="09123456789"
                           value="<?php echo htmlspecialchars($_SESSION['contact_number'] ?? ''); ?>">
                    </div>
                    <div class="form-text">Enter exactly 11 digits, must start with 09</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Delivery Address</label>
                                <textarea class="form-control" name="delivery_address" id="delivery_address" rows="3" required><?php echo htmlspecialchars($_SESSION['address'] ?? ''); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Payment Method</label>
                                <select class="form-select" name="payment_method" required>
                                    <option value="">Select payment method</option>
                                    <option value="cash">Cash on Delivery</option>
                                    <option value="gcash">GCash</option>
                                    <option value="paymaya">PayMaya</option>
                                </select>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check"></i> Place Order
                                </button>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Your cart is empty. Add items to proceed with checkout.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Simple functionality only
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide success alert after 3 seconds
            const successAlert = document.getElementById('successAlert');
            if (successAlert) {
                setTimeout(function() {
                    const alert = new bootstrap.Alert(successAlert);
                    alert.close();
                }, 3000);
            }
            
            // Contact number validation
            const contactInput = document.getElementById('contact_number');
            if (contactInput) {
                contactInput.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                    if (this.value.length > 11) {
                        this.value = this.value.slice(0, 11);
                    }
                    // Enforce starts with 09
                    if (this.value.length >= 2 && this.value.slice(0,2) !== '09') {
                        this.setCustomValidity('Contact number must start with 09');
                    } else {
                        this.setCustomValidity('');
                    }
                });
            }

            // Prevent checkout if not logged in
            const checkoutModal = document.getElementById('checkoutModal');
            if (checkoutModal) {
                checkoutModal.addEventListener('show.bs.modal', function(event) {
                    <?php if (!isset($_SESSION['user_id'])): ?>
                    event.preventDefault();
                    window.location.href = 'login.php?redirect=index.php&error=login_required';
                    <?php endif; ?>
                });
            }


            // If item was just added, open the cart offcanvas so user can review
            try {
                if (successAlert) {
                    const cartOff = document.getElementById('cartOffcanvas');
                    if (cartOff) {
                        const off = new bootstrap.Offcanvas(cartOff);
                        off.show();
                    }
                }
            } catch (e) {
                // ignore if bootstrap not available or element missing
            }

            // Menu Filter Functionality with enhanced animations
            const filterButtons = document.querySelectorAll('.filter-btn');
            const menuItems = document.querySelectorAll('.menu-item');

            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const filter = this.getAttribute('data-filter');
                    
                    // Update active button with animation
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                    
                    menuItems.forEach((item) => {
                        const category = item.getAttribute('data-category');
                        
                        if (filter === 'all' || category === filter) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            });

            // Initialize: Show only milkteas by default
            menuItems.forEach(item => {
                const category = item.getAttribute('data-category');
                if (category !== 'milkteas') {
                    item.style.display = 'none';
                }
            });


            // Update floating cart badge when cart changes
            function updateFloatingCartBadge() {
                const cartBadge = document.getElementById('floatingCartBadge');
                const navCartBadge = document.querySelector('.navbar .badge');
                
                // Get cart count from navbar badge or count items
                let count = 0;
                if (navCartBadge) {
                    count = parseInt(navCartBadge.textContent) || 0;
                }
                
                if (cartBadge) {
                    cartBadge.textContent = count;
                    if (count > 0) {
                        cartBadge.classList.remove('empty');
                    } else {
                        cartBadge.classList.add('empty');
                    }
                }
            }

            // Update badge on page load
            updateFloatingCartBadge();

            // Listen for cart updates (when items are added/removed)
            const cartOffcanvas = document.getElementById('cartOffcanvas');
            if (cartOffcanvas) {
                cartOffcanvas.addEventListener('shown.bs.offcanvas', function() {
                    updateFloatingCartBadge();
                });
            }

            // Watch for changes in the cart offcanvas content
            const observer = new MutationObserver(function(mutations) {
                updateFloatingCartBadge();
            });

            const cartBody = document.querySelector('#cartOffcanvas .offcanvas-body');
            if (cartBody) {
                observer.observe(cartBody, {
                    childList: true,
                    subtree: true
                });
            }
        });
    </script>
    <style>
        /* Floating Cart Button */
        .floating-cart-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #8B4513, #D2691E);
            border-radius: 50%;
            box-shadow: 0 4px 12px rgba(139, 69, 19, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            z-index: 1000;
            cursor: pointer;
            border: none;
        }
        
        .floating-cart-btn:hover {
            opacity: 0.9;
        }
        
        .floating-cart-btn .cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: bold;
            border: 2px solid white;
        }
        
        .floating-cart-btn .cart-badge.empty {
            display: none;
        }
        
        @media (max-width: 768px) {
            .floating-cart-btn {
                width: 55px;
                height: 55px;
                bottom: 20px;
                right: 20px;
                font-size: 1.3rem;
            }
            
            .floating-cart-btn .cart-badge {
                width: 22px;
                height: 22px;
                font-size: 0.7rem;
            }
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #8B4513;
            box-shadow: 0 0 0 0.2rem rgba(139, 69, 19, 0.25);
        }
    </style>

</body>
</html>
