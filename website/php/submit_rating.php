<?php
/**
 * Submit product rating
 */
header('Content-Type: application/json');
error_reporting(0);
ini_set('display_errors', 0);
require_once __DIR__ . '/../includes.php';

// Support session OR explicit user_id from POST (for Postman E2E tests)
$sessionUserId = $_SESSION['user_id'] ?? ($_POST['user_id'] ?? null);
if (!$sessionUserId) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$orderId    = (int)($_POST['order_id']   ?? 0);
$productId  = (int)($_POST['product_id'] ?? 0);
$rating     = (int)($_POST['rating']     ?? 0);

if ($orderId <= 0 || $productId <= 0 || $rating < 1 || $rating > 5) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit;
}

$orderRepository = new OrderRepository();
$order = $orderRepository->getOrderById($orderId);
if (!$order || $order['user_id'] != $sessionUserId) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Order not found']);
    exit;
}

$reviewRepository = new ReviewRepository();
$existing = $reviewRepository->getByOrderProductUser($orderId, $productId, (int)$sessionUserId);

if ($existing) {
    $updateSql = "
        UPDATE product_reviews 
        SET rating = :rating, updated_at = CURRENT_TIMESTAMP
        WHERE review_id = :review_id
    ";
    $stmt = Database::connect()->prepare($updateSql);
    $stmt->execute([
        'rating'    => $rating,
        'review_id' => $existing['review_id']
    ]);
} else {
    $reviewRepository->create([
        'product_id'     => $productId,
        'user_id'        => (int)$sessionUserId,
        'rating'         => $rating,
        'review_content' => null
    ]);
}

$reviewRepository->updateProductRating($productId);

echo json_encode(['success' => true, 'message' => 'Rating submitted']);
exit;

