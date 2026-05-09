<?php
/**
 * GET /api/reviews/get_reviews.php?product_id=N
 * Returns all reviews and the average rating for a specific product.
 */
header('Content-Type: application/json');
error_reporting(0);
ini_set('display_errors', 0);
require_once __DIR__ . '/../../includes.php';

$product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;
if ($product_id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing or invalid product_id.']);
    exit;
}

try {
    $pdo = Database::connect();
    $chk = $pdo->prepare('SELECT product_id FROM products WHERE product_id = :id');
    $chk->execute(['id' => $product_id]);
    if (!$chk->fetch()) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Product not found.']);
        exit;
    }

    $reviewRepo = new ReviewRepository();
    $reviews    = $reviewRepo->getByProductId($product_id);

    // Calculate average rating
    $total = count($reviews);
    $avg   = $total > 0
        ? round(array_sum(array_column($reviews, 'rating')) / $total, 2)
        : 0;

    // Attach reviewer name from joined data if available
    $formatted = array_map(function ($r) {
        return [
            'review_id'     => $r['review_id'],
            'reviewer_name' => ($r['first_name'] ?? '') . ' ' . ($r['last_name'] ?? ''),
            'rating'        => (int)$r['rating'],
            'review_content'=> $r['review_content'] ?? '',
            'created_at'    => $r['created_at'],
        ];
    }, $reviews);

    echo json_encode([
        'success' => true,
        'data'    => [
            'product_id'    => $product_id,
            'total_reviews' => $total,
            'average_rating'=> $avg,
            'reviews'       => $formatted,
        ],
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
