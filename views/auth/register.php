<div class="form-box">
    <h2>Create an Account</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <?php foreach ($errors as $error) echo htmlspecialchars($error) . "<br>"; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo BASE_URL; ?>/index.php?page=auth&action=register" onsubmit="return validateRegisterForm()">

        <div class="form-group">
            <label for="full_name">Full Name</label>
            <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($old['fullName'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($old['phone'] ?? ''); ?>" placeholder="e.g. 0771234567">
            <span class="form-hint">Must be exactly 10 digits.</span>
        </div>

        <div class="form-group">
            <label for="user_type">I am a</label>
            <select id="user_type" name="user_type">
                <option value="">-- Select --</option>
                <option value="student">Student</option>
                <option value="staff">Academic Staff</option>
            </select>
            <span class="form-hint">Admin accounts are created by an existing administrator.</span>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password">
            <span class="form-hint">At least 6 characters.</span>
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password">
        </div>

        <button type="submit" class="btn btn-full">Register</button>
    </form>

    <p style="margin-top:15px; text-align:center; font-size:14px;">
        Already have an account? <a href="<?php echo BASE_URL; ?>/index.php?page=auth&action=showLogin">Login here</a>
    </p>
</div>

<script src="<?php echo BASE_URL; ?>/assets/js/validation.js"></script>
