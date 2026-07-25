<?php
// ==========================================
// AuthController
// Handles registration, login, and logout.
// ==========================================
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/UserRepository.php';

class AuthController extends Controller
{
    private UserRepository $users;

    public function __construct()
    {
        parent::__construct();
        $this->users = new UserRepository();
        Auth::start();
    }

    public function showRegister(): void
    {
        $this->render('auth/register', ['errors' => [], 'old' => []]);
    }

    public function register(): void
    {
        $errors = [];
        $fullName = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $userType = $_POST['user_type'] ?? '';
        $password = trim($_POST['password'] ?? '');
        $confirmPassword = trim($_POST['confirm_password'] ?? '');

        // ---- Server-side validation ----
        if ($fullName === '') {
            $errors[] = "Full name is required.";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Please enter a valid email address.";
        }
        if (!preg_match('/^[0-9]{10}$/', $phone)) {
            $errors[] = "Phone number must be exactly 10 digits.";
        }
        // Note: registration only ever creates student/staff accounts.
        // Admin accounts are created separately by an existing Admin.
        if ($userType !== 'student' && $userType !== 'staff') {
            $errors[] = "Please select a valid user type.";
        }
        if (strlen($password) < 6) {
            $errors[] = "Password must be at least 6 characters long.";
        }
        if ($password !== $confirmPassword) {
            $errors[] = "Passwords do not match.";
        }
        if (empty($errors) && $this->users->emailExists($email)) {
            $errors[] = "This email is already registered. Please login instead.";
        }

        if (!empty($errors)) {
            $this->render('auth/register', ['errors' => $errors, 'old' => compact('fullName', 'email', 'phone')]);
            return;
        }

        $this->users->create($fullName, $email, $phone, $password, $userType);
        $_SESSION['register_success'] = "Registration successful! You can now login.";
        $this->redirect('index.php?page=auth&action=showLogin');
    }

    public function showLogin(): void
    {
        if (Auth::check()) {
            $this->redirect('index.php');
        }

        $success = $_SESSION['register_success'] ?? '';
        unset($_SESSION['register_success']);

        $this->render('auth/login', ['errors' => [], 'success' => $success]);
    }

    public function login(): void
    {
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $errors = [];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Please enter a valid email address.";
        }
        if ($password === '') {
            $errors[] = "Password is required.";
        }

        if (empty($errors)) {
            $user = $this->users->findByEmail($email);

            if ($user && $user->verifyPassword($password)) {
                Auth::login($user);
                $this->redirect('index.php');
                return;
            }
            $errors[] = "Invalid email or password.";
        }

        $this->render('auth/login', ['errors' => $errors, 'success' => '']);
    }

    public function logout(): void
    {
        Auth::logout();
        $this->redirect('index.php?page=auth&action=showLogin');
    }
}
