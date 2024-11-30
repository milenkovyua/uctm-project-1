CREATE DATABASE IF NOT EXISTS bookings;
USE bookings;

CREATE TABLE IF NOT EXISTS rooms (
    room_id INT PRIMARY KEY AUTO_INCREMENT,
    room_number VARCHAR(10) UNIQUE NOT NULL,
    room_type ENUM('SINGLE', 'DOUBLE') NOT NULL,
    has_terrace BOOLEAN DEFAULT FALSE,
    has_bathtub BOOLEAN DEFAULT FALSE
);

CREATE TABLE IF NOT EXISTS visitors (
    visitor_id INT PRIMARY KEY AUTO_INCREMENT,
    full_name VARCHAR(100) NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS bookings (
    booking_id INT PRIMARY KEY AUTO_INCREMENT,
    room_id INT NOT NULL,
    visitor_id INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (room_id) REFERENCES rooms(room_id),
    FOREIGN KEY (visitor_id) REFERENCES visitors(visitor_id),
    CONSTRAINT check_dates CHECK (end_date >= start_date)
); 

 