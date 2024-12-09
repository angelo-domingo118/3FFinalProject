-- sql/schema.sql

-- Create database
CREATE DATABASE IF NOT EXISTS spa_booking_db;
USE spa_booking_db;

-- First drop tables in reverse order of dependencies
DROP TABLE IF EXISTS Reviews;
DROP TABLE IF EXISTS Payments;
DROP TABLE IF EXISTS Appointments;
DROP TABLE IF EXISTS Availability;
DROP TABLE IF EXISTS Services;
DROP TABLE IF EXISTS Promotions;
DROP TABLE IF EXISTS Users;
DROP TABLE IF EXISTS Roles;

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
('Sports Massage', 'Designed to enhance athletic performance and recovery.', 60, 1800.00, 'massage', 'sports-massage.jpg', 70, TRUE),
('Prenatal Massage', 'Safe and gentle massage specifically designed for expectant mothers.', 60, 1700.00, 'massage', 'prenatal-massage.jpg', 65, FALSE),
('Reflexology', 'Focused massage on feet, hands, and ears to stimulate body systems.', 45, 1200.00, 'massage', 'reflexology.jpg', 60, TRUE),
('Couples Massage', 'Relaxing massage experience for two people in the same room.', 90, 3000.00, 'massage', 'couples-massage.jpg', 80, TRUE),

-- Facial Treatments
('Classic Facial', 'Deep cleansing facial treatment suitable for all skin types.', 60, 1200.00, 'facial', 'classic-facial.jpg', 80, TRUE),
('Anti-Aging Facial', 'Advanced treatment targeting fine lines and wrinkles.', 75, 2800.00, 'facial', 'anti-aging.jpg', 70, FALSE),
('Hydrating Facial', 'Intensive moisture treatment for dry and dehydrated skin.', 60, 1800.00, 'facial', 'hydrating-facial.jpg', 65, FALSE),
('Acne Treatment Facial', 'Specialized treatment for acne-prone skin.', 75, 2000.00, 'facial', 'acne-facial.jpg', 75, TRUE),
('Brightening Facial', 'Treatment focused on evening skin tone and adding radiance.', 60, 2200.00, 'facial', 'brightening-facial.jpg', 72, TRUE),
('Oxygen Facial', 'Infuses oxygen, vitamins, and minerals into the skin.', 90, 3000.00, 'facial', 'oxygen-facial.jpg', 68, FALSE),

-- Body Treatments
('Body Scrub', 'Full body exfoliation treatment that removes dead skin cells.', 45, 1300.00, 'body', 'body-scrub.jpg', 60, FALSE),
('Aromatherapy Wrap', 'Relaxing body wrap using essential oils for deep relaxation.', 60, 1700.00, 'body', 'aromatherapy-wrap.jpg', 55, FALSE),
('Detox Treatment', 'Full body treatment designed to help eliminate toxins.', 90, 2200.00, 'body', 'detox.jpg', 50, TRUE),
('Slimming Body Wrap', 'Body wrap designed to tone and tighten skin.', 90, 2500.00, 'body', 'slimming-wrap.jpg', 65, TRUE),
('Salt Glow Treatment', 'Exfoliating treatment using sea salts for smooth skin.', 45, 1400.00, 'body', 'salt-glow.jpg', 58, FALSE),
('Mud Body Wrap', 'Therapeutic wrap using mineral-rich mud.', 75, 1900.00, 'body', 'mud-wrap.jpg', 52, FALSE);

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

-- Insert sample users (1 admin, 1 customer, 5 therapists)
INSERT INTO Users (full_name, email, phone_number, password, role_id) VALUES 
-- Admin
('Admin User', 'admin@spa.com', '09123456789', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3),
-- Customer
('John Doe', 'customer@example.com', '09187654321', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1),
-- Therapists
('Maria Santos', 'maria@spa.com', '09234567890', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2),
('Juan Cruz', 'juan@spa.com', '09345678901', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2),
('Ana Reyes', 'ana@spa.com', '09456789012', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2),
('Pedro Garcia', 'pedro@spa.com', '09567890123', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2),
('Lisa Gomez', 'lisa@spa.com', '09678901234', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2);

