<?php
// ==========================================
// Auth
// A small helper that wraps everything to do with "who is logged in"
// so controllers and views don't touch $_SESSION directly everywhere.
// ==========================================
require_once __DIR__ . '/../models/UserRepository.php';

class Auth
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function login(User $user): void
    {
        self::start();
        $_SESSION['user_id'] = $user->getId();
    }

    public static function logout(): void
    {
        self::start();
        session_unset();
        session_destroy();
    }

    public static function check(): bool
    {
        self::start();
        return isset($_SESSION['user_id']);
    }

    // Returns the logged-in User object (Student/Staff/Admin), or null
    public static function user(): ?User
    {
        self::start();
        if (!isset($_SESSION['user_id'])) {
            return null;
        }

        static $cachedUser = null;
        if ($cachedUser === null) {
            $repo = new UserRepository();
            $cachedUser = $repo->findById((int) $_SESSION['user_id']);
        }
        return $cachedUser;
    }

    public static function isAdmin(): bool
    {
        $user = self::user();
        // Polymorphism: we don't care if $user is a Student, Staff, or
        // Admin object - we just ask it whether it can manage users.
        return $user !== null && $user->canManageUsers();
    }
}
