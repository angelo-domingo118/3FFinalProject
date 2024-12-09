class DashboardApiController {
    private $db;
    private $user;
    private $appointment;
    private $review;

    public function __construct($db) {
        $this->db = $db;
        $this->user = new User($db);
        $this->appointment = new Appointment($db);
        $this->review = new Review($db);
    }

    public function updateProfile() {
        try {
            $userId = $_SESSION['user_id'];
            $data = [
                'full_name' => $_POST['full_name'],
                'email' => $_POST['email'],
                'phone_number' => $_POST['phone_number']
            ];

            if ($this->user->updateProfile($userId, $data)) {
                $_SESSION['success'] = 'Profile updated successfully';
                header('Location: ' . BASE_URL . '/public/dashboard/profile');
            } else {
                throw new Exception('Failed to update profile');
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ' . BASE_URL . '/public/dashboard/profile');
        }
    }

    public function updatePassword() {
        try {
            $userId = $_SESSION['user_id'];
            
            if (!$this->user->verifyPassword($userId, $_POST['current_password'])) {
                throw new Exception('Current password is incorrect');
            }

            if ($_POST['new_password'] !== $_POST['confirm_password']) {
                throw new Exception('New passwords do not match');
            }

            if ($this->user->updatePassword($userId, $_POST['new_password'])) {
                $_SESSION['success'] = 'Password updated successfully';
            } else {
                throw new Exception('Failed to update password');
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        
        header('Location: ' . BASE_URL . '/public/dashboard/profile');
    }

    public function cancelAppointment() {
        try {
            $userId = $_SESSION['user_id'];
            $appointmentId = $_POST['appointment_id'];

            if ($this->appointment->cancelAppointment($appointmentId, $userId)) {
                echo json_encode(['success' => true]);
            } else {
                throw new Exception('Failed to cancel appointment');
            }
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function submitReview() {
        try {
            // Verify AJAX request
            if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || 
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                throw new Exception('Invalid request method');
            }

            // Check if user is logged in
            if (!isset($_SESSION['user_id'])) {
                throw new Exception('User not logged in');
            }

            // Get POST data
            $appointmentId = filter_input(INPUT_POST, 'appointment_id', FILTER_VALIDATE_INT);
            $rating = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT);
            $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);

            // Validate inputs
            if (!$appointmentId || !$rating || !$comment) {
                throw new Exception('Missing or invalid input data');
            }

            // Validate rating range
            if ($rating < 1 || $rating > 5) {
                throw new Exception('Rating must be between 1 and 5');
            }

            // Verify appointment belongs to user
            if (!$this->appointment->belongsToUser($appointmentId, $_SESSION['user_id'])) {
                throw new Exception('Invalid appointment access');
            }

            // Prepare review data
            $reviewData = [
                'appointment_id' => $appointmentId,
                'user_id' => $_SESSION['user_id'],
                'rating' => $rating,
                'comment' => $comment
            ];

            // Create the review
            if ($this->review->createReview($reviewData)) {
                echo json_encode(['success' => true]);
            } else {
                throw new Exception('Failed to save review');
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
} 