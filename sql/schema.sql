-- sql/schema.sql

-- First drop tables in reverse order of dependencies
DROP TABLE IF EXISTS Promotions;
DROP TABLE IF EXISTS Reviews;
DROP TABLE IF EXISTS Availability;
DROP TABLE IF EXISTS Payments;
DROP TABLE IF EXISTS Appointments;
DROP TABLE IF EXISTS Services;
DROP TABLE IF EXISTS Users;
DROP TABLE IF EXISTS Roles;

-- Create database
CREATE DATABASE IF NOT EXISTS booking_system;
USE booking_system;

-- Create Roles table first (since Users depends on it)
CREATE TABLE IF NOT EXISTS Roles (
    role_id INT AUTO_INCREMENT PRIMARY KEY,
    role_name VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- Insert roles
INSERT INTO Roles (role_name) VALUES 
('customer'), 
('therapist'), 
('admin');

-- Create Services table with all required columns
CREATE TABLE IF NOT EXISTS Services (
    service_id INT AUTO_INCREMENT PRIMARY KEY,
    service_name VARCHAR(100) NOT NULL,
    description TEXT,
    duration INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    service_type ENUM('massage', 'facial', 'body') NOT NULL,
    image VARCHAR(255) DEFAULT 'placeholder.jpg',
    popularity INT DEFAULT 0,
    is_popular BOOLEAN DEFAULT FALSE,
    is_deleted BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Now insert the sample services data
INSERT INTO Services (service_name, description, duration, price, service_type, image, popularity, is_popular) VALUES
-- Massage Services
('Swedish Massage', 'A gentle form of massage that uses long strokes, kneading, deep circular movements, vibration and tapping.', 60, 1500.00, 'massage', 'swedish-massage.jpg', 95, TRUE),
('Deep Tissue Massage', 'Targets knots and adhesions in the deeper layers of muscle tissue.', 90, 2000.00, 'massage', 'deep-tissue.jpg', 85, TRUE),
('Hot Stone Massage', 'Uses heated stones to enhance relaxation and promote better circulation.', 90, 2500.00, 'massage', 'hot-stone.jpg', 75, FALSE),

-- Facial Treatments
('Classic Facial', 'Deep cleansing facial treatment suitable for all skin types.', 60, 1200.00, 'facial', 'classic-facial.jpg', 80, TRUE),
('Anti-Aging Facial', 'Advanced treatment targeting fine lines and wrinkles.', 75, 2800.00, 'facial', 'anti-aging.jpg', 70, FALSE),
('Hydrating Facial', 'Intensive moisture treatment for dry and dehydrated skin.', 60, 1800.00, 'facial', 'hydrating-facial.jpg', 65, FALSE),

-- Body Treatments
('Body Scrub', 'Full body exfoliation treatment that removes dead skin cells.', 45, 1300.00, 'body', 'body-scrub.jpg', 60, FALSE),
('Aromatherapy Wrap', 'Relaxing body wrap using essential oils for deep relaxation.', 60, 1700.00, 'body', 'aromatherapy-wrap.jpg', 55, FALSE),
('Detox Treatment', 'Full body treatment designed to help eliminate toxins.', 90, 2200.00, 'body', 'detox.jpg', 50, TRUE);

-- Create Users table
CREATE TABLE IF NOT EXISTS Users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone_number VARCHAR(15) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role_id INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_deleted BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (role_id) REFERENCES Roles(role_id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Create remaining tables...
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