<?php
// app/models/User.php

class User {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function getTherapists() {
        $sql = "SELECT user_id, full_name, email, phone_number 
                FROM Users 
                WHERE role_id = (SELECT role_id FROM Roles WHERE role_name = 'therapist') 
                AND is_deleted = FALSE";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById($userId) {
        $sql = "SELECT * FROM Users WHERE user_id = ? AND is_deleted = FALSE";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByEmail($email) {
        $sql = "SELECT * FROM Users WHERE email = ? AND is_deleted = FALSE";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($userData) {
        $sql = "INSERT INTO Users (full_name, email, phone_number, password, role_id) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $userData['full_name'],
            $userData['email'],
            $userData['phone_number'],
            password_hash($userData['password'], PASSWORD_DEFAULT),
            $userData['role_id'] ?? 1 // Default to customer role
        ]);
    }

    public function updateUser($userId, $userData) {
        $sql = "UPDATE Users 
                SET full_name = ?, email = ?, phone_number = ? 
                WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $userData['full_name'],
            $userData['email'],
            $userData['phone_number'],
            $userId
        ]);
    }

    public function updateProfile($userId, $data) {
        $sql = "UPDATE Users SET 
                full_name = ?, 
                email = ?, 
                phone_number = ?,
                updated_at = CURRENT_TIMESTAMP 
                WHERE user_id = ?";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['full_name'],
            $data['email'],
            $data['phone_number'],
            $userId
        ]);
    }

    public function updatePassword($userId, $hashedPassword) {
        $sql = "UPDATE Users SET password = ?, updated_at = CURRENT_TIMESTAMP WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$hashedPassword, $userId]);
    }

    public function verifyPassword($userId, $password) {
        $sql = "SELECT password FROM Users WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $user && password_verify($password, $user['password']);
    }

    public function validateLogin($email, $password) {
        $sql = "SELECT user_id, full_name, email, password, role_id 
                FROM Users 
                WHERE email = ? 
                AND is_deleted = FALSE";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            // Remove password from array before returning
            unset($user['password']);
            return $user;
        }
        
        return false;
    }

    public function getTherapistCount() {
        try {
            $sql = "SELECT COUNT(*) as count FROM Users WHERE role_id = 2 AND is_active = TRUE";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        } catch (PDOException $e) {
            error_log("Error getting therapist count: " . $e->getMessage());
            return 0;
        }
    }

    public function getCustomerCount() {
        try {
            $sql = "SELECT COUNT(*) as count FROM Users WHERE role_id = 1 AND is_active = TRUE";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        } catch (PDOException $e) {
            error_log("Error getting customer count: " . $e->getMessage());
            return 0;
        }
    }
} 