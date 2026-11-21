<?php
/**
 * PHPUnit Bootstrap File - Dolaby Test Suite
 */
define('DB_NAME', 'dolaby_v2_testing');

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../website/includes.php';

// Setup in-memory SQLite PDO for isolated, ultra-fast unit testing
try {
    $sqlitePdo = new PDO('sqlite::memory:');
    $sqlitePdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sqlitePdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $sqlitePdo->exec("
        CREATE TABLE IF NOT EXISTS orders (
            order_id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER,
            total_amount REAL,
            order_status TEXT,
            created_at DATETIME
        );
        CREATE TABLE IF NOT EXISTS order_items (
            order_item_id INTEGER PRIMARY KEY AUTOINCREMENT,
            order_id INTEGER,
            product_id INTEGER,
            quantity INTEGER,
            price REAL
        );
        CREATE TABLE IF NOT EXISTS products (
            product_id INTEGER PRIMARY KEY AUTOINCREMENT,
            product_name TEXT, image_url TEXT,
            category_id INTEGER,
            price REAL
        );
        CREATE TABLE IF NOT EXISTS categories (
            category_id INTEGER PRIMARY KEY AUTOINCREMENT,
            category_name TEXT
        );

        INSERT INTO categories (category_id, category_name) VALUES (1, 'Hoodies'), (2, 'Denim');
        INSERT INTO products (product_id, product_name, image_url, category_id, price) VALUES (1, 'Harbaa Hoodie', 'assets/images/shop/Harbaa.png', 1, 850.00), (2, 'Cielo Jeans', 'assets/images/shop/CieloDenim.png', 2, 700.00);
        INSERT INTO orders (order_id, user_id, total_amount, order_status, created_at) VALUES 
            (1, 101, 850.00, 'delivered', '2026-01-10 12:00:00'),
            (2, 101, 700.00, 'delivered', '2026-02-15 14:00:00'),
            (3, 102, 1550.00, 'shipped', '2026-03-01 10:00:00');
        INSERT INTO order_items (order_id, product_id, quantity, price) VALUES 
            (1, 1, 1, 850.00),
            (2, 2, 1, 700.00),
            (3, 1, 1, 850.00),
            (3, 2, 1, 700.00);
    ");

    Database::setTestConnection($sqlitePdo);
} catch (Exception $e) {
    // If SQLite driver unavailable, fallback to default connection
}

class TestCase extends PHPUnit\Framework\TestCase {
}
