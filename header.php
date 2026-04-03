<?php
// header.php
// Start session if it hasn't been started yet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Bank Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar">
    <div class="nav-brand">
        <a href="index.php" style="color: white; text-decoration: none;">🩸 Starwess BloodBank</a>
    </div>
    
    <div class="nav-items">
        <?php if (isset($_SESSION['user_id'])): ?>
            <?php if ($_SESSION['role'] == 'admin'): ?>
                <a href="admin_dashboard.php">Dashboard</a>
                <a href="inventory.php">Inventory</a>
            <?php else: ?>
                <a href="user_profile.php">My Profile</a>
            <?php endif; ?>
            <a href="logout.php" class="btn-logout">Logout</a>
            
        <?php else: ?>
            <?php endif; ?>
    </div>
</nav>

<div class="main-content">