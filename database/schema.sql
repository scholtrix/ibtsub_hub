-- Ibtsub Hub database schema
-- Phase 1: DATABASE DESIGN for Registration + Admin Management System

CREATE DATABASE IF NOT EXISTS snhgnltn_hub
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE snhgnltn_hub;

-- Admin users table
CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(80) NOT NULL UNIQUE,
  email VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  full_name VARCHAR(150) NOT NULL,
  role ENUM('admin') NOT NULL DEFAULT 'admin',
  status ENUM('active','disabled') NOT NULL DEFAULT 'active',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Courses available for registration
CREATE TABLE IF NOT EXISTS courses (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  slug VARCHAR(150) NOT NULL UNIQUE,
  description TEXT NOT NULL,
  price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  duration VARCHAR(100) NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Students and their profile records
CREATE TABLE IF NOT EXISTS students (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  student_id VARCHAR(50) NOT NULL UNIQUE,
  full_name VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  phone VARCHAR(30) NOT NULL,
  address TEXT NOT NULL,
  gender ENUM('Male','Female','Other','Prefer not to say') NULL,
  passport_photo VARCHAR(255) NULL,
  status ENUM('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_students_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Registration records linking students to courses
CREATE TABLE IF NOT EXISTS registrations (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  student_id INT UNSIGNED NOT NULL,
  course_id INT UNSIGNED NOT NULL,
  status ENUM('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
  payment_status ENUM('Unpaid','Paid','Failed') NOT NULL DEFAULT 'Unpaid',
  payment_reference VARCHAR(120) NULL,
  applied_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_registrations_student FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_registrations_course FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE RESTRICT ON UPDATE CASCADE,
  INDEX idx_registrations_status (status),
  INDEX idx_registrations_payment_status (payment_status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Settings table for website content and Paystack API keys
CREATE TABLE IF NOT EXISTS settings (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  setting_key VARCHAR(100) NOT NULL UNIQUE,
  setting_value TEXT NOT NULL,
  description VARCHAR(255) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed initial courses
INSERT INTO courses (title, slug, description, price, duration) VALUES
  ('Computer Basics', 'computer-basics', 'Hands-on computer literacy training for beginners, covering Microsoft Office, internet usage, and foundational PC skills.', 25000.00, '6 weeks'),
  ('Full Stack Web Development', 'full-stack-web-development', 'Practical full stack development training including HTML, CSS, JavaScript, PHP, MySQL, and deployment essentials.', 50000.00, '12 weeks');

-- Seed default admin user with sample credentials
-- Username: admin
-- Password: Admin@1234
INSERT INTO users (username, email, password_hash, full_name, role) VALUES
  ('admin', 'admin@ibtsubhub.com', '$2y$12$yYRxZHIi0i/tuIZBDDkBLeinyqfFXRlumrvdRw9FuSXqw7Cdi7qla', 'Ibtsub Hub Admin', 'admin')
  ON DUPLICATE KEY UPDATE username=username;

-- Seed default settings for site content and payment integration
INSERT INTO settings (setting_key, setting_value, description) VALUES
  ('site_title', 'Ibtsub Hub', 'Site title shown in the header and page title'),
  ('site_tagline', 'Physical Technology Training Center', 'Short tagline for homepage display'),
  ('contact_email', 'info@ibtsubhub.com', 'Primary contact email for the training center'),
  ('paystack_public_key', '', 'Paystack public API key for payment integration'),
  ('paystack_secret_key', '', 'Paystack secret API key for payment integration')
  ON DUPLICATE KEY UPDATE setting_key=setting_key;
