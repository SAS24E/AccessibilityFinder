USE accessibility_finder;


INSERT INTO users (name, nick_name, email, password, biography)
VALUES 
('Alice Johnson', 'alicej', 'alice@example.com', 'password123', 'Accessibility advocate and food lover.'),
('Brian Smith', 'brianS', 'brian@example.com', 'securepass', 'Wheelchair user passionate about accessible dining.'),
('Carla Reyes', 'carlaR', 'carla@example.com', 'mypassword', 'Traveler exploring inclusive restaurants.');

INSERT INTO locations (name, latitude, longitude, address, nominatim_place_id, osm_type, osm_id)
VALUES
('Sunny Diner', 40.712776, -74.005974, '123 Main St, New York, NY', 'place_12345', 'node', 987654321),
('Blue Lagoon Cafe', 34.052235, -118.243683, '456 Ocean Ave, Los Angeles, CA', 'place_67890', 'way', 123456789),
('Green Garden Bistro', 41.878113, -87.629799, '789 Park Blvd, Chicago, IL', 'place_54321', 'relation', 192837465);


INSERT INTO posts (location_id, location_name, user_id, opinion, assistance_friendly, image)
VALUES
(1, 'Sunny Diner', 1, 'The entrance ramp was smooth and easy to navigate. Staff were helpful!', 'yes', 'sunny_diner.jpg'),
(2, 'Blue Lagoon Cafe', 2, 'Great food but the restroom was not wheelchair accessible.', 'no', 'blue_lagoon.jpg'),
(3, 'Green Garden Bistro', 3, 'Wide seating areas and accessible restrooms. Highly recommended!', 'yes', 'green_garden.jpg');


