-- ========================================================
-- Dolaby E-commerce Database Schema (FINAL)
-- ========================================================

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS promo_code_usage;
DROP TABLE IF EXISTS promo_codes;
DROP TABLE IF EXISTS discount_campaign_products;
DROP TABLE IF EXISTS discount_campaigns;
DROP TABLE IF EXISTS loyalty_transactions;
DROP TABLE IF EXISTS loyalty_points;
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS cart_items;
DROP TABLE IF EXISTS payment_methods;
DROP TABLE IF EXISTS addresses;
DROP TABLE IF EXISTS user_favorites;
DROP TABLE IF EXISTS product_reviews;
DROP TABLE IF EXISTS product_sizes;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS brands;
DROP TABLE IF EXISTS brand_analytics;
DROP TABLE IF EXISTS users;

SET FOREIGN_KEY_CHECKS = 1;

-- ========================================================
-- USERS TABLE  (customers + brand owners + admin)
-- ========================================================
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    role ENUM('customer','brand_owner','admin') DEFAULT 'customer',
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    date_of_birth DATE,
    account_status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    email_notifications BOOLEAN DEFAULT TRUE,
    member_since TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_account_status (account_status),
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- BRANDS TABLE
-- ========================================================
CREATE TABLE brands (
    brand_id INT PRIMARY KEY AUTO_INCREMENT,
    brand_name VARCHAR(100) NOT NULL UNIQUE,
    brand_logo VARCHAR(255),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_brand_name (brand_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- CATEGORIES TABLE
-- ========================================================
CREATE TABLE categories (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_category_name (category_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- PRODUCTS TABLE (single main image per product)
-- ========================================================
CREATE TABLE products (
    product_id INT PRIMARY KEY AUTO_INCREMENT,
    brand_id INT NOT NULL,
    category_id INT,
    product_name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    original_price DECIMAL(10, 2), -- For sale items
    stock_quantity INT DEFAULT 0,
    in_stock BOOLEAN DEFAULT TRUE,
    on_sale BOOLEAN DEFAULT FALSE,
    is_new BOOLEAN DEFAULT FALSE,
    average_rating DECIMAL(3, 2) DEFAULT 0.00,
    total_reviews INT DEFAULT 0,
    sales_count INT DEFAULT 0,
    image_url VARCHAR(500), -- main product image
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (brand_id) REFERENCES brands(brand_id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE SET NULL,
    INDEX idx_brand_id (brand_id),
    INDEX idx_category_id (category_id),
    INDEX idx_in_stock (in_stock),
    INDEX idx_on_sale (on_sale),
    INDEX idx_is_new (is_new),
    INDEX idx_price (price),
    INDEX idx_sales_count (sales_count),
    FULLTEXT idx_search (product_name, description)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- PRODUCT SIZES TABLE (XS, S, M, L, XL)
-- ========================================================
CREATE TABLE product_sizes (
    size_id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT NOT NULL,
    size VARCHAR(10) NOT NULL, -- XS, S, M, L, XL
    stock_quantity INT DEFAULT 0,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE,
    UNIQUE KEY unique_product_size (product_id, size),
    INDEX idx_product_id (product_id),
    INDEX idx_size (size)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- PRODUCT REVIEWS TABLE
-- ========================================================
CREATE TABLE product_reviews (
    review_id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    review_content TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    INDEX idx_product_id (product_id),
    INDEX idx_user_id (user_id),
    INDEX idx_rating (rating),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- USER FAVORITES TABLE (wishlist)
-- ========================================================
CREATE TABLE user_favorites (
    favorite_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_product (user_id, product_id),
    INDEX idx_user_id (user_id),
    INDEX idx_product_id (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- ADDRESSES TABLE
-- ========================================================
CREATE TABLE addresses (
    address_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    address_type ENUM('Home', 'Work', 'Other') NOT NULL DEFAULT 'Home',
    area VARCHAR(255) NOT NULL,
    building_name VARCHAR(255),
    apartment_number VARCHAR(50),
    floor VARCHAR(50),
    street VARCHAR(255),
    additional_directions TEXT,
    phone VARCHAR(20),
    address_label VARCHAR(100),
    is_default BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_is_default (is_default)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- PAYMENT METHODS TABLE (saved cards)
-- ========================================================
CREATE TABLE payment_methods (
    payment_method_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    card_type ENUM('VISA', 'MasterCard', 'American Express', 'Other') NOT NULL,
    last_four_digits VARCHAR(4) NOT NULL,
    cardholder_name VARCHAR(255),
    expiry_month INT,
    expiry_year INT,
    is_default BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_is_default (is_default)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- CART ITEMS TABLE
-- ========================================================
CREATE TABLE cart_items (
    cart_item_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    size VARCHAR(10) NOT NULL,
    quantity INT NOT NULL DEFAULT 1 CHECK (quantity > 0),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_product_size (user_id, product_id, size),
    INDEX idx_user_id (user_id),
    INDEX idx_product_id (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- PROMO CODES TABLE
-- ========================================================
CREATE TABLE promo_codes (
    promo_code_id INT PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(50) UNIQUE NOT NULL,
    type ENUM('percent','fixed') NOT NULL,
    value DECIMAL(10,2) NOT NULL,
    min_order_amount DECIMAL(10,2) DEFAULT 0,
    max_discount DECIMAL(10,2) DEFAULT NULL,
    start_date DATE,
    end_date DATE,
    usage_limit INT DEFAULT NULL, -- total allowed uses
    created_by INT, -- user_id of admin or brand_owner
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(user_id) ON DELETE SET NULL,
    INDEX idx_code (code),
    INDEX idx_dates (start_date, end_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- ORDERS TABLE
-- ========================================================
CREATE TABLE orders (
    order_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    order_number VARCHAR(50) NOT NULL UNIQUE,
    address_id INT NOT NULL,
    payment_method_id INT,
    order_status ENUM('pending', 'confirmed', 'processing', 'out_for_shipping', 'shipped', 'delivered', 'cancelled', 'refunded') DEFAULT 'pending',
    subtotal DECIMAL(10, 2) NOT NULL,
    shipping_fees DECIMAL(10, 2) DEFAULT 0.00,
    service_fees DECIMAL(10, 2) DEFAULT 0.00,
    discount_amount DECIMAL(10, 2) DEFAULT 0.00,
    total_amount DECIMAL(10, 2) NOT NULL,
    promo_code_id INT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    shipped_date TIMESTAMP NULL,
    delivered_date TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (address_id) REFERENCES addresses(address_id) ON DELETE RESTRICT,
    FOREIGN KEY (payment_method_id) REFERENCES payment_methods(payment_method_id) ON DELETE SET NULL,
    FOREIGN KEY (promo_code_id) REFERENCES promo_codes(promo_code_id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_order_number (order_number),
    INDEX idx_order_status (order_status),
    INDEX idx_order_date (order_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- ORDER ITEMS TABLE
-- ========================================================
CREATE TABLE order_items (
    order_item_id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    size VARCHAR(10) NOT NULL,
    color VARCHAR(50),
    quantity INT NOT NULL,
    unit_price DECIMAL(10, 2) NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE RESTRICT,
    INDEX idx_order_id (order_id),
    INDEX idx_product_id (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- PROMO CODE USAGE TABLE
-- ========================================================
CREATE TABLE promo_code_usage (
    id INT PRIMARY KEY AUTO_INCREMENT,
    promo_code_id INT NOT NULL,
    user_id INT NOT NULL,
    order_id INT,
    used_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (promo_code_id) REFERENCES promo_codes(promo_code_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE SET NULL,
    INDEX idx_promo_code_id (promo_code_id),
    INDEX idx_user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- LOYALTY POINTS TABLE
-- ========================================================
-- Loyalty Points System:
-- - Points are awarded automatically when orders are placed
-- - Calculation: 1 point per 10 EGP spent (based on order subtotal, excluding shipping/fees)
-- - Tier System:
--   * Bronze: 0-499 points (needs 500 for Silver)
--   * Silver: 500-1499 points (needs 1500 for Gold)
--   * Gold: 1500-2999 points (needs 3000 for Platinum)
--   * Platinum: 3000-4999 points (needs 5000 for Diamond)
--   * Diamond: 5000+ points (highest tier)
-- - Points value: 1 point = 0.1 EGP (can be redeemed for discounts)
CREATE TABLE loyalty_points (
    loyalty_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL UNIQUE,
    current_balance INT DEFAULT 0,
    total_earned INT DEFAULT 0,
    total_redeemed INT DEFAULT 0,
    tier_status ENUM('Bronze', 'Silver', 'Gold', 'Platinum', 'Diamond') DEFAULT 'Bronze',
    points_to_next_tier INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_tier_status (tier_status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- LOYALTY TRANSACTIONS TABLE
-- ========================================================
-- Tracks all loyalty point transactions:
-- - 'earned': Points awarded from purchases (linked to order_id)
-- - 'redeemed': Points used for discounts/vouchers
-- - 'expired': Points that expired (if expiration is implemented)
-- - 'bonus': Bonus points from promotions, referrals, etc.
-- order_id is NULL for non-purchase transactions (redeemed, bonus, expired)
CREATE TABLE loyalty_transactions (
    transaction_id INT PRIMARY KEY AUTO_INCREMENT,
    loyalty_id INT NOT NULL,
    user_id INT NOT NULL,
    transaction_type ENUM('earned', 'redeemed', 'expired', 'bonus') NOT NULL,
    points INT NOT NULL,
    description VARCHAR(255),
    order_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (loyalty_id) REFERENCES loyalty_points(loyalty_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE SET NULL,
    INDEX idx_loyalty_id (loyalty_id),
    INDEX idx_user_id (user_id),
    INDEX idx_transaction_type (transaction_type),
    INDEX idx_created_at (created_at),
    INDEX idx_order_id (order_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- DISCOUNT CAMPAIGNS TABLE (Brand owner dashboard)
-- ========================================================
CREATE TABLE discount_campaigns (
    campaign_id INT PRIMARY KEY AUTO_INCREMENT,
    brand_owner_user_id INT NOT NULL,   -- user with role = 'brand_owner'
    brand_id INT,                       -- nullable for platform-wide
    campaign_name VARCHAR(255) NOT NULL,
    description TEXT,
    discount_type ENUM('percentage', 'fixed') NOT NULL,
    discount_value DECIMAL(10, 2) NOT NULL,
    product_group ENUM('all', 'men', 'women', 'kids') DEFAULT 'all',
    start_date DATETIME NOT NULL,
    end_date DATETIME NOT NULL,
    status ENUM('draft', 'scheduled', 'active', 'ended', 'cancelled') DEFAULT 'draft',
    campaign_image VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (brand_owner_user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (brand_id) REFERENCES brands(brand_id) ON DELETE CASCADE,
    INDEX idx_brand_owner_user_id (brand_owner_user_id),
    INDEX idx_brand_id (brand_id),
    INDEX idx_status (status),
    INDEX idx_dates (start_date, end_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- DISCOUNT CAMPAIGN PRODUCTS TABLE
-- ========================================================
CREATE TABLE discount_campaign_products (
    campaign_product_id INT PRIMARY KEY AUTO_INCREMENT,
    campaign_id INT NOT NULL,
    product_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (campaign_id) REFERENCES discount_campaigns(campaign_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE,
    UNIQUE KEY unique_campaign_product (campaign_id, product_id),
    INDEX idx_campaign_id (campaign_id),
    INDEX idx_product_id (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- BRAND ANALYTICS TABLE (for brandowner analytics.html)
-- ========================================================
CREATE TABLE brand_analytics (
    id INT PRIMARY KEY AUTO_INCREMENT,
    brand_id INT NOT NULL,
    metric VARCHAR(100) NOT NULL,      -- e.g. 'monthly_revenue', 'orders_count'
    value DECIMAL(10,2) NOT NULL,
    recorded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (brand_id) REFERENCES brands(brand_id) ON DELETE CASCADE,
    INDEX idx_brand_id (brand_id),
    INDEX idx_metric (metric),
    INDEX idx_recorded_at (recorded_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;