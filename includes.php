<?php
/**
 * Common includes file
 * Contains database connection, configuration, and all classes
 */

// Prevent multiple includes
if (defined('INCLUDES_LOADED')) {
    return;
}
define('INCLUDES_LOADED', true);

// Define custom session name based on the URL or parameters to allow concurrent testing
if (session_status() === PHP_SESSION_NONE) {
    $uri = $_SERVER['REQUEST_URI'] ?? '';
    if (strpos($uri, 'brandowner') !== false || (isset($_GET['role']) && $_GET['role'] === 'brand_owner') || (isset($_GET['role']) && $_GET['role'] === 'brandowner')) {
        session_name('BRAND_OWNER_SESSION');
    } else {
        session_name('CUSTOMER_SESSION');
    }
    session_start();
}

// Database configuration (only define if not already defined)
if (!defined('DB_HOST')) {
    define('DB_HOST', '127.0.0.1');
}
if (!defined('DB_PORT')) {
    define('DB_PORT', '3307');
}
if (!defined('DB_NAME')) {
    define('DB_NAME', 'dolaby_v2');
}
if (!defined('DB_USER')) {
    define('DB_USER', 'root');
}
if (!defined('DB_PASS')) {
    define('DB_PASS', '');
}

// Base URL for the app (only define if not already defined)
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/dolaby_v2_SQA');
}

/**
 * Database Connection Class
 */
if (!class_exists('Database')) {
class Database
{
    public static function connect(): PDO
    {
        if (!defined('DB_HOST') || !defined('DB_PORT') || !defined('DB_NAME') || !defined('DB_USER') || !defined('DB_PASS')) {
            throw new Exception('Database configuration constants are not defined');
        }

        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
            DB_HOST,
            DB_PORT,
            DB_NAME
        );

        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]);

        return $pdo;
    }
}
}

/**
 * User Repository
 */
if (!class_exists('UserRepository')) {
class UserRepository
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::connect();
    }

    public function findByEmail(string $email): ?array
    {
        $sql = "SELECT user_id, first_name, last_name, email, password_hash, role, phone, date_of_birth, account_status, created_at FROM users WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch() ?: null;
    }

    public function findById(int $id): ?array
    {
        $sql = "SELECT user_id, first_name, last_name, email, password_hash, role, phone, date_of_birth, account_status, created_at FROM users WHERE user_id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $userData): int
    {
        $sql = "INSERT INTO users (first_name, last_name, email, password_hash, role, phone, date_of_birth) VALUES (:first_name, :last_name, :email, :password_hash, :role, :phone, :date_of_birth)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'first_name' => $userData['first_name'],
            'last_name' => $userData['last_name'],
            'email' => $userData['email'],
            'password_hash' => $userData['password_hash'],
            'role' => $userData['role'] ?? 'customer',
            'phone' => $userData['phone'] ?? null,
            'date_of_birth' => $userData['date_of_birth'] ?? null
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function updateEmail(int $userId, string $newEmail): bool
    {
        // Check if email already exists
        $existingUser = $this->findByEmail($newEmail);
        if ($existingUser && $existingUser['user_id'] != $userId) {
            return false; // Email already in use by another user
        }
        
        $sql = "UPDATE users SET email = :email WHERE user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'email' => $newEmail,
            'user_id' => $userId
        ]);
    }

    public function updatePassword(int $userId, string $newPassword): bool
    {
        $sql = "UPDATE users SET password_hash = :password_hash WHERE user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'password_hash' => $newPassword,
            'user_id' => $userId
        ]);
    }

    public function deleteById(int $userId): bool
    {
        // Delete user from database
        // Due to foreign key constraints with ON DELETE CASCADE, related records will be automatically deleted
        $sql = "DELETE FROM users WHERE user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['user_id' => $userId]);
    }
}
}

/**
 * Product Repository
 */
