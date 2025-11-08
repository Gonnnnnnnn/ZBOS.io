<?php
session_start();

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Menu items for featured section
$menu_items = [
    'classic' => [
        'name' => 'Classic Milk Tea',
        'price' => 75,
        'description' => 'Traditional milk tea with chewy tapioca pearls',
        'image' => 'images/classic milk tea.jpg',
        'category' => 'classic',
        'popular' => true,
        'rating' => 4.8
    ],
    'matcha' => [
        'name' => 'Matcha Milk Tea',
        'price' => 85,
        'description' => 'Premium matcha green tea with milk',
        'image' => 'images/matcha milk tea.jpg',
        'category' => 'premium',
        'popular' => true,
        'rating' => 4.9
    ],
    'brown_sugar' => [
        'name' => 'Brown Sugar Milk Tea',
        'price' => 90,
        'description' => 'Caramelized brown sugar with fresh milk',
        'image' => 'images/brown sugar milk tea.jpg',
        'category' => 'premium',
        'popular' => true,
        'rating' => 4.9
    ],
    'frappe_caramel' => [
        'name' => 'Caramel Frappe',
        'price' => 95,
        'description' => 'Rich caramel flavor blended with coffee and ice',
        'image' => 'images/caramel frappe.jpg',
        'category' => 'frappes',
        'popular' => true,
        'rating' => 4.9
    ]
];

// Get featured items
$featured_items = array_filter($menu_items, function($item) {
    return $item['popular'] === true;
});
$featured_items = array_slice($featured_items, 0, 4, true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZapBrew - Premium Milk Tea & Coffee</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: #f5f5f5;
        }
        .navbar {
            backdrop-filter: blur(10px);
            background: rgba(0, 0, 0, 0.9) !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }
        .navbar-brand {
            font-weight: bold;
            color: #8B4513 !important;
            font-size: 1.5rem;
        }
        .hero-section {
            background: linear-gradient(135deg, #8B4513, #D2691E);
            color: white;
            padding: 100px 0;
            text-align: center;
        }
        .hero-section h1 {
            font-size: 3.5rem;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .hero-section p {
            font-size: 1.3rem;
            margin-bottom: 30px;
            opacity: 0.95;
        }
        .btn-primary {
            background: white;
            color: #8B4513;
            border: none;
            padding: 15px 40px;
            font-size: 1.1rem;
            font-weight: bold;
            border-radius: 30px;
        }
        .btn-primary:hover {
            background: #f0f0f0;
            color: #8B4513;
        }
        .featured-section {
            padding: 80px 0;
            background: white;
        }
        .section-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: bold;
            color: #8B4513;
            margin-bottom: 50px;
        }
        .feature-card {
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            overflow: hidden;
            background: white;
            margin-bottom: 30px;
        }
        .feature-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .feature-card-body {
            padding: 20px;
        }
        .feature-card h5 {
            color: #333;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .feature-card p {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }
        .price-tag {
            background: linear-gradient(135deg, #FF6B6B, #FF8E53);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: bold;
            display: inline-block;
        }
        .features-section {
            padding: 80px 0;
            background: #f8f9fa;
        }
        .feature-item {
            text-align: center;
            padding: 30px;
        }
        .feature-item i {
            font-size: 3rem;
            color: #8B4513;
            margin-bottom: 20px;
        }
        .feature-item h4 {
            color: #333;
            margin-bottom: 15px;
        }
        .feature-item p {
            color: #666;
        }
        .cta-section {
            padding: 80px 0;
            background: linear-gradient(135deg, #8B4513, #D2691E);
            color: white;
            text-align: center;
        }
        .cta-section h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }
        .cta-section p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            opacity: 0.95;
        }
        .footer {
            background: #333;
            color: white;
            padding: 40px 0;
            text-align: center;
        }
        .rating {
            color: #FFD700;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
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
                        <a class="nav-link active" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Menu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#cart" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas">
                            <i class="fas fa-shopping-cart"></i> Cart
                            <?php if (!empty($_SESSION['cart'])): ?>
                                <span class="badge bg-danger"><?php echo count($_SESSION['cart']); ?></span>
                            <?php endif; ?>
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

    <section class="hero-section">
        <div class="container">
            <h1>Welcome to ZapBrew</h1>
            <p>Premium Milk Tea, Coffee & More</p>
            <p style="font-size: 1rem; opacity: 0.9;">Fresh ingredients, delicious flavors, affordable prices</p>
            <a href="index.php" class="btn btn-primary">
                <i class="fas fa-utensils"></i> View Full Menu
            </a>
        </div>
    </section>

    <section class="featured-section">
        <div class="container">
            <h2 class="section-title">Featured Items</h2>
            <div class="row">
                <?php foreach ($featured_items as $key => $item): ?>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="feature-card">
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            <div class="feature-card-body">
                                <div class="rating">
                                    <?php
                                    $full_stars = floor($item['rating']);
                                    $half_star = ($item['rating'] - $full_stars) >= 0.5;
                                    for ($i = 0; $i < $full_stars; $i++) {
                                        echo '<i class="fas fa-star"></i>';
                                    }
                                    if ($half_star) {
                                        echo '<i class="fas fa-star-half-alt"></i>';
                                    }
                                    ?>
                                    <span style="color: #666; margin-left: 5px;"><?php echo $item['rating']; ?></span>
                                </div>
                                <h5><?php echo htmlspecialchars($item['name']); ?></h5>
                                <p><?php echo htmlspecialchars($item['description']); ?></p>
                                <div class="price-tag">₱<?php echo number_format($item['price'], 2); ?></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="text-center mt-4">
                <a href="index.php" class="btn btn-primary">See All Items</a>
            </div>
        </div>
    </section>

    <section class="features-section">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="feature-item">
                        <i class="fas fa-shipping-fast"></i>
                        <h4>Fast Delivery</h4>
                        <p>Quick and reliable delivery service to your doorstep</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-item">
                        <i class="fas fa-leaf"></i>
                        <h4>Fresh Ingredients</h4>
                        <p>We use only the freshest and highest quality ingredients</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-item">
                        <i class="fas fa-peso-sign"></i>
                        <h4>Affordable Prices</h4>
                        <p>Delicious drinks at prices that won't break the bank</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-section">
        <div class="container">
            <h2>Ready to Order?</h2>
            <p>Browse our complete menu and order your favorite drinks</p>
            <a href="index.php" class="btn btn-primary btn-lg">
                <i class="fas fa-shopping-cart"></i> Order Now
            </a>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 ZapBrew. All rights reserved.</p>
            <p class="mb-0">
                <a href="home.php" class="text-white me-3">Home</a>
                <a href="index.php" class="text-white me-3">Menu</a>
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

