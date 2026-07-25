<?php
// ==========================================
// Staff - academic staff member, same permissions as Student on the
// item side of the system, just a different label/role.
// ==========================================
require_once __DIR__ . '/User.php';

class Staff extends User
{
    public function getRoleLabel(): string
    {
        return "Academic Staff";
    }

    public function canManageUsers(): bool
    {
        return false;
    }
}
