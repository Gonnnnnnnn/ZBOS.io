<?php
// Database-based authentication system
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'db.php';

// Find user by username or email
function findUser($username) {
    try {
        $pdo = getDB();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $username]);
        return $stmt->fetch();
    } catch (Exception $e) {
        error_log("Database error in findUser: " . $e->getMessage());
        return null;
    }
}

// Register new user
function registerUser($username, $email, $password, $full_name, $contact_number = '', $address = '') {
    try {
        $pdo = getDB();
        
        // Check if username or email already exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetchColumn() > 0) {
            return false;
        }
        
        // Insert new user
        $stmt = $pdo->prepare("INSERT INTO users 
            (username, email, password_hash, full_name, contact_number, address, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?)");
            
        $stmt->execute([
            $username,
            $email,
            password_hash($password, PASSWORD_DEFAULT),
            $full_name,
            $contact_number,
            $address,
            date('Y-m-d H:i:s')
        ]);
        
        return true;
    } catch (Exception $e) {
        error_log("Database error in registerUser: " . $e->getMessage());
        return false;
    }
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Require login - redirect if not logged in
function requireLogin($redirect_url = 'login.php') {
    if (!isLoggedIn()) {
        header("Location: $redirect_url?error=login_required");
        exit;
    }
    
    // Verify user still exists in database
    try {
        $pdo = getDB();
        $stmt = $pdo->prepare("SELECT id FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        if (!$stmt->fetch()) {
            // User no longer exists in database
            session_destroy();
            header("Location: $redirect_url?error=login_required");
            exit;
        }
    } catch (Exception $e) {
        error_log("Database error in requireLogin: " . $e->getMessage());
    }
}

// Get user by ID
function getUserById($id) {
    try {
        $pdo = getDB();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    } catch (Exception $e) {
        error_log("Database error in getUserById: " . $e->getMessage());
        return null;
    }
}

