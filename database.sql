-- Users Table (Handles Donors and Admins)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role ENUM('admin', 'donor', 'hospital') DEFAULT 'donor',
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    blood_group VARCHAR(5),
    age INT,
    weight DECIMAL(5,2),
    is_eligible BOOLEAN DEFAULT FALSE,
    security_question VARCHAR(150),
    security_answer VARCHAR(255)
);

-- Inventory Table
CREATE TABLE inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    blood_group VARCHAR(5) NOT NULL,
    quantity INT DEFAULT 0,
    expiry_date DATE NOT NULL
);

-- Hospital Requests Table
CREATE TABLE hospital_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hospital_name VARCHAR(150) NOT NULL,
    blood_group VARCHAR(5) NOT NULL,
    units_requested INT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);