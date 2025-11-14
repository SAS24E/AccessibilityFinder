CREATE DATABASE IF NOT EXISTS accessibility_finder;
USE accessibility_finder;

-- Drop tables in dependency order
DROP TABLE IF EXISTS posts;
DROP TABLE IF EXISTS locations;
DROP TABLE IF EXISTS users;

-- Users table with admin + flag support
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    nick_name VARCHAR(50) NULL DEFAULT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    biography TEXT,
    profile_image VARCHAR(255) DEFAULT 'default.png',
    is_admin TINYINT(1) NOT NULL DEFAULT 0,
    is_flagged TINYINT(1) NOT NULL DEFAULT 0
);

-- Locations table (matches your DML)
CREATE TABLE locations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    latitude DOUBLE NOT NULL,
    longitude DOUBLE NOT NULL,
    address VARCHAR(255),
    nominatim_place_id VARCHAR(100),
    osm_type VARCHAR(50),
    osm_id BIGINT
);

CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    location_id INT NOT NULL,
    location_name VARCHAR(200) NOT NULL,
    user_id INT NOT NULL,
    opinion TEXT,
    assistance_friendly ENUM('yes', 'no') NOT NULL,
    image VARCHAR(255),
    is_flagged TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (location_id) REFERENCES locations(id) ON DELETE CASCADE
);
