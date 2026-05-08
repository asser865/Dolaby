<?php
require_once __DIR__ . '/../../includes.php';
require_once __DIR__ . '/../ApiResponse.php';

// 1. Authenticate Brand Owner
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'brand_owner') {
    ApiResponse::error('Unauthorized. Brand Owner access required.', 401);
}

// 2. Validate Request Method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    ApiResponse::error('Method not allowed. Use POST.', 405);
}

// 3. Get JSON Payload
$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    // Fallback for form-data
    $input = $_POST;
}

$productId = $input['product_id'] ?? null;
$newPrice = $input['new_price'] ?? null;
$originalPrice = $input['original_price'] ?? null;

if (!$productId || !$newPrice) {
    ApiResponse::error('Missing required parameters: product_id, new_price.', 400);
}

try {
    $pdo = Database::connect();
    
    // Check if product exists
    $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = :id");
    $stmt->execute(['id' => $productId]);
    $product = $stmt->fetch();
    
    if (!$product) {
        ApiResponse::error('Product not found.', 404);
    }
    
    // If an original price is passed, it's a sale. Otherwise, it's just a standard price update.
    $isOnSale = ($originalPrice && $originalPrice > $newPrice) ? 1 : 0;
    
    $updateSql = "UPDATE products 
                  SET price = :new_price, 
                      original_price = :original_price, 
                      on_sale = :on_sale,
                      updated_at = CURRENT_TIMESTAMP 
                  WHERE product_id = :id";
                  
    $updateStmt = $pdo->prepare($updateSql);
    $updateStmt->execute([
        'new_price' => $newPrice,
        'original_price' => $originalPrice,
        'on_sale' => $isOnSale,
        'id' => $productId
    ]);
    
    ApiResponse::success([
        'product_id' => $productId,
        'product_name' => $product['product_name'],
        'price' => $newPrice,
        'original_price' => $originalPrice,
        'on_sale' => (bool)$isOnSale
    ], "Price for {$product['product_name']} updated to {$newPrice} EGP.");

} catch (Exception $e) {
    ApiResponse::error('Database error: ' . $e->getMessage(), 500);
}
