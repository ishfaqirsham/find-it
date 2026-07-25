<?php
// ==========================================
// UserFactory
// Takes a raw database row (an associative array from the `users`
// table) and returns the correct object: Student, Staff, or Admin.
// The rest of the app just calls $user->getRoleLabel() or
// $user->canManageUsers() without caring which subclass it got -
// this is the Factory pattern working together with polymorphism.
// ==========================================
require_once __DIR__ . '/Student.php';
require_once __DIR__ . '/Staff.php';
require_once __DIR__ . '/Admin.php';

class UserFactory
{
    public static function create(array $row): User
    {
        $id = (int) $row['id'];
        $fullName = $row['full_name'];
        $email = $row['email'];
        $phone = $row['phone'];
        $password = $row['password'];
        $userType = $row['user_type'];

        switch ($userType) {
            case 'admin':
                return new Admin($id, $fullName, $email, $phone, $password, $userType);
            case 'staff':
                return new Staff($id, $fullName, $email, $phone, $password, $userType);
            case 'student':
            default:
                return new Student($id, $fullName, $email, $phone, $password, $userType);
        }
    }
}
