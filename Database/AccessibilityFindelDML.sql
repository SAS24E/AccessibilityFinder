-- Sample data for restaurants
INSERT INTO restaurants (name, location, food_type) VALUES
('Pasta Palace', 'New York, NY', 'Italian'),
('Dragon Wok', 'San Francisco, CA', 'Chinese'),
('Taco Haven', 'Austin, TX', 'Mexican');

-- Sample data for users
INSERT INTO users (username, password_hash, email) VALUES
('user1', 'hashed_password_1', 'user1@example.com'),
('user2', 'hashed_password_2', 'user2@example.com');

-- Sample data for posts
INSERT INTO posts (restaurant_id, user_id, opinion, assistance_friendly, image_url) VALUES
(1, 1, 'Great place with excellent wheelchair access and helpful staff!', TRUE, 'https://example.com/image1.jpg'),
(1, NULL, 'Not very friendly for assistance needs - narrow aisles.', FALSE, NULL),
(2, 2, 'Very accommodating for visual impairments, braille menus available.', TRUE, 'https://example.com/image2.jpg'),
(3, NULL, 'Loud environment, not ideal for hearing assistance.', FALSE, 'https://example.com/image3.jpg'),
(3, 1, 'Friendly staff, good ramps and seating options.', TRUE, NULL);