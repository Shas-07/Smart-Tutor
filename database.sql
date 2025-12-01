-- ===========================================
-- Smart Tutor - Complete Database Schema
-- ===========================================
-- This file contains the complete database structure
-- Import this file in phpMyAdmin to set up the database
-- ===========================================

CREATE DATABASE IF NOT EXISTS smart_tutor;
USE smart_tutor;

-- ===========================================
-- Users table
-- Supports shared username system (student and parent can have same username)
-- ===========================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    role ENUM('student', 'parent', 'lecturer') NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_username_role (username, role)
);

-- ===========================================
-- Students table (linked to users)
-- ===========================================
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    student_id VARCHAR(20) UNIQUE,
    parent_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (parent_id) REFERENCES users(id) ON DELETE SET NULL
);

-- ===========================================
-- Materials table (lectures, homework, videos)
-- ===========================================
CREATE TABLE IF NOT EXISTS materials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lecturer_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    type ENUM('lecture', 'homework', 'video') NOT NULL,
    file_path VARCHAR(500),
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (lecturer_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ===========================================
-- Student progress table
-- ===========================================
CREATE TABLE IF NOT EXISTS student_progress (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    material_id INT NOT NULL,
    status ENUM('not_started', 'in_progress', 'completed') DEFAULT 'not_started',
    completion_date TIMESTAMP NULL,
    score DECIMAL(5,2) NULL,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (material_id) REFERENCES materials(id) ON DELETE CASCADE
);

-- ===========================================
-- Report Cards / Test Results table
-- ===========================================
CREATE TABLE IF NOT EXISTS report_cards (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    lecturer_id INT NOT NULL,
    test_name VARCHAR(200) NOT NULL,
    test_date DATE NOT NULL,
    subject VARCHAR(100),
    score DECIMAL(5,2),
    max_score DECIMAL(5,2),
    grade VARCHAR(10),
    remarks TEXT,
    file_path VARCHAR(500),
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (lecturer_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ===========================================
-- Work Log / Study Log table
-- ===========================================
CREATE TABLE IF NOT EXISTS work_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    date DATE NOT NULL,
    subject VARCHAR(100),
    topic VARCHAR(200),
    hours_studied DECIMAL(4,2),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

-- ===========================================
-- Ads table
-- ===========================================
CREATE TABLE IF NOT EXISTS ads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    image_path VARCHAR(500),
    link_url VARCHAR(500),
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ===========================================
-- News table
-- ===========================================
CREATE TABLE IF NOT EXISTS news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    category VARCHAR(50),
    exam_type VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ===========================================
-- Sample Data
-- ===========================================

-- Sample Users
-- Password for all sample users: password
INSERT INTO users (username, password, email, role, full_name) VALUES
('lecturer1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'lecturer1@smarttutor.com', 'lecturer', 'Dr. John Smith'),
('student1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student1@smarttutor.com', 'student', 'Alice Johnson'),
('parent1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'parent1@smarttutor.com', 'parent', 'Robert Johnson');

-- Sample Student Record
INSERT INTO students (user_id, student_id, parent_id) VALUES
(2, 'STU001', 3);

-- Sample Ads
INSERT INTO ads (title, description, image_path, link_url, active) VALUES
('Premium Course Offer', 'Get 50% off on all premium courses this month!', 'assets/ads/ad1.jpg', '#', TRUE),
('Study Material Sale', 'Buy study materials at discounted prices', 'assets/ads/ad2.jpg', '#', TRUE);

-- Sample News
INSERT INTO news (title, content, category, exam_type) VALUES
('JEE Main 2024 Registration Open', 'Registration for JEE Main 2024 has started. Last date to apply is December 15, 2023.', 'exam', 'JEE'),
('NEET 2024 Important Dates', 'NEET 2024 exam scheduled for May 5, 2024. Application forms available from January 2024.', 'exam', 'NEET'),
('UPSC CSE 2024 Notification', 'UPSC Civil Services Examination 2024 notification released. Check official website for details.', 'exam', 'UPSC');

-- ===========================================
-- Database Setup Complete!
-- ===========================================
-- Features included:
-- - Shared username system (student & parent can share username)
-- - Report cards/test results tracking
-- - Work log/study log tracking
-- - Materials management (lectures, homework, videos)
-- - Student progress tracking
-- - Ads and news management
-- ===========================================
