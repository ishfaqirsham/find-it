<h2 class="page-title">Welcome to the Lost &amp; Found System</h2>
<p>Lost something on campus? Found something that isn't yours? Post it here so it can find its way back to the right person.</p>

<h3 style="margin-top:30px;">Recently Reported Lost Items</h3>
<div class="item-grid">
    <?php if (!empty($recentLost)): ?>
        <?php foreach ($recentLost as $item): /* @var LostItem $item */ ?>
            <div class="item-card">
                <?php if ($item->getImage()): ?>
                    <img src="<?php echo BASE_URL; ?>/uploads/<?php echo htmlspecialchars($item->getImage()); ?>" alt="<?php echo htmlspecialchars($item->getItemName()); ?>">
                <?php else: ?>
                    <div class="no-image">No Image</div>
                <?php endif; ?>
                <div class="item-card-body">
                    <span class="badge <?php echo $item->getBadgeClass(); ?>"><?php echo strtoupper($item->getTypeLabel()); ?></span>
                    <h3><?php echo htmlspecialchars($item->getItemName()); ?></h3>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($item->getLocation()); ?></p>
                    <p><strong><?php echo $item->getDateFieldLabel(); ?>:</strong> <?php echo htmlspecialchars($item->getItemDate()); ?></p>
                    <a href="<?php echo BASE_URL; ?>/index.php?page=item&action=view&type=lost&id=<?php echo $item->getId(); ?>" class="btn btn-small">View Details</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No lost items reported yet.</p>
    <?php endif; ?>
</div>

<h3 style="margin-top:30px;">Recently Reported Found Items</h3>
<div class="item-grid">
    <?php if (!empty($recentFound)): ?>
        <?php foreach ($recentFound as $item): /* @var FoundItem $item */ ?>
            <div class="item-card">
                <?php if ($item->getImage()): ?>
                    <img src="<?php echo BASE_URL; ?>/uploads/<?php echo htmlspecialchars($item->getImage()); ?>" alt="<?php echo htmlspecialchars($item->getItemName()); ?>">
                <?php else: ?>
                    <div class="no-image">No Image</div>
                <?php endif; ?>
                <div class="item-card-body">
                    <span class="badge <?php echo $item->getBadgeClass(); ?>"><?php echo strtoupper($item->getTypeLabel()); ?></span>
                    <h3><?php echo htmlspecialchars($item->getItemName()); ?></h3>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($item->getLocation()); ?></p>
                    <p><strong><?php echo $item->getDateFieldLabel(); ?>:</strong> <?php echo htmlspecialchars($item->getItemDate()); ?></p>
                    <a href="<?php echo BASE_URL; ?>/index.php?page=item&action=view&type=found&id=<?php echo $item->getId(); ?>" class="btn btn-small">View Details</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No found items reported yet.</p>
    <?php endif; ?>
</div>
