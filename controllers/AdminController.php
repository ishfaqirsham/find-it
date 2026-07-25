<?php
// ==========================================
// AdminController
// Only reachable by users whose object answers canManageUsers() = true
// (i.e. an Admin instance). Every method starts by checking this,
// so there's no way for a Student/Staff to reach these actions even
// if they guess the URL.
// ==========================================
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/ItemRepository.php';
require_once __DIR__ . '/../models/UserRepository.php';

class AdminController extends Controller
{
    private ItemRepository $items;
    private UserRepository $users;

    public function __construct()
    {
        parent::__construct();
        $this->items = new ItemRepository();
        $this->users = new UserRepository();
        Auth::start();
        $this->guard();
    }

    // Blocks anyone who isn't logged in as an Admin
    private function guard(): void
    {
        if (!Auth::check() || !Auth::isAdmin()) {
            $this->redirect('index.php?page=auth&action=showLogin');
            exit();
        }
    }

    // ---- Post moderation ----

    public function dashboard(): void
    {
        $lostItems = $this->items->getAll('lost');
        $foundItems = $this->items->getAll('found');
        $deleted = isset($_GET['deleted']);
        $this->renderBare('admin/dashboard', compact('lostItems', 'foundItems', 'deleted'));
    }

    public function deleteItem(): void
    {
        $type = ($_GET['type'] ?? 'lost') === 'found' ? 'found' : 'lost';
        $id = (int) ($_GET['id'] ?? 0);

        if ($id > 0) {
            $imageName = $this->items->getImageName($type, $id);
            if ($imageName) {
                $path = UPLOAD_DIR . $imageName;
                if (file_exists($path)) unlink($path);
            }
            $this->items->delete($type, $id);
        }

        $this->redirect('index.php?page=admin&action=dashboard&deleted=1');
    }

    // ---- User management (Admin-only actor capability) ----

    public function manageUsers(): void
    {
        $allUsers = $this->users->getAll();
        $message = $_SESSION['admin_message'] ?? '';
        unset($_SESSION['admin_message']);
        $this->renderBare('admin/manage_users', compact('allUsers', 'message'));
    }

    public function showAddUser(): void
    {
        $this->renderBare('admin/user_form', ['errors' => [], 'old' => [], 'mode' => 'add', 'targetUser' => null]);
    }

    public function addUser(): void
    {
        $errors = $this->validateUserInput($_POST, true);

        if (!empty($errors)) {
            $this->renderBare('admin/user_form', ['errors' => $errors, 'old' => $_POST, 'mode' => 'add', 'targetUser' => null]);
            return;
        }

        $this->users->create(
            trim($_POST['full_name']),
            trim($_POST['email']),
            trim($_POST['phone']),
            trim($_POST['password']),
            $_POST['user_type']
        );

        $_SESSION['admin_message'] = "User created successfully.";
        $this->redirect('index.php?page=admin&action=manageUsers');
    }

    public function showEditUser(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $targetUser = $this->users->findById($id);

        if (!$targetUser) {
            $this->redirect('index.php?page=admin&action=manageUsers');
            return;
        }

        $this->renderBare('admin/user_form', ['errors' => [], 'old' => [], 'mode' => 'edit', 'targetUser' => $targetUser]);
    }

    public function editUser(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $errors = $this->validateUserInput($_POST, false);

        if (!empty($errors)) {
            $targetUser = $this->users->findById($id);
            $this->renderBare('admin/user_form', ['errors' => $errors, 'old' => $_POST, 'mode' => 'edit', 'targetUser' => $targetUser]);
            return;
        }

        $this->users->update(
            $id,
            trim($_POST['full_name']),
            trim($_POST['email']),
            trim($_POST['phone']),
            $_POST['user_type'],
            trim($_POST['password'] ?? '') // optional on edit
        );

        $_SESSION['admin_message'] = "User updated successfully.";
        $this->redirect('index.php?page=admin&action=manageUsers');
    }

    public function deleteUser(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $currentAdmin = Auth::user();

        // Safety check: an admin should not be able to delete their own account
        // while logged in, to avoid locking themselves out.
        if ($currentAdmin && $currentAdmin->getId() === $id) {
            $_SESSION['admin_message'] = "You cannot delete your own account while logged in.";
            $this->redirect('index.php?page=admin&action=manageUsers');
            return;
        }

        $this->users->delete($id);
        $_SESSION['admin_message'] = "User deleted successfully.";
        $this->redirect('index.php?page=admin&action=manageUsers');
    }

    // Shared validation for both add and edit forms.
    // $requirePassword = true for "add" (password is mandatory),
    // false for "edit" (password is optional - leave blank to keep it).
    private function validateUserInput(array $data, bool $requirePassword): array
    {
        $errors = [];
        $fullName = trim($data['full_name'] ?? '');
        $email = trim($data['email'] ?? '');
        $phone = trim($data['phone'] ?? '');
        $userType = $data['user_type'] ?? '';
        $password = trim($data['password'] ?? '');

        if ($fullName === '') {
            $errors[] = "Full name is required.";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Please enter a valid email address.";
        }
        if (!preg_match('/^[0-9]{10}$/', $phone)) {
            $errors[] = "Phone number must be exactly 10 digits.";
        }
        if (!in_array($userType, ['student', 'staff', 'admin'], true)) {
            $errors[] = "Please select a valid role.";
        }
        if ($requirePassword && strlen($password) < 6) {
            $errors[] = "Password must be at least 6 characters long.";
        }
        if (!$requirePassword && $password !== '' && strlen($password) < 6) {
            $errors[] = "New password must be at least 6 characters long.";
        }

        // Check email uniqueness, but allow the current user to keep their own email
        $existing = $this->users->findByEmail($email);
        $editingId = (int) ($data['id'] ?? 0);
        if ($existing && $existing->getId() !== $editingId) {
            $errors[] = "This email is already used by another account.";
        }

        return $errors;
    }
}
