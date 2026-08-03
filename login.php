<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/inc/functions.php';

// If user is logged in, redirect to appropriate dashboard
if (isLoggedIn()) {
    $user = getCurrentUser();
    if ($user['role'] === 'admin' || $user['role'] === 'restaurant_manager') {
        redirect('public/index.php?route=admin');
    } else {
        redirect('public/index.php?route=home');
    }
}

// Redirect to unified auth page
redirect('public/index.php?route=auth&action=unified');
?>
