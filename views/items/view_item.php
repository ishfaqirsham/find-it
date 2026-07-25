<div class="form-box" style="max-width:600px;">

    <span class="badge <?php echo $item->getBadgeClass(); ?>"><?php echo strtoupper($item->getTypeLabel()); ?></span>

    <?php if ($item->getStatus() === 'resolved'): ?>
        <span class="badge badge-resolved">RESOLVED</span>
    <?php endif; ?>

    <?php if ($item->getImage()): ?>
        <img src="<?php echo BASE_URL; ?>/uploads/<?php echo htmlspecialchars($item->getImage()); ?>" class="detail-image" alt="<?php echo htmlspecialchars($item->getItemName()); ?>">
    <?php endif; ?>

    <h2><?php echo htmlspecialchars($item->getItemName()); ?></h2>
    <p><strong>Category:</strong> <?php echo htmlspecialchars($item->getCategory()); ?></p>
    <p><strong>Location:</strong> <?php echo htmlspecialchars($item->getLocation()); ?></p>
    <p><strong><?php echo $item->getDateFieldLabel(); ?>:</strong> <?php echo htmlspecialchars($item->getItemDate()); ?></p>
    <p><strong>Description:</strong><br><?php echo nl2br(htmlspecialchars($item->getDescription())); ?></p>
    <p><strong>Posted by:</strong> <?php echo htmlspecialchars($item->getPostedByName()); ?></p>

    <div class="contact-box">
        <strong>Contact Details:</strong> <?php echo htmlspecialchars($item->getContactDetails()); ?>
    </div>

    <p style="margin-top:20px;">
        <a href="<?php echo BASE_URL; ?>/index.php?page=item&action=search&type=<?php echo $type; ?>" class="btn">Back to Search</a>
    </p>
</div>
