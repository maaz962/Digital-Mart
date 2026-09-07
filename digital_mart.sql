-- =========================================================
-- Digital Mart Complete Database (Auto Create + Auto Seed)
-- =========================================================

DROP DATABASE IF EXISTS digital_mart;
CREATE DATABASE digital_mart CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE digital_mart;

-- =====================
-- 1) ADMINS
-- =====================
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(120) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('main_admin','admin') DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Password hashes:
-- main admin password: admin123
-- second admin password: admin123
INSERT INTO admins (name, email, password, role) VALUES
('Main Admin', 'admin@digitalmart.com', '$2y$10$y3TaQfcn8dTzWr4P9JP8uuWiG5N6f4Mco0j8P2xDYwz2aPSvq84Ru', 'main_admin'),
('Support Admin', 'support@digitalmart.com', '$2y$10$y3TaQfcn8dTzWr4P9JP8uuWiG5N6f4Mco0j8P2xDYwz2aPSvq84Ru', 'admin');

-- =====================
-- 2) CATEGORIES
-- =====================
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO categories (name, description) VALUES
('Groceries', 'Daily grocery items including flour, rice, pulses and spices.'),
('Beverages', 'Tea, coffee, juices and soft drinks.'),
('Household', 'Cleaning and home care essentials.'),
('Personal Care', 'Shampoo, soap, toothpaste and related products.'),
('Snacks', 'Biscuits, chips and quick snacks.');

-- =====================
-- 3) PRODUCTS
-- =====================
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

INSERT INTO products (category_id, name, description, price, stock, image) VALUES
(1, 'Basmati Rice 1kg', 'Premium quality basmati rice.', 480.00, 60, 'assets/images/rice.jpg'),
(1, 'Wheat Flour 5kg', 'Fresh chakki atta for daily use.', 820.00, 40, 'assets/images/flour.jpg'),
(1, 'Cooking Oil 1L', 'Refined cooking oil bottle.', 620.00, 55, 'assets/images/oil.jpg'),

(2, 'Black Tea 200g', 'Strong taste tea leaves.', 390.00, 70, 'assets/images/tea.jpg'),
(2, 'Instant Coffee 100g', 'Rich aroma coffee jar.', 750.00, 30, 'assets/images/coffee.jpg'),
(2, 'Orange Juice 1L', 'Refreshing fruit drink.', 280.00, 45, 'assets/images/juice.jpg'),

(3, 'Dishwash Liquid 500ml', 'Removes grease effectively.', 260.00, 50, 'assets/images/dishwash.jpg'),
(3, 'Laundry Detergent 1kg', 'Powerful stain removal powder.', 540.00, 35, 'assets/images/detergent.jpg'),
(3, 'Floor Cleaner 1L', 'Pleasant fragrance floor cleaner.', 320.00, 28, 'assets/images/floor-cleaner.jpg'),

(4, 'Shampoo 180ml', 'Hair care shampoo for all types.', 420.00, 38, 'assets/images/shampoo.jpg'),
(4, 'Toothpaste 140g', 'Cavity protection toothpaste.', 210.00, 80, 'assets/images/toothpaste.jpg'),
(4, 'Bath Soap Pack', 'Moisturizing soap pack of 3.', 300.00, 65, 'assets/images/soap.jpg'),

(5, 'Potato Chips', 'Crispy salted chips pack.', 120.00, 100, 'assets/images/chips.jpg'),
(5, 'Chocolate Biscuits', 'Crunchy biscuits with chocolate.', 180.00, 90, 'assets/images/biscuits.jpg'),
(5, 'Namkeen Mix 250g', 'Spicy and crunchy mix.', 220.00, 75, 'assets/images/namkeen.jpg');

-- =====================
-- 4) ORDERS
-- =====================
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(120) NOT NULL,
    customer_email VARCHAR(120) NOT NULL,
    phone VARCHAR(20),
    address TEXT NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    shipping DECIMAL(10,2) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    payment_method ENUM('cash_on_delivery','card','bank_transfer') DEFAULT 'cash_on_delivery',
    order_status ENUM('pending','confirmed','shipped','delivered') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Optional sample order
INSERT INTO orders (customer_name, customer_email, phone, address, subtotal, shipping, total, payment_method, order_status)
VALUES
('Ali Raza', 'ali@example.com', '03001234567', 'House 10, Street 5, Lahore', 1080.00, 200.00, 1280.00, 'cash_on_delivery', 'confirmed');

-- =====================
-- 5) ORDER ITEMS
-- =====================
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    line_total DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
);

INSERT INTO order_items (order_id, product_id, quantity, unit_price, line_total) VALUES
(1, 1, 1, 480.00, 480.00),
(1, 5, 1, 750.00, 750.00);

-- =====================
-- 6) FEEDBACK
-- =====================
CREATE TABLE feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(120),
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO feedback (customer_name, customer_email, message) VALUES
('Sara Khan', 'sara@example.com', 'Great service and timely delivery.'),
('Usman', 'usman@example.com', 'Product quality was good, keep it up.');

-- =====================
-- Helpful indexes
-- =====================
CREATE INDEX idx_products_category ON products(category_id);
CREATE INDEX idx_order_items_order ON order_items(order_id);
CREATE INDEX idx_order_items_product ON order_items(product_id);