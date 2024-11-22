<?php
// app/models/User.php

class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function findByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM Users WHERE email = ? AND is_deleted = 0 LIMIT 1");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function validateLogin($email, $password) {
        $user = $this->findByEmail($email);
        
        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']); // Don't store password in session
            return $user;
        }
        
        return false;
    }

    public function create($userData) {
        try {
            $stmt = $this->pdo->prepare(
                "INSERT INTO Users (full_name, email, password, role_id, created_at) 
                 VALUES (?, ?, ?, ?, NOW())"
            );
            
            $stmt->execute([
                $userData['full_name'],
                $userData['email'],
                $userData['password'],
                $userData['role_id']
            ]);
            
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            // Log error
            error_log("Error creating user: " . $e->getMessage());
            return false;
        }
    }
} 