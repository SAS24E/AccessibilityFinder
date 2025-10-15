
INSERT INTO users (name, email, password)
VALUES ('test', 'test@example.com', 'test');

INSERT INTO posts (location_id, location_name, user_id, opinion, assistance_friendly, image)
VALUES
(1, 'Sunshine Mall', 1, 'Great accessibility! Ramps and elevators are well placed.', 'yes', 'mall.jpg'),
(2, 'Central Park', 1, 'Most paths are accessible, but restrooms need improvement.', 'no', 'park.jpg'),
(3, 'Downtown Center', 1, 'Excellent wheelchair access and friendly staff.', 'yes', 'library.jpg');
