<h2 class="page-title">Search Items</h2>

<?php if (!empty($posted)): ?>
    <div class="alert alert-success">Your item has been posted successfully!</div>
<?php endif; ?>

<div class="tab-links">
    <a href="<?php echo BASE_URL; ?>/index.php?page=item&action=search&type=lost" class="<?php echo $type == 'lost' ? 'active' : ''; ?>">Lost Items</a>
    <a href="<?php echo BASE_URL; ?>/index.php?page=item&action=search&type=found" class="<?php echo $type == 'found' ? 'active' : ''; ?>">Found Items</a>
</div>

<form method="GET" action="<?php echo BASE_URL; ?>/index.php" class="search-bar">
    <input type="hidden" name="page" value="item">
    <input type="hidden" name="action" value="search">
    <input type="hidden" name="type" value="<?php echo htmlspecialchars($type); ?>">
    <input type="text" name="keyword" placeholder="Search by name, description, or location..." value="<?php echo htmlspecialchars($keyword); ?>">
    <select name="category">
        <option value="">All Categories</option>
        <?php foreach (['Electronics', 'Personal Item', 'Documents', 'Accessories', 'Others'] as $cat): ?>
            <option value="<?php echo $cat; ?>" <?php echo $category == $cat ? 'selected' : ''; ?>><?php echo $cat; ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit" class="btn">Filter</button>
</form>

<div class="item-grid">
    <?php if (!empty($results)): ?>
        <?php foreach ($results as $item): /* @var Item $item */ ?>
            <div class="item-card">
                <?php if ($item->getImage()): ?>
                    <img src="<?php echo BASE_URL; ?>/uploads/<?php echo htmlspecialchars($item->getImage()); ?>" alt="<?php echo htmlspecialchars($item->getItemName()); ?>">
                <?php else: ?>
                    <div class="no-image">No Image</div>
                <?php endif; ?>
                <div class="item-card-body">
                    <span class="badge <?php echo $item->getBadgeClass(); ?>"><?php echo strtoupper($item->getTypeLabel()); ?></span>
                    <h3><?php echo htmlspecialchars($item->getItemName()); ?></h3>
                    <p><strong>Category:</strong> <?php echo htmlspecialchars($item->getCategory()); ?></p>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($item->getLocation()); ?></p>
                    <p><strong><?php echo $item->getDateFieldLabel(); ?>:</strong> <?php echo htmlspecialchars($item->getItemDate()); ?></p>
                    <a href="<?php echo BASE_URL; ?>/index.php?page=item&action=view&type=<?php echo $type; ?>&id=<?php echo $item->getId(); ?>" class="btn btn-small">View Details</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No <?php echo htmlspecialchars($type); ?> items match your search.</p>
    <?php endif; ?>
</div>
