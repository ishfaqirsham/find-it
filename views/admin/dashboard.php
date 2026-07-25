<?php
require_once __DIR__ . '/../../core/Auth.php';
Auth::start();
$currentUser = Auth::user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - Lost and Found System</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>

<div class="navbar">
    <div class="nav-title">🔎 Admin Panel</div>
    <div class="nav-links">
        <a href="<?php echo BASE_URL; ?>/index.php">Home</a>
        <a href="<?php echo BASE_URL; ?>/index.php?page=admin&action=dashboard">Manage Posts</a>
        <a href="<?php echo BASE_URL; ?>/index.php?page=admin&action=manageUsers">Manage Users</a>
        <span class="nav-user">Hi, <?php echo htmlspecialchars($currentUser->getFullName()); ?></span>
        <a href="<?php echo BASE_URL; ?>/index.php?page=auth&action=logout">Logout</a>
    </div>
</div>

<div class="container">
    <h2 class="page-title">Manage Posts</h2>

    <?php if (!empty($deleted)): ?>
        <div class="alert alert-success">Post deleted successfully.</div>
    <?php endif; ?>

    <h3>Lost Items</h3>
    <table>
        <tr>
            <th>ID</th><th>Item Name</th><th>Posted By</th><th>Location</th><th>Date</th><th>Action</th>
        </tr>
        <?php foreach ($lostItems as $item): ?>
            <tr>
                <td><?php echo $item->getId(); ?></td>
                <td><?php echo htmlspecialchars($item->getItemName()); ?></td>
                <td><?php echo htmlspecialchars($item->getPostedByName()); ?></td>
                <td><?php echo htmlspecialchars($item->getLocation()); ?></td>
                <td><?php echo htmlspecialchars($item->getItemDate()); ?></td>
                <td>
                    <a href="<?php echo BASE_URL; ?>/index.php?page=admin&action=deleteItem&type=lost&id=<?php echo $item->getId(); ?>"
                       class="btn btn-danger btn-small"
                       onclick="return confirm('Delete this post permanently?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h3 style="margin-top:30px;">Found Items</h3>
    <table>
        <tr>
            <th>ID</th><th>Item Name</th><th>Posted By</th><th>Location</th><th>Date</th><th>Action</th>
        </tr>
        <?php foreach ($foundItems as $item): ?>
            <tr>
                <td><?php echo $item->getId(); ?></td>
                <td><?php echo htmlspecialchars($item->getItemName()); ?></td>
                <td><?php echo htmlspecialchars($item->getPostedByName()); ?></td>
                <td><?php echo htmlspecialchars($item->getLocation()); ?></td>
                <td><?php echo htmlspecialchars($item->getItemDate()); ?></td>
                <td>
                    <a href="<?php echo BASE_URL; ?>/index.php?page=admin&action=deleteItem&type=found&id=<?php echo $item->getId(); ?>"
                       class="btn btn-danger btn-small"
                       onclick="return confirm('Delete this post permanently?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<div class="footer">
    <p>&copy; <?php echo date("Y"); ?> Lost and Found System &mdash; Student Project (OOP Edition)</p>
</div>

</body>
</html>
