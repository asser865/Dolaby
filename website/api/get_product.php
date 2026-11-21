<?php
require_once __DIR__ . '/../includes.php';
require_once __DIR__ . '/ApiResponse.php';

$productId = $_GET['id'] ?? null;

if (!$productId) {
    ApiResponse::error('Missing product id.', 400);
}

try {
    $pdo = Database::connect();
    $stmt = $pdo->prepare("SELECT product_id, product_name, price, original_price, on_sale FROM products WHERE product_id = :id");
    $stmt->execute(['id' => $productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$product) {
        ApiResponse::error('Product not found.', 404);
    }
    
    // Ensure numeric values
    $product['price'] = (float)$product['price'];
    $product['original_price'] = $product['original_price'] !== null ? (float)$product['original_price'] : null;
    
    ApiResponse::success($product, 'Product fetched successfully');
} catch (Exception $e) {
    ApiResponse::error('Database error: ' . $e->getMessage(), 500);
}