if (!class_exists('ProductRepository')) {
class ProductRepository
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::connect();
    }

    private function mapImageUrl(string $productName, int $productId, string $defaultUrl): string
    {
        $availableImages = [
            '/assets/images/shop/Harbaa.png',
            '/assets/images/shop/HarbaaJacket.png',
            '/assets/images/shop/HarbaaSet.png',
            '/assets/images/shop/Zedzee.png',
            '/assets/images/shop/spiral.png',
            '/assets/images/shop/CieloDenim.png',
            '/assets/images/shop/Basic Hoodie.png',
            '/assets/images/shop/knit crewneck.Png',
            '/assets/images/shop/1.png',
            '/assets/images/shop/3.png',
            '/assets/images/shop/5.png',
            '/assets/images/shop/6.png',
            '/assets/images/shop/8.png',
            '/assets/images/shop/9.png',
            '/assets/images/shop/10.png',
            '/assets/images/shop/21.png',
            '/assets/images/shop/55.png',
            '/assets/images/shop/89.png',
            '/assets/images/shop/98.png',
            '/assets/images/shop/102.png',
            '/assets/images/shop/103.png',
            '/assets/images/shop/877.png',
        ];

        $specificMap = [
            'Harbaa Black Hoodie' => '/assets/images/shop/Harbaa.png',
            'Harbaa Pink Hoodie' => '/assets/images/shop/1.png',
            'Harbaa Oversized Tee White' => '/assets/images/shop/3.png',
            'Harbaa Oversized Tee Black' => '/assets/images/shop/5.png',
            'ZedZee Urban Hoodie' => '/assets/images/shop/Zedzee.png',
            'ZedZee Streetwear Tee' => '/assets/images/shop/6.png',
            'ZedZee Puffer Jacket' => '/assets/images/shop/8.png',
            'Spiral Knitwear Top' => '/assets/images/shop/spiral.png',
            'Spiral Hoodie Black' => '/assets/images/shop/9.png',
            'Cielo Denim Jacket' => '/assets/images/shop/CieloDenim.png',
            'Cielo Blue Jeans' => '/assets/images/shop/10.png',
            'Cielo Knit Sweater' => '/assets/images/shop/21.png',
            'Cielo Soft Hoodie' => '/assets/images/shop/55.png',
            'TwentySeven Crewneck Grey' => '/assets/images/shop/Basic Hoodie.png',
            'TwentySeven Basic Tee' => '/assets/images/shop/89.png',
            'TwentySeven Joggers Set' => '/assets/images/shop/98.png',
            'Asili Tribal Tee' => '/assets/images/shop/102.png',
            'Asili Tribal Hoodie' => '/assets/images/shop/103.png',
            'Mavin Zip Jacket' => '/assets/images/shop/877.png',
            'Mavin Crewneck Beige' => '/assets/images/shop/knit crewneck.Png',
        ];

        $matchedKey = '';
        $matchedImage = '';
        foreach ($specificMap as $key => $image) {
            if (stripos($productName, $key) === 0 || $productName === $key) {
                if (strlen($key) > strlen($matchedKey)) {
                    $matchedKey = $key;
                    $matchedImage = $image;
                }
            }
        }

        if ($matchedImage) {
            return $matchedImage;
        }

        $imageIndex = ($productId - 1) % count($availableImages);
        return $availableImages[$imageIndex];
    }

    public function findAll(): array
    {
        $sql = "SELECT DISTINCT p.product_id AS id, p.product_name AS name, b.brand_name AS brand, c.category_name AS category, p.price, p.original_price, p.stock_quantity AS stock, p.is_new, p.on_sale AS is_on_sale, p.average_rating AS rating, p.sales_count, p.image_url FROM products p LEFT JOIN brands b ON p.brand_id = b.brand_id LEFT JOIN categories c ON p.category_id = c.category_id ORDER BY p.created_at DESC";
        $stmt = $this->pdo->query($sql);
        $products = $stmt->fetchAll();
        foreach ($products as &$product) {
            $product['image_url'] = $this->mapImageUrl($product['name'], $product['id'], $product['image_url']);
        }
        return $products;
    }

    public function findById(int $id): ?array
    {
        $sql = "SELECT p.product_id AS id, p.product_name AS name, b.brand_name AS brand, c.category_name AS category, p.price, p.original_price, p.stock_quantity AS stock, p.is_new, p.on_sale AS is_on_sale, p.average_rating AS rating, p.image_url, p.description, p.total_reviews FROM products p LEFT JOIN brands b ON p.brand_id = b.brand_id LEFT JOIN categories c ON p.category_id = c.category_id WHERE p.product_id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        if ($result) {
            $result['image_url'] = $this->mapImageUrl($result['name'], $result['id'], $result['image_url']);
        }
        return $result ?: null;
    }
}
}

/**
 * Cart Repository
 */
if (!class_exists('CartRepository')) {
class CartRepository
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::connect();
    }

    public function getCartByUserId(int $userId): array
    {
        $sql = "SELECT ci.cart_item_id, ci.product_id, ci.size, ci.quantity, p.product_name AS name, p.price, p.original_price, p.image_url, b.brand_name AS brand FROM cart_items ci JOIN products p ON ci.product_id = p.product_id LEFT JOIN brands b ON p.brand_id = b.brand_id WHERE ci.user_id = :user_id ORDER BY ci.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function addItem(int $userId, int $productId, string $size, int $quantity): void
    {
        $checkSql = "SELECT cart_item_id, quantity FROM cart_items WHERE user_id = :user_id AND product_id = :product_id AND size = :size";
        $checkStmt = $this->pdo->prepare($checkSql);
        $checkStmt->execute(['user_id' => $userId, 'product_id' => $productId, 'size' => $size]);
        $existing = $checkStmt->fetch();

        if ($existing) {
            $updateSql = "UPDATE cart_items SET quantity = quantity + :quantity WHERE cart_item_id = :cart_item_id";
            $updateStmt = $this->pdo->prepare($updateSql);
            $updateStmt->execute(['quantity' => $quantity, 'cart_item_id' => $existing['cart_item_id']]);
        } else {
            $insertSql = "INSERT INTO cart_items (user_id, product_id, size, quantity) VALUES (:user_id, :product_id, :size, :quantity)";
            $insertStmt = $this->pdo->prepare($insertSql);
            $insertStmt->execute(['user_id' => $userId, 'product_id' => $productId, 'size' => $size, 'quantity' => $quantity]);
        }
    }

    public function updateItem(int $cartItemId, int $quantity): void
    {
        if ($quantity <= 0) {
            $this->removeItem($cartItemId);
            return;
        }
        $sql = "UPDATE cart_items SET quantity = :quantity WHERE cart_item_id = :cart_item_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['quantity' => $quantity, 'cart_item_id' => $cartItemId]);
    }

    public function removeItem(int $cartItemId): void
    {
        $sql = "DELETE FROM cart_items WHERE cart_item_id = :cart_item_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['cart_item_id' => $cartItemId]);
    }

    public function getCartItemById(int $cartItemId): ?array
    {
        $sql = "SELECT ci.*, p.stock_quantity FROM cart_items ci JOIN products p ON ci.product_id = p.product_id WHERE ci.cart_item_id = :cart_item_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['cart_item_id' => $cartItemId]);
        return $stmt->fetch() ?: null;
    }

    public function clearCart(int $userId): void
    {
        $sql = "DELETE FROM cart_items WHERE user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
    }
}
}

