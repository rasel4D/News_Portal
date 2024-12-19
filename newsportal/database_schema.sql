DROP DATABASE IF EXISTS newsportal;
CREATE DATABASE newsportal;
USE newsportal;

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'subadmin') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    is_deleted BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE subcategories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    is_deleted BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE posts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    content TEXT NOT NULL,
    category_id INT,
    subcategory_id INT,
    author_id INT,
    image VARCHAR(255) COMMENT 'Relative path to image file',
    is_deleted BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id),
    FOREIGN KEY (subcategory_id) REFERENCES subcategories(id),
    FOREIGN KEY (author_id) REFERENCES users(id)
);

CREATE TABLE comments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    post_id INT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    comment TEXT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id)
);

CREATE TABLE pages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    content TEXT,
    status ENUM('draft', 'published') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE contact_messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('new', 'read', 'replied') DEFAULT 'new'
);

-- Insert admin users
INSERT INTO users (username, email, password, role) VALUES
('admin', 'admin@newsportal.com', 'admin1234', 'admin'),
('subadmin', 'subadmin@newsportal.com', 'sub12345', 'subadmin');

-- Insert categories
INSERT INTO categories (name, slug) VALUES
('প্রযুক্তি', 'প্রযুক্তি'),
('খেলাধুলা', 'খেলাধুলা'),
('বিনোদন', 'বিনোদন'),
('ব্যবসা', 'ব্যবসা'),
('রাজনীতি', 'রাজনীতি');

-- Insert subcategories
INSERT INTO subcategories (category_id, name, slug) VALUES
(1, 'কৃত্রিম বুদ্ধিমত্তা', 'কৃত্রিম বুদ্ধিমত্তা'),
(1, 'সফটওয়্যার', 'সফটওয়্যার'),
(2, 'ফুটবল', 'ফুটবল'),
(2, 'ক্রিকেট', 'ক্রিকেট'),
(3, 'চলচ্চিত্র', 'চলচ্চিত্র'),
(3, 'সঙ্গীত', 'সঙ্গীত'),
(4, 'ফাইন্যান্স', 'ফাইন্যান্স'),
(4, 'স্টার্টআপ', 'স্টার্টআপ'),
(5, 'বিএনপি', 'বিএনপি'),
(5, 'আওয়ামীলীগ', 'আওয়ামীলীগ');





-- Insert pages
INSERT INTO pages (title, slug, content) VALUES
('About Us', 'about-us', 'Welcome to our news portal. We are dedicated to bringing you the latest news...'),
('Privacy Policy', 'privacy-policy', 'This privacy policy sets out how we use and protect any information...'),
('Contact Us', 'contact-us', 'Get in touch with us through the following channels...');

-- Insert contact messages
INSERT INTO contact_messages (name, email, message, status) VALUES
('Robert Brown', 'robert@example.com', 'I would like to advertise on your platform.', 'new'),
('Lisa Anderson', 'lisa@example.com', 'Great work with the website!', 'read'),
('Tom Wilson', 'tom@example.com', 'Please update my subscription details.', 'replied');

-- Insert default about page content
INSERT INTO pages (title, slug, content, status) VALUES (
    'About Us',
    'about',
    '<h2>Welcome to Our News Portal</h2>
    <p>We are dedicated to bringing you the latest and most accurate news from around the world. Our team of experienced journalists works tirelessly to ensure that you stay informed about current events, breaking news, and important stories that matter to you.</p>
    <p>Our mission is to provide unbiased, factual reporting that helps our readers make informed decisions and stay connected with their world. We cover a wide range of topics including politics, business, technology, sports, entertainment, and more.</p>
    <p>Thank you for choosing us as your trusted source for news and information.</p>',
    'published'
);

-- Update or insert contact page content
INSERT INTO pages (title, slug, content, status) VALUES (
    'Contact Us',
    'contact',
    '<h2>Contact Us</h2>
    <p>We value your feedback and inquiries. Please feel free to reach out to us:</p>
    <div class="contact-info">
        <p><strong>Email:</strong> ques@newsportal.com</p>
    </div>
    <p>You can also use the contact form below to send us a message directly.</p>',
    'published'
) ON DUPLICATE KEY UPDATE 
    content = VALUES(content),
    status = VALUES(status); 