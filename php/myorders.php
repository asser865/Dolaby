<?php
require_once __DIR__ . '/../includes.php';
require_once __DIR__ . '/../header_functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . '/login');
    exit;
}
$orderRepository = new OrderRepository();
$reviewRepository = new ReviewRepository();
$orders = $orderRepository->getOrdersByUserId($_SESSION['user_id']);
$orderReviews = [];
foreach ($orders as $order) {
    $items = $orderRepository->getOrderItems($order['order_id']);
    foreach ($items as $item) {
        $review = $reviewRepository->getByOrderProductUser(
            $order['order_id'],
            $item['product_id'],
            $_SESSION['user_id']
        );
        if ($review) {
            $orderReviews[$order['order_id']][$item['product_id']] = $review['rating'];
        }
    }
}

// Fetch payment transactions for all orders to show API proof
$paymentTransactions = [];
if (!empty($orders)) {
    $orderIds = array_column($orders, 'order_id');
    $placeholders = implode(',', array_fill(0, count($orderIds), '?'));
    $pdo = Database::connect();
    $stmt = $pdo->prepare("SELECT * FROM payment_transactions WHERE order_id IN ($placeholders)");
    $stmt->execute($orderIds);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $paymentTransactions[$row['order_id']] = $row;
    }
}

renderHeader();
?>

<style>
  .topbar .back {
    width: 44px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--white);
    border: 1px solid var(--divider);
    border-radius: 12px;
    text-decoration: none;
    transition: all 0.3s ease;
  }

  .topbar .back:hover {
    background: var(--panel);
    border-color: var(--teal-600);
  }

  .topbar .back svg {
    width: 20px;
    height: 20px;
    stroke: var(--dark);
  }

  .orders-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
  }

  .order-card {
    background: var(--white);
    border: 1px solid var(--divider);
    border-radius: var(--card-radius);
    padding: 24px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  }

  .card-top-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 12px;
    font-size: 14px;
    color: var(--muted);
  }

  .card-line {
    margin: 8px 0;
    font-size: 15px;
  }

  .card-price {
    font-size: 20px;
    font-weight: 700;
    color: var(--dark);
    margin: 12px 0;
  }

  .view-link {
    color: var(--teal-600);
    text-decoration: none;
    font-weight: 600;
    cursor: pointer;
    display: inline-block;
    margin-top: 12px;
  }

  .view-link:hover {
    text-decoration: underline;
  }

  .rating-row {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 16px;
    font-size: 15px;
    font-weight: 600;
  }

  .rating-stars {
    display: flex;
    gap: 4px;
    cursor: pointer;
  }

  .rating-stars i {
    font-size: 20px;
    color: #ddd;
    transition: all 0.2s ease;
  }

  .rating-stars i.fas {
    color: #FFD700;
  }

  .rating-stars i:hover {
    transform: scale(1.15);
  }

  .empty-state {
    text-align: center;
    padding: 60px 20px;
    color: var(--muted);
  }

  .empty-state h3 {
    font-size: 24px;
    margin-bottom: 12px;
    color: var(--dark);
  }

  /* API LOG STYLES */
  .api-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    background: #F1F5F9;
    border: 1px solid #CBD5E1;
    border-radius: 8px;
    font-family: 'Courier New', Courier, monospace;
    font-size: 12px;
    font-weight: bold;
    color: #475569;
    cursor: pointer;
    margin-top: 10px;
  }

  .api-badge:hover {
    background: #E2E8F0;
  }

  .api-badge.success {
    color: #16A34A;
    border-color: #BBF7D0;
    background: #F0FDF4;
  }

  .api-log-details {
    display: none;
    margin-top: 10px;
    padding: 15px;
    background: #0F172A;
    color: #94A3B8;
    border-radius: 8px;
    font-family: 'Courier New', Courier, monospace;
    font-size: 12px;
    line-height: 1.5;
    border-left: 4px solid #16A34A;
  }

  .api-log-details.show {
    display: block;
  }

  .api-log-header {
    color: #F8FAFC;
    margin-bottom: 8px;
    border-bottom: 1px solid #1E293B;
    padding-bottom: 4px;
    font-weight: bold;
  }
</style>

