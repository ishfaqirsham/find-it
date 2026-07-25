-- ===================================================
-- Lost and Found Item System (OOP Edition) - Schema
-- ===================================================

CREATE DATABASE IF NOT EXISTS lost_found_oop;
USE lost_found_oop;

-- ---------------------------------------------------
-- Table: users
-- user_type now has 3 possible roles: student, staff, admin.
-- Admin is a distinct actor, not just a flag on a student/staff account.
-- ---------------------------------------------------
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(15) NOT NULL,
    password VARCHAR(255) NOT NULL,
    user_type ENUM('student', 'staff', 'admin') NOT NULL DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ---------------------------------------------------
-- Table: lost_items
-- ---------------------------------------------------
CREATE TABLE lost_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    item_name VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    description TEXT,
    location VARCHAR(150),
    item_date DATE,
    image VARCHAR(255) DEFAULT NULL,
    contact_details VARCHAR(150) NOT NULL,
    status ENUM('pending', 'resolved') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ---------------------------------------------------
-- Table: found_items
-- ---------------------------------------------------
CREATE TABLE found_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    item_name VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    description TEXT,
    location VARCHAR(150),
    item_date DATE,
    image VARCHAR(255) DEFAULT NULL,
    contact_details VARCHAR(150) NOT NULL,
    status ENUM('pending', 'resolved') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ===================================================
-- Dummy Data (1 admin, 3 students, 2 staff, 3 lost, 3 found)
-- ===================================================

-- Password for ALL dummy users below is: password123
INSERT INTO users (full_name, email, phone, password, user_type) VALUES
('Admin User', 'admin@lostfound.edu.lk', '0700000000', '$2b$10$YdeQUewGzh9Tu7dRiFUrRuGCnEyhrqVH71WOvYOF7yJcNHc7r7XEC', 'admin'),
('Nimal Perera', 'nimal.perera@student.edu.lk', '0771234567', '$2b$10$YdeQUewGzh9Tu7dRiFUrRuGCnEyhrqVH71WOvYOF7yJcNHc7r7XEC', 'student'),
('Kavindi Silva', 'kavindi.silva@student.edu.lk', '0719876543', '$2b$10$YdeQUewGzh9Tu7dRiFUrRuGCnEyhrqVH71WOvYOF7yJcNHc7r7XEC', 'student'),
('Dr. Ruwan Fernando', 'ruwan.fernando@staff.edu.lk', '0765551234', '$2b$10$YdeQUewGzh9Tu7dRiFUrRuGCnEyhrqVH71WOvYOF7yJcNHc7r7XEC', 'staff'),
('Ms. Amaya Jayasuriya', 'amaya.jaya@staff.edu.lk', '0752223344', '$2b$10$YdeQUewGzh9Tu7dRiFUrRuGCnEyhrqVH71WOvYOF7yJcNHc7r7XEC', 'staff'),
('Tharindu Wickrama', 'tharindu.w@student.edu.lk', '0701112233', '$2b$10$YdeQUewGzh9Tu7dRiFUrRuGCnEyhrqVH71WOvYOF7yJcNHc7r7XEC', 'student');

-- Dummy lost items
INSERT INTO lost_items (user_id, item_name, category, description, location, item_date, contact_details) VALUES
(3, 'Black Wallet', 'Personal Item', 'A black leather wallet with a student ID card inside.', 'Library, 2nd Floor', '2026-07-10', '0719876543'),
(6, 'Blue Water Bottle', 'Accessories', 'Steel water bottle with a small mountain sticker on it.', 'Sports Complex', '2026-07-14', 'tharindu.w@student.edu.lk'),
(2, 'Scientific Calculator', 'Electronics', 'Casio fx-991 calculator with initials "N.P." on the back.', 'Exam Hall B', '2026-07-16', '0771234567');

-- Dummy found items
INSERT INTO found_items (user_id, item_name, category, description, location, item_date, contact_details) VALUES
(4, 'Umbrella', 'Accessories', 'Black foldable umbrella found near the main gate.', 'Main Gate', '2026-07-12', 'ruwan.fernando@staff.edu.lk'),
(5, 'Student ID Card', 'Documents', 'Found a student ID card, name partially visible on the front.', 'Cafeteria', '2026-07-15', '0752223344'),
(4, 'Wireless Headphones', 'Electronics', 'White wireless headphones found on a bench.', 'Lecture Hall 3', '2026-07-17', 'ruwan.fernando@staff.edu.lk');
