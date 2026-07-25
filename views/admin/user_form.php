<?php
require_once __DIR__ . '/../../core/Auth.php';
Auth::start();
$currentUser = Auth::user();
$isEdit = $mode === 'edit';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $isEdit ? 'Edit User' : 'Add User'; ?> - Admin Panel</title>
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
    <div class="form-box">
        <h2><?php echo $isEdit ? 'Edit User' : 'Add New User'; ?></h2>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <?php foreach ($errors as $error) echo htmlspecialchars($error) . "<br>"; ?>
            </div>
        <?php endif; ?>

        <?php
        // Values shown in the form: prefer resubmitted "old" values (after a
        // validation error), otherwise fall back to the existing user's data.
        $fullName = $old['full_name'] ?? ($targetUser ? $targetUser->getFullName() : '');
        $email = $old['email'] ?? ($targetUser ? $targetUser->getEmail() : '');
        $phone = $old['phone'] ?? ($targetUser ? $targetUser->getPhone() : '');
        $userType = $old['user_type'] ?? ($targetUser ? $targetUser->getUserType() : '');
        ?>

        <form method="POST" action="<?php echo BASE_URL; ?>/index.php?page=admin&action=<?php echo $isEdit ? 'editUser' : 'addUser'; ?>" onsubmit="return validateRegisterFormAdmin(<?php echo $isEdit ? 'true' : 'false'; ?>)">

            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?php echo $targetUser->getId(); ?>">
            <?php endif; ?>

            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($fullName); ?>">
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" placeholder="e.g. 0771234567">
                <span class="form-hint">Must be exactly 10 digits.</span>
            </div>

            <div class="form-group">
                <label for="user_type">Role</label>
                <select id="user_type" name="user_type">
                    <option value="">-- Select --</option>
                    <option value="student" <?php echo $userType === 'student' ? 'selected' : ''; ?>>Student</option>
                    <option value="staff" <?php echo $userType === 'staff' ? 'selected' : ''; ?>>Academic Staff</option>
                    <option value="admin" <?php echo $userType === 'admin' ? 'selected' : ''; ?>>Administrator</option>
                </select>
            </div>

            <div class="form-group">
                <label for="password">Password <?php echo $isEdit ? '(leave blank to keep unchanged)' : ''; ?></label>
                <input type="password" id="password" name="password">
                <span class="form-hint">At least 6 characters.</span>
            </div>

            <button type="submit" class="btn btn-full"><?php echo $isEdit ? 'Save Changes' : 'Create User'; ?></button>
        </form>
    </div>
</div>

<div class="footer">
    <p>&copy; <?php echo date("Y"); ?> Lost and Found System &mdash; Student Project (OOP Edition)</p>
</div>

<script src="<?php echo BASE_URL; ?>/assets/js/validation.js"></script>
</body>
</html>
