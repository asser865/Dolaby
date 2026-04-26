<?php
require_once __DIR__ . '/../includes.php';
require_once __DIR__ . '/../header_functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . '/login');
    exit;
}

$userRepository = new UserRepository();
$user = $userRepository->findById($_SESSION['user_id']);

$loyaltyService = new LoyaltyService();
$loyaltyData = $loyaltyService->getLoyaltyPoints($_SESSION['user_id']);
$transactions = $loyaltyService->getTransactions($_SESSION['user_id'], 5);

// Get featured products for collections
$productRepository = new ProductRepository();
$featuredProducts = $productRepository->findAll();
// Get top 3 products for display
$collectionProducts = array_slice($featuredProducts, 0, 3);

// Calculate points value (1 point = 0.1 EGP)
$pointsValue = number_format($loyaltyData['current_balance'] * 0.1, 2);

// Get tier info
$tierStatus = $loyaltyData['tier_status'] ?? 'Bronze';
$pointsToNext = $loyaltyData['points_to_next_tier'] ?? 500;

renderHeader();
?>

<style>
  .loyalty-page-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 32px;
  }

  .back-arrow {
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

  .back-arrow:hover {
    background: var(--panel);
    border-color: var(--teal-600);
  }

  .back-arrow svg {
    stroke: var(--dark);
  }

  .loyalty-page-header h1 {
    font-size: 24px;
    font-weight: 800;
    color: var(--dark);
    margin: 0;
    flex: 1;
  }

  .loyalty-greetings {
    font-size: 14px;
    color: var(--muted);
  }

  .loyalty-metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
  }

  .loyalty-feature-card {
    background: var(--white);
    border: 1px solid var(--divider);
    border-radius: 16px;
    padding: 24px;
    text-align: center;
  }

  .feature-meta {
    font-size: 14px;
    color: var(--muted);
    margin-bottom: 8px;
  }

  .feature-points {
    font-size: 36px;
    font-weight: 800;
    color: var(--teal-600);
    margin-bottom: 4px;
  }

  .feature-value {
    font-size: 14px;
    color: var(--muted);
    margin-bottom: 16px;
  }

  .loyalty-action-button {
    width: 100%;
    padding: 12px;
    background: var(--teal-600);
    color: var(--white);
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  .loyalty-action-button:hover {
    background: var(--teal-500);
  }

  .loyalty-info-card {
    background: var(--white);
    border: 1px solid var(--divider);
    border-radius: 16px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  .loyalty-info-card:hover {
    border-color: var(--teal-600);
    box-shadow: 0 2px 8px rgba(0, 184, 212, 0.1);
  }

  .offers-count {
    font-size: 32px;
    font-weight: 800;
    color: var(--teal-600);
  }

  .offers-text-block {
    flex: 1;
  }

  .offers-label {
    font-size: 16px;
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 4px;
  }

  .offers-view-all {
    font-size: 13px;
    color: var(--muted);
  }

  .loyalty-arrow {
    color: var(--muted);
    font-size: 16px;
  }

  .status-icon {
    font-size: 32px;
    color: var(--teal-600);
  }

  .loyalty-promotions {
    margin-top: 40px;
  }

  .promotions-heading {
    font-size: 20px;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 20px;
  }

  .loyalty-collection-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
  }

  .collection-item {
    background: var(--white);
    border: 1px solid var(--divider);
    border-radius: 12px;
    padding: 0;
    text-align: center;
    transition: all 0.3s ease;
    overflow: hidden;
    cursor: pointer;
    position: relative;
  }

  .collection-item:hover {
    border-color: var(--teal-600);
    box-shadow: 0 2px 8px rgba(0, 184, 212, 0.1);
    transform: translateY(-2px);
  }

  .collection-image {
    width: 100%;
    height: 220px;
    object-fit: cover;
    background: var(--panel);
    display: block;
  }

  .collection-content {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to top, rgba(0, 0, 0, 0.75) 0%, rgba(0, 0, 0, 0.5) 50%, transparent 100%);
    padding: 20px 16px 16px;
    color: var(--white);
  }

  .collection-placeholder {
    font-size: 11px;
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 600;
  }

  .collection-label {
    font-size: 16px;
    font-weight: 600;
    color: var(--white);
    margin-bottom: 6px;
    text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
  }

  .collection-meta {
    font-size: 13px;
    color: rgba(255, 255, 255, 0.85);
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
  }

  .tier-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    margin-top: 8px;
  }

  .tier-bronze {
    background: #CD7F32;
    color: white;
  }

  .tier-silver {
    background: #C0C0C0;
    color: white;
  }

  .tier-gold {
    background: #FFD700;
    color: #333;
  }

  .tier-platinum {
    background: #E5E4E2;
    color: #333;
  }

  .tier-diamond {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
  }
</style>

