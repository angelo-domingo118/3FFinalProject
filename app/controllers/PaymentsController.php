<?php

class PaymentsController {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function index() {
        // Get all payments with related information
        $query = "SELECT 
                    p.id,
                    p.transaction_id,
                    p.booking_id,
                    p.amount,
                    p.status,
                    p.payment_date,
                    CONCAT(c.first_name, ' ', c.last_name) as customer_name,
                    s.name as service_name
                FROM payments p
                JOIN bookings b ON p.booking_id = b.id
                JOIN customers c ON b.customer_id = c.id
                JOIN services s ON b.service_id = s.id
                ORDER BY p.payment_date DESC";
        
        $payments = $this->db->query($query)->fetchAll();
        
        // Load the view with the payments data
        require_once '../app/views/admin/payments.php';
    }

    public function updateStatus() {
        // Check if request is POST and has required data
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        // Get POST data
        $data = json_decode(file_get_contents('php://input'), true);
        $payment_id = $data['payment_id'] ?? null;
        $status = $data['status'] ?? null;

        if (!$payment_id || !$status) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
            return;
        }

        // Update payment status
        $query = "UPDATE payments SET status = ?, updated_at = NOW() WHERE id = ?";
        $result = $this->db->query($query, [$status, $payment_id]);

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to update payment status']);
        }
    }

    public function getDetails($id) {
        // Get detailed payment information
        $query = "SELECT 
                    p.*,
                    b.booking_date,
                    b.status as booking_status,
                    CONCAT(c.first_name, ' ', c.last_name) as customer_name,
                    c.email as customer_email,
                    s.name as service_name,
                    s.price as service_price
                FROM payments p
                JOIN bookings b ON p.booking_id = b.id
                JOIN customers c ON b.customer_id = c.id
                JOIN services s ON b.service_id = s.id
                WHERE p.id = ?";
        
        $payment = $this->db->query($query, [$id])->fetch();
        
        if ($payment) {
            echo json_encode($payment);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Payment not found']);
        }
    }
}
