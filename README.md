# Accessibility Finder

A crowdsourced web application that helps users discover, rate, and share accessibility information for real-world locations.

## Team: Bit by Bit
- Jose Solano – jls23k@fsu.edu
- Eliah Hooks – enh22a@fsu.edu
- Alex Secor – sas24e@fsu.edu
- Maria Vegas – mvv22a@fsu.edu
- Daniela Nunez – dn23e@fsu.edu

Website: https://accessiblityfinder.xo.je

## Overview
Accessibility Finder provides a centralized place where users can view accessibility ratings for locations and contribute their own experiences. The goal of the project is to improve the quality of life for individuals with disabilities by making accessibility information easy to find and community-driven.

## Technologies Used
**Frontend**
- HTML, CSS, JavaScript
- Bootstrap 5
- MapLibre GL JS
- Nominatim API (OpenStreetMap)

**Backend**
- PHP 8
- PDO (secure database access)
- Session-based authentication
- bcrypt password hashing

**Database**
- MySQL (InfinityFree hosting)
- Foreign keys, cascade deletes
- Tables for users, posts, comments, and reviews

## Features
- Interactive map with accessibility markers
- Location search using Nominatim API
- User authentication (register/login)
- Create and review locations
- Comment and voting features
- Mobile-friendly interface (in progress)

## Requirements
- PHP 8+
- MySQL 5.7+
- Apache or Nginx server
- PHP extensions: pdo_mysql, openssl, session
- XAMPP or similar for local testing

## Installation
1. Clone the repository:
   git clone https://github.com/SAS24E/AccessibilityFinder.git

2. Import the database:
   - Create a MySQL database
   - Import the SQL file from the /Database folder

3. Configure the database connection:
   Edit application/config/Database.php with your credentials.

4. Run locally:
   Place the project inside /xampp/htdocs/
   Visit: http://localhost/AccessibilityFinder/public/index.php

## Roadmap
- Add profile pictures
- Add image uploads to posts
- Improve mobile UI
- Add AI-assisted review scoring
- Clean URL routing (MVC)

## Contributors
Developed by the Bit by Bit team at Florida State University.

