<?php

class Database {
    private $host = 'localhost';
    private $db_name = 'spa_booking_db';
    private $username = 'root';
    private $password = '';
    private $conn;

    public function connect() {
        $this->conn = null;

        try {
            $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ];

            error_log("Attempting database connection to {$this->host}");
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
            error_log("Database connection successful");

            return $this->conn;
            
        } catch(PDOException $e) {
            error_log("Connection Error: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            throw $e; // Re-throw the exception to be handled by the caller
        }
    }
}
