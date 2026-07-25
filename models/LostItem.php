<?php
// ==========================================
// LostItem - an item someone has reported as lost
// ==========================================
require_once __DIR__ . '/Item.php';

class LostItem extends Item
{
    public function getTypeLabel(): string { return "Lost"; }
    public function getBadgeClass(): string { return "badge-lost"; }
    public function getDateFieldLabel(): string { return "Date Lost"; }
    public function getTableName(): string { return "lost_items"; }
}
