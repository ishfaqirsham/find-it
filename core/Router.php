<?php
// ==========================================
// Router (our "small framework" piece)
// Reads ?page=... and ?action=... from the URL and calls the
// matching Controller@method. Every request goes through index.php,
// which is the single "front controller".
//
// Example URL:  index.php?page=item&action=postLost
// Calls:        (new ItemController())->postLost();
// ==========================================
class Router
{
    // Map of page => [ControllerClassName, file path]
    private array $routes = [
        'auth'  => ['AuthController', __DIR__ . '/../controllers/AuthController.php'],
        'item'  => ['ItemController', __DIR__ . '/../controllers/ItemController.php'],
        'admin' => ['AdminController', __DIR__ . '/../controllers/AdminController.php'],
    ];

    public function dispatch(string $page, string $action): void
    {
        if (!isset($this->routes[$page])) {
            $this->notFound();
            return;
        }

        [$className, $file] = $this->routes[$page];
        require_once $file;

        $controller = new $className();

        if (!method_exists($controller, $action)) {
            $this->notFound();
            return;
        }

        // Polymorphism in practice: $controller could be AuthController,
        // ItemController, or AdminController - PHP calls whichever
        // class's version of the method actually applies.
        $controller->$action();
    }

    private function notFound(): void
    {
        http_response_code(404);
        echo "<h2 style='font-family:Arial;text-align:center;margin-top:50px;'>404 - Page Not Found</h2>";
    }
}
