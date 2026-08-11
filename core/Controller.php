<?php
// ==========================================
// Base Controller
// Every controller (Auth, Item, Admin) extends this class.
// It gives them shared helpers: render a view, redirect, and
// access to the database - this is inheritance in action.
// ==========================================
require_once __DIR__ . '/Database.php';

abstract class Controller
{
    // Protected = visible to this class AND any class that extends it,
    // but hidden from outside code (encapsulation)
    protected Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // Loads a view file and passes it an array of data.
    // $view example: "auth/login" -> views/auth/login.php
    protected function render(string $view, array $data = []): void
    {
        // extract() turns $data['title'] into a variable $title, etc.
        extract($data);
        $viewFile = __DIR__ . "/../views/{$view}.php";

        if (!file_exists($viewFile)) {
            die("View not found: {$view}");
        }

        require __DIR__ . '/../views/layout/header.php';
        require $viewFile;
        require __DIR__ . '/../views/layout/footer.php';
    }

    // Same as render() but without the header/footer wrapper
    // (used for the admin panel which has its own layout)
    protected function renderBare(string $view, array $data = []): void
    {
        extract($data);
        require __DIR__ . "/../views/{$view}.php";
    }

    // Shortcut for header("Location: ...") + exit()
    protected function redirect(string $path): void
    {
        header("Location: " . BASE_URL . "/" . ltrim($path, '/'));
        exit();
    }
}
