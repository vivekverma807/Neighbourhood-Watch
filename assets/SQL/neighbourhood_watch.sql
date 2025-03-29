-- Create the Database
CREATE DATABASE neighbourhood_watch;
USE neighbourhood_watch;

-- Users Table
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,  -- Fixed column name
    fullname VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    username VARCHAR(10) UNIQUE NOT NULL,
    password VARCHAR(8) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    address TEXT NOT NULL
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