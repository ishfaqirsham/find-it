<?php
require_once __DIR__ . '/../../core/Auth.php';
Auth::start();
$currentUser = Auth::user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lost and Found System</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>

<div class="navbar">
    <div class="nav-title">🔎 Lost &amp; Found System</div>
    <div class="nav-links">
        <a href="<?php echo BASE_URL; ?>/index.php">Home</a>
        <a href="<?php echo BASE_URL; ?>/index.php?page=item&action=search">Search</a>

        <?php if ($currentUser): ?>
            <a href="<?php echo BASE_URL; ?>/index.php?page=item&action=showPostForm&type=lost">Post Lost Item</a>
            <a href="<?php echo BASE_URL; ?>/index.php?page=item&action=showPostForm&type=found">Post Found Item</a>
            <?php if ($currentUser->canManageUsers()): ?>
                <a href="<?php echo BASE_URL; ?>/index.php?page=admin&action=dashboard">Admin Panel</a>
            <?php endif; ?>
            <span class="nav-user">Hi, <?php echo htmlspecialchars($currentUser->getFullName()); ?> (<?php echo htmlspecialchars($currentUser->getRoleLabel()); ?>)</span>
            <a href="<?php echo BASE_URL; ?>/index.php?page=auth&action=logout">Logout</a>
        <?php else: ?>
            <a href="<?php echo BASE_URL; ?>/index.php?page=auth&action=showLogin">Login</a>
            <a href="<?php echo BASE_URL; ?>/index.php?page=auth&action=showRegister">Register</a>
        <?php endif; ?>
    </div>
</div>

<div class="container">
