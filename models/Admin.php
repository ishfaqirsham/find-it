<?php
// ==========================================
// Admin - a separate actor/role from Student and Staff.
// Only Admins can manage user accounts (add/edit/delete) and
// delete inappropriate lost/found posts.
// ==========================================
require_once __DIR__ . '/User.php';

class Admin extends User
{
    public function getRoleLabel(): string
    {
        return "Administrator";
    }

    // This is the whole reason Admin exists as its own class:
    // it overrides the parent's answer and returns true.
    public function canManageUsers(): bool
    {
        return true;
    }
}
