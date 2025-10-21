USE accessibility_finder;

INSERT INTO users (name, nick_name, email, password)
VALUES
('Jose Solano', 'jlsol', 'jlsol@example.com', 'password123'),
('Maria Perez', 'mariap', 'mariap@example.com', 'securepass456'),
('John Doe', 'jdoe', 'john.doe@example.com', 'pass789'),
('Emily Carter', 'emc', 'emily.carter@example.com', 'mysecret'),
('David Lee', 'dlee', 'david.lee@example.com', 'hashmeplease');

INSERT INTO locations (name, latitude, longitude, address)
VALUES
('The Green Fork', 30.3322, -81.6557, '123 Main St, Jacksonville, FL'),
('Ocean Breeze Diner', 30.2950, -81.3917, '45 Beach Ave, Jacksonville Beach, FL'),
('Downtown Cafe', 30.3200, -81.6600, '99 Central Blvd, Jacksonville, FL'),
('Golden Spoon', 30.3450, -81.6200, '210 Riverside Dr, Jacksonville, FL'),
('Riverside Eatery', 30.3215, -81.6802, '56 River Rd, Jacksonville, FL');

INSERT INTO posts (location_id, location_name, user_id, opinion, assistance_friendly, image)
VALUES
(1, 'The Green Fork', 1, 'Amazing experience! Wheelchair accessible and staff was super kind.', 'yes', 'greenfork.jpg'),
(2, 'Ocean Breeze Diner', 2, 'Beautiful view, but limited accessible seating.', 'no', 'oceanbreeze.jpg'),
(3, 'Downtown Cafe', 3, 'Very friendly staff, accessible bathrooms, and wide aisles.', 'yes', 'downtowncafe.png'),
(4, 'Golden Spoon', 4, 'Nice ambiance but no ramp at the entrance.', 'no', 'goldenspoon.jpg'),
(5, 'Riverside Eatery', 5, 'Perfect place! Parking and entrances are accessible.', 'yes', 'riversideeatery.jpg'),
(1, 'The Green Fork', 2, 'Food was great, and they had Braille menus!', 'yes', 'braillemenu.jpg'),
(2, 'Ocean Breeze Diner', 3, 'Accessible restrooms, but crowded during weekends.', 'yes', NULL);

