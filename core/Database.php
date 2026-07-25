<?php
// ==========================================
// Database class
// Wraps PDO in a Singleton so the whole app shares ONE connection.
// This is our encapsulation example: the connection details ($host,
// $user, $pass ...) are private and can only be reached through the
// public methods below.
// ==========================================
require_once __DIR__ . '/../config/config.php';

class Database
{
    // Only one instance of this class will ever exist
    private static ?Database $instance = null;

    // The actual PDO connection - kept private so nothing outside
    // this class can touch it directly
    private PDO $connection;

    // Constructor is private -> nobody can do "new Database()" from outside
    private function __construct()
    {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $this->connection = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    // The only way to get access to the database
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Returns the raw PDO object for running queries
    public function getConnection(): PDO
    {
        return $this->connection;
    }

    // ---- Small helper methods so the rest of the app rarely
    //      needs to touch PDO syntax directly ----

    // Run a SELECT and return all matching rows
    public function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // Run a SELECT and return only the first matching row (or null)
    public function fetchOne(string $sql, array $params = []): ?array
    {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    // Run an INSERT/UPDATE/DELETE and return true/false
    public function execute(string $sql, array $params = []): bool
    {
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute($params);
    }

    // Get the ID of the last inserted row
    public function lastInsertId(): string
    {
        return $this->connection->lastInsertId();
    }
}
