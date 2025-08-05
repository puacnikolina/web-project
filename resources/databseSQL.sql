
CREATE DATABASE museum;
USE museum;


CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user'
);


CREATE TABLE artists (
	   id INT AUTO_INCREMENT PRIMARY KEY,
	   name VARCHAR(255) NOT NULL,
	   birth_year INT,
	   death_year INT,
	   nationality VARCHAR(100),
	   biography TEXT,
	   profile_image VARCHAR(255)
);

CREATE TABLE exhibitions (
	   id INT AUTO_INCREMENT PRIMARY KEY,
	   title VARCHAR(255) NOT NULL,
	   description TEXT,
	   start_date DATE,
	   end_date DATE,
	   hero_image VARCHAR(255) NOT NULL,
	   gallery_image VARCHAR(255) NOT NULL,
	   is_active TINYINT(1) DEFAULT 0,
	   artist_id INT,
	   FOREIGN KEY (artist_id) REFERENCES artists(id) ON DELETE SET NULL
);


CREATE TABLE artwork (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    artist_id INT,
    sold TINYINT(1) DEFAULT 0,
    FOREIGN KEY (artist_id) REFERENCES artists(id) ON DELETE SET NULL
);



CREATE TABLE ticket_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(50) NOT NULL,
    price DECIMAL(10,2) NOT NULL
);


CREATE TABLE tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    exhibition_id INT NOT NULL,
    category_id INT NOT NULL,
    quantity INT NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    reservation_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (exhibition_id) REFERENCES exhibitions(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES ticket_categories(id) ON DELETE CASCADE
);


CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending',
    total_amount DECIMAL(10,2),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    artwork_id INT NOT NULL,
    quantity INT NOT NULL,
    price_per_item DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (artwork_id) REFERENCES artwork(id) ON DELETE CASCADE
);
