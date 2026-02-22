-- ============================================================
-- categories.sql (converted from categories.php)
-- Run this FIRST (products.sql depends on this table)
-- ============================================================

CREATE TABLE categories (
    id      SERIAL PRIMARY KEY,
    name    VARCHAR(100) NOT NULL UNIQUE,
    icon    TEXT
);

INSERT INTO categories (id, name, icon) VALUES
(1, 'Electronics', '<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2c-5.52 0-10 4.48-10 10s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"></path><path d="M7 12l5 5 5-5"></path></svg>'),
(2, 'Fashion',     '<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20.38 3.4a1.6 1.6 0 0 0-1.6-1.6H5.22a1.6 1.6 0 0 0-1.6 1.6l-1.39 6.48-.21.98A2.4 2.4 0 0 0 4.38 13.8l.6.06a2.4 2.4 0 0 0 2.37-1.8l.24-1.16.59.13a2.4 2.4 0 0 0 2.37 1.8h2.9a2.4 2.4 0 0 0 2.37-1.8l.59-.13.24 1.16a2.4 2.4 0 0 0 2.37 1.8l.6-.06a2.4 2.4 0 0 0 2.36-2.96l-.21-.98-1.39-6.48zM4 14v7a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-7"></path></svg>'),
(3, 'Home',        '<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>'),
(4, 'Beauty',      '<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path></svg>');

SELECT setval('categories_id_seq', (SELECT MAX(id) FROM categories));
