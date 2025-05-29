# Event Registration Management System (DBMS Mini Project)

## Overview
This is a PHP-MySQL based web application that allows users to:
- Register and log in
- View available events
- Register for events
- View their registered events

Admins can:
- Add new events
- Edit existing events
- Delete events

## Technologies Used
- PHP (Backend)
- MySQL (Database)
- XAMPP (Local Server)

## Database Tables
1. *users*
   - user_id (INT, PRIMARY KEY, AUTO_INCREMENT)
   - name (VARCHAR)
   - email (VARCHAR, UNIQUE)
   - password (VARCHAR)

2. *events*
   - event_id (INT, PRIMARY KEY, AUTO_INCREMENT)
   - title (VARCHAR)
   - description (TEXT)
   - event_date (DATE)
   - location (VARCHAR)

3. *registrations*
   - id (INT, PRIMARY KEY, AUTO_INCREMENT)
   - user_id (INT, FOREIGN KEY)
   - event_id (INT, FOREIGN KEY)

## How to Run
1. Install and run XAMPP
2. Place the event_system folder in htdocs
3. Create the database event_system and import the tables
4. Start Apache and MySQL in XAMPP
5. Visit http://localhost/event_system/index.php in browser

## Admin Login
- You can add an admin manually in the users table using hashed password:
  Use PHP password_hash('your_password', PASSWORD_DEFAULT);

## Developed By
- ABC
- Course: DBMS Mini Project