<main class="main" role="main" aria-labelledby="loyalty-main-title">
  <div class="container">
    <header class="loyalty-page-header">
      <a href="<?= BASE_URL ?>/account" class="back-arrow" aria-label="Go back to profile">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
          <path d="M15 18L9 12l6-6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"
            stroke-linejoin="round" />
        </svg>
      </a>
      <h1 id="loyalty-main-title">Loyalty Points</h1>
      <?php if (isset($user) && $user): ?>
        <div class="loyalty-greetings">Hello, <?= htmlspecialchars($user['first_name'] ?? 'User') ?></div>
      <?php endif; ?>
    </header>

    <section class="loyalty-metrics-grid">
      <div class="loyalty-feature-card">
        <div class="feature-meta">Your Current Balance</div>
        <div class="feature-points"><?= number_format($loyaltyData['current_balance']) ?></div>
        <div class="feature-value">Worth <?= $pointsValue ?> EGP</div>
        <button class="loyalty-action-button" onclick="showTransactions()">View Transaction History</button>
      </div>

      <div class="loyalty-info-card">
        <div class="offers-count"><?= count($collectionProducts) ?></div>
        <div class="offers-text-block">
          <div class="offers-label">Featured Collections</div>
          <div class="offers-view-all">Redeem points for rewards</div>
        </div>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" style="color: var(--muted);">
          <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </div>

      <div class="loyalty-info-card">
        <div class="status-icon">
          <?php
          $tierIcons = [
            'Bronze' => '🥉',
            'Silver' => '🥈',
            'Gold' => '🥇',
            'Platinum' => '💎',
            'Diamond' => '💠'
          ];
          echo $tierIcons[$tierStatus] ?? '🏅';
          ?>
        </div>
        <div class="offers-text-block">
          <div class="offers-label"><?= $tierStatus ?> Tier Status</div>
          <div class="offers-view-all">
            <?php if ($pointsToNext > 0): ?>
              Need <?= number_format($pointsToNext) ?> more points for 
              <?php
              $nextTiers = [
                'Bronze' => 'Silver',
                'Silver' => 'Gold',
                'Gold' => 'Platinum',
                'Platinum' => 'Diamond',
                'Diamond' => 'Diamond'
              ];
              echo $nextTiers[$tierStatus] ?? 'next tier';
              ?>
            <?php else: ?>
              You've reached the highest tier!
            <?php endif; ?>
          </div>
        </div>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" style="color: var(--muted);">
          <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </div>
    </section>

    <section class="loyalty-promotions">
      <h2 class="promotions-heading">View Collection & Rewards</h2>

      <div class="loyalty-collection-grid">
        <?php if (!empty($collectionProducts)): ?>
          <?php foreach ($collectionProducts as $index => $product): ?>
            <a href="<?= BASE_URL ?>/product?id=<?= $product['id'] ?>" class="collection-item" style="text-decoration: none; color: inherit;">
              <img src="<?= rtrim(BASE_URL, '/') ?><?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="collection-image" onerror="this.src='<?= rtrim(BASE_URL, '/') ?>/assets/images/shop/Harbaa.png'">
              <div class="collection-content">
                <div class="collection-label"><?= htmlspecialchars($product['name']) ?></div>
                <div class="collection-meta">
                  <?php
                  $pointRequirements = [500, 300, 200];
                  $req = $pointRequirements[$index] ?? 250;
                  echo $req . ' points or ' . number_format($product['price'], 2) . ' EGP';
                  ?>
                </div>
              </div>
            </a>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="collection-item">
            <img src="<?= rtrim(BASE_URL, '/') ?>/assets/images/shop/Harbaa.png" alt="Harbaa Collection" class="collection-image" onerror="this.src='<?= rtrim(BASE_URL, '/') ?>/assets/images/shop/1.png'">
            <div class="collection-content">
              <div class="collection-label">Harbaa. Collection</div>
              <div class="collection-meta">500 points or more</div>
            </div>
          </div>
          <div class="collection-item">
            <img src="<?= rtrim(BASE_URL, '/') ?>/assets/images/shop/CieloDenim.png" alt="Winter Comfort" class="collection-image" onerror="this.src='<?= rtrim(BASE_URL, '/') ?>/assets/images/shop/1.png'">
            <div class="collection-content">
              <div class="collection-label">Discover the Winter Comfort</div>
              <div class="collection-meta">Spend 20 EGP or more</div>
            </div>
          </div>
          <div class="collection-item">
            <img src="<?= rtrim(BASE_URL, '/') ?>/assets/images/shop/Basic Hoodie.png" alt="Vouchers" class="collection-image" onerror="this.src='<?= rtrim(BASE_URL, '/') ?>/assets/images/shop/1.png'">
            <div class="collection-content">
              <div class="collection-label">10% OFF Vouchers</div>
              <div class="collection-meta">Redeem 100 points</div>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </section>

    <?php if (!empty($transactions)): ?>
    <section class="loyalty-promotions" style="margin-top: 40px;">
      <h2 class="promotions-heading">Recent Transactions</h2>
      <div style="background: var(--white); border: 1px solid var(--divider); border-radius: 16px; padding: 20px;">
        <?php foreach ($transactions as $transaction): ?>
          <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid var(--divider);">
            <div>
              <div style="font-weight: 600; color: var(--dark); margin-bottom: 4px;">
                <?= htmlspecialchars($transaction['description'] ?? 'Transaction') ?>
              </div>
              <div style="font-size: 12px; color: var(--muted);">
                <?= date('M d, Y', strtotime($transaction['created_at'])) ?>
              </div>
            </div>
            <div style="font-weight: 700; color: <?= $transaction['transaction_type'] === 'earned' ? 'var(--teal-600)' : 'var(--error)' ?>;">
              <?= $transaction['transaction_type'] === 'earned' ? '+' : '-' ?><?= number_format($transaction['points']) ?> pts
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </section>
    <?php endif; ?>
  </div>
</main>

<script>
function showTransactions() {
  const transactionsSection = document.querySelector('.loyalty-promotions:last-of-type');
  if (transactionsSection) {
    transactionsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }
}
</script>

</body>
</html>
