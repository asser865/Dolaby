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

$promoId = $input['promo_id'] ?? null;
$status = $input['status'] ?? null; // 'active' or 'inactive'

if (!$promoId || !$status || !in_array($status, ['active', 'inactive'])) {
    ApiResponse::error('Missing or invalid parameters. Required: promo_id, status (active/inactive).', 400);
}

try {
    $pdo = Database::connect();
    
    // Check if promo exists
    $stmt = $pdo->prepare("SELECT * FROM promo_codes WHERE promo_code_id = :id");
    $stmt->execute(['id' => $promoId]);
    $promo = $stmt->fetch();
    
    if (!$promo) {
        ApiResponse::error('Promo code not found.', 404);
    }
    
    // Toggle Logic:
    // Inactive = Set is_active to 0
    // Active = Set is_active to 1
    if ($status === 'inactive') {
        $updateSql = "UPDATE promo_codes SET is_active = 0 WHERE promo_code_id = :id";
    } else {
        $updateSql = "UPDATE promo_codes SET is_active = 1 WHERE promo_code_id = :id";
    }
    
    $updateStmt = $pdo->prepare($updateSql);
    $updateStmt->execute(['id' => $promoId]);
    
    ApiResponse::success([
        'promo_id' => $promoId,
        'code' => $promo['code'],
        'new_status' => $status
    ], "Promo code {$promo['code']} successfully marked as {$status}.");

} catch (Exception $e) {
    ApiResponse::error('Database error: ' . $e->getMessage(), 500);
}
