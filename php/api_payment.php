<?php
/**
 * Payment Processing API
 *
 * PURPOSE: Processes a card payment for an existing order.
 *          Takes real card details, validates them, charges the amount,
 *          and records a payment receipt in the database.
 *          The order status changes to "confirmed" ONLY as a result of a successful payment.
 *
 * METHOD:  POST
 * URL:     http://localhost/dolaby_v2_SQA/api_payment
 *
 * REQUEST BODY (JSON):
 * {
 *   "order_id": 5,
 *   "card_number": "4111111111111111",
 *   "cardholder_name": "Ahmed Yasser",
 *   "expiry_month": "12",
 *   "expiry_year": "2027",
 *   "cvv": "123"
 * }
 *
 * SUCCESS RESPONSE (200):
 * {
 *   "success": true,
 *   "message": "Payment of EGP 600.00 charged successfully.",
 *   "receipt": {
 *     "transaction_ref": "PAY-ABC123",
 *     "order_number": "ORD001",
 *     "amount_charged": 600.00,
 *     "card_charged": "**** **** **** 1111",
 *     "status": "approved"
 *   }
 * }
 *
 * FAILURE RESPONSES:
 *   400 - Missing fields / validation error (expired card, bad CVV format)
 *   404 - Order not found
 *   409 - Order already paid
 *   402 - Payment declined (simulated)
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../includes.php';

// ── 1. Only accept POST ───────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed. Use POST.']);
    exit;
}

// ── 2. Read JSON input ────────────────────────────────────────────────────────
$input = json_decode(file_get_contents('php://input'), true);

$orderId        = isset($input['order_id'])        ? (int)trim($input['order_id'])           : null;
$cardNumber     = isset($input['card_number'])     ? preg_replace('/\s+/', '', $input['card_number']) : null;
$cardHolder     = isset($input['cardholder_name']) ? trim($input['cardholder_name'])          : null;
$expiryMonth    = isset($input['expiry_month'])    ? (int)$input['expiry_month']              : null;
$expiryYear     = isset($input['expiry_year'])     ? (int)$input['expiry_year']               : null;
$cvv            = isset($input['cvv'])             ? trim($input['cvv'])                      : null;

// ── 3. Validate required fields ───────────────────────────────────────────────
if (!$orderId || $orderId <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing or invalid "order_id".']);
    exit;
}
if (!$cardNumber || !$cardHolder || !$expiryMonth || !$expiryYear || !$cvv) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing card details. Required: card_number, cardholder_name, expiry_month, expiry_year, cvv.']);
    exit;
}

// ── 4. Validate card format ───────────────────────────────────────────────────

// Card number must be 13–19 digits
if (!preg_match('/^\d{13,19}$/', $cardNumber)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid card number format.']);
    exit;
}

// CVV must be 3 or 4 digits
if (!preg_match('/^\d{3,4}$/', $cvv)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid CVV. Must be 3 or 4 digits.']);
    exit;
}

// Card must not be expired
$currentYear  = (int)date('Y');
$currentMonth = (int)date('n');
if ($expiryYear < $currentYear || ($expiryYear === $currentYear && $expiryMonth < $currentMonth)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Card is expired.']);
    exit;
}

// ── 5. Fetch the order from the database ──────────────────────────────────────
try {
    $pdo = Database::connect();

    $stmt = $pdo->prepare("SELECT order_id, order_number, order_status, total_amount FROM orders WHERE order_id = :id");
    $stmt->execute(['id' => $orderId]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => "Order #$orderId not found."]);
        exit;
    }

    // ── 6. Check if order is already paid ────────────────────────────────────
    // Also ensure the status column supports our values
    try {
        $pdo->exec("ALTER TABLE payment_transactions MODIFY COLUMN status ENUM('pending','success','failed') NOT NULL DEFAULT 'pending'");
    } catch (Exception $alterEx) { /* already correct, ignore */ }

    $checkStmt = $pdo->prepare("SELECT transaction_id FROM payment_transactions WHERE order_id = :id AND status = 'success'");
    $checkStmt->execute(['id' => $orderId]);
    if ($checkStmt->fetch()) {
        http_response_code(409);
        echo json_encode([
            'success'      => false,
            'message'      => "Order #$orderId has already been paid.",
            'order_number' => $order['order_number'],
        ]);
        exit;
    }

    $amount = (float)$order['total_amount'];

    // ── 7. Simulate card charge (payment processing logic) ────────────────────
    // In a real system, this is where you call Stripe/PayPal/Paymob.
    // Here we simulate: any valid card is approved UNLESS the amount is exactly 0
    // or the card number starts with "0000" (simulates a declined card for testing).

    $declined = (str_starts_with($cardNumber, '0000') || $amount <= 0);

    if ($declined) {
        // Log the declined attempt as 'failed'
        $logStmt = $pdo->prepare(
            "INSERT INTO payment_transactions (order_id, amount, status, gateway_transaction_id) VALUES (:oid, :amt, 'failed', :ref)"
        );
        $logStmt->execute([
            'oid' => $orderId,
            'amt' => $amount,
            'ref' => 'PAY-' . strtoupper(uniqid()),
        ]);

        http_response_code(402);
        echo json_encode([
            'success' => false,
            'message' => 'Payment declined. Card was rejected by the payment processor.',
            'order_id' => $orderId,
        ]);
        exit;
    }

    // ── 8. Payment approved — log the receipt as 'success' ───────────────────
    $transactionRef = 'PAY-' . strtoupper(uniqid());
    $maskedCard     = '**** **** **** ' . substr($cardNumber, -4);

    $logStmt = $pdo->prepare(
        "INSERT INTO payment_transactions (order_id, amount, status, gateway_transaction_id) VALUES (:oid, :amt, 'success', :ref)"
    );
    $logStmt->execute([
        'oid' => $orderId,
        'amt' => $amount,
        'ref' => $transactionRef,
    ]);
    $transactionId = (int)$pdo->lastInsertId();

    // ── 9. Update the order status to "confirmed" as a result of payment ──────
    $updateStmt = $pdo->prepare("UPDATE orders SET order_status = 'confirmed' WHERE order_id = :id");
    $updateStmt->execute(['id' => $orderId]);

    // ── 10. Return the payment receipt ────────────────────────────────────────
    echo json_encode([
        'success' => true,
        'message' => "Payment of EGP " . number_format($amount, 2) . " charged successfully.",
        'receipt' => [
            'transaction_id'  => $transactionId,
            'transaction_ref' => $transactionRef,
            'order_id'        => $orderId,
            'order_number'    => $order['order_number'],
            'amount_charged'  => $amount,
            'card_charged'    => $maskedCard,
            'cardholder'      => $cardHolder,
            'status'          => 'success',
        ],
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