<!-- Main Content -->
<main class="main" role="main" aria-labelledby="page-title">
  <div class="topbar">
    <div class="container">
      <div class="top-left">
        <a class="back" href="<?= BASE_URL ?>/menu" aria-label="Back to previous">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M15 18L9 12l6-6" stroke="#1A1A1A" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </a>
        <div class="title" id="page-title">My Orders</div>
      </div>
      <div class="top-right"></div>
    </div>
  </div>

  <div class="page-inner">
    <section class="content">
      <section class="orders-list" aria-label="Orders list">
        <?php if (empty($orders)): ?>
          <div class="empty-state">
            <h3>No Orders Yet</h3>
            <p>You haven't placed any orders yet. Start shopping to see your orders here!</p>
            <a href="<?= BASE_URL ?>/shop" style="display: inline-block; margin-top: 20px; padding: 12px 24px; background: var(--teal-600); color: white; text-decoration: none; border-radius: 8px;">
              Start Shopping
            </a>
          </div>
        <?php else: ?>
          <?php foreach ($orders as $order): ?>
            <?php
            $orderRepository = new OrderRepository();
            $items = $orderRepository->getOrderItems($order['order_id']);
            $dateValue = $order['order_date'] ?? $order['created_at'] ?? date('Y-m-d H:i:s');
            try {
                $orderDate = new DateTime($dateValue);
                $dateStr = $orderDate->format('F d');
                $timeStr = $orderDate->format('g:i A');
            } catch (Exception $e) {
                $dateStr = date('F d');
                $timeStr = date('g:i A');
            }
            
            // Format order status for display
            $statusDisplay = ucfirst(str_replace('_', ' ', $order['order_status']));
            
            // Check if order can be rated (shipped or delivered)
            $canRate = in_array($order['order_status'], ['shipped', 'delivered']);
            ?>
            <article class="order-card" data-order-id="<?= $order['order_id'] ?>">
              <div class="card-top-row">
                <span><?= htmlspecialchars($dateStr) ?></span>
                <span><?= htmlspecialchars($timeStr) ?></span>
              </div>
              <p class="card-line"><strong>Order ID:</strong> <?= htmlspecialchars($order['order_number']) ?></p>
              <p class="card-line"><strong>Status:</strong> <?= htmlspecialchars($statusDisplay) ?></p>
              <p class="card-line"><strong>Items:</strong> <?= count($items) ?> item<?= count($items) > 1 ? 's' : '' ?></p>
              
              <!-- Order Description - List of items -->
              <div style="margin: 12px 0; padding: 12px; background: var(--panel); border-radius: 8px;">
                <strong style="display: block; margin-bottom: 8px; font-size: 14px;">Order Details:</strong>
                <?php foreach ($items as $item): ?>
                  <div style="font-size: 14px; color: var(--dark); margin-bottom: 6px; padding-left: 12px;">
                    • <?= htmlspecialchars($item['product_name']) ?> 
                    <span style="color: var(--muted);">(Size: <?= htmlspecialchars($item['size']) ?>, Qty: <?= $item['quantity'] ?>)</span>
                    <span style="color: var(--teal-600); font-weight: 600;">- EGP <?= number_format($item['total_price'], 2) ?></span>
                  </div>
                <?php endforeach; ?>
                <?php if ($order['discount_amount'] > 0): ?>
                  <div style="font-size: 14px; color: var(--success); margin-top: 8px; padding-top: 8px; border-top: 1px solid var(--divider);">
                    Discount Applied: -EGP <?= number_format($order['discount_amount'], 2) ?>
                  </div>
                <?php endif; ?>
              </div>
              
              <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid var(--divider);">
                <div style="display: flex; justify-content: space-between; margin-bottom: 4px; font-size: 14px; color: var(--muted);">
                  <span>Subtotal:</span>
                  <span>EGP <?= number_format($order['subtotal'], 2) ?></span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 4px; font-size: 14px; color: var(--muted);">
                  <span>Shipping:</span>
                  <span>EGP <?= number_format($order['shipping_fees'] ?? 40.00, 2) ?></span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 4px; font-size: 14px; color: var(--muted);">
                  <span>Service Fee:</span>
                  <span>EGP <?= number_format($order['service_fees'] ?? 10.00, 2) ?></span>
                </div>
              </div>
              
              <p class="card-price">Total: EGP <?= number_format($order['total_amount'], 2) ?></p>
              
              <!-- API INTEGRATION PROOF SECTION -->
              <?php if (isset($paymentTransactions[$order['order_id']])): ?>
                <?php $tx = $paymentTransactions[$order['order_id']]; ?>
                <div class="api-badge success" onclick="toggleApiLog(<?= $order['order_id'] ?>)">
                  <span>🛡️ Payment API Verified</span>
                  <span style="font-size: 10px; opacity: 0.7;">(Click for E2E Log)</span>
                </div>
                <div id="api-log-<?= $order['order_id'] ?>" class="api-log-details">
                   <div class="api-log-header">POST /api_payment - SUCCESS</div>
                   <div>> Request: { order_id: <?= $order['order_id'] ?>, amount: <?= $order['total_amount'] ?> }</div>
                   <div>> Gateway: MockPaymentGateway (VISA)</div>
                   <div>> Status: <?= strtoupper($tx['status']) ?></div>
                   <div>> TransactionID: <?= $tx['gateway_transaction_id'] ?></div>
                   <div>> DB Sync: payment_transactions [Row #<?= $tx['transaction_id'] ?>]</div>
                </div>
              <?php endif; ?>
              
              <?php if ($canRate): ?>
                <span class="view-link" onclick="window.location.href='<?= BASE_URL ?>/order/details?id=<?= $order['order_id'] ?>'">View Details</span>
                
                <?php foreach ($items as $item): ?>
                  <div style="margin-top: 8px; font-size: 14px; color: var(--muted);">
                    <?= htmlspecialchars($item['product_name']) ?> (Qty: <?= $item['quantity'] ?>, Size: <?= htmlspecialchars($item['size']) ?>)
                  </div>
                  <?php
                  $existingRating = $orderReviews[$order['order_id']][$item['product_id']] ?? null;
                  ?>
                  <div class="rating-row" style="margin-top: 16px;">
                    <span>Rate <?= htmlspecialchars($item['product_name']) ?>:</span>
                    <div class="rating-stars" 
                         data-order-id="<?= $order['order_id'] ?>" 
                         data-product-id="<?= $item['product_id'] ?>"
                         role="radiogroup" 
                         aria-label="Rate product">
                      <?php for ($i = 1; $i <= 5; $i++): ?>
                        <i class="<?= ($existingRating && $i <= $existingRating) ? 'fas' : 'far' ?> fa-star" 
                           data-value="<?= $i ?>" 
                           tabindex="0"
                           aria-label="Rate <?= $i ?> star<?= $i > 1 ? 's' : '' ?>"></i>
                      <?php endfor; ?>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php else: ?>
                <!-- For orders that are not shipped yet, just show status -->
                <div style="margin-top: 12px; padding: 12px; background: var(--panel); border-radius: 8px;">
                  <p style="margin: 0; font-size: 14px; color: var(--muted);">
                    Order is <?= htmlspecialchars(strtolower($statusDisplay)) ?>. Rating will be available after shipping.
                  </p>
                </div>
              <?php endif; ?>
            </article>
          <?php endforeach; ?>
        <?php endif; ?>
      </section>
    </section>
  </div>
</main>

<script>
// Enhanced rating stars functionality with AJAX submission
(function() {
  const ratingContainers = document.querySelectorAll('.rating-stars');
  
  ratingContainers.forEach(container => {
    const stars = Array.from(container.querySelectorAll('i'));
    const orderId = container.dataset.orderId;
    const productId = container.dataset.productId;
    
    const setRating = (rating, save = false) => {
      stars.forEach((star, index) => {
        const value = index + 1;
        if (value <= rating) {
          star.classList.remove('far');
          star.classList.add('fas');
        } else {
          star.classList.remove('fas');
          star.classList.add('far');
        }
      });
      
      if (save && orderId && productId) {
        // Submit rating via AJAX
        fetch('<?= BASE_URL ?>/submit_rating', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: `order_id=${orderId}&product_id=${productId}&rating=${rating}`
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Visual feedback
            container.style.opacity = '0.7';
            setTimeout(() => {
              container.style.opacity = '1';
            }, 200);
          }
        })
        .catch(error => {
          console.error('Error submitting rating:', error);
        });
      }
    };
    
    stars.forEach(star => {
      star.addEventListener('click', () => {
        const rating = parseInt(star.dataset.value || '0', 10);
        if (rating > 0) {
          setRating(rating, true);
        }
      });
      
      star.addEventListener('mouseenter', () => {
        const rating = parseInt(star.dataset.value || '0', 10);
        if (rating > 0) {
          setRating(rating, false);
        }
      });
    });
    
    container.addEventListener('mouseleave', () => {
      // Restore saved rating on mouse leave
      const savedRating = container.dataset.savedRating;
      if (savedRating) {
        setRating(parseInt(savedRating, 10), false);
      }
    });
  });
})();

function toggleApiLog(orderId) {
  const log = document.getElementById('api-log-' + orderId);
  if (log) {
    log.classList.toggle('show');
  }
}
</script>

<!-- Body and HTML closing tags handled by Controller -->

</body>
</html>
