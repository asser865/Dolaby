<?php
/**
 * GET /api/loyalty/get_loyalty.php
 * Returns the current loyalty balance, tier status, and points_to_next_tier
 * for the logged-in customer. For Postman testing, pass ?user_id=N.
 */
header('Content-Type: application/json');
error_reporting(0);
ini_set('display_errors', 0);
require_once __DIR__ . '/../../includes.php';

$user_id = $_SESSION['user_id'] ?? ($_GET['user_id'] ?? null);
if (!$user_id) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized. Login or pass user_id.']);
    exit;
}

try {
    $loyaltyService = new LoyaltyService();
    $data = $loyaltyService->getLoyaltyPoints((int)$user_id);

    echo json_encode(['success' => true, 'data' => $data]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
