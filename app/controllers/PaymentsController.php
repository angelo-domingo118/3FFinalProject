<?php

class PaymentsController {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function index() {
        try {
            $conn = $this->db->connect();
            
            // Debug: Log the connection status
            error_log("Database connection established");
            
            // Simple query to test data existence
            $testQuery = "SELECT COUNT(*) as count FROM payments";
            $testStmt = $conn->query($testQuery);
            $count = $testStmt->fetch(PDO::FETCH_ASSOC)['count'];
            error_log("Number of payments found: " . $count);

            // Main query to fetch payments - modified to match exact database structure
            $query = "SELECT 
                        payment_id,
                        appointment_id,
                        amount,
                        payment_method,
                        payment_status,
                        transaction_id,
                        payment_date,
                        is_deleted,
                        promo_id,
                        original_amount,
                        discount_amount,
                        final_amount
                    FROM payments
                    WHERE is_deleted = 0
                    ORDER BY payment_date DESC";
            
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Debug: Log the number of payments fetched
            error_log("Fetched payments count: " . count($payments));
            
            // Load the view with the payments data
            require_once '../app/views/admin/payments.php';
            
        } catch (PDOException $e) {
            error_log("Error in PaymentsController::index: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            require_once '../app/views/errors/500.php';
        }
    }

    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $payment_id = $data['payment_id'] ?? null;
            $status = $data['status'] ?? null;

            if (!$payment_id || !$status) {
                http_response_code(400);
                echo json_encode(['error' => 'Missing required fields']);
                return;
            }

            $conn = $this->db->connect();
            $query = "UPDATE payments SET payment_status = ? WHERE payment_id = ? AND is_deleted = 0";
            $stmt = $conn->prepare($query);
            $result = $stmt->execute([$status, $payment_id]);

            if ($result) {
                echo json_encode(['success' => true]);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Failed to update payment status']);
            }
        } catch (PDOException $e) {
            error_log("Error in PaymentsController::updateStatus: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Database error occurred']);
        }
    }

    public function getDetails($id) {
        try {
            $conn = $this->db->connect();
            $query = "SELECT * FROM payments WHERE payment_id = ? AND is_deleted = 0";
            
            $stmt = $conn->prepare($query);
            $stmt->execute([$id]);
            $payment = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($payment) {
                echo json_encode($payment);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Payment not found']);
            }
        } catch (PDOException $e) {
            error_log("Error in PaymentsController::getDetails: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Database error occurred']);
        }
    }
}
