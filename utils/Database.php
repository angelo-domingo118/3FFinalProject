<?php

class Database {
    private $host = 'localhost';
    private $db_name = 'serenebook';
    private $username = 'root';
    private $password = '';
    private $conn = null;

    public function connect() {
        try {
            if ($this->conn === null) {
                $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4";
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ];
                
                error_log("[Database] Attempting to connect to database");
                $this->conn = new PDO($dsn, $this->username, $this->password, $options);
                error_log("[Database] Successfully connected to database");
            }
            return $this->conn;
        } catch (PDOException $e) {
            error_log("[Database] Connection Error: " . $e->getMessage());
            error_log("[Database] Stack trace: " . $e->getTraceAsString());
            return null;
        }
    }
} 