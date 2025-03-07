
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
ALTER TABLE users ADD COLUMN address VARCHAR(255) NOT NULL;
ALTER TABLE users ADD COLUMN full_name VARCHAR(255) NOT NULL;




CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    mobile_no VARCHAR(15) NOT NULL,
    address TEXT NOT NULL,
    business_type VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);



CREATE TABLE Dashboard (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'employee') NOT NULL,
    profile_pic VARCHAR(255) DEFAULT 'default.jpg'
);



CREATE TABLE sellers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(15) NOT NULL,
    store_name VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);





/* Entertainment Database */
CREATE TABLE entertainment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    category ENUM('Movie', 'Music', 'Game', 'Live Event') NOT NULL,
    description TEXT,
    media_url VARCHAR(255) NOT NULL,
    thumbnail_url VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    likes INT DEFAULT 0,
    views INT DEFAULT 0
);

ALTER TABLE entertainment ADD COLUMN embed_url VARCHAR(255);
ALTER TABLE entertainment ADD COLUMN type ENUM('Movie', 'Live Event', 'Game');


CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    entertainment_id INT,
    user_id INT,
    rating INT CHECK (rating BETWEEN 1 AND 5),
    review TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (entertainment_id) REFERENCES entertainment(id)
);



ALTER TABLE users ADD profile_pic VARCHAR(255) DEFAULT 'assets/default.jpg';


ALTER TABLE admins ADD profile_pic VARCHAR(255) DEFAULT 'assets/default.jpg';


CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

ALTER TABLE orders ADD COLUMN product_id INT;
ALTER TABLE orders ADD COLUMN quantity INT NOT NULL DEFAULT 1;
ALTER TABLE orders ADD COLUMN total_price DECIMAL(10,2) NOT NULL DEFAULT 0.00;
ALTER TABLE orders ADD COLUMN status ENUM('Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled') NOT NULL DEFAULT 'Pending';




CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    description TEXT NOT NULL,
    image VARCHAR(255) NOT NULL
);


INSERT INTO products (name, description, price, image) VALUES
('Laptop', 'High-performance laptop', 45000, 'laptop.jpg');

ALTER TABLE products ADD COLUMN category VARCHAR(255) NOT NULL;
ALTER TABLE products ADD COLUMN stock INT NOT NULL DEFAULT 0;


CREATE TABLE queries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE queries ADD COLUMN response TEXT;



CREATE TABLE user_queries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    query TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    status ENUM('unread', 'read') DEFAULT 'unread',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


CREATE TABLE query_responses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    query_id INT NOT NULL,
    response TEXT NOT NULL,
    admin_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);



CREATE TABLE business_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    business_id VARCHAR(20) UNIQUE NOT NULL,
    user_id INT NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

ALTER TABLE business_users ADD COLUMN status VARCHAR(20) DEFAULT 'pending';


CREATE TABLE businesses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    business_name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE businesses ADD COLUMN status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending';
ALTER TABLE businesses 
ADD COLUMN business_id VARCHAR(20) UNIQUE NOT NULL AFTER id,
ADD COLUMN owner_id INT NOT NULL AFTER business_id,
ADD COLUMN password VARCHAR(255) NOT NULL AFTER email;



CREATE TABLE business_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    business_name VARCHAR(255) NOT NULL,
    owner_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone_number VARCHAR(15) NOT NULL UNIQUE,
    business_address TEXT NOT NULL,
    otp_code VARCHAR(10), -- Store the OTP for verification
    otp_verified BOOLEAN DEFAULT FALSE, -- To track OTP verification status
    request_status ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending', -- Request processing status
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE pending_businesses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    business_name VARCHAR(255) NOT NULL,
    owner_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    business_type VARCHAR(100) NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'
);

CREATE TABLE approved_businesses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    business_name VARCHAR(255) NOT NULL,
    owner_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    business_type VARCHAR(100) NOT NULL
);

ALTER TABLE approved_businesses ADD COLUMN business_id VARCHAR(50) NOT NULL UNIQUE;
ALTER TABLE approved_businesses ADD COLUMN password VARCHAR(255) NOT NULL;
ALTER TABLE approved_businesses ADD COLUMN dashboard_url VARCHAR(255) NOT NULL;



CREATE TABLE seller_products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    seller_id INT NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    category VARCHAR(100) NOT NULL,
    image VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (seller_id) REFERENCES sellers(id) ON DELETE CASCADE
);


ALTER TABLE seller_products ADD COLUMN description TEXT;
