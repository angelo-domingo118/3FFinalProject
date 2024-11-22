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
        if ($_SESSION['role_id'] !== 3) { // 3 is admin role
            header('Location: /dashboard');
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