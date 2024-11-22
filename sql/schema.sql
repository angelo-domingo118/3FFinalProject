-- sql/schema.sql

CREATE DATABASE IF NOT EXISTS booking_system;
USE booking_system;

-- Users Table
CREATE TABLE IF NOT EXISTS Users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone_number VARCHAR(15) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role_id INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES Roles(role_id) ON DELETE RESTRICT ON UPDATE CASCADE,
    is_deleted BOOLEAN DEFAULT FALSE
) ENGINE=InnoDB;

-- Services Table
CREATE TABLE IF NOT EXISTS Services (
    service_id INT AUTO_INCREMENT PRIMARY KEY,
    service_name VARCHAR(100) NOT NULL,
    description TEXT,
    duration INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_deleted BOOLEAN DEFAULT FALSE
) ENGINE=InnoDB;

-- Appointments Table
CREATE TABLE IF NOT EXISTS Appointments (
    appointment_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    therapist_id INT NOT NULL,
    service_id INT NOT NULL,
    appointment_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    status ENUM('pending', 'confirmed', 'completed', 'canceled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (therapist_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES Services(service_id) ON DELETE CASCADE,
    is_deleted BOOLEAN DEFAULT FALSE
) ENGINE=InnoDB;

-- Payments Table
CREATE TABLE IF NOT EXISTS Payments (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    appointment_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_method ENUM('cash', 'credit_card', 'paypal') NOT NULL,
    payment_status ENUM('paid', 'unpaid', 'refunded') DEFAULT 'unpaid',
    transaction_id VARCHAR(100),
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (appointment_id) REFERENCES Appointments(appointment_id) ON DELETE CASCADE,
    is_deleted BOOLEAN DEFAULT FALSE
) ENGINE=InnoDB;

-- Availability Table
CREATE TABLE IF NOT EXISTS Availability (
    availability_id INT AUTO_INCREMENT PRIMARY KEY,
    therapist_id INT NOT NULL,
    date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    FOREIGN KEY (therapist_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    is_deleted BOOLEAN DEFAULT FALSE
) ENGINE=InnoDB;

-- Reviews Table
CREATE TABLE IF NOT EXISTS Reviews (
    review_id INT AUTO_INCREMENT PRIMARY KEY,
    appointment_id INT NOT NULL,
    user_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (appointment_id) REFERENCES Appointments(appointment_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    is_deleted BOOLEAN DEFAULT FALSE
) ENGINE=InnoDB;

-- Promotions Table
CREATE TABLE IF NOT EXISTS Promotions (
    promo_id INT AUTO_INCREMENT PRIMARY KEY,
    promo_code VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    discount_percent DECIMAL(5,2) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    is_deleted BOOLEAN DEFAULT FALSE
) ENGINE=InnoDB;

-- Creating Roles Table
CREATE TABLE IF NOT EXISTS Roles (
    role_id INT AUTO_INCREMENT PRIMARY KEY,
    role_name VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- Populating Roles Table
INSERT INTO Roles (role_name) VALUES ('customer'), ('therapist'), ('admin');

-- Adding indexes for performance optimization
CREATE INDEX idx_users_email ON Users(email);
CREATE INDEX idx_services_service_name ON Services(service_name);
CREATE INDEX idx_appointments_status ON Appointments(status);
CREATE INDEX idx_payments_payment_status ON Payments(payment_status);

-- Adding CHECK constraints for enhanced data integrity
ALTER TABLE Appointments
    ADD CONSTRAINT chk_end_time_after_start_time CHECK (end_time > start_time);

ALTER TABLE Payments
    ADD CONSTRAINT chk_amount_positive CHECK (amount > 0);

-- Adding UNIQUE constraint on promo_code and date ranges to prevent overlaps
ALTER TABLE Promotions
    ADD CONSTRAINT uc_promo_code_date UNIQUE (promo_code, start_date, end_date);