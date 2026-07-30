<?php
// ==========================================
// FoundItem - an item someone has reported as found will be shown
// ==========================================
require_once __DIR__ . '/Item.php';

class FoundItem extends Item
{
    public function getTypeLabel(): string { return "Found"; }
    public function getBadgeClass(): string { return "badge-found"; }
    public function getDateFieldLabel(): string { return "Date Found"; }
    public function getTableName(): string { return "found_items"; }
}
