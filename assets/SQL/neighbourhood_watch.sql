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
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    type VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    address TEXT NOT NULL,
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    evidence_path VARCHAR(255),
    contact_info VARCHAR(255),
    incident_date DATE NOT NULL,
    incident_time TIME NOT NULL,
    status VARCHAR(50) DEFAULT 'reported',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);