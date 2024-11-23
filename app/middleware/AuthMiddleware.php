class AuthMiddleware {
    public static function requireAuth() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Please log in to access this page";
            header('Location: /login');
            exit;
        }
    }

    public static function requireAdmin() {
        self::requireAuth();
        if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] !== 3) {
            $_SESSION['error'] = "Access denied. Admin privileges required.";
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }

    public static function requireTherapist() {
        self::requireAuth();
        if ($_SESSION['role_id'] !== 2) { // 2 is therapist role
            header('Location: /dashboard');
            exit;
        }
    }
} 