-- Create Appointments table
CREATE TABLE IF NOT EXISTS Appointments (
    appointment_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    therapist_id INT NOT NULL,
    service_id INT NOT NULL,
    appointment_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    notes TEXT,
    status ENUM('pending', 'confirmed', 'completed', 'canceled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (therapist_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES Services(service_id) ON DELETE CASCADE,
    is_deleted BOOLEAN DEFAULT FALSE
) ENGINE=InnoDB;

-- Create Promotions table
CREATE TABLE IF NOT EXISTS Promotions (
    promo_id INT AUTO_INCREMENT PRIMARY KEY,
    promo_code VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    discount_percent DECIMAL(5,2) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    is_deleted BOOLEAN DEFAULT FALSE
) ENGINE=InnoDB;

-- Insert sample promotions
INSERT INTO Promotions (promo_code, description, discount_percent, start_date, end_date) VALUES
('WELCOME2024', 'New Year Special Offer', 15.00, '2024-01-01', '2024-02-29'),
('FIRSTTIME', 'First-time Customer Discount', 20.00, '2024-01-01', '2024-12-31'),
('SUMMER24', 'Summer Season Special', 10.00, '2024-03-01', '2024-05-31'),
('HOLIDAY', 'Holiday Season Discount', 25.00, '2024-12-01', '2024-12-31'),
('WEEKEND', 'Weekend Special Offer', 5.00, '2024-01-01', '2024-12-31');

-- Create Payments table
CREATE TABLE IF NOT EXISTS Payments (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    appointment_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_method ENUM('cash', 'credit_card', 'paypal') NOT NULL,
    payment_status ENUM('paid', 'unpaid', 'refunded') DEFAULT 'unpaid',
    transaction_id VARCHAR(100),
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_deleted BOOLEAN DEFAULT FALSE,
    promo_id INT DEFAULT NULL,
    original_amount DECIMAL(10,2) NOT NULL,
    discount_amount DECIMAL(10,2) DEFAULT 0.00,
    final_amount DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (appointment_id) REFERENCES Appointments(appointment_id) ON DELETE CASCADE,
    FOREIGN KEY (promo_id) REFERENCES Promotions(promo_id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Create Availability table
CREATE TABLE IF NOT EXISTS Availability (
    availability_id INT AUTO_INCREMENT PRIMARY KEY,
    therapist_id INT NOT NULL,
    date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    FOREIGN KEY (therapist_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    is_deleted BOOLEAN DEFAULT FALSE
) ENGINE=InnoDB;

-- Insert sample availability data for therapists (for the next 30 days)
INSERT INTO Availability (therapist_id, date, start_time, end_time)
SELECT 
    u.user_id,
    DATE_ADD(CURRENT_DATE, INTERVAL d.day DAY) as date,
    CASE 
        WHEN WEEKDAY(DATE_ADD(CURRENT_DATE, INTERVAL d.day DAY)) < 5 
        THEN '09:00:00' -- Weekday start time
        ELSE '10:00:00' -- Weekend start time
    END as start_time,
    CASE 
        WHEN WEEKDAY(DATE_ADD(CURRENT_DATE, INTERVAL d.day DAY)) < 5 
        THEN '18:00:00' -- Weekday end time
        ELSE '17:00:00' -- Weekend end time
    END as end_time
FROM 
    Users u
    CROSS JOIN (
        SELECT a.a + b.a * 10 as day
        FROM 
            (SELECT 0 as a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) a,
            (SELECT 0 as a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3) b
        ORDER BY day
    ) d
WHERE 
    u.role_id = 2 -- Only for therapists
    AND d.day < 30; -- For the next 30 days

-- Create Reviews table
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