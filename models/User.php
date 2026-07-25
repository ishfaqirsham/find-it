<?php
// ==========================================
// Abstract User class
// Student, Staff, and Admin all extend this. It holds the data every
// account has in common, keeps that data private (encapsulation),
// and forces every subclass to define its own role name and
// dashboard link (this is what makes getRole() polymorphic later).
// ==========================================
abstract class User
{
    // Private properties: only reachable through the getters below.
    // Nothing outside this class (or its children) can do $user->password.
    private int $id;
    private string $fullName;
    private string $email;
    private string $phone;
    private string $password; // already hashed, never stored in plain text
    private string $userType;

    public function __construct(int $id, string $fullName, string $email, string $phone, string $password, string $userType)
    {
        $this->id = $id;
        $this->fullName = $fullName;
        $this->email = $email;
        $this->phone = $phone;
        $this->password = $password;
        $this->userType = $userType;
    }

    // ---- Getters (encapsulation: controlled read access) ----
    public function getId(): int { return $this->id; }
    public function getFullName(): string { return $this->fullName; }
    public function getEmail(): string { return $this->email; }
    public function getPhone(): string { return $this->phone; }
    public function getPasswordHash(): string { return $this->password; }
    public function getUserType(): string { return $this->userType; }

    // ---- Abstract methods ----
    // Every subclass (Student, Staff, Admin) MUST provide its own
    // version of these. Calling $user->getRoleLabel() on different
    // subclasses returns a different answer - that's polymorphism.
    abstract public function getRoleLabel(): string;
    abstract public function canManageUsers(): bool;

    // Shared, non-abstract behaviour every role gets for free
    public function verifyPassword(string $plainPassword): bool
    {
        return password_verify($plainPassword, $this->password);
    }
}