/**
 * Order Repository
 */
if (!class_exists('OrderRepository')) {
class OrderRepository
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::connect();
    }

    public function createOrder(array $orderData): int
    {
        $sql = "INSERT INTO orders (user_id, order_number, address_id, payment_method_id, order_status, subtotal, shipping_fees, service_fees, discount_amount, total_amount, promo_code_id) VALUES (:user_id, :order_number, :address_id, :payment_method_id, :order_status, :subtotal, :shipping_fees, :service_fees, :discount_amount, :total_amount, :promo_code_id)";
        $paymentMethodId = null;
        if (!empty($orderData['payment_method_id'])) {
            $pmId = $orderData['payment_method_id'];
            if ($pmId !== null && $pmId !== '' && $pmId !== 0 && $pmId !== '0') {
                $paymentMethodId = (int)$pmId;
            }
        }
        $promoCodeId = null;
        if (!empty($orderData['promo_code_id'])) {
            $pcId = $orderData['promo_code_id'];
            if ($pcId !== null && $pcId !== '' && $pcId !== 0 && $pcId !== '0') {
                $promoCodeId = (int)$pcId;
            }
        }
        $params = [
            'user_id' => (int)$orderData['user_id'],
            'order_number' => (string)$orderData['order_number'],
            'address_id' => (int)$orderData['address_id'],
            'payment_method_id' => $paymentMethodId,
            'order_status' => (string)($orderData['order_status'] ?? 'pending'),
            'subtotal' => (float)$orderData['subtotal'],
            'shipping_fees' => (float)($orderData['shipping_fees'] ?? 40.00),
            'service_fees' => (float)($orderData['service_fees'] ?? 10.00),
            'discount_amount' => (float)($orderData['discount_amount'] ?? 0.00),
            'total_amount' => (float)$orderData['total_amount'],
            'promo_code_id' => $promoCodeId
        ];
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return (int)$this->pdo->lastInsertId();
    }

    public function createOrderItem(array $itemData): void
    {
        $sql = "INSERT INTO order_items (order_id, product_id, product_name, size, color, quantity, unit_price, total_price) VALUES (:order_id, :product_id, :product_name, :size, :color, :quantity, :unit_price, :total_price)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($itemData);
    }

    public function getOrderById(int $orderId): ?array
    {
        $sql = "SELECT o.*, a.area, a.street, a.building_name FROM orders o LEFT JOIN addresses a ON o.address_id = a.address_id WHERE o.order_id = :order_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['order_id' => $orderId]);
        return $stmt->fetch() ?: null;
    }

    public function getOrdersByUserId(int $userId): array
    {
        $sql = "SELECT o.*, COUNT(oi.order_item_id) as item_count FROM orders o LEFT JOIN order_items oi ON o.order_id = oi.order_id WHERE o.user_id = :user_id GROUP BY o.order_id ORDER BY COALESCE(o.order_date, o.created_at) DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function getOrderItems(int $orderId): array
    {
        $sql = "SELECT oi.*, p.image_url FROM order_items oi LEFT JOIN products p ON oi.product_id = p.product_id WHERE oi.order_id = :order_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['order_id' => $orderId]);
        return $stmt->fetchAll();
    }
}
}

/**
 * Address Repository
 */
if (!class_exists('AddressRepository')) {
class AddressRepository
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::connect();
    }

    public function getByUserId(int $userId): array
    {
        $sql = "SELECT address_id, address_type, area, building_name, apartment_number, floor, street, phone, address_label, is_default FROM addresses WHERE user_id = :user_id ORDER BY is_default DESC, created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function create(array $data): int
    {
        $sql = "INSERT INTO addresses (user_id, address_type, area, building_name, apartment_number, floor, street, phone, address_label, is_default) VALUES (:user_id, :address_type, :area, :building_name, :apartment_number, :floor, :street, :phone, :address_label, :is_default)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
        return (int)$this->pdo->lastInsertId();
    }

    public function getById(int $addressId, int $userId): ?array
    {
        $sql = "SELECT address_id, address_type, area, building_name, apartment_number, floor, street, phone, address_label, is_default FROM addresses WHERE address_id = :address_id AND user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'address_id' => $addressId,
            'user_id' => $userId
        ]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function deleteById(int $addressId, int $userId): bool
    {
        $sql = "DELETE FROM addresses WHERE address_id = :address_id AND user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'address_id' => $addressId,
            'user_id' => $userId
        ]);
    }
}
}

