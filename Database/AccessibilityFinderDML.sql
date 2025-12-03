USE accessibility_finder;

DELETE FROM posts;
DELETE FROM locations;
DELETE FROM users;

INSERT INTO users (name, nick_name, email, password, biography, is_admin, is_flagged)
VALUES 
('Admin User', 'admin', 'admin@example.com',
 '$2b$12$MAflr.gxHUayDnmHOymiDOX0JmEitAprAWb4glkZqln32mxKnToNm',
 'Default administrator account.', 1, 0)

