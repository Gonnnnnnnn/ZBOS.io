<?php
session_start();
require_once 'config.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=dashboard.php');
    exit;
}

$conn = getDBConnection();

// Get user orders
$user_id = $_SESSION['user_id'];
$orders_stmt = $conn->prepare("SELECT id, order_number, total_amount, status, order_date FROM orders WHERE user_id = ? ORDER BY order_date DESC LIMIT 10");
$orders_stmt->bind_param("i", $user_id);
$orders_stmt->execute();
$orders_result = $orders_stmt->get_result();
$orders = [];
while ($row = $orders_result->fetch_assoc()) {
    $orders[] = $row;
}
$orders_stmt->close();

// Get user info
$user_stmt = $conn->prepare("SELECT username, email, full_name, contact_number, address, created_at, last_login FROM users WHERE id = ?");
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user_info = $user_result->fetch_assoc();
$user_stmt->close();
$conn->close();

// Handle profile update
$update_success = '';
$update_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    $full_name = trim($_POST['full_name'] ?? '');
    $contact_number = trim($_POST['contact_number'] ?? '');
    $address = trim($_POST['address'] ?? '');
    
    if (!empty($contact_number) && !preg_match('/^[0-9]{11}$/', $contact_number)) {
        $update_error = 'Contact number must be exactly 11 digits.';
    } else {
        $conn = getDBConnection();
        $stmt = $conn->prepare("UPDATE users SET full_name = ?, contact_number = ?, address = ? WHERE id = ?");
        $stmt->bind_param("sssi", $full_name, $contact_number, $address, $user_id);
        
        if ($stmt->execute()) {
            $update_success = 'Profile updated successfully!';
            $_SESSION['full_name'] = $full_name;
            $_SESSION['contact_number'] = $contact_number;
            $_SESSION['address'] = $address;
            $user_info['full_name'] = $full_name;
            $user_info['contact_number'] = $contact_number;
            $user_info['address'] = $address;
        } else {
            $update_error = 'Error updating profile. Please try again.';
        }
        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ZapBrew</title>
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
        .dashboard-header {
            background: linear-gradient(135deg, #8B4513, #D2691E);
            color: white;
            padding: 40px 0;
            margin-bottom: 30px;
        }
        .card {
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            margin-bottom: 20px;
        }
        .card-header {
            background: linear-gradient(135deg, #8B4513, #D2691E);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            font-weight: bold;
        }
        .btn-primary {
            background: linear-gradient(135deg, #8B4513, #D2691E);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #A0522D, #CD853F);
        }
        .order-status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .status-pending { background: #ffc107; color: #000; }
        .status-preparing { background: #17a2b8; color: #fff; }
        .status-completed { background: #28a745; color: #fff; }
        .status-cancelled { background: #dc3545; color: #fff; }
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
                        <a class="nav-link" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Menu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="dashboard-header">
        <div class="container">
            <h1><i class="fas fa-user-circle"></i> Welcome, <?php echo htmlspecialchars($user_info['full_name']); ?>!</h1>
            <p class="mb-0">Manage your account and view your orders</p>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-user"></i> Profile Information
                    </div>
                    <div class="card-body">
                        <p><strong>Username:</strong><br><?php echo htmlspecialchars($user_info['username']); ?></p>
                        <p><strong>Email:</strong><br><?php echo htmlspecialchars($user_info['email']); ?></p>
                        <p><strong>Full Name:</strong><br><?php echo htmlspecialchars($user_info['full_name']); ?></p>
                        <?php if ($user_info['contact_number']): ?>
                            <p><strong>Contact:</strong><br><?php echo htmlspecialchars($user_info['contact_number']); ?></p>
                        <?php endif; ?>
                        <?php if ($user_info['address']): ?>
                            <p><strong>Address:</strong><br><?php echo htmlspecialchars($user_info['address']); ?></p>
                        <?php endif; ?>
                        <p><strong>Member Since:</strong><br><?php echo date('F j, Y', strtotime($user_info['created_at'])); ?></p>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                            <i class="fas fa-edit"></i> Edit Profile
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-shopping-bag"></i> Recent Orders
                    </div>
                    <div class="card-body">
                        <?php if (empty($orders)): ?>
                            <p class="text-center text-muted">No orders yet. <a href="index.php">Start ordering now!</a></p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Order Number</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($orders as $order): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($order['order_number']); ?></td>
                                                <td><?php echo date('M j, Y g:i A', strtotime($order['order_date'])); ?></td>
                                                <td>₱<?php echo number_format($order['total_amount'], 2); ?></td>
                                                <td>
                                                    <span class="order-status status-<?php echo $order['status']; ?>">
                                                        <?php echo ucfirst($order['status']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="order_confirmation.php?order=<?php echo urlencode($order['order_number']); ?>" class="btn btn-sm btn-outline-primary">
                                                        View
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="post" action="">
                    <div class="modal-body">
                        <?php if ($update_error): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($update_error); ?></div>
                        <?php endif; ?>
                        <?php if ($update_success): ?>
                            <div class="alert alert-success"><?php echo htmlspecialchars($update_success); ?></div>
                        <?php endif; ?>
                        <input type="hidden" name="action" value="update_profile">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="full_name" class="form-control" 
                                   value="<?php echo htmlspecialchars($user_info['full_name']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contact Number</label>
                            <input type="text" name="contact_number" class="form-control" 
                                   value="<?php echo htmlspecialchars($user_info['contact_number'] ?? ''); ?>" 
                                   maxlength="11" pattern="[0-9]{11}">
                            <small class="form-text text-muted">11 digits, numbers only</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control" rows="3"><?php echo htmlspecialchars($user_info['address'] ?? ''); ?></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

