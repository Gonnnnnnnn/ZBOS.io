<?php
session_start();
require_once 'auth.php';
require_once 'db.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $full_name = trim($_POST['full_name'] ?? '');
    
    // Validation
    if (empty($username) || empty($email) || empty($password) || empty($full_name)) {
        $error = 'Please fill in all required fields.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address.';
    } else {
        if (registerUser($username, $email, $password, $full_name)) {
            header('Location: login.php?signup=success');
            exit;
        } else {
            $error = 'Username or email already exists.';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - ZapBrew</title>
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
        .signup-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 12px 30px rgba(0,0,0,0.18);
            padding: 2.5rem;
            width: 100%;
            max-width: 500px;
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
        .signup-card h3 {
            text-align: center;
            margin-bottom: 0.4rem;
            color: #4b2f1a;
            font-weight: 700;
        }
        .signup-card p.lead {
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
        .required {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="signup-card">
        <div class="brand">
            <div class="logo"><i class="fas fa-coffee"></i></div>
            <div>
                <h4 style="margin:0;color:#4b2f1a;">ZapBrew</h4>
                <small style="color:#6b3f24">Create Account</small>
            </div>
        </div>

        <h3>Sign Up</h3>
        <p class="lead">Create your account to start ordering</p>

        <?php if ($error): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="post" action="" id="signupForm">
            <div class="mb-3">
                <label class="form-label">Full Name <span class="required">*</span></label>
                <input type="text" name="full_name" class="form-control" placeholder="Enter your full name" 
                       value="<?php echo htmlspecialchars($full_name ?? ''); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Username <span class="required">*</span></label>
                <input type="text" name="username" class="form-control" placeholder="Choose a username" 
                       value="<?php echo htmlspecialchars($username ?? ''); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email <span class="required">*</span></label>
                <input type="email" name="email" class="form-control" placeholder="Enter your email" 
                       value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password <span class="required">*</span></label>
                <input type="password" name="password" class="form-control" placeholder="At least 6 characters" required minlength="6">
            </div>

            <div class="mb-3">
                <label class="form-label">Confirm Password <span class="required">*</span></label>
                <input type="password" name="confirm_password" class="form-control" placeholder="Confirm your password" required minlength="6">
            </div>

            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary">Create Account</button>
            </div>
        </form>

        <div class="footer-note mt-3">
            <p>Already have an account? <a href="login.php">Login here</a></p>
            <p><a href="home.php">← Back to Home</a></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('signupForm')?.addEventListener('submit', function(e) {
            const password = document.querySelector('input[name="password"]').value;
            const confirmPassword = document.querySelector('input[name="confirm_password"]').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
                return false;
            }
            
            if (password.length < 6) {
                e.preventDefault();
                alert('Password must be at least 6 characters long!');
                return false;
            }
        });
    </script>
</body>
</html>