/**
 * Payment Method Repository
 */
if (!class_exists('PaymentMethodRepository')) {
class PaymentMethodRepository
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::connect();
    }

    public function getByUserId(int $userId): array
    {
        $sql = "SELECT payment_method_id, card_type, last_four_digits, cardholder_name, expiry_month, expiry_year, is_default FROM payment_methods WHERE user_id = :user_id ORDER BY is_default DESC, created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function getById(int $paymentMethodId, int $userId): ?array
    {
        $sql = "SELECT payment_method_id, card_type, last_four_digits, cardholder_name, expiry_month, expiry_year, is_default FROM payment_methods WHERE payment_method_id = :payment_method_id AND user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'payment_method_id' => $paymentMethodId,
            'user_id' => $userId
        ]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function create(array $data): int
    {
        $sql = "INSERT INTO payment_methods (user_id, card_type, last_four_digits, cardholder_name, expiry_month, expiry_year, is_default) VALUES (:user_id, :card_type, :last_four_digits, :cardholder_name, :expiry_month, :expiry_year, :is_default)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
        return (int)$this->pdo->lastInsertId();
    }

    public function deleteById(int $paymentMethodId, int $userId): bool
    {
        $sql = "DELETE FROM payment_methods WHERE payment_method_id = :payment_method_id AND user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'payment_method_id' => $paymentMethodId,
            'user_id' => $userId
        ]);
    }
}
}

/**
 * Promo Code Repository
 */
if (!class_exists('PromoCodeRepository')) {
class PromoCodeRepository
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::connect();
    }

    public function findByCode(string $code): ?array
    {
        $sql = "SELECT promo_code_id, code, type, value, min_order_amount, max_discount, start_date, end_date, usage_limit FROM promo_codes WHERE code = :code";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['code' => strtoupper(trim($code))]);
        return $stmt->fetch() ?: null;
    }

    public function validateCode(string $code, float $orderAmount): ?array
    {
        $promo = $this->findByCode($code);
        if (!$promo) {
            return null;
        }
        $today = date('Y-m-d');
        if ($promo['start_date'] && $today < $promo['start_date']) {
            return null;
        }
        if ($promo['end_date'] && $today > $promo['end_date']) {
            return null;
        }
        if ($promo['min_order_amount'] > 0 && $orderAmount < $promo['min_order_amount']) {
            return null;
        }
        if ($promo['usage_limit'] !== null) {
            $usageCount = $this->getUsageCount($promo['promo_code_id']);
            if ($usageCount >= $promo['usage_limit']) {
                return null;
            }
        }
        return $promo;
    }

    public function getUsageCount(int $promoCodeId): int
    {
        $sql = "SELECT COUNT(*) as count FROM orders WHERE promo_code_id = :promo_code_id AND order_status != 'cancelled'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['promo_code_id' => $promoCodeId]);
        $result = $stmt->fetch();
        return (int)($result['count'] ?? 0);
    }

    public function recordUsage(int $promoCodeId, int $userId, ?int $orderId = null): int
    {
        $sql = "INSERT INTO promo_code_usage (promo_code_id, user_id, order_id) VALUES (:promo_code_id, :user_id, :order_id)";
        $params = [
            'promo_code_id' => $promoCodeId,
            'user_id' => $userId,
            'order_id' => $orderId !== null ? (int)$orderId : null
        ];
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return (int)$this->pdo->lastInsertId();
    }

    public function calculateDiscount(array $promo, float $orderAmount): float
    {
        if ($promo['type'] === 'percent') {
            $discount = ($orderAmount * $promo['value']) / 100;
            if ($promo['max_discount'] !== null && $discount > $promo['max_discount']) {
                $discount = $promo['max_discount'];
            }
        } else {
            $discount = $promo['value'];
        }
        return min($discount, $orderAmount);
    }

    public function findAll(): array
    {
        $sql = "SELECT promo_code_id, code, type, value, min_order_amount, max_discount, start_date, end_date, usage_limit FROM promo_codes ORDER BY created_at DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll() ?: [];
    }

    public function create(array $data): int
    {
        $sql = "INSERT INTO promo_codes (code, type, value, min_order_amount, max_discount, start_date, end_date, usage_limit) VALUES (:code, :type, :value, :min_order_amount, :max_discount, :start_date, :end_date, :usage_limit)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'code' => strtoupper(trim($data['code'])),
            'type' => $data['type'],
            'value' => (float)$data['value'],
            'min_order_amount' => !empty($data['min_order_amount']) ? (float)$data['min_order_amount'] : null,
            'max_discount' => !empty($data['max_discount']) ? (float)$data['max_discount'] : null,
            'start_date' => !empty($data['start_date']) ? $data['start_date'] : null,
            'end_date' => !empty($data['end_date']) ? $data['end_date'] : null,
            'usage_limit' => !empty($data['usage_limit']) ? (int)$data['usage_limit'] : null
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function delete(int $promoCodeId): void
    {
        // Delete usages first to avoid foreign key constraints (if no cascade)
        $stmt = $this->pdo->prepare("DELETE FROM promo_code_usage WHERE promo_code_id = :promo_code_id");
        $stmt->execute(['promo_code_id' => $promoCodeId]);

        $stmt = $this->pdo->prepare("DELETE FROM promo_codes WHERE promo_code_id = :promo_code_id");
        $stmt->execute(['promo_code_id' => $promoCodeId]);
    }
}
}

