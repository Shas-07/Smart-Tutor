<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['role']);
}

// Check user role
function checkRole($requiredRole) {
    if (!isLoggedIn() || $_SESSION['role'] !== $requiredRole) {
        header('Location: ../index.php');
        exit();
    }
}

// Get user role
function getUserRole() {
    return $_SESSION['role'] ?? null;
}

// Logout function
function logout() {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit();
}
?>

