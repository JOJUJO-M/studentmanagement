<?php
// config/auth.php
session_start();

/**
 * Check if user is logged in
 */
function is_logged_in()
{
    return isset($_SESSION['user_id']);
}

/**
 * Check if user has a specific role
 */
function has_role($role_name)
{
    if (!is_logged_in())
        return false;
    return in_array($role_name, $_SESSION['roles'] ?? []);
}

/**
 * Middleware: Redirect to login if not authenticated
 */
function require_login()
{
    if (!is_logged_in()) {
        header('Location: ' . BASE_URL . '/login.php');
        exit();
    }
}

/**
 * Middleware: Redirect if not admin
 */
function require_admin()
{
    require_login();
    if (!has_role('admin')) {
        header('Location: ' . BASE_URL . '/dashboard.php?error=unauthorized');
        exit();
    }
}

/**
 * Get current user data
 */
function get_user()
{
    return $_SESSION['user'] ?? null;
}