/**
 * Review Repository
 */
if (!class_exists('ReviewRepository')) {
class ReviewRepository
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::connect();
    }

    public function create(array $reviewData): int
    {
        $sql = "INSERT INTO product_reviews (product_id, user_id, rating, review_content) VALUES (:product_id, :user_id, :rating, :review_content)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'product_id' => $reviewData['product_id'],
            'user_id' => $reviewData['user_id'],
            'rating' => $reviewData['rating'],
            'review_content' => $reviewData['review_content'] ?? null
        ]);
        $this->updateProductRating($reviewData['product_id']);
        return (int)$this->pdo->lastInsertId();
    }

    public function getByOrderProductUser(int $orderId, int $productId, int $userId): ?array
    {
        $sql = "SELECT r.* FROM product_reviews r JOIN order_items oi ON r.product_id = oi.product_id WHERE oi.order_id = :order_id AND r.product_id = :product_id AND r.user_id = :user_id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['order_id' => $orderId, 'product_id' => $productId, 'user_id' => $userId]);
        return $stmt->fetch() ?: null;
    }

    public function getByProductId(int $productId): array
    {
        $sql = "SELECT r.review_id, r.rating, r.review_content, r.created_at, u.first_name, u.last_name, CONCAT(u.first_name, ' ', u.last_name) AS reviewer_name FROM product_reviews r JOIN users u ON r.user_id = u.user_id WHERE r.product_id = :product_id ORDER BY r.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['product_id' => $productId]);
        return $stmt->fetchAll();
    }

    public function updateProductRating(int $productId): void
    {
        $sql = "UPDATE products SET average_rating = (SELECT AVG(rating) FROM product_reviews WHERE product_id = :product_id), total_reviews = (SELECT COUNT(*) FROM product_reviews WHERE product_id = :product_id) WHERE product_id = :product_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['product_id' => $productId]);
    }
}
}

/**
 * Auth Service
 */
if (!class_exists('AuthService')) {
class AuthService
{
    private $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function register(array $data): int
    {
        $data['password_hash'] = $data['password'];
        unset($data['password']);
        return $this->userRepository->create($data);
    }

    public function login(string $email, string $password): ?array
    {
        $user = $this->userRepository->findByEmail($email);
        if (!$user) {
            return null;
        }
        if ($user['password_hash'] !== $password) {
            return null;
        }
        if (isset($user['account_status']) && $user['account_status'] !== 'active') {
            return null;
        }
        unset($user['password_hash']);
        return $user;
    }

    public function getUserById(int $id): ?array
    {
        return $this->userRepository->findById($id);
    }
}
}

/**
 * Product Service
 */
if (!class_exists('ProductService')) {
class ProductService
{
    private $repository;

    public function __construct()
    {
        $this->repository = new ProductRepository();
    }

    public function getAllProducts(): array
    {
        return $this->repository->findAll();
    }

    public function getProductById(int $id): ?array
    {
        return $this->repository->findById($id);
    }
}
}

/**
 * Cart Service
 */
if (!class_exists('CartService')) {
class CartService
{
    private $cartRepository;
    private $productRepository;

    public function __construct()
    {
        $this->cartRepository = new CartRepository();
        $this->productRepository = new ProductRepository();
    }

    public function getCart(int $userId): array
    {
        $items = $this->cartRepository->getCartByUserId($userId);
        $subtotal = 0;
        foreach ($items as &$item) {
            $price = $item['original_price'] ?? $item['price'];
            $item['line_total'] = $price * $item['quantity'];
            $subtotal += $item['line_total'];
            $product = $this->productRepository->findById($item['product_id']);
            if ($product && isset($product['image_url'])) {
                $item['image_url'] = $product['image_url'];
            }
        }
        return [
            'items' => $items,
            'subtotal' => $subtotal,
            'shipping' => 40.00,
            'service_fee' => 10.00,
            'total' => $subtotal + 40.00 + 10.00
        ];
    }

    public function addItem(int $userId, int $productId, string $size, int $quantity): void
    {
        $userRepository = new UserRepository();
        $user = $userRepository->findById($userId);
        if (!$user) {
            throw new Exception('User not found. Please log in again.');
        }
        $product = $this->productRepository->findById($productId);
        if (!$product) {
            throw new Exception('Product not found');
        }
        if ($product['stock'] < $quantity) {
            throw new Exception('Insufficient stock');
        }
        $this->cartRepository->addItem($userId, $productId, $size, $quantity);
    }

    public function updateItem(int $cartItemId, int $quantity): void
    {
        if ($quantity <= 0) {
            throw new Exception('Quantity must be greater than 0');
        }
        $cartItem = $this->cartRepository->getCartItemById($cartItemId);
        if (!$cartItem) {
            throw new Exception('Cart item not found');
        }
        $product = $this->productRepository->findById($cartItem['product_id']);
        if (!$product) {
            throw new Exception('Product not found');
        }
        if ($product['stock'] < $quantity) {
            throw new Exception('Insufficient stock. Available: ' . $product['stock']);
        }
        $this->cartRepository->updateItem($cartItemId, $quantity);
    }

    public function removeItem(int $cartItemId): void
    {
        $this->cartRepository->removeItem($cartItemId);
    }

    public function clearCart(int $userId): void
    {
        $this->cartRepository->clearCart($userId);
    }

    public function getProductRepository(): ProductRepository
    {
        return $this->productRepository;
    }
}
}

