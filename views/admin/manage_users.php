<?php
require_once __DIR__ . '/../../core/Auth.php';
Auth::start();
$currentUser = Auth::user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users - Admin Panel</title>
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
    <h2 class="page-title">Manage Users</h2>

    <?php if (!empty($message)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <p style="margin-bottom:15px;">
        <a href="<?php echo BASE_URL; ?>/index.php?page=admin&action=showAddUser" class="btn">+ Add New User</a>
    </p>

    <table>
        <tr>
            <th>ID</th><th>Full Name</th><th>Email</th><th>Phone</th><th>Role</th><th>Actions</th>
        </tr>
        <?php foreach ($allUsers as $u): /* @var User $u */ ?>
            <tr>
                <td><?php echo $u->getId(); ?></td>
                <td><?php echo htmlspecialchars($u->getFullName()); ?></td>
                <td><?php echo htmlspecialchars($u->getEmail()); ?></td>
                <td><?php echo htmlspecialchars($u->getPhone()); ?></td>
                <td><?php echo htmlspecialchars($u->getRoleLabel()); ?></td>
                <td>
                    <a href="<?php echo BASE_URL; ?>/index.php?page=admin&action=showEditUser&id=<?php echo $u->getId(); ?>" class="btn btn-small">Edit</a>
                    <a href="<?php echo BASE_URL; ?>/index.php?page=admin&action=deleteUser&id=<?php echo $u->getId(); ?>"
                       class="btn btn-danger btn-small"
                       onclick="return confirm('Delete this user permanently?')">Delete</a>
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
