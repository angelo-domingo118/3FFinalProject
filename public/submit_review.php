<?php
// Prevent any output before our JSON response
ob_start();

// Enable error reporting but log to file instead of output
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/review_errors.log');

// Set JSON header
header('Content-Type: application/json');

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('User not logged in');
    }

    // Validate inputs
    if (!isset($_POST['appointment_id']) || !isset($_POST['rating']) || !isset($_POST['comment'])) {
        throw new Exception('Missing required fields');
    }

    $appointmentId = filter_var($_POST['appointment_id'], FILTER_VALIDATE_INT);
    $rating = filter_var($_POST['rating'], FILTER_VALIDATE_INT);
    $comment = trim(filter_var($_POST['comment'], FILTER_SANITIZE_STRING));

    if (!$appointmentId || !$rating || empty($comment)) {
        throw new Exception('Invalid input data');
    }

    if ($rating < 1 || $rating > 5) {
        throw new Exception('Rating must be between 1 and 5');
    }

    // Connect to database
    $db = new PDO(
        'mysql:host=localhost;dbname=spa_booking_db;charset=utf8mb4',
        'root',
        '',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    // Verify appointment belongs to user
    $checkStmt = $db->prepare(
        "SELECT COUNT(*) FROM Appointments 
         WHERE appointment_id = ? AND user_id = ? AND is_deleted = FALSE"
    );
    $checkStmt->execute([$appointmentId, $_SESSION['user_id']]);
    
    if ($checkStmt->fetchColumn() == 0) {
        throw new Exception('Invalid appointment access');
    }

    // Check if review already exists
    $checkReviewStmt = $db->prepare(
        "SELECT COUNT(*) FROM Reviews 
         WHERE appointment_id = ? AND is_deleted = FALSE"
    );
    $checkReviewStmt->execute([$appointmentId]);
    
    if ($checkReviewStmt->fetchColumn() > 0) {
        throw new Exception('Review already exists for this appointment');
    }

    // Insert the review
    $stmt = $db->prepare(
        "INSERT INTO Reviews (appointment_id, user_id, rating, comment, created_at) 
         VALUES (?, ?, ?, ?, NOW())"
    );
    
    $success = $stmt->execute([
        $appointmentId,
        $_SESSION['user_id'],
        $rating,
        $comment
    ]);

    if (!$success) {
        throw new Exception('Failed to save review');
    }

    // Clear any buffered output
    ob_clean();
    
    // Send success response
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    // Log the error
    error_log("Review submission error: " . $e->getMessage());
    
    // Clear any buffered output
    ob_clean();
    
    // Send error response
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
