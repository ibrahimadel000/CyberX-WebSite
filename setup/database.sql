-- CyberX Database Schema
-- Run this script in phpMyAdmin or MySQL CLI

CREATE DATABASE IF NOT EXISTS cyberx_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cyberx_db;

-- Admins Table
CREATE TABLE IF NOT EXISTS admins (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Courses Table
CREATE TABLE IF NOT EXISTS courses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    short_description VARCHAR(500),
    type ENUM('online', 'offline') NOT NULL DEFAULT 'online',
    category VARCHAR(100),
    price DECIMAL(10, 2) DEFAULT 0.00,
    duration VARCHAR(50),
    image VARCHAR(255),
    instructor VARCHAR(255),
    students_enrolled INT DEFAULT 0,
    rating DECIMAL(2, 1) DEFAULT 4.5,
    status ENUM('active', 'inactive') DEFAULT 'active',
    featured TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Students Table (registered users)
CREATE TABLE IF NOT EXISTS students (
    id INT PRIMARY KEY AUTO_INCREMENT,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(20),
    password VARCHAR(255) NOT NULL,
    profile_image VARCHAR(255),
    email_verified TINYINT(1) DEFAULT 0,
    verification_token VARCHAR(64),
    reset_token VARCHAR(64),
    reset_expires DATETIME,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Enrollments Table
CREATE TABLE IF NOT EXISTS enrollments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    profile_image VARCHAR(255),
    course_id INT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE SET NULL,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS software_solutions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    short_description VARCHAR(500),
    image VARCHAR(255),
    icon VARCHAR(100) DEFAULT 'fa-shield-alt',
    technologies TEXT,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Services Table (4 Service Categories)
CREATE TABLE IF NOT EXISTS services (
    id INT PRIMARY KEY AUTO_INCREMENT,
    category_key VARCHAR(50) NOT NULL UNIQUE,
    title VARCHAR(255) NOT NULL,
    subtitle VARCHAR(255),
    icon VARCHAR(100) DEFAULT 'fa-cogs',
    gradient VARCHAR(100),
    color VARCHAR(50),
    description TEXT,
    features JSON,
    technologies JSON,
    status ENUM('active', 'inactive') DEFAULT 'active',
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Messages Table
CREATE TABLE IF NOT EXISTS messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NULL,
    phone VARCHAR(20),
    message TEXT NOT NULL,
    solution_id INT,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (solution_id) REFERENCES software_solutions(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Course Lessons Table (for offline courses)
CREATE TABLE IF NOT EXISTS course_lessons (
    id INT PRIMARY KEY AUTO_INCREMENT,
    course_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    video_file VARCHAR(255) NOT NULL,
    duration VARCHAR(50),
    sort_order INT DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Remember Me Tokens Table
CREATE TABLE IF NOT EXISTS remember_tokens (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_type ENUM('student', 'admin') NOT NULL,
    user_id INT NOT NULL,
    token_hash VARCHAR(255) NOT NULL,
    expires_at DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user (user_type, user_id),
    INDEX idx_token (token_hash)
) ENGINE=InnoDB;

-- Insert Default Admin (Password: admin123)
INSERT INTO admins (email, password, name) VALUES 
('admin@cyberx.com', '$2y$10$EIuFh.ke3Ox/bRkz7yH8xOeZGh7yJkMjC6pZmQnXrBj5oKJqnXnHa', 'Admin User');

-- Insert Sample Courses
INSERT INTO courses (title, description, short_description, type, category, price, duration, image, instructor, students_enrolled, rating, featured) VALUES
('Certified Ethical Hacker (CEH)', 'Comprehensive training on ethical hacking techniques, penetration testing methodologies, and security assessment. Learn to think like a hacker to protect systems effectively.', 'Master ethical hacking techniques and penetration testing to protect systems from cyber threats.', 'online', 'Ethical Hacking', 499.00, '60 Hours', 'course-ceh.jpg', 'John Doe', 1245, 4.8, 1),
('Advanced Network Security', 'Deep dive into network security protocols, firewall configuration, intrusion detection systems, and secure network architecture design.', 'Learn advanced network defense strategies and secure infrastructure design.', 'online', 'Network Security', 399.00, '45 Hours', 'course-network.jpg', 'Jane Smith', 876, 4.7, 1),
('Cyber Threat Intelligence', 'Learn to identify, analyze, and respond to cyber threats. Understand threat actors, their motivations, and how to build effective threat intelligence programs.', 'Analyze and respond to cyber threats with professional intelligence techniques.', 'online', 'Threat Intelligence', 349.00, '40 Hours', 'course-threat.jpg', 'Mike Johnson', 654, 4.6, 1),
('SOC Analyst Certification', 'Complete training for Security Operations Center analysts. Learn SIEM tools, log analysis, incident detection, and response procedures.', 'Become a certified SOC analyst with hands-on SIEM and incident response training.', 'offline', 'SOC Analyst', 599.00, '80 Hours', 'course-soc.jpg', 'Sarah Williams', 432, 4.9, 1),
('Digital Forensics & Incident Response', 'Master digital forensics techniques, evidence collection, chain of custody, and incident response procedures for cybercrime investigations.', 'Learn forensic investigation techniques and incident response procedures.', 'offline', 'DFIR', 549.00, '70 Hours', 'course-dfir.jpg', 'David Brown', 321, 4.7, 1),
('Cloud Security Fundamentals', 'Secure cloud environments across AWS, Azure, and GCP. Learn cloud security architecture, identity management, and compliance frameworks.', 'Master cloud security across major platforms with hands-on labs.', 'online', 'Cloud Security', 449.00, '50 Hours', 'course-cloud.jpg', 'Emily Davis', 567, 4.5, 0),
('Malware Analysis & Reverse Engineering', 'Advanced course on analyzing malicious software, reverse engineering techniques, and developing malware detection strategies.', 'Analyze malware and develop detection strategies through reverse engineering.', 'offline', 'Malware Analysis', 649.00, '65 Hours', 'course-malware.jpg', 'Robert Wilson', 234, 4.8, 0),
('Web Application Security', 'Comprehensive training on web application vulnerabilities, OWASP Top 10, secure coding practices, and web penetration testing.', 'Master web security testing and secure development practices.', 'online', 'Web Security', 379.00, '55 Hours', 'course-webapp.jpg', 'Lisa Anderson', 789, 4.6, 0);

-- Insert Sample Software Solutions
INSERT INTO software_solutions (title, description, short_description, icon, technologies, status) VALUES
('Penetration Testing', 'Comprehensive penetration testing services to identify vulnerabilities in your systems before attackers do. Our expert team simulates real-world attacks to assess your security posture.', 'Proactively identify vulnerabilities in your systems and applications before attackers do.', 'fa-user-secret', '["Python", "Kali Linux", "Metasploit", "Burp Suite"]', 'active'),
('Security Audit', 'Thorough security audits to evaluate your organization''s security controls, policies, and compliance with industry standards and regulations.', 'Comprehensive assessment of your security posture, compliance, and risk management practices.', 'fa-clipboard-check', '["Nessus", "OpenVAS", "CIS Benchmarks", "Wireshark"]', 'active'),
('Custom Software Development', 'Secure software development services tailored to your business needs. We build applications with security integrated from the ground up.', 'Build secure, scalable software solutions tailored to your unique business needs.', 'fa-code', '["Java", "Node.js", "React", "Docker", "Kubernetes"]', 'active'),
('SOC Implementation', 'End-to-end Security Operations Center setup and implementation. We help you build a 24/7 monitoring capability to detect and respond to threats.', 'Establish a 24/7 Security Operations Center to monitor, detect, and respond to threats in real-time.', 'fa-desktop', '["SIEM", "Splunk", "Elastic Stack", "Threat Intelligence"]', 'active'),
('Incident Response', 'Rapid incident response services to contain, investigate, and recover from security breaches. Our team is available 24/7 for emergency response.', 'Rapid, effective response and recovery services to minimize damage and downtime during a security incident.', 'fa-fire-extinguisher', '["Forensic Tools", "EDR", "Malware Analysis", "Playbooks"]', 'active'),
('Training Programs', 'Customized cybersecurity training programs for your team. From awareness training to advanced technical skills, we help build your security culture.', 'Empower your team with the knowledge and skills to recognize and prevent cyber threats.', 'fa-graduation-cap', '["Phishing Simulations", "Awareness Platforms", "LMS", "Workshops"]', 'active');
