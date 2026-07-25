<?php
// ==========================================
// UserRepository
// Handles every database query related to the `users` table, and
// hands back proper User objects (Student/Staff/Admin) built via
// UserFactory. Controllers never write raw SQL themselves - they
// just call methods on this class.
// ==========================================
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/UserFactory.php';

class UserRepository
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findByEmail(string $email): ?User
    {
        $row = $this->db->fetchOne("SELECT * FROM users WHERE email = ?", [$email]);
        return $row ? UserFactory::create($row) : null;
    }

    public function findById(int $id): ?User
    {
        $row = $this->db->fetchOne("SELECT * FROM users WHERE id = ?", [$id]);
        return $row ? UserFactory::create($row) : null;
    }

    public function emailExists(string $email): bool
    {
        return $this->findByEmail($email) !== null;
    }

    public function create(string $fullName, string $email, string $phone, string $plainPassword, string $userType): bool
    {
        $hashed = password_hash($plainPassword, PASSWORD_DEFAULT);
        return $this->db->execute(
            "INSERT INTO users (full_name, email, phone, password, user_type) VALUES (?, ?, ?, ?, ?)",
            [$fullName, $email, $phone, $hashed, $userType]
        );
    }

    // Used by the Admin "manage users" screen. If $plainPassword is
    // empty, the existing password is left unchanged.
    public function update(int $id, string $fullName, string $email, string $phone, string $userType, string $plainPassword = ''): bool
    {
        if (!empty($plainPassword)) {
            $hashed = password_hash($plainPassword, PASSWORD_DEFAULT);
            return $this->db->execute(
                "UPDATE users SET full_name = ?, email = ?, phone = ?, user_type = ?, password = ? WHERE id = ?",
                [$fullName, $email, $phone, $userType, $hashed, $id]
            );
        }

        return $this->db->execute(
            "UPDATE users SET full_name = ?, email = ?, phone = ?, user_type = ? WHERE id = ?",
            [$fullName, $email, $phone, $userType, $id]
        );
    }

    public function delete(int $id): bool
    {
        return $this->db->execute("DELETE FROM users WHERE id = ?", [$id]);
    }

    // Returns an array of User objects (Student/Staff/Admin mixed),
    // used on the admin "Manage Users" page
    public function getAll(): array
    {
        $rows = $this->db->fetchAll("SELECT * FROM users ORDER BY created_at DESC");
        return array_map(fn($row) => UserFactory::create($row), $rows);
    }
}
