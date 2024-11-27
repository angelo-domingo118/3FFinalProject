<?php

class AuthController {
    private $pdo;
    private $userModel;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->userModel = new User($pdo);
    }

    public function showLogin() {
        // Show login form
        require_once '../app/views/auth/login.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];
            
            if (empty($email) || empty($password)) {
                $_SESSION['error'] = "All fields are required";
                header('Location: ' . BASE_URL . '/public/login');
                exit;
            }

            $user = $this->userModel->validateLogin($email, $password);
            
            if ($user) {
                // Start session and store user data
                session_start();
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['role_id'] = $user['role_id'];
                
                // Check if there's a booking redirect
                if (isset($_SESSION['booking_redirect'])) {
                    $redirect = $_SESSION['booking_redirect'];
                    unset($_SESSION['booking_redirect']); // Clear the redirect data
                    
                    // Reconstruct the booking URL
                    $params = http_build_query($redirect['params']);
                    header('Location: ' . BASE_URL . '/public/booking/' . $redirect['page'] . '?' . $params);
                    exit;
                }
                
                // Default redirects based on role
                switch ($user['role_id']) {
                    case 1: // Customer
                        header('Location: ' . BASE_URL . '/public/dashboard');
                        break;
                    case 2: // Therapist
                        header('Location: ' . BASE_URL . '/public/therapist/dashboard');
                        break;
                    case 3: // Admin
                        header('Location: ' . BASE_URL . '/public/admin');
                        break;
                }
                exit;
            } else {
                $_SESSION['error'] = "Invalid email or password";
                header('Location: ' . BASE_URL . '/public/login');
                exit;
            }
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: ' . BASE_URL . '/public');
        exit;
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullName = filter_input(INPUT_POST, 'full_name', FILTER_SANITIZE_STRING);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];
            $terms = isset($_POST['terms']);
            $phoneNumber = $_POST['phone_number'];
            
            // Validation
            if (empty($fullName) || empty($email) || empty($password) || empty($confirmPassword)) {
                $_SESSION['error'] = "All fields are required";
                header('Location: ' . BASE_URL . '/public/register');
                exit;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = "Invalid email format";
                header('Location: ' . BASE_URL . '/public/register');
                exit;
            }

            if ($password !== $confirmPassword) {
                $_SESSION['error'] = "Passwords do not match";
                header('Location: ' . BASE_URL . '/public/register');
                exit;
            }

            if (!$terms) {
                $_SESSION['error'] = "You must accept the terms and conditions";
                header('Location: ' . BASE_URL . '/public/register');
                exit;
            }

            // Check if email already exists
            if ($this->userModel->findByEmail($email)) {
                $_SESSION['error'] = "Email already registered";
                header('Location: ' . BASE_URL . '/public/register');
                exit;
            }

            // Create user
            $success = $this->userModel->createUser([
                'full_name' => $fullName,
                'email' => $email,
                'phone_number' => $phoneNumber,
                'password' => $password,
                'role_id' => 1 // Default role (customer)
            ]);

            if ($success) {
                // Get the newly created user
                $user = $this->userModel->findByEmail($email);
                
                // Auto login after registration
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['full_name'] = $fullName;
                $_SESSION['role_id'] = 1;
                
                header('Location: ' . BASE_URL . '/public/dashboard');
                exit;
            } else {
                $_SESSION['error'] = "Registration failed. Please try again.";
                header('Location: ' . BASE_URL . '/public/register');
                exit;
            }
        }

        // Show registration form
        require_once '../app/views/auth/register.php';
    }
} 