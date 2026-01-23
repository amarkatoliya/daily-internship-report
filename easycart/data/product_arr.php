<?php
// Products Array
$products = [
    [
        'id' => 1,
        'name' => 'Wireless Headphones',
        'price' => 6499,
        'image_url' => 'assets/images/headphones.jpg',
        'category' => 'Electronics',
        'brand' => 'Samsung',
        'description' => 'Premium wireless headphones with active noise cancellation, 30-hour battery life, and crystal-clear audio quality. Perfect for music lovers and professionals.',
        'features' => [
            'Active Noise Cancellation',
            '30-hour battery life',
            'Bluetooth 5.0',
            'Premium sound quality',
            'Comfortable ear cushions'
        ],
        'images' => [
            'assets/images/headphones.jpg',
            'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1546435770-a3e426bf472b?q=80&w=800&auto=format&fit=crop'
        ]
    ],
    [
        'id' => 2,
        'name' => 'Smart Watch',
        'price' => 16499,
        'image_url' => 'assets/images/smartwatch.jpg',
        'category' => 'Electronics',
        'brand' => 'Apple',
        'description' => 'Advanced smart watch with fitness tracking, health monitoring, and seamless smartphone integration. Track your health and stay connected.',
        'features' => [
            'Heart rate monitoring',
            'Sleep tracking',
            'GPS enabled',
            'Water resistant',
            '7-day battery life'
        ],
        'images' => [
            'assets/images/smartwatch.jpg',
            'https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1579586337278-3befd40fd17a?q=80&w=800&auto=format&fit=crop'
        ]
    ],
    [
        'id' => 3,
        'name' => 'Laptop Backpack',
        'price' => 3999,
        'image_url' => 'assets/images/laptop-backpack.jpg',
        'category' => 'Fashion',
        'brand' => 'Nike',
        'description' => 'Professional laptop backpack designed for business and travel. Features multiple compartments, padded laptop sleeve, and ergonomic design.',
        'features' => [
            'Fits up to 15.6" laptop',
            'Multiple compartments',
            'Water-resistant material',
            'Ergonomic design',
            'Anti-theft pocket'
        ],
        'images' => [
            'assets/images/laptop-backpack.jpg',
            'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1622560480605-d83c853bc5c3?q=80&w=800&auto=format&fit=crop'
        ]
    ],
    [
        'id' => 4,
        'name' => 'USB-C Cable',
        'price' => 1599,
        'image_url' => 'assets/images/usb-cable.jpg',
        'category' => 'Electronics',
        'brand' => 'Samsung',
        'description' => 'High-speed USB-C charging cable with durable braided design. Supports fast charging and data transfer.',
        'features' => [
            'Fast charging support',
            'Braided cable design',
            '3-meter length',
            'Universal compatibility',
            'Durable construction'
        ],
        'images' => [
            'assets/images/usb-cable.jpg',
            'https://images.unsplash.com/photo-1649959223405-f927e0fc1e05?q=80&w=800&auto=format&fit=crop',
            'https://plus.unsplash.com/premium_photo-1760423006855-81cbed965ac7?q=80&w=800&auto=format&fit=crop'
        ]
    ],
    [
        'id' => 5,
        'name' => 'Professional Laptop',
        'price' => 89999,
        'image_url' => 'assets/images/laptop.jpg',
        'category' => 'Electronics',
        'brand' => 'Apple',
        'description' => 'High-performance laptop perfect for professionals and creators. Featuring powerful processor, stunning display, and all-day battery life.',
        'features' => [
            'Intel Core i7 processor',
            '16GB RAM',
            '512GB SSD',
            '15.6" Full HD display',
            '10-hour battery life'
        ],
        'images' => [
            'assets/images/laptop.jpg',
            'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?q=80&w=800&auto=format&fit=crop'
        ]
    ],
    [
        'id' => 6,
        'name' => 'Wireless Mouse',
        'price' => 2499,
        'image_url' => 'assets/images/mouse.jpg',
        'category' => 'Electronics',
        'brand' => 'Samsung',
        'description' => 'Ergonomic wireless mouse with precision tracking and long battery life. Perfect for office and gaming.',
        'features' => [
            'Ergonomic design',
            '6-month battery life',
            'Precision tracking',
            'Silent clicks',
            'Wireless 2.4GHz'
        ],
        'images' => [
            'assets/images/mouse.jpg',
            'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?q=80&w=800&auto=format&fit=crop'
        ]
    ],
    [
        'id' => 7,
        'name' => 'Smartphone',
        'price' => 54999,
        'image_url' => 'assets/images/smartphone.jpg',
        'category' => 'Electronics',
        'brand' => 'Samsung',
        'description' => 'Latest smartphone with advanced camera system, powerful processor, and stunning AMOLED display.',
        'features' => [
            '6.5" AMOLED display',
            '128GB storage',
            'Triple camera system',
            '5000mAh battery',
            '5G enabled'
        ],
        'images' => [
            'assets/images/smartphone.jpg',
            'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1598327105666-5b89351aff97?q=80&w=800&auto=format&fit=crop'
        ]
    ],
    [
        'id' => 8,
        'name' => 'Running Shoes',
        'price' => 7999,
        'image_url' => 'assets/images/run.jpg',
        'category' => 'Fashion',
        'brand' => 'Nike',
        'description' => 'Premium running shoes with advanced cushioning and breathable design. Perfect for athletes and fitness enthusiasts.',
        'features' => [
            'Advanced cushioning',
            'Breathable mesh',
            'Lightweight design',
            'Durable outsole',
            'Ergonomic fit'
        ],
        'images' => [
            'assets/images/run.jpg',
            'https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?q=80&w=800&auto=format&fit=crop'
        ]
    ],
    [
        'id' => 9,
        'name' => 'White Shoes',
        'price' => 6999,
        'image_url' => 'assets/images/white-shoes.jpg',
        'category' => 'Fashion',
        'brand' => 'Nike',
        'description' => 'Classic white shoes with modern design and superior comfort. Perfect for casual wear and everyday use.',
        'features' => [
            'Classic white design',
            'Comfortable fit',
            'Durable construction',
            'Versatile style',
            'Easy to clean'
        ],
        'images' => [
            'assets/images/white-shoes.jpg',
            'https://images.unsplash.com/photo-1632993819204-3ad5253a4a72?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1608229751021-ed4bd8677753?q=80&w=800&auto=format&fit=crop'
        ]
    ]
];
