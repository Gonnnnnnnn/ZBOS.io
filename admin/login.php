<?php
session_start();

// Load credentials from config
$config = require __DIR__ . '/config.php';
$ADMIN_USER = $config['user'] ?? 'admin';
$ADMIN_PASS = $config['pass'] ?? 'admin123';

// If already logged in, go to dashboard
if (!empty($_SESSION['is_admin'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['username'] ?? '');
    $pass = trim($_POST['password'] ?? '');

    if ($user === $ADMIN_USER && $pass === $ADMIN_PASS) {
        $_SESSION['is_admin'] = true;
        session_regenerate_id(true);
        $_SESSION['admin_csrf'] = bin2hex(random_bytes(32));
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid credentials. Please try again.';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        /* Match site palette: warm browns/orange used in index.php */
        body {
            background: linear-gradient(135deg, #8B4513 0%, #D2691E 50%, #CD853F 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
            color: #2b2b2b;
        }

        .login-card {
            background: linear-gradient(180deg, rgba(255,255,255,0.98), #fff);
            border-radius: 16px;
            box-shadow: 0 12px 30px rgba(0,0,0,0.18);
            padding: 2rem;
            width: 100%;
            max-width: 440px;
            animation: fadeIn 0.5s ease;
            border: 1px solid rgba(0,0,0,0.06);
        }

        .brand {
            display:flex; align-items:center; gap:10px; justify-content:center; margin-bottom:1rem;
        }
        .brand .logo {
            background: linear-gradient(45deg,#8B4513,#D2691E);
            color: #fff; width:48px; height:48px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:22px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.15);
        }

        .login-card h3 {
            text-align: center;
            margin-bottom: 0.4rem;
            color: #4b2f1a;
            font-weight:700;
        }

        .login-card p.lead { text-align:center; margin-bottom:1.2rem; color:#6b3f24; }

        .btn-primary {
            background: linear-gradient(45deg,#8B4513,#D2691E);
            border: none; color:#fff; font-weight:600;
            box-shadow: 0 8px 20px rgba(210,105,30,0.18);
        }
        .btn-primary:hover { transform: translateY(-2px); }

        .alert { font-size: 0.95rem; }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(-6px);} to { opacity:1; transform:none;} }

        .footer-note { text-align:center; font-size:0.88rem; color:#6b3f24; margin-top:1rem; }

        a { text-decoration:none; color:#8B4513; }
        a:hover { color:#6b3f24; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="brand">
            <div class="logo"><i class="fas fa-coffee"></i></div>
            <div>
                <h4 style="margin:0;color:#4b2f1a;">ZapBrew Admin</h4>
                <small style="color:#6b3f24">Management Console</small>
            </div>
        </div>

        <h3>🔐 Admin Login</h3>
        <p class="lead">Sign in to manage orders and settings</p>

        <?php if ($error): ?>
            <div class="alert alert-danger text-center">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="post" action="">
            <div class="mb-3">
                <label class="form-label fw-semibold">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Enter username" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
            </div>

            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary py-2">Sign In</button>
            </div>
        </form>

        <div class="footer-note mt-3">
            
            <p><a href="../index.php">← Return to main site</a></p>
        </div>
    </div>
</body>
</html>
