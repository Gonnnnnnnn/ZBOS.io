<?php
session_start();

$order_number = isset($_GET['order_number']) ? $_GET['order_number'] : '';

if (!$order_number) {
    header("Location: index.php");
    exit;
}

require_once 'db.php';

// Load order data from database
try {
    $pdo = getDB();
    
    // Get the order details
    $orderStmt = $pdo->prepare("
        SELECT o.*, u.username 
        FROM orders o 
        LEFT JOIN users u ON o.user_id = u.id 
        WHERE o.order_number = ?
    ");
    $orderStmt->execute([$order_number]);
    $order_data = $orderStmt->fetch(PDO::FETCH_ASSOC);
    
    if ($order_data) {
        // Get the order items
        $itemsStmt = $pdo->prepare("
            SELECT * FROM order_items 
            WHERE order_id = ?
        ");
        $itemsStmt->execute([$order_data['id']]);
        $order_data['items'] = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (Exception $e) {
    error_log("Failed to load order: " . $e->getMessage());
    header("Location: index.php?error=load_failed");
    exit;
}

if (!$order_data) {
    header("Location: index.php?error=order_not_found");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - ZapBrew</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .confirmation-card {
            border: 2px solid #28a745;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.2);
        }
        .success-icon {
            color: #28a745;
            font-size: 4rem;
        }
        .order-details {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
        }
        .item-row {
            border-bottom: 1px solid #dee2e6;
            padding: 10px 0;
        }
        .item-row:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-coffee"></i> ZapBrew
            </a>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card confirmation-card">
                    <div class="card-body text-center p-5">
                        <div class="success-icon mb-4">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        
                        <h1 class="text-success mb-3">Order Confirmed!</h1>
                        <p class="lead mb-4">Thank you for your order. We'll prepare your milk tea with love!</p>
                        
                        <div class="alert alert-info">
                            <h5><i class="fas fa-receipt"></i> Order Number: <strong><?php echo $order_data['order_number']; ?></strong></h5>
                            <p class="mb-0">Please keep this order number for your reference.</p>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h5><i class="fas fa-list"></i> Order Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Customer Information</h6>
                                <p><strong>Name:</strong> <?php echo htmlspecialchars($order_data['customer_name']); ?></p>
                                <p><strong>Contact:</strong> <?php echo htmlspecialchars($order_data['contact_number']); ?></p>
                                <p><strong>Address:</strong> <?php echo htmlspecialchars($order_data['delivery_address']); ?></p>
                                <p><strong>Payment:</strong> <?php echo ucfirst(str_replace('_', ' ', $order_data['payment_method'])); ?></p>
                            </div>
                            <div class="col-md-6">
                                <h6>Order Information</h6>
                                <p><strong>Order Date:</strong> <?php echo date('M d, Y H:i', strtotime($order_data['order_date'])); ?></p>
                                <p><strong>Status:</strong> <span class="badge bg-warning"><?php echo ucfirst($order_data['status']); ?></span></p>
                                <p><strong>Estimated Delivery:</strong> 30-45 minutes</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h5><i class="fas fa-shopping-cart"></i> Items Ordered</h5>
                    </div>
                    <div class="card-body">
                        <div class="order-details">
                            <?php foreach ($order_data['items'] as $item): ?>
                            <div class="item-row">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6><?php echo htmlspecialchars($item['item_name']); ?></h6>
                                        <small class="text-muted">
                                            Size: <?php echo ucfirst($item['size']); ?> | 
                                            Sugar: <?php echo ucfirst(str_replace('_', ' ', $item['sugar_level'])); ?> | 
                                            Ice: <?php echo ucfirst(str_replace('_', ' ', $item['ice_level'])); ?>
                                        </small>
                                    </div>
                                    <div class="col-md-3 text-center">
                                        <span class="badge bg-secondary">Qty: <?php echo $item['quantity']; ?></span>
                                    </div>
                                    <div class="col-md-3 text-end">
                                        <strong>₱<?php echo number_format($item['total_price'], 2); ?></strong>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            
                            <hr>
                            <div class="row">
                                <div class="col-md-9">
                                    <h5>Total Amount:</h5>
                                </div>
                                <div class="col-md-3 text-end">
                                    <h5 class="text-success">₱<?php echo number_format($order_data['total_amount'], 2); ?></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-body text-center">
                        <h5><i class="fas fa-info-circle"></i> What's Next?</h5>
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <div class="text-center">
                                    <i class="fas fa-clock fa-2x text-primary mb-2"></i>
                                    <h6>Preparation</h6>
                                    <small>We're preparing your order with fresh ingredients</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <i class="fas fa-motorcycle fa-2x text-warning mb-2"></i>
                                    <h6>Delivery</h6>
                                    <small>Our rider will deliver to your address</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <i class="fas fa-home fa-2x text-success mb-2"></i>
                                    <h6>Enjoy</h6>
                                    <small>Receive and enjoy your delicious milk tea!</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="index.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-plus"></i> Order More
                    </a>
                    <button onclick="window.print()" class="btn btn-outline-secondary btn-lg ms-2">
                        <i class="fas fa-print"></i> Print Receipt
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
