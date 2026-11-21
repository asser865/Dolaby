<?php
/**
 * Validate promo code via AJAX
 */
require_once __DIR__ . '/../includes.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'valid' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'valid' => false, 'message' => 'Method not allowed']);
    exit;
}

$promoCode = trim($_POST['promo_code'] ?? '');
$orderAmount = (float)($_POST['order_amount'] ?? 0);

if (empty($promoCode)) {
    echo json_encode(['success' => false, 'valid' => false, 'message' => 'Promo code is required']);
    exit;
}

$promoCodeRepository = new PromoCodeRepository();
$promo = $promoCodeRepository->validateCode($promoCode, $orderAmount);

if ($promo) {
    $discountAmount = $promoCodeRepository->calculateDiscount($promo, $orderAmount);
    echo json_encode([
        'success' => true,
        'valid' => true,
        'promo_code_id' => $promo['promo_code_id'],
        'discount_amount' => $discountAmount,
        'message' => 'Promo code applied successfully'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'valid' => false,
        'message' => 'Invalid or expired promo code'
    ]);
}
exit;

