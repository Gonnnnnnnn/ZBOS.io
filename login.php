<?php
session_start();
require_once 'auth.php';

// Redirect if already logged in
if (isLoggedIn()) {
    $redirect = $_GET['redirect'] ?? 'index.php';
    header("Location: $redirect");
    exit;
}

$error = '';
$success = '';

// Check if redirected from signup
if (isset($_GET['signup']) && $_GET['signup'] === 'success') {
    $success = 'Account created successfully! Please login.';
}

// Check if redirected from checkout
if (isset($_GET['error']) && $_GET['error'] === 'login_required') {
    $error = 'You must be logged in to place an order. Please login to continue.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        $user = findUser($username);
        
        if ($user && password_verify($password, $user['password_hash'])) {
            // Login successful
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['contact_number'] = $user['contact_number'] ?? '';
            $_SESSION['address'] = $user['address'] ?? '';
            
            // Redirect to intended page or index
            $redirect = $_GET['redirect'] ?? 'index.php';
            header("Location: $redirect");
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ZapBrew</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #8B4513 0%, #D2691E 50%, #CD853F 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }
        .login-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 12px 30px rgba(0,0,0,0.18);
            padding: 2.5rem;
            width: 100%;
            max-width: 450px;
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            justify-content: center;
            margin-bottom: 1.5rem;
        }
        .brand .logo {
            background: linear-gradient(45deg, #8B4513, #D2691E);
            color: #fff;
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.15);
        }
        .login-card h3 {
            text-align: center;
            margin-bottom: 0.4rem;
            color: #4b2f1a;
            font-weight: 700;
        }
        .login-card p.lead {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #6b3f24;
        }
        .btn-primary {
            background: linear-gradient(45deg, #8B4513, #D2691E);
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 12px;
            box-shadow: 0 8px 20px rgba(210,105,30,0.18);
        }
        .btn-primary:hover {
            background: linear-gradient(45deg, #A0522D, #CD853F);
            transform: translateY(-2px);
        }
        .form-label {
            font-weight: 600;
            color: #4b2f1a;
            margin-bottom: 0.5rem;
        }
        .form-control:focus {
            border-color: #8B4513;
            box-shadow: 0 0 0 0.2rem rgba(139, 69, 19, 0.25);
        }
        .footer-note {
            text-align: center;
            font-size: 0.9rem;
            color: #6b3f24;
            margin-top: 1.5rem;
        }
        a {
            text-decoration: none;
            color: #8B4513;
            font-weight: 600;
        }
        a:hover {
            color: #6b3f24;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="brand">
            <div class="logo"><i class="fas fa-coffee"></i></div>
            <div>
                <h4 style="margin:0;color:#4b2f1a;">ZapBrew</h4>
                <small style="color:#6b3f24">Customer Login</small>
            </div>
        </div>

        <h3>Login</h3>
        <p class="lead">Sign in to your account</p>

        <?php if ($error): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <form method="post" action="">
            <div class="mb-3">
                <label class="form-label">Username or Email</label>
                <input type="text" name="username" class="form-control" placeholder="Enter username or email" 
                       value="<?php echo htmlspecialchars($username ?? ''); ?>" required autofocus>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
            </div>

            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary">Sign In</button>
            </div>
        </form>

        <div class="footer-note mt-3">
            <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
            <p><a href="home.php">← Back to Home</a></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
