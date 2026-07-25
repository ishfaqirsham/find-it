<?php
// ==========================================
// Student - a regular user who can post/search lost & found items
// ==========================================
require_once __DIR__ . '/User.php';

class Student extends User
{
    public function getRoleLabel(): string
    {
        return "Student";
    }

    public function canManageUsers(): bool
    {
        return false;
    }
}
