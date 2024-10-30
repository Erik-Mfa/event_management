INSTALL MYSQL SERVER AND MYSQL WORKBENCH

CREATE CONNECT TO YOUR SERVER AND CREATE AN "event_management" DATABASE

CREATE THE FOLLOWING TABLES:

CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    message TEXT NOT NULL,
    user_id VARCHAR(20),
    FOREIGN KEY (user_id) REFERENCES users(UserID)
);

CREATE TABLE users (
    UserId INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,  -- Ensure the password is hashed before storing
    email VARCHAR(100) UNIQUE,        -- Optional, for user email
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


#INSTALL:
-PHP composer <8.0
-PHP 8

cd "event_management"

composer install

cd "./public"
php -S localhost:8000 
