-- Create the Database
CREATE DATABASE neighbourhood_watch;
USE neighbourhood_watch;

-- Users Table
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    date_of_birth DATE NOT NULL,
    phone_number VARCHAR(10) NOT NULL,
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100) NOT NULL,
    username VARCHAR(12) UNIQUE NOT NULL,
    password VARCHAR(8) NOT NULL
);

-- Login Table
CREATE TABLE login (
    -- login_id INT AUTO_INCREMENT PRIMARY KEY,  -- Added primary key
    user_id INT NOT NULL,
    username VARCHAR(10) NOT NULL,
    password VARCHAR(8) NOT NULL,
    login_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS incidents (
    incident_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    type VARCHAR(50) NOT NULL COMMENT 'Incident category like theft, vandalism etc.',
    description TEXT NOT NULL,
    address TEXT NOT NULL,
    latitude DECIMAL(10, 8) COMMENT 'GPS latitude coordinate',
    longitude DECIMAL(11, 8) COMMENT 'GPS longitude coordinate',
    evidence_path VARCHAR(255) COMMENT 'Path to uploaded evidence file',
    contact_info VARCHAR(255) COMMENT 'Reporter contact information',
    status VARCHAR(20) DEFAULT 'reported' COMMENT 'Incident status',
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add indexes for better performance
ALTER TABLE incidents ADD INDEX idx_status (status);
ALTER TABLE incidents ADD INDEX idx_type (type);
ALTER TABLE incidents ADD INDEX idx_timestamp (timestamp);