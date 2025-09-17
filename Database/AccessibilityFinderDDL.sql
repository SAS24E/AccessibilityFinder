-- ======================
-- Users table
-- ======================
CREATE TABLE users (
    user_id SERIAL PRIMARY KEY,             -- auto-increment (use AUTO_INCREMENT in MySQL)
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,    -- store hashed passwords only
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ======================
-- Restaurants table
-- ======================
CREATE TABLE restaurants (
    restaurant_id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    location VARCHAR(255),                  -- city/address/etc.
    food_type VARCHAR(50),                  -- e.g., Italian, Mexican, etc.
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ======================
-- Posts table
-- ======================
CREATE TABLE posts (
    post_id SERIAL PRIMARY KEY,
    restaurant_id INT NOT NULL,
    user_id INT,                            -- NULL if guest
    opinion TEXT NOT NULL,
    assistance_friendly BOOLEAN NOT NULL,   -- true = yes, false = no
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_restaurant
        FOREIGN KEY (restaurant_id) REFERENCES restaurants(restaurant_id)
        ON DELETE CASCADE,

    CONSTRAINT fk_user
        FOREIGN KEY (user_id) REFERENCES users(user_id)
        ON DELETE SET NULL
);
