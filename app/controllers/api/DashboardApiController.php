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
            $data = [
                'appointment_id' => $_POST['appointment_id'],
                'user_id' => $_SESSION['user_id'],
                'rating' => $_POST['rating'],
                'comment' => $_POST['comment']
            ];

            if ($this->review->createReview($data)) {
                echo json_encode(['success' => true]);
            } else {
                throw new Exception('Failed to submit review');
            }
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
} 