-- =======================================================
-- EASYCART - SCHEMA SETUP SCRIPT (PostgreSQL)
-- =======================================================


-- =======================================================
-- DROP TABLES (reverse dependency order)
-- =======================================================
DROP TABLE IF EXISTS sales_order_item;
DROP TABLE IF EXISTS sales_order;
DROP TABLE IF EXISTS sales_cart_address;
DROP TABLE IF EXISTS sales_cart_payment;
DROP TABLE IF EXISTS sales_cart_shipping;
DROP TABLE IF EXISTS sales_cart_item;
DROP TABLE IF EXISTS sales_cart;
DROP TABLE IF EXISTS catalog_category_product;
DROP TABLE IF EXISTS catalog_product_attribute;
DROP TABLE IF EXISTS catalog_product_entity;
DROP TABLE IF EXISTS catalog_category_attribute;
DROP TABLE IF EXISTS catalog_category_entity;
DROP TABLE IF EXISTS customer_entity;


-- =======================================================
-- 1. CATALOG: CATEGORIES
-- -------------------------------------------------------

-- Entity: Main Category Info
CREATE TABLE catalog_category_entity (
    entity_id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Attribute: Variable data (e.g., icon, description)
CREATE TABLE catalog_category_attribute (
    attribute_id SERIAL PRIMARY KEY,
    category_id INT NOT NULL REFERENCES catalog_category_entity(entity_id) ON DELETE CASCADE,
    attribute_code VARCHAR(50) NOT NULL,
    value TEXT,
    UNIQUE(category_id, attribute_code)
);


-- =======================================================
-- 2. CATALOG: PRODUCTS
-- -------------------------------------------------------

-- Entity: Main Product Info
CREATE TABLE catalog_product_entity (
    entity_id SERIAL PRIMARY KEY,
    sku VARCHAR(64) UNIQUE,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(12, 4) NOT NULL DEFAULT 0.0000,
    brand VARCHAR(100),
    shipping_type VARCHAR(50),
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Attribute: Product Attributes (description, gallery_item, feature, etc.)
CREATE TABLE catalog_product_attribute (
    attribute_id SERIAL PRIMARY KEY,
    product_id INT NOT NULL REFERENCES catalog_product_entity(entity_id) ON DELETE CASCADE,
    attribute_code VARCHAR(50) NOT NULL,
    value TEXT
);


-- =======================================================
-- 3. LINKING: PRODUCTS TO CATEGORIES
-- -------------------------------------------------------
CREATE TABLE catalog_category_product (
    category_id INT NOT NULL REFERENCES catalog_category_entity(entity_id) ON DELETE CASCADE,
    product_id INT NOT NULL REFERENCES catalog_product_entity(entity_id) ON DELETE CASCADE,
    PRIMARY KEY (category_id, product_id)
);


-- =======================================================
-- 4. CUSTOMER
-- -------------------------------------------------------
CREATE TABLE customer_entity (
    entity_id SERIAL PRIMARY KEY,
    firstname VARCHAR(100) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- =======================================================
-- 5. SALES: QUOTE / CART
-- -------------------------------------------------------

-- Main Cart Table
CREATE TABLE sales_cart (
    entity_id SERIAL PRIMARY KEY,
    session_id VARCHAR(255) UNIQUE NOT NULL,
    customer_id INT REFERENCES customer_entity(entity_id) ON DELETE SET NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Cart Items
CREATE TABLE sales_cart_item (
    item_id SERIAL PRIMARY KEY,
    cart_id INT NOT NULL REFERENCES sales_cart(entity_id) ON DELETE CASCADE,
    product_id INT NOT NULL REFERENCES catalog_product_entity(entity_id) ON DELETE CASCADE,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Cart Shipping Method Selection
CREATE TABLE sales_cart_shipping (
    entity_id SERIAL PRIMARY KEY,
    cart_id INT NOT NULL REFERENCES sales_cart(entity_id) ON DELETE CASCADE,
    method_code VARCHAR(50),
    carrier_code VARCHAR(50),
    amount DECIMAL(12, 4) DEFAULT 0.0000
);

-- Cart Payment Selection
CREATE TABLE sales_cart_payment (
    entity_id SERIAL PRIMARY KEY,
    cart_id INT NOT NULL REFERENCES sales_cart(entity_id) ON DELETE CASCADE,
    method_code VARCHAR(50)
);

-- Cart Address (Billing & Shipping)
CREATE TABLE sales_cart_address (
    entity_id SERIAL PRIMARY KEY,
    cart_id INT NOT NULL REFERENCES sales_cart(entity_id) ON DELETE CASCADE,
    address_type VARCHAR(20) NOT NULL,
    firstname VARCHAR(100),
    lastname VARCHAR(100),
    email VARCHAR(255),
    street TEXT,
    city VARCHAR(100),
    region VARCHAR(100),
    postcode VARCHAR(20),
    telephone VARCHAR(20)
);


-- =======================================================
-- 6. SALES: ORDERS
-- -------------------------------------------------------

-- Order Main Table
CREATE TABLE sales_order (
    entity_id SERIAL PRIMARY KEY,
    increment_id VARCHAR(32) UNIQUE,
    customer_id INT REFERENCES customer_entity(entity_id) ON DELETE SET NULL,
    status VARCHAR(32) DEFAULT 'pending',

    -- Pricing
    subtotal DECIMAL(12, 4) NOT NULL,
    discount_amount DECIMAL(12, 4) DEFAULT 0.0000,
    coupon_code VARCHAR(50),
    shipping_amount DECIMAL(12, 4) NOT NULL,
    tax_amount DECIMAL(12, 4) NOT NULL,
    extra_charges DECIMAL(12, 4) DEFAULT 0.0000,
    grand_total DECIMAL(12, 4) NOT NULL,

    -- Customer Info Snapshot
    customer_email VARCHAR(255),
    customer_firstname VARCHAR(100),
    customer_lastname VARCHAR(100),

    -- Method snapshots
    shipping_method VARCHAR(100),
    payment_method VARCHAR(50),

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Order Items
CREATE TABLE sales_order_item (
    item_id SERIAL PRIMARY KEY,
    order_id INT NOT NULL REFERENCES sales_order(entity_id) ON DELETE CASCADE,
    product_id INT,

    -- Snapshot data (Price at time of purchase)
    sku VARCHAR(64),
    name VARCHAR(255),
    price DECIMAL(12, 4),
    quantity INT,
    row_total DECIMAL(12, 4)
);


-- =======================================================
-- DATA POPULATION (MIGRATION)
-- =======================================================


-- 1. Insert Categories (from data/categories.php)
INSERT INTO catalog_category_entity (entity_id, name) VALUES
(1, 'Electronics'),
(2, 'Fashion'),
(3, 'Home'),
(4, 'Beauty');

SELECT setval('catalog_category_entity_entity_id_seq', (SELECT MAX(entity_id) FROM catalog_category_entity));

-- Category Attributes (icons from categories.php)
INSERT INTO catalog_category_attribute (category_id, attribute_code, value) VALUES
(1, 'icon', '<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2c-5.52 0-10 4.48-10 10s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"></path><path d="M7 12l5 5 5-5"></path></svg>'),
(2, 'icon', '<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20.38 3.4a1.6 1.6 0 0 0-1.6-1.6H5.22a1.6 1.6 0 0 0-1.6 1.6l-1.39 6.48-.21.98A2.4 2.4 0 0 0 4.38 13.8l.6.06a2.4 2.4 0 0 0 2.37-1.8l.24-1.16.59.13a2.4 2.4 0 0 0 2.37 1.8h2.9a2.4 2.4 0 0 0 2.37-1.8l.59-.13.24 1.16a2.4 2.4 0 0 0 2.37 1.8l.6-.06a2.4 2.4 0 0 0 2.36-2.96l-.21-.98-1.39-6.48zM4 14v7a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-7"></path></svg>'),
(3, 'icon', '<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>'),
(4, 'icon', '<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path></svg>');


-- 2. Insert Products (from data/product_arr.php)
INSERT INTO catalog_product_entity (entity_id, sku, name, price, brand, shipping_type, image) VALUES
(1, 'SAM-WL-HEADPHONES',  'Wireless Headphones', 64.0000,    'Samsung', 'Express', 'assets/images/headphones.jpg'),
(2, 'APPLE-SMART-WATCH',  'Smart Watch',         499.0000,   'Apple',   'Freight', 'assets/images/smartwatch.jpg'),
(3, 'NIKE-LAPTOP-BKPACK', 'Laptop Backpack',     200.0000,   'Nike',    'Express', 'assets/images/laptop-backpack.jpg'),
(4, 'SAM-USBC-CABLE',     'USB-C Cable',         99.0000,    'Samsung', 'Express', 'assets/images/usb-cable.jpg'),
(5, 'APPLE-PRO-LAPTOP',   'Professional Laptop', 18999.0000, 'Apple',   'Freight', 'assets/images/laptop.jpg'),
(6, 'SAM-WL-MOUSE',       'Wireless Mouse',      249.0000,   'Samsung', 'Express', 'assets/images/mouse.jpg'),
(7, 'SAM-SMARTPHONE',     'Smartphone',          8999.0000,  'Samsung', 'Freight', 'assets/images/smartphone.jpg'),
(8, 'NIKE-RUN-SHOES',     'Running Shoes',       799.0000,   'Nike',    'Freight', 'assets/images/run.jpg'),
(9, 'NIKE-WHITE-SHOES',   'White Shoes',         699.0000,   'Nike',    'Freight', 'assets/images/white-shoes.jpg');

SELECT setval('catalog_product_entity_entity_id_seq', (SELECT MAX(entity_id) FROM catalog_product_entity));


-- 3. Insert Product Attributes (Description)
INSERT INTO catalog_product_attribute (product_id, attribute_code, value) VALUES
(1, 'description', 'Premium wireless headphones with active noise cancellation, 30-hour battery life, and crystal-clear audio quality. Perfect for music lovers and professionals.'),
(2, 'description', 'Advanced smart watch with fitness tracking, health monitoring, and seamless smartphone integration. Track your health and stay connected.'),
(3, 'description', 'Professional laptop backpack designed for business and travel. Features multiple compartments, padded laptop sleeve, and ergonomic design.'),
(4, 'description', 'High-speed USB-C charging cable with durable braided design. Supports fast charging and data transfer.'),
(5, 'description', 'High-performance laptop perfect for professionals and creators. Featuring powerful processor, stunning display, and all-day battery life.'),
(6, 'description', 'Ergonomic wireless mouse with precision tracking and long battery life. Perfect for office and gaming.'),
(7, 'description', 'Latest smartphone with advanced camera system, powerful processor, and stunning AMOLED display.'),
(8, 'description', 'Premium running shoes with advanced cushioning and breathable design. Perfect for athletes and fitness enthusiasts.'),
(9, 'description', 'Classic white shoes with modern design and superior comfort. Perfect for casual wear and everyday use.');


-- 4. Insert Product Attributes (Features)
INSERT INTO catalog_product_attribute (product_id, attribute_code, value) VALUES
-- Product 1: Wireless Headphones
(1, 'feature', 'Active Noise Cancellation'),
(1, 'feature', '30-hour battery life'),
(1, 'feature', 'Bluetooth 5.0'),
(1, 'feature', 'Premium sound quality'),
(1, 'feature', 'Comfortable ear cushions'),
-- Product 2: Smart Watch
(2, 'feature', 'Heart rate monitoring'),
(2, 'feature', 'Sleep tracking'),
(2, 'feature', 'GPS enabled'),
(2, 'feature', 'Water resistant'),
(2, 'feature', '7-day battery life'),
-- Product 3: Laptop Backpack
(3, 'feature', 'Fits up to 15.6" laptop'),
(3, 'feature', 'Multiple compartments'),
(3, 'feature', 'Water-resistant material'),
(3, 'feature', 'Ergonomic design'),
(3, 'feature', 'Anti-theft pocket'),
-- Product 4: USB-C Cable
(4, 'feature', 'Fast charging support'),
(4, 'feature', 'Braided cable design'),
(4, 'feature', '3-meter length'),
(4, 'feature', 'Universal compatibility'),
(4, 'feature', 'Durable construction'),
-- Product 5: Professional Laptop
(5, 'feature', 'Intel Core i7 processor'),
(5, 'feature', '16GB RAM'),
(5, 'feature', '512GB SSD'),
(5, 'feature', '15.6" Full HD display'),
(5, 'feature', '10-hour battery life'),
-- Product 6: Wireless Mouse
(6, 'feature', 'Ergonomic design'),
(6, 'feature', '6-month battery life'),
(6, 'feature', 'Precision tracking'),
(6, 'feature', 'Silent clicks'),
(6, 'feature', 'Wireless 2.4GHz'),
-- Product 7: Smartphone
(7, 'feature', '6.5" AMOLED display'),
(7, 'feature', '128GB storage'),
(7, 'feature', 'Triple camera system'),
(7, 'feature', '5000mAh battery'),
(7, 'feature', '5G enabled'),
-- Product 8: Running Shoes
(8, 'feature', 'Advanced cushioning'),
(8, 'feature', 'Breathable mesh'),
(8, 'feature', 'Lightweight design'),
(8, 'feature', 'Durable outsole'),
(8, 'feature', 'Ergonomic fit'),
-- Product 9: White Shoes
(9, 'feature', 'Classic white design'),
(9, 'feature', 'Comfortable fit'),
(9, 'feature', 'Durable construction'),
(9, 'feature', 'Versatile style'),
(9, 'feature', 'Easy to clean');


-- 5. Insert Product Attributes (Gallery Images)
INSERT INTO catalog_product_attribute (product_id, attribute_code, value) VALUES
-- Product 1: Wireless Headphones
(1, 'gallery_item', 'assets/images/headphones.jpg'),
(1, 'gallery_item', 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=800&auto=format&fit=crop'),
(1, 'gallery_item', 'https://images.unsplash.com/photo-1546435770-a3e426bf472b?q=80&w=800&auto=format&fit=crop'),
-- Product 2: Smart Watch
(2, 'gallery_item', 'assets/images/smartwatch.jpg'),
(2, 'gallery_item', 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=800&auto=format&fit=crop'),
(2, 'gallery_item', 'https://images.unsplash.com/photo-1579586337278-3befd40fd17a?q=80&w=800&auto=format&fit=crop'),
-- Product 3: Laptop Backpack
(3, 'gallery_item', 'assets/images/laptop-backpack.jpg'),
(3, 'gallery_item', 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?q=80&w=800&auto=format&fit=crop'),
(3, 'gallery_item', 'https://images.unsplash.com/photo-1622560480605-d83c853bc5c3?q=80&w=800&auto=format&fit=crop'),
-- Product 4: USB-C Cable
(4, 'gallery_item', 'assets/images/usb-cable.jpg'),
(4, 'gallery_item', 'https://images.unsplash.com/photo-1649959223405-f927e0fc1e05?q=80&w=800&auto=format&fit=crop'),
(4, 'gallery_item', 'https://plus.unsplash.com/premium_photo-1760423006855-81cbed965ac7?q=80&w=800&auto=format&fit=crop'),
-- Product 5: Professional Laptop
(5, 'gallery_item', 'assets/images/laptop.jpg'),
(5, 'gallery_item', 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?q=80&w=800&auto=format&fit=crop'),
(5, 'gallery_item', 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?q=80&w=800&auto=format&fit=crop'),
-- Product 6: Wireless Mouse
(6, 'gallery_item', 'assets/images/mouse.jpg'),
(6, 'gallery_item', 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?q=80&w=800&auto=format&fit=crop'),
(6, 'gallery_item', 'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?q=80&w=800&auto=format&fit=crop'),
-- Product 7: Smartphone
(7, 'gallery_item', 'assets/images/smartphone.jpg'),
(7, 'gallery_item', 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?q=80&w=800&auto=format&fit=crop'),
(7, 'gallery_item', 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?q=80&w=800&auto=format&fit=crop'),
-- Product 8: Running Shoes
(8, 'gallery_item', 'assets/images/run.jpg'),
(8, 'gallery_item', 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=800&auto=format&fit=crop'),
(8, 'gallery_item', 'https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?q=80&w=800&auto=format&fit=crop'),
-- Product 9: White Shoes
(9, 'gallery_item', 'assets/images/white-shoes.jpg'),
(9, 'gallery_item', 'https://images.unsplash.com/photo-1632993819204-3ad5253a4a72?q=80&w=800&auto=format&fit=crop'),
(9, 'gallery_item', 'https://images.unsplash.com/photo-1608229751021-ed4bd8677753?q=80&w=800&auto=format&fit=crop');


-- 6. Insert Category-Product Links
-- category_id: 1=Electronics, 2=Fashion
INSERT INTO catalog_category_product (category_id, product_id) VALUES
(1, 1),  -- Wireless Headphones → Electronics
(1, 2),  -- Smart Watch → Electronics
(2, 3),  -- Laptop Backpack → Fashion
(1, 4),  -- USB-C Cable → Electronics
(1, 5),  -- Professional Laptop → Electronics
(1, 6),  -- Wireless Mouse → Electronics
(1, 7),  -- Smartphone → Electronics
(2, 8),  -- Running Shoes → Fashion
(2, 9);  -- White Shoes → Fashion


-- 7. Insert Customers (from data/users.json)
INSERT INTO customer_entity (entity_id, firstname, lastname, email, password_hash, created_at) VALUES
(1, 'abc', 'abc', 'abc@gmail.com', '$2y$10$WrlptbO7uNDrPdCqR2XvsuFanYzEtyytq6smcag1Vo810ezQq/zpq', '2026-01-27 09:17:01'),
(2, 'one', 'one', 'one@gmail.com', '$2y$10$b8sVtTeVR9Ebyzq62sYjqO39wuGDePrien4zpImr1XTGV6UMTmSPy', '2026-01-27 09:44:14'),
(3, 'xyz', 'xyz', 'xyz@gmail.com', '$2y$10$RRxlRjL12FSCCHQrxy.j6OBM2o/tXm09AUIiVpslchMGTfbVjPtvK', '2026-01-28 06:07:31');

SELECT setval('customer_entity_entity_id_seq', (SELECT MAX(entity_id) FROM customer_entity));


-- 8. Insert Orders (from data/orders.json)
INSERT INTO sales_order (entity_id, increment_id, customer_id, status, subtotal, discount_amount, coupon_code, shipping_amount, tax_amount, extra_charges, grand_total, customer_email, customer_firstname, customer_lastname, shipping_method, payment_method, created_at) VALUES
(1, 'ORD-2026-001', 1, 'CONFIRMED', 12998.0000, 0.0000, NULL,     500.0000, 0.0000,  0.0000, 13498.0000, 'abc@gmail.com', 'abc', 'abc', 'standard',    'card', '2026-01-27 09:40:18'),
(2, 'ORD-2026-002', 2, 'CONFIRMED', 20498.0000, 0.0000, NULL,    2500.0000, 0.0000,  0.0000, 22998.0000, 'one@gmail.com', 'one', 'one', 'standard',    'upi',  '2026-01-27 09:45:00'),
(3, 'ORD-2026-003', 3, 'CONFIRMED',   400.0000, 40.0000, 'SAVE10', 20.0000, 68.4000, 0.0000,   448.4000, 'xyz@gmail.com', 'xyz', 'xyz', 'white_glove', 'upi',  '2026-01-30 13:42:20');

SELECT setval('sales_order_entity_id_seq', (SELECT MAX(entity_id) FROM sales_order));


-- 9. Insert Order Items (from data/orders.json)
INSERT INTO sales_order_item (order_id, product_id, sku, name, price, quantity, row_total) VALUES
-- Order 1: 2x Wireless Headphones
(1, 1, 'SAM-WL-HEADPHONES', 'Wireless Headphones', 6499.0000, 2, 12998.0000),
-- Order 2: 1x Smart Watch + 1x Laptop Backpack
(2, 2, 'APPLE-SMART-WATCH',  'Smart Watch',        16499.0000, 1, 16499.0000),
(2, 3, 'NIKE-LAPTOP-BKPACK', 'Laptop Backpack',     3999.0000, 1,  3999.0000),
-- Order 3: 2x Laptop Backpack
(3, 3, 'NIKE-LAPTOP-BKPACK', 'Laptop Backpack',      200.0000, 2,   400.0000);


-- =======================================================
-- RELATIONSHIP SUMMARY
-- =======================================================
-- catalog_category_entity (PK: entity_id)
--     ├── catalog_category_attribute.category_id (FK, CASCADE)
--     └── catalog_category_product.category_id (FK, CASCADE)
--
-- catalog_product_entity (PK: entity_id)
--     ├── catalog_product_attribute.product_id (FK, CASCADE)
--     ├── catalog_category_product.product_id (FK, CASCADE)
--     └── sales_cart_item.product_id (FK, CASCADE)
--
-- customer_entity (PK: entity_id)
--     ├── sales_cart.customer_id (FK, SET NULL)
--     └── sales_order.customer_id (FK, SET NULL)
--
-- sales_cart (PK: entity_id)
--     ├── sales_cart_item.cart_id (FK, CASCADE)
--     ├── sales_cart_shipping.cart_id (FK, CASCADE)
--     ├── sales_cart_payment.cart_id (FK, CASCADE)
--     └── sales_cart_address.cart_id (FK, CASCADE)
--
-- sales_order (PK: entity_id)
--     └── sales_order_item.order_id (FK, CASCADE)
-- =======================================================
