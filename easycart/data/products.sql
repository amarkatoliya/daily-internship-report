-- ============================================================
-- products.sql (converted from product_arr.php)
-- Run this THIRD (depends on categories and brands tables)
-- ============================================================
-- FK: category_id → categories(id)
-- FK: product_features.product_id → products(id)
-- FK: product_images.product_id → products(id)
-- ============================================================


-- PRODUCTS TABLE (FK to categories and brands)
CREATE TABLE products (
    id              SERIAL PRIMARY KEY,
    name            VARCHAR(255) NOT NULL,
    price           DECIMAL(10, 2) NOT NULL,
    shipping_type   VARCHAR(50) DEFAULT 'Express',
    image_url       VARCHAR(500),
    category_id     INTEGER REFERENCES categories(id) ON DELETE SET NULL,
    brand_id        INTEGER REFERENCES brands(id) ON DELETE SET NULL,
    description     TEXT,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- PRODUCT FEATURES TABLE (FK to products)
CREATE TABLE product_features (
    id          SERIAL PRIMARY KEY,
    product_id  INTEGER NOT NULL REFERENCES products(id) ON DELETE CASCADE,
    feature     VARCHAR(255) NOT NULL
);

-- PRODUCT IMAGES TABLE (FK to products)
CREATE TABLE product_images (
    id          SERIAL PRIMARY KEY,
    product_id  INTEGER NOT NULL REFERENCES products(id) ON DELETE CASCADE,
    image_url   VARCHAR(500) NOT NULL,
    sort_order  INTEGER DEFAULT 0
);


-- ============================================================
-- INSERT 9 PRODUCTS
-- category_id: 1=Electronics, 2=Fashion (FK → categories)
-- brand_id:    1=Apple, 2=Samsung, 3=Nike (FK → brands)
-- ============================================================
INSERT INTO products (id, name, price, shipping_type, image_url, category_id, brand_id, description) VALUES
(1, 'Wireless Headphones', 64.00,    'Express', 'assets/images/headphones.jpg',      1, 2, 'Premium wireless headphones with active noise cancellation, 30-hour battery life, and crystal-clear audio quality. Perfect for music lovers and professionals.'),
(2, 'Smart Watch',         499.00,   'Freight', 'assets/images/smartwatch.jpg',       1, 1, 'Advanced smart watch with fitness tracking, health monitoring, and seamless smartphone integration. Track your health and stay connected.'),
(3, 'Laptop Backpack',     200.00,   'Express', 'assets/images/laptop-backpack.jpg',  2, 3, 'Professional laptop backpack designed for business and travel. Features multiple compartments, padded laptop sleeve, and ergonomic design.'),
(4, 'USB-C Cable',         99.00,    'Express', 'assets/images/usb-cable.jpg',        1, 2, 'High-speed USB-C charging cable with durable braided design. Supports fast charging and data transfer.'),
(5, 'Professional Laptop', 18999.00, 'Freight', 'assets/images/laptop.jpg',           1, 1, 'High-performance laptop perfect for professionals and creators. Featuring powerful processor, stunning display, and all-day battery life.'),
(6, 'Wireless Mouse',      249.00,   'Express', 'assets/images/mouse.jpg',            1, 2, 'Ergonomic wireless mouse with precision tracking and long battery life. Perfect for office and gaming.'),
(7, 'Smartphone',          8999.00,  'Freight', 'assets/images/smartphone.jpg',       1, 2, 'Latest smartphone with advanced camera system, powerful processor, and stunning AMOLED display.'),
(8, 'Running Shoes',       799.00,   'Freight', 'assets/images/run.jpg',              2, 3, 'Premium running shoes with advanced cushioning and breathable design. Perfect for athletes and fitness enthusiasts.'),
(9, 'White Shoes',         699.00,   'Freight', 'assets/images/white-shoes.jpg',      2, 3, 'Classic white shoes with modern design and superior comfort. Perfect for casual wear and everyday use.');

SELECT setval('products_id_seq', (SELECT MAX(id) FROM products));


-- ============================================================
-- INSERT PRODUCT FEATURES (FK: product_id → products.id)
-- ============================================================

-- Product 1: Wireless Headphones
INSERT INTO product_features (product_id, feature) VALUES
(1, 'Active Noise Cancellation'),
(1, '30-hour battery life'),
(1, 'Bluetooth 5.0'),
(1, 'Premium sound quality'),
(1, 'Comfortable ear cushions');

-- Product 2: Smart Watch
INSERT INTO product_features (product_id, feature) VALUES
(2, 'Heart rate monitoring'),
(2, 'Sleep tracking'),
(2, 'GPS enabled'),
(2, 'Water resistant'),
(2, '7-day battery life');

-- Product 3: Laptop Backpack
INSERT INTO product_features (product_id, feature) VALUES
(3, 'Fits up to 15.6" laptop'),
(3, 'Multiple compartments'),
(3, 'Water-resistant material'),
(3, 'Ergonomic design'),
(3, 'Anti-theft pocket');

-- Product 4: USB-C Cable
INSERT INTO product_features (product_id, feature) VALUES
(4, 'Fast charging support'),
(4, 'Braided cable design'),
(4, '3-meter length'),
(4, 'Universal compatibility'),
(4, 'Durable construction');

-- Product 5: Professional Laptop
INSERT INTO product_features (product_id, feature) VALUES
(5, 'Intel Core i7 processor'),
(5, '16GB RAM'),
(5, '512GB SSD'),
(5, '15.6" Full HD display'),
(5, '10-hour battery life');

-- Product 6: Wireless Mouse
INSERT INTO product_features (product_id, feature) VALUES
(6, 'Ergonomic design'),
(6, '6-month battery life'),
(6, 'Precision tracking'),
(6, 'Silent clicks'),
(6, 'Wireless 2.4GHz');

-- Product 7: Smartphone
INSERT INTO product_features (product_id, feature) VALUES
(7, '6.5" AMOLED display'),
(7, '128GB storage'),
(7, 'Triple camera system'),
(7, '5000mAh battery'),
(7, '5G enabled');

-- Product 8: Running Shoes
INSERT INTO product_features (product_id, feature) VALUES
(8, 'Advanced cushioning'),
(8, 'Breathable mesh'),
(8, 'Lightweight design'),
(8, 'Durable outsole'),
(8, 'Ergonomic fit');

-- Product 9: White Shoes
INSERT INTO product_features (product_id, feature) VALUES
(9, 'Classic white design'),
(9, 'Comfortable fit'),
(9, 'Durable construction'),
(9, 'Versatile style'),
(9, 'Easy to clean');


-- ============================================================
-- INSERT PRODUCT IMAGES (FK: product_id → products.id)
-- ============================================================

INSERT INTO product_images (product_id, image_url, sort_order) VALUES
-- Product 1: Wireless Headphones
(1, 'assets/images/headphones.jpg', 1),
(1, 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=800&auto=format&fit=crop', 2),
(1, 'https://images.unsplash.com/photo-1546435770-a3e426bf472b?q=80&w=800&auto=format&fit=crop', 3),
-- Product 2: Smart Watch
(2, 'assets/images/smartwatch.jpg', 1),
(2, 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=800&auto=format&fit=crop', 2),
(2, 'https://images.unsplash.com/photo-1579586337278-3befd40fd17a?q=80&w=800&auto=format&fit=crop', 3),
-- Product 3: Laptop Backpack
(3, 'assets/images/laptop-backpack.jpg', 1),
(3, 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?q=80&w=800&auto=format&fit=crop', 2),
(3, 'https://images.unsplash.com/photo-1622560480605-d83c853bc5c3?q=80&w=800&auto=format&fit=crop', 3),
-- Product 4: USB-C Cable
(4, 'assets/images/usb-cable.jpg', 1),
(4, 'https://images.unsplash.com/photo-1649959223405-f927e0fc1e05?q=80&w=800&auto=format&fit=crop', 2),
(4, 'https://plus.unsplash.com/premium_photo-1760423006855-81cbed965ac7?q=80&w=800&auto=format&fit=crop', 3),
-- Product 5: Professional Laptop
(5, 'assets/images/laptop.jpg', 1),
(5, 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?q=80&w=800&auto=format&fit=crop', 2),
(5, 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?q=80&w=800&auto=format&fit=crop', 3),
-- Product 6: Wireless Mouse
(6, 'assets/images/mouse.jpg', 1),
(6, 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?q=80&w=800&auto=format&fit=crop', 2),
(6, 'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?q=80&w=800&auto=format&fit=crop', 3),
-- Product 7: Smartphone
(7, 'assets/images/smartphone.jpg', 1),
(7, 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?q=80&w=800&auto=format&fit=crop', 2),
(7, 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?q=80&w=800&auto=format&fit=crop', 3),
-- Product 8: Running Shoes
(8, 'assets/images/run.jpg', 1),
(8, 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=800&auto=format&fit=crop', 2),
(8, 'https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?q=80&w=800&auto=format&fit=crop', 3),
-- Product 9: White Shoes
(9, 'assets/images/white-shoes.jpg', 1),
(9, 'https://images.unsplash.com/photo-1632993819204-3ad5253a4a72?q=80&w=800&auto=format&fit=crop', 2),
(9, 'https://images.unsplash.com/photo-1608229751021-ed4bd8677753?q=80&w=800&auto=format&fit=crop', 3);