/**
 * Order Service
 */
if (!class_exists('OrderService')) {
class OrderService
{
    private $orderRepository;
    private $cartService;
    private $productRepository;
    private $pdo;

    public function __construct()
    {
        $this->orderRepository = new OrderRepository();
        $this->cartService = new CartService();
        $this->productRepository = new ProductRepository();
        $this->pdo = Database::connect();
    }

    public function placeOrder(int $userId, array $address, array $payment, array $cartItems, array $promoCode = []): int
    {
        $this->pdo->beginTransaction();
        try {
            $addressId = $address['address_id'] ?? null;
            if (!$addressId) {
                throw new Exception('Address is required');
            }
            $subtotal = 0;
            foreach ($cartItems as $item) {
                $price = $item['original_price'] ?? $item['price'];
                $subtotal += $price * $item['quantity'];
            }
            $shipping = 40.00;
            $serviceFee = 10.00;
            $discount = (float)($promoCode['discount_amount'] ?? 0.00);
            $promoCodeIdValue = $promoCode['promo_code_id'] ?? '';
            $promoCodeId = (!empty($promoCodeIdValue) && $promoCodeIdValue !== '0' && (int)$promoCodeIdValue > 0) ? (int)$promoCodeIdValue : null;
            $total = $subtotal + $shipping + $serviceFee - $discount;
            $orderNumber = 'ORD' . str_pad(time(), 8, '0', STR_PAD_LEFT);
            $orderId = $this->orderRepository->createOrder([
                'user_id' => $userId,
                'order_number' => $orderNumber,
                'address_id' => $addressId,
                'payment_method_id' => $payment['payment_method_id'] ?? null,
                'order_status' => 'out_for_shipping',
                'subtotal' => $subtotal,
                'shipping_fees' => $shipping,
                'service_fees' => $serviceFee,
                'discount_amount' => $discount,
                'total_amount' => $total,
                'promo_code_id' => $promoCodeId
            ]);
            foreach ($cartItems as $item) {
                $price = $item['original_price'] ?? $item['price'];
                $this->orderRepository->createOrderItem([
                    'order_id' => $orderId,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['name'],
                    'size' => $item['size'],
                    'color' => null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $price,
                    'total_price' => $price * $item['quantity']
                ]);
                $updateStockSql = "UPDATE products SET stock_quantity = stock_quantity - :quantity, sales_count = sales_count + :quantity WHERE product_id = :product_id";
                $stmt = $this->pdo->prepare($updateStockSql);
                $stmt->execute(['quantity' => $item['quantity'], 'product_id' => $item['product_id']]);
            }
            $this->pdo->commit();
            // Clear cart after successful order - ensure it happens
            try {
                $this->cartService->clearCart($userId);
            } catch (Exception $e) {
                error_log('Failed to clear cart for user ' . $userId . ': ' . $e->getMessage());
                // Retry once if it fails
                try {
                    $this->cartService->clearCart($userId);
                } catch (Exception $e2) {
                    error_log('Failed to clear cart on retry for user ' . $userId . ': ' . $e2->getMessage());
                }
            }
            if ($promoCodeId && $orderId) {
                try {
                    $promoCodeRepository = new PromoCodeRepository();
                    $promoCodeRepository->recordUsage($promoCodeId, $userId, $orderId);
                } catch (Exception $e) {
                    error_log('Failed to record promo code usage for order ' . $orderId . ': ' . $e->getMessage());
                }
            }
            // Award loyalty points for the order (calculate on subtotal, not total with fees)
            try {
                $loyaltyService = new LoyaltyService();
                // Use subtotal for points calculation (exclude shipping and service fees)
                $loyaltyService->awardPointsForOrder($userId, $subtotal, $orderId);
            } catch (Exception $e) {
                error_log('Failed to award loyalty points for order ' . $orderId . ': ' . $e->getMessage());
                // Don't fail the order if loyalty points fail, but log it
            }
            return $orderId;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}
}

// ========================================================
// LOYALTY REPOSITORY
// ========================================================
if (!class_exists('LoyaltyRepository')) {
class LoyaltyRepository
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::connect();
    }

    public function getLoyaltyByUserId(int $userId): ?array
    {
        $sql = "SELECT * FROM loyalty_points WHERE user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function createLoyaltyAccount(int $userId): int
    {
        $sql = "INSERT INTO loyalty_points (user_id, current_balance, total_earned, tier_status, points_to_next_tier) VALUES (:user_id, 0, 0, 'Bronze', 100)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return (int)$this->pdo->lastInsertId();
    }

    public function addPoints(int $userId, int $points, string $description, ?int $orderId = null): void
    {
        // Ensure loyalty account exists
        $loyalty = $this->getLoyaltyByUserId($userId);
        if (!$loyalty) {
            $loyaltyId = $this->createLoyaltyAccount($userId);
            // Re-fetch to ensure we have the correct data
            $loyalty = $this->getLoyaltyByUserId($userId);
            if (!$loyalty) {
                throw new Exception('Failed to create loyalty account for user ' . $userId);
            }
            $loyaltyId = $loyalty['loyalty_id'];
        } else {
            $loyaltyId = $loyalty['loyalty_id'];
        }

        $this->pdo->beginTransaction();
        try {
            // Update loyalty points - use separate parameter names to avoid binding issues
            $updateSql = "UPDATE loyalty_points SET current_balance = current_balance + :points1, total_earned = total_earned + :points2, updated_at = CURRENT_TIMESTAMP WHERE user_id = :user_id";
            $stmt = $this->pdo->prepare($updateSql);
            $stmt->bindValue(':points1', $points, PDO::PARAM_INT);
            $stmt->bindValue(':points2', $points, PDO::PARAM_INT);
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $result = $stmt->execute();
            
            if (!$result || $stmt->rowCount() === 0) {
                throw new Exception('Failed to update loyalty points for user ' . $userId . ' - No rows affected');
            }

            // Create transaction record
            $transSql = "INSERT INTO loyalty_transactions (loyalty_id, user_id, transaction_type, points, description, order_id) VALUES (:loyalty_id, :user_id, 'earned', :points, :description, :order_id)";
            $transStmt = $this->pdo->prepare($transSql);
            $transStmt->bindValue(':loyalty_id', $loyaltyId, PDO::PARAM_INT);
            $transStmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $transStmt->bindValue(':points', $points, PDO::PARAM_INT);
            $transStmt->bindValue(':description', $description, PDO::PARAM_STR);
            $transStmt->bindValue(':order_id', $orderId, $orderId !== null ? PDO::PARAM_INT : PDO::PARAM_NULL);
            $transResult = $transStmt->execute();
            
            if (!$transResult) {
                throw new Exception('Failed to create loyalty transaction record');
            }

            // Update tier status
            $this->updateTierStatus($userId);

            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function redeemPoints(int $userId, int $points, string $description): bool
    {
        $loyalty = $this->getLoyaltyByUserId($userId);
        if (!$loyalty || $loyalty['current_balance'] < $points) {
            return false;
        }

        $this->pdo->beginTransaction();
        try {
            $updateSql = "UPDATE loyalty_points SET current_balance = current_balance - :points1, total_redeemed = total_redeemed + :points2, updated_at = CURRENT_TIMESTAMP WHERE user_id = :user_id";
            $stmt = $this->pdo->prepare($updateSql);
            $stmt->bindValue(':points1', $points, PDO::PARAM_INT);
            $stmt->bindValue(':points2', $points, PDO::PARAM_INT);
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();

            $transSql = "INSERT INTO loyalty_transactions (loyalty_id, user_id, transaction_type, points, description) VALUES (:loyalty_id, :user_id, 'redeemed', :points, :description)";
            $transStmt = $this->pdo->prepare($transSql);
            $transStmt->execute([
                'loyalty_id' => $loyalty['loyalty_id'],
                'user_id' => $userId,
                'points' => $points,
                'description' => $description
            ]);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function getTransactions(int $userId, int $limit = 10): array
    {
        $sql = "SELECT lt.* FROM loyalty_transactions lt 
                INNER JOIN loyalty_points lp ON lt.loyalty_id = lp.loyalty_id 
                WHERE lp.user_id = :user_id 
                ORDER BY lt.created_at DESC 
                LIMIT :limit";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function updateTierStatus(int $userId): void
    {
        $loyalty = $this->getLoyaltyByUserId($userId);
        if (!$loyalty) {
            return;
        }

        $totalEarned = $loyalty['total_earned'];
        $tierStatus = 'Bronze';
        $pointsToNext = 0;

        if ($totalEarned >= 5000) {
            $tierStatus = 'Diamond';
            $pointsToNext = 0;
        } elseif ($totalEarned >= 3000) {
            $tierStatus = 'Platinum';
            $pointsToNext = 5000 - $totalEarned;
        } elseif ($totalEarned >= 1500) {
            $tierStatus = 'Gold';
            $pointsToNext = 3000 - $totalEarned;
        } elseif ($totalEarned >= 500) {
            $tierStatus = 'Silver';
            $pointsToNext = 1500 - $totalEarned;
        } else {
            $tierStatus = 'Bronze';
            $pointsToNext = 500 - $totalEarned;
        }

        $updateSql = "UPDATE loyalty_points SET tier_status = :tier_status, points_to_next_tier = :points_to_next WHERE user_id = :user_id";
        $stmt = $this->pdo->prepare($updateSql);
        $stmt->execute([
            'tier_status' => $tierStatus,
            'points_to_next' => max(0, $pointsToNext),
            'user_id' => $userId
        ]);
    }
}
}

// ========================================================
// LOYALTY SERVICE
// ========================================================
if (!class_exists('LoyaltyService')) {
class LoyaltyService
{
    private $loyaltyRepository;

    public function __construct()
    {
        $this->loyaltyRepository = new LoyaltyRepository();
    }

    public function getLoyaltyPoints(int $userId): array
    {
        $loyalty = $this->loyaltyRepository->getLoyaltyByUserId($userId);
        if (!$loyalty) {
            // Create account if doesn't exist
            $this->loyaltyRepository->createLoyaltyAccount($userId);
            $loyalty = $this->loyaltyRepository->getLoyaltyByUserId($userId);
        }
        return $loyalty ?: [
            'user_id' => $userId,
            'current_balance' => 0,
            'total_earned' => 0,
            'total_redeemed' => 0,
            'tier_status' => 'Bronze',
            'points_to_next_tier' => 500
        ];
    }

    public function awardPointsForOrder(int $userId, float $orderSubtotal, int $orderId): void
    {
        // Award 1 point per 10 EGP spent (rounded down)
        // Points are calculated on subtotal (excluding shipping and service fees)
        $points = (int)floor($orderSubtotal / 10);
        
        if ($points > 0) {
            $this->loyaltyRepository->addPoints(
                $userId,
                $points,
                "Order purchase - " . number_format($orderSubtotal, 2) . " EGP",
                $orderId
            );
        }
    }

    public function getTransactions(int $userId, int $limit = 10): array
    {
        return $this->loyaltyRepository->getTransactions($userId, $limit);
    }
}
}

/**
 * Analytics Service
 */
if (!class_exists('AnalyticsService')) {
class AnalyticsService
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::connect();
    }

    public function getTotalSales(): float
    {
        $sql = "SELECT SUM(total_amount) as total FROM orders WHERE order_status != 'cancelled'";
        $stmt = $this->pdo->query($sql);
        $result = $stmt->fetch();
        return (float)($result['total'] ?? 0);
    }

    public function getUniqueCustomers(): int
    {
        $sql = "SELECT COUNT(DISTINCT user_id) as count FROM orders WHERE order_status != 'cancelled'";
        $stmt = $this->pdo->query($sql);
        $result = $stmt->fetch();
        return (int)($result['count'] ?? 0);
    }

    public function getReturningCustomerRate(): int
    {
        $sql = "SELECT user_id, COUNT(*) as order_count FROM orders WHERE order_status != 'cancelled' GROUP BY user_id";
        $stmt = $this->pdo->query($sql);
        $users = $stmt->fetchAll();
        
        if (empty($users)) return 0;

        $returningCount = 0;
        foreach ($users as $u) {
            if ($u['order_count'] > 1) {
                $returningCount++;
            }
        }

        return (int)round(($returningCount / count($users)) * 100);
    }

    public function getCategoryDemographics(): array
    {
        $sql = "SELECT c.category_name, SUM(oi.quantity) as qty 
                FROM order_items oi 
                JOIN products p ON oi.product_id = p.product_id 
                LEFT JOIN categories c ON p.category_id = c.category_id 
                JOIN orders o ON oi.order_id = o.order_id
                WHERE o.order_status != 'cancelled'
                GROUP BY c.category_name";
        $stmt = $this->pdo->query($sql);
        $results = $stmt->fetchAll();

        $demographics = ['Men' => 0, 'Women' => 0, 'Kids' => 0];
        $total = 0;

        foreach ($results as $row) {
            $cat = strtolower((string)$row['category_name']);
            if (strpos($cat, 'men') !== false && strpos($cat, 'women') === false) $demographics['Men'] += $row['qty'];
            elseif (strpos($cat, 'women') !== false) $demographics['Women'] += $row['qty'];
            elseif (strpos($cat, 'kid') !== false) $demographics['Kids'] += $row['qty'];
            else $demographics['Men'] += $row['qty']; // Default fallback if no category
            $total += $row['qty'];
        }

        if ($total === 0) return ['Men' => 0, 'Women' => 0, 'Kids' => 0];

        return [
            'Men' => (int)round(($demographics['Men'] / $total) * 100),
            'Women' => (int)round(($demographics['Women'] / $total) * 100),
            'Kids' => (int)round(($demographics['Kids'] / $total) * 100),
        ];
    }

    public function getTopProducts(int $limit = 3): array
    {
        $sql = "SELECT p.product_id as id, p.product_name as name, p.image_url, SUM(oi.quantity) as sold_count 
                FROM order_items oi 
                JOIN products p ON oi.product_id = p.product_id 
                JOIN orders o ON oi.order_id = o.order_id
                WHERE o.order_status != 'cancelled'
                GROUP BY p.product_id, p.product_name, p.image_url 
                ORDER BY sold_count DESC 
                LIMIT " . (int)$limit;
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll() ?: [];
    }
}
}
