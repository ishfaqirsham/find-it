<?php $isFound = $type === 'found'; ?>
<div class="form-box">
    <h2>Report a <?php echo $isFound ? 'Found' : 'Lost'; ?> Item</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <?php foreach ($errors as $error) echo htmlspecialchars($error) . "<br>"; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo BASE_URL; ?>/index.php?page=item&action=post" enctype="multipart/form-data" onsubmit="return validateItemForm()">
        <input type="hidden" name="type" value="<?php echo htmlspecialchars($type); ?>">

        <div class="form-group">
            <label for="item_name">Item Name</label>
            <input type="text" id="item_name" name="item_name" value="<?php echo htmlspecialchars($old['itemName'] ?? ''); ?>" placeholder="e.g. Black Wallet">
        </div>

        <div class="form-group">
            <label for="category">Category</label>
            <select id="category" name="category">
                <option value="">-- Select --</option>
                <option value="Electronics">Electronics</option>
                <option value="Personal Item">Personal Item</option>
                <option value="Documents">Documents</option>
                <option value="Accessories">Accessories</option>
                <option value="Others">Others</option>
            </select>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" placeholder="Any details that help identify the item"><?php echo htmlspecialchars($old['description'] ?? ''); ?></textarea>
        </div>

        <div class="form-group">
            <label for="location">Where did you <?php echo $isFound ? 'find' : 'lose'; ?> it?</label>
            <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($old['location'] ?? ''); ?>" placeholder="e.g. Library, 2nd Floor">
        </div>

        <div class="form-group">
            <label for="item_date"><?php echo $isFound ? 'Date Found' : 'Date Lost'; ?></label>
            <input type="date" id="item_date" name="item_date" value="<?php echo htmlspecialchars($old['itemDate'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label for="image">Upload an Image (optional)</label>
            <input type="file" id="image" name="image" accept="image/png, image/jpeg">
            <span class="form-hint">JPG or PNG only, max 2MB.</span>
        </div>

        <div class="form-group">
            <label for="contact_details">Contact Details</label>
            <input type="text" id="contact_details" name="contact_details" value="<?php echo htmlspecialchars($old['contactDetails'] ?? ''); ?>" placeholder="Phone number or email">
        </div>

        <button type="submit" class="btn btn-full">Submit <?php echo $isFound ? 'Found' : 'Lost'; ?> Item</button>
    </form>
</div>

<script src="<?php echo BASE_URL; ?>/assets/js/validation.js"></script>
