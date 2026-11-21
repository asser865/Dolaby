<?php
/**
 * POST /api/reviews/submit_review.php
 * Submits a product review. User must have purchased the product.
 * For Postman testing, pass user_id in body alongside product_id, rating, review_content.
 *
 * REQUEST BODY (JSON):
 * {
 *   "product_id": 10,
 *   "rating": 5,
 *   "review_content": "Great product!",
 *   "user_id": 5   // only needed when not in a session (Postman testing)
 * }
 */
header('Content-Type: application/json');
error_reporting(0);
ini_set('display_errors', 0);
require_once __DIR__ . '/../../includes.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Use POST.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true) ?? [];

$user_id        = $_SESSION['user_id'] ?? ($input['user_id'] ?? null);
$product_id     = isset($input['product_id'])     ? (int)$input['product_id']     : 0;
$rating         = isset($input['rating'])         ? (int)$input['rating']         : 0;
$review_content = isset($input['review_content']) ? trim($input['review_content']) : '';

if (!$user_id) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized. Login or pass user_id.']);
    exit;
}

if ($product_id <= 0 || $rating < 1 || $rating > 5) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'product_id and rating (1-5) are required.']);
    exit;
}

try {
    $pdo = Database::connect();

    // Check if user has purchased this product in any delivered/confirmed order
    $purchaseStmt = $pdo->prepare("
        SELECT oi.order_item_id
        FROM order_items oi
        JOIN orders o ON oi.order_id = o.order_id
        WHERE o.user_id = :uid
          AND oi.product_id = :pid
          AND o.order_status IN ('delivered', 'confirmed', 'processing', 'shipped', 'out_for_shipping')
        LIMIT 1
    ");
    $purchaseStmt->execute(['uid' => $user_id, 'pid' => $product_id]);
    if (!$purchaseStmt->fetch()) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'You can only review products you have purchased.']);
        exit;
    }

    // Check for duplicate review
    $dupStmt = $pdo->prepare("SELECT review_id FROM product_reviews WHERE user_id = :uid AND product_id = :pid LIMIT 1");
    $dupStmt->execute(['uid' => $user_id, 'pid' => $product_id]);
    if ($dupStmt->fetch()) {
        http_response_code(409);
        echo json_encode(['success' => false, 'message' => 'You have already reviewed this product.']);
        exit;
    }

    // Insert review
    $reviewRepo = new ReviewRepository();
    $review_id  = $reviewRepo->create([
        'product_id'     => $product_id,
        'user_id'        => $user_id,
        'rating'         => $rating,
        'review_content' => $review_content,
    ]);

    // Update average rating on the product
    $reviewRepo->updateProductRating($product_id);

    echo json_encode([
        'success' => true,
        'message' => 'Review submitted successfully.',
        'data'    => [
            'review_id'      => $review_id,
            'product_id'     => $product_id,
            'rating'         => $rating,
            'review_content' => $review_content,
        ],
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
