-- Creating the users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    reset_token_hash VARCHAR(255),
    reset_token_expires_at TIMESTAMP
);

-- Creating the password reset table
CREATE TABLE IF NOT EXISTS password_reset_request (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    reset_token VARCHAR(255) NOT NULL,
    expiration_time TIMESTAMP NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Creating the images table
CREATE TABLE IF NOT EXISTS images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    image_filename VARCHAR(255) NOT NULL,
    image_alt VARCHAR(255),
    description TEXT,
    category_name VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Creating the photo_categories table
CREATE TABLE IF NOT EXISTS photo_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

-- Query to fetch top uploaders
SELECT i.user_id, COUNT(*) AS upload_count 
FROM images i 
INNER JOIN users u ON i.user_id = u.id 
GROUP BY i.user_id 
ORDER BY upload_count DESC 
LIMIT 5;

-- Adding user_id column to the images table if not exists
ALTER TABLE images
ADD COLUMN IF NOT EXISTS user_id INT,
ADD FOREIGN KEY IF NOT EXISTS (user_id) REFERENCES users(id);
