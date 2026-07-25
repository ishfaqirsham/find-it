<div class="form-box">
    <h2>Login</h2>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <?php foreach ($errors as $error) echo htmlspecialchars($error) . "<br>"; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo BASE_URL; ?>/index.php?page=auth&action=login" onsubmit="return validateLoginForm()">

        <div class="form-group">
            <label for="email">Email</label>
            <input type="text" id="email" name="email">
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password">
        </div>

        <button type="submit" class="btn btn-full">Login</button>
    </form>

    <p style="margin-top:15px; text-align:center; font-size:14px;">
        Don't have an account? <a href="<?php echo BASE_URL; ?>/index.php?page=auth&action=showRegister">Register here</a>
    </p>

    <p style="margin-top:10px; text-align:center; font-size:12px; color:#888;">
        Demo admin: admin@lostfound.edu.lk / password123<br>
        Demo student: nimal.perera@student.edu.lk / password123
    </p>
</div>

<script src="<?php echo BASE_URL; ?>/assets/js/validation.js"></script>
