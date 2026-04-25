<?php
require_once __DIR__ . '/../includes.php';
require_once __DIR__ . '/../header_functions.php';

if (!isset($_GET['id'])) {
    echo 'Product id missing';
    exit;
}
$id = (int)$_GET['id'];
$productService = new ProductService();
$product = $productService->getProductById($id);
if (!$product) {
    echo 'Product not found';
    exit;
}
$reviewRepository = new ReviewRepository();
$reviews = $reviewRepository->getByProductId($id);

renderHeader();
?>

  <style>
    /* Product Details Page Styles */
    body {
      background: var(--bg);
    }

    .product-details-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 40px 20px;
    }

    .product-details-wrapper {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 60px;
      background: var(--white);
      padding: 40px;
      border-radius: var(--card-radius);
      box-shadow: var(--shadow);
      margin-bottom: 40px;
    }

  .main-image-container {
      width: 100%;
      position: relative;
      background: var(--bg);
      border-radius: var(--radius);
      overflow: hidden;
      min-height: 500px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .main-image {
      width: 100%;
      height: 100%;
      object-fit: contain;
    }

    .product-info-section {
      display: flex;
      flex-direction: column;
      gap: 24px;
    }

    .product-title {
      font-family: var(--font-brand);
      font-size: 36px;
      font-weight: 800;
      color: var(--dark);
      line-height: 1.2;
      margin: 0;
    }

    .product-price-section {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .product-price-row {
      display: flex;
      align-items: center;
      gap: 16px;
      flex-wrap: wrap;
    }

    .product-price {
      font-size: 32px;
      font-weight: 700;
      color: var(--dark);
    }

    .product-price.original {
      font-size: 24px;
      color: var(--muted);
      text-decoration: line-through;
      font-weight: 400;
    }

    .product-price.sale {
      color: var(--danger);
    }

    .product-badges-row {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
    }

    .product-badge {
      padding: 6px 14px;
      border-radius: 6px;
      font-size: 12px;
      font-weight: 700;
      text-transform: uppercase;
    }

    .product-badge.stock {
      background: var(--success);
      color: var(--white);
    }

    .product-badge.sale {
      background: var(--warning);
      color: var(--white);
    }

    .product-badge.new {
      background: var(--teal-600);
      color: var(--white);
    }

    .size-selector {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .size-label {
      font-size: 16px;
      font-weight: 600;
      color: var(--dark);
    }

    .size-buttons {
      display: flex;
      gap: 12px;
      flex-wrap: wrap;
    }

    .size-btn {
      width: 50px;
      height: 50px;
      border: 2px solid var(--divider);
      border-radius: 50%;
      background: var(--white);
      font-size: 16px;
      font-weight: 600;
      color: var(--dark);
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .size-btn:hover {
      border-color: var(--teal-600);
    }

    .size-btn.selected {
      background: var(--teal-600);
      color: var(--white);
      border-color: var(--teal-600);
    }

    .action-buttons {
      display: flex;
      flex-direction: column;
      gap: 16px;
      margin-top: 8px;
    }

  .btn-add-to-cart {
      width: 100%;
      padding: 16px 24px;
      border-radius: var(--radius);
      font-size: 16px;
      font-weight: 700;
      cursor: pointer;
      background: var(--white);
      border: 2px solid var(--teal-600);
      color: var(--teal-600);
    transition: all 0.3s ease;
    }

    .btn-add-to-cart:hover {
      background: var(--teal-600);
      color: var(--white);
  }

    .product-description {
      background: var(--white);
      padding: 40px;
      border-radius: var(--card-radius);
      box-shadow: var(--shadow);
      margin-bottom: 40px;
    }

    .description-title {
      font-size: 24px;
      font-weight: 700;
      color: var(--dark);
      margin-bottom: 20px;
    }

    .description-content {
      font-size: 16px;
      line-height: 1.8;
      color: var(--dark);
    }

    /* Reviews Section */
    .reviews-section {
      background: var(--white);
      padding: 40px;
      border-radius: var(--card-radius);
      box-shadow: var(--shadow);
      margin-bottom: 40px;
    }

    .reviews-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
      flex-wrap: wrap;
      gap: 20px;
    }

    .reviews-title {
      font-size: 24px;
      font-weight: 700;
      color: var(--dark);
      margin: 0;
    }

    .reviews-summary {
      display: flex;
      align-items: center;
      gap: 20px;
      flex-wrap: wrap;
    }

    .average-rating {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .rating-number {
      font-size: 36px;
      font-weight: 700;
      color: var(--dark);
      font-family: var(--font-brand);
    }

    .rating-stars {
      display: flex;
      gap: 4px;
      font-size: 20px;
      color: #FFB800;
    }

    .rating-stars i {
      font-size: 20px;
    }

    .rating-stars .fas {
      color: #FFB800;
    }

    .rating-stars .far {
      color: #ddd;
    }

    .reviews-count {
      font-size: 14px;
      color: var(--muted);
    }

    .reviews-list {
      display: flex;
      flex-direction: column;
      gap: 24px;
    }

    .review-item {
      padding: 24px;
      background: var(--bg);
      border-radius: var(--radius);
      border: 1px solid var(--divider);
    }

    .review-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 12px;
      flex-wrap: wrap;
      gap: 12px;
    }

    .reviewer-info {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .reviewer-avatar {
      width: 48px;
      height: 48px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--teal-600), var(--teal-300));
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--white);
      font-weight: 700;
      font-size: 18px;
      flex-shrink: 0;
    }

    .reviewer-details {
      display: flex;
      flex-direction: column;
      gap: 4px;
    }

    .reviewer-name {
      font-size: 16px;
      font-weight: 600;
      color: var(--dark);
    }

    .review-date {
      font-size: 13px;
      color: var(--muted);
    }

    .review-rating {
      display: flex;
      gap: 3px;
      font-size: 18px;
      color: #FFB800;
      align-items: center;
    }

    .review-rating .fas.fa-star {
      color: #FFB800;
    }

    .review-rating .far.fa-star {
      color: #ddd;
    }

    .review-content {
      font-size: 15px;
      line-height: 1.6;
      color: var(--dark);
    }

    .no-reviews {
      text-align: center;
      padding: 40px 20px;
      color: var(--muted);
    }

    .no-reviews p {
      font-size: 16px;
      margin: 0;
    }

    @media (max-width: 968px) {
      .product-details-wrapper {
        grid-template-columns: 1fr;
      }
    }
  </style>

  <!-- PRODUCT DETAILS -->
  <div class="product-details-container">
    <div class="product-details-wrapper">
    <!-- Image -->
        <div class="main-image-container">
      <img src="<?= BASE_URL ?><?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="main-image" onerror="this.src='<?= BASE_URL ?>/assets/images/shop/Harbaa.png'">
      </div>

      <!-- Product Info -->
      <div class="product-info-section">
      <h1 class="product-title"><?= htmlspecialchars($product['name']) ?></h1>
      
      <div style="color: var(--muted); font-size: 16px;">
        Brand: <?= htmlspecialchars($product['brand']) ?> | 
        Category: <?= htmlspecialchars($product['category'] ?? 'N/A') ?>
      </div>

        <div class="product-price-section">
        <div class="product-price-row">
          <?php if (!empty($product['original_price'])): ?>
            <span class="product-price original">EGP <?= number_format($product['original_price'], 2) ?></span>
            <span class="product-price sale">EGP <?= number_format($product['price'], 2) ?></span>
          <?php else: ?>
            <span class="product-price">EGP <?= number_format($product['price'], 2) ?></span>
          <?php endif; ?>
          </div>
        <div class="product-badges-row">
          <?php if (!empty($product['stock']) && $product['stock'] > 0): ?>
            <span class="product-badge stock">In Stock (<?= $product['stock'] ?>)</span>
          <?php else: ?>
            <span class="product-badge" style="background: var(--danger); color: var(--white);">Out of Stock</span>
          <?php endif; ?>
          <?php if (!empty($product['is_on_sale'])): ?>
            <span class="product-badge sale">On Sale</span>
          <?php endif; ?>
          <?php if (!empty($product['is_new'])): ?>
            <span class="product-badge new">New</span>
          <?php endif; ?>
          </div>
        <div style="font-size: 14px; color: var(--muted); margin-top: 8px;">
          Shipping calculated at checkout.
        </div>
        </div>

        <div class="size-selector">
          <div class="size-label">Size</div>
        <div class="size-buttons">
            <button class="size-btn" data-size="XS">XS</button>
            <button class="size-btn" data-size="S">S</button>
            <button class="size-btn" data-size="M">M</button>
            <button class="size-btn" data-size="L">L</button>
            <button class="size-btn" data-size="XL">XL</button>
          </div>
        </div>

      <form method="POST" action="<?= BASE_URL ?>/cart/add" class="action-buttons" id="addToCartForm">
        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
        <input type="hidden" name="size" id="selectedSize" value="M">
        <div style="display: flex; gap: 12px; align-items: center;">
          <label for="quantity" style="font-weight: 600;">Quantity:</label>
          <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?= $product['stock'] ?>" style="padding: 8px; border: 1px solid var(--divider); border-radius: var(--radius); width: 80px;">
        </div>
        <button type="submit" class="btn-add-to-cart" <?= (empty($product['stock']) || $product['stock'] <= 0) ? 'disabled' : '' ?>>
          Add to Cart
        </button>
      </form>
    </div>
    </div>

    <!-- Product Description -->
    <div class="product-description">
      <h2 class="description-title">Product Description</h2>
    <div class="description-content">
      <?= nl2br(htmlspecialchars($product['description'] ?? 'No description available.')) ?>
    </div>
    </div>

    <!-- Reviews Section -->
    <div class="reviews-section">
      <div class="reviews-header">
        <h2 class="reviews-title">Customer Reviews</h2>
        <div class="reviews-summary">
          <?php
          $reviews = $reviews ?? [];
          $reviewCount = count($reviews);
                    $averageRating = $product['rating'] ?? 0;
          if ($averageRating == 0 && $reviewCount > 0) {
                        $sum = 0;
            foreach ($reviews as $review) {
              $sum += (int)$review['rating'];
            }
            $averageRating = $sum / $reviewCount;
          }
                    $totalReviews = $product['total_reviews'] ?? $reviewCount;
          
          if ($reviewCount > 0 || $averageRating > 0):
            $displayRating = $averageRating > 0 ? number_format($averageRating, 1) : '0.0';
          ?>
            <div class="average-rating">
              <span class="rating-number"><?= $displayRating ?></span>
              <div class="rating-stars">
                <?php
                $fullStars = floor($averageRating);
                $hasHalfStar = ($averageRating - $fullStars) >= 0.5;
                for ($i = 1; $i <= 5; $i++):
                  if ($i <= $fullStars):
                ?>
                  <i class="fas fa-star"></i>
                <?php elseif ($i == $fullStars + 1 && $hasHalfStar): ?>
                  <i class="fas fa-star-half-alt"></i>
                <?php else: ?>
                  <i class="far fa-star"></i>
                <?php endif; endfor; ?>
              </div>
            </div>
            <div class="reviews-count"><?= $totalReviews ?> <?= $totalReviews === 1 ? 'review' : 'reviews' ?></div>
          <?php else: ?>
            <div class="average-rating">
              <span class="rating-number" style="font-size: 18px; color: var(--muted);">No rating</span>
            </div>
            <div class="reviews-count">No reviews yet</div>
          <?php endif; ?>
        </div>
      </div>
      
      <div class="reviews-list">
        <?php if (empty($reviews)): ?>
          <div class="no-reviews">
            <p>No customer reviews yet. Be the first to review this product!</p>
          </div>
        <?php else: ?>
          <?php foreach ($reviews as $review): ?>
            <div class="review-item">
              <div class="review-header">
                <div class="reviewer-info">
                  <div class="reviewer-avatar">
                    <?= strtoupper(substr($review['reviewer_name'] ?? 'U', 0, 1)) ?>
                  </div>
                  <div class="reviewer-details">
                    <div class="reviewer-name"><?= htmlspecialchars($review['reviewer_name'] ?? 'Anonymous') ?></div>
                    <div class="review-date">
                      <?php
                      $reviewDate = new DateTime($review['created_at']);
                      echo $reviewDate->format('F j, Y');
                      ?>
                    </div>
                  </div>
                </div>
                <div class="review-rating" title="<?= $rating ?> out of 5 stars">
                  <?php
                  $rating = (int)$review['rating'];
                  for ($i = 1; $i <= 5; $i++):
                    if ($i <= $rating):
                  ?>
                    <i class="fas fa-star" aria-label="<?= $i ?> star"></i>
                  <?php else: ?>
                    <i class="far fa-star" aria-label="<?= $i ?> star"></i>
                  <?php endif; endfor; ?>
                  <span style="margin-left: 8px; font-size: 14px; color: var(--dark); font-weight: 600;"><?= $rating ?>.0</span>
                </div>
              </div>
              <div class="review-content">
                <?= nl2br(htmlspecialchars($review['review_content'] ?? 'No comment provided.')) ?>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <script>
  // Size selection
      // Set default size 'M' as selected
      document.addEventListener('DOMContentLoaded', function() {
        const defaultSizeBtn = document.querySelector('.size-btn[data-size="M"]');
        if (defaultSizeBtn) {
          defaultSizeBtn.classList.add('selected');
        }
      });

      document.querySelectorAll('.size-btn').forEach(btn => {
        btn.addEventListener('click', function() {
          document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('selected'));
          this.classList.add('selected');
          // Update hidden input with selected size
          const selectedSize = this.getAttribute('data-size');
          document.getElementById('selectedSize').value = selectedSize;
        });
      });
</script>

<!-- Body and HTML closing tags handled by Controller -->

</body>
</html>
