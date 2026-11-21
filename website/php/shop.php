<?php
require_once __DIR__ . '/../includes.php';
require_once __DIR__ . '/../header_functions.php';

$productService = new ProductService();
$products = $productService->getAllProducts();

renderHeader();
?>

<style>
  /* Shop Page Styles */
  body {
    background: var(--bg);
  }

  .shop-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 30px 20px;
  }

  /* Compact Filters & Search Toolbar */
  .filters-toolbar {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 30px;
    padding: 0;
  }

  .search-wrapper {
    position: relative;
    flex: 1;
    min-width: 200px;
    max-width: 400px;
  }

  .search-bar {
    width: 100%;
    padding: 10px 16px 10px 44px;
    border: 1px solid var(--divider);
    border-radius: var(--radius);
    font-size: 14px;
    font-family: var(--font-ui);
    background: var(--white);
    transition: all 0.3s ease;
  }

  .search-bar:focus {
    outline: none;
    border-color: var(--teal-600);
    box-shadow: 0 0 0 3px rgba(0, 184, 212, 0.1);
  }

  .search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    width: 18px;
    height: 18px;
    opacity: 0.5;
    pointer-events: none;
  }

  .filter-btn {
    padding: 10px 18px;
    border: 1px solid var(--divider);
    border-radius: var(--radius);
    background: var(--white);
    font-size: 14px;
    font-weight: 500;
    color: var(--dark);
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
  }

  .filter-btn:hover {
    border-color: var(--teal-600);
    background: var(--panel);
    color: var(--teal-600);
  }

  .filter-btn.active {
    background: var(--teal-600);
    color: var(--white);
    border-color: var(--teal-600);
  }

  .filter-btn svg {
    width: 16px;
    height: 16px;
  }

  .sort-btn {
    padding: 10px 18px;
    border: 1px solid var(--divider);
    border-radius: var(--radius);
    background: var(--white);
    font-size: 14px;
    font-weight: 500;
    color: var(--dark);
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
    position: relative;
  }

  .sort-btn:hover {
    border-color: var(--teal-600);
    background: var(--panel);
    color: var(--teal-600);
  }

  .sort-btn svg {
    width: 16px;
    height: 16px;
    transition: transform 0.3s ease;
  }

  .sort-btn.active svg {
    transform: rotate(180deg);
  }

  .results-count {
    font-size: 13px;
    color: var(--muted);
    margin-left: auto;
    padding: 10px 0;
  }

  /* Filter Dropdown Menu */
  .filter-dropdown {
    position: relative;
  }

  .filter-menu {
    position: absolute;
    top: calc(100% + 8px);
    left: 0;
    background: var(--white);
    border: 1px solid var(--divider);
    border-radius: var(--radius);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    min-width: 200px;
    padding: 12px;
    z-index: 100;
    display: none;
  }

  .filter-menu.active {
    display: block;
  }

  .filter-menu-item {
    padding: 8px 12px;
    border-radius: 6px;
    cursor: pointer;
    transition: background 0.2s ease;
    font-size: 14px;
    color: var(--dark);
  }

  .filter-menu-item:hover {
    background: var(--bg);
  }

  .filter-menu-item.selected {
    background: rgba(0, 184, 212, 0.1);
    color: var(--teal-600);
    font-weight: 500;
  }

  .price-inputs {
    display: flex;
    gap: 8px;
    align-items: center;
  }

  .price-inputs input {
    width: 80px;
    padding: 8px 10px;
    border: 1px solid var(--divider);
    border-radius: 6px;
    font-size: 13px;
  }

  .price-inputs input:focus {
    outline: none;
    border-color: var(--teal-600);
  }

  /* Product Grid */
  .products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 30px;
    margin-bottom: 40px;
  }

  .product-card-minimal {
    position: relative;
    display: flex;
    flex-direction: column;
    padding: 0;
    width: 100%;
    min-height: auto;
    background: var(--white);
    border: 1px solid var(--divider);
    border-radius: var(--card-radius);
    overflow: hidden;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    color: inherit;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
  }

  .product-card-minimal:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0, 184, 212, 0.2);
    border-color: var(--teal-600);
  }

  .product-card-content {
    position: relative;
    display: flex;
    flex-direction: column;
    width: 100%;
    height: 100%;
    overflow: hidden;
    border-radius: var(--card-radius);
  }

  .product-image-wrapper {
    position: relative;
    width: 100%;
    padding-top: 100%; /* Square aspect ratio */
    overflow: hidden;
    background: var(--bg);
  }

  .product-image-wrapper img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  }

  .product-card-minimal:hover .product-image-wrapper img {
    transform: scale(1.1);
    filter: brightness(1.1);
  }

  .product-card-minimal:hover .product-info-minimal {
    background: rgba(255, 255, 255, 0.5);
    backdrop-filter: blur(25px) saturate(200%);
  }

  .product-badges {
    position: absolute;
    top: 12px;
    left: 12px;
    display: flex;
    flex-direction: column;
    gap: 6px;
    z-index: 20;
  }

  .badge {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    backdrop-filter: blur(10px);
  }

  .badge-stock {
    background: rgba(22, 163, 74, 0.1);
    color: #16A34A;
    border: 1px solid rgba(22, 163, 74, 0.2);
  }

  .badge-out-of-stock {
    background: rgba(220, 38, 38, 0.1);
    color: #DC2626;
    border: 1px solid rgba(220, 38, 38, 0.2);
  }

  .badge-sale {
    background: rgba(0, 184, 212, 0.2);
    color: var(--teal-600);
    border: 1px solid rgba(0, 184, 212, 0.3);
  }

  .badge-new {
    background: rgba(0, 184, 212, 0.2);
    color: var(--teal-600);
    border: 1px solid rgba(0, 184, 212, 0.3);
  }

  .product-info-minimal {
    padding: 18px;
    background: var(--white);
    border-top: 1px solid var(--divider);
    position: relative;
    transition: all 0.3s ease;
  }

  .product-card-minimal:hover .product-info-minimal {
    background: var(--panel);
  }

  .product-name-minimal {
    font-size: 16px;
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 8px;
    line-height: 1.4;
  }

  .product-price-minimal {
    font-size: 18px;
    font-weight: 700;
    color: var(--dark);
  }

  .product-price-minimal .original-price {
    font-size: 14px;
    color: var(--muted);
    text-decoration: line-through;
    margin-right: 8px;
    font-weight: 400;
  }

  .product-price-minimal .sale-price {
    color: var(--danger);
  }

  /* Pagination */
  .pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    margin-top: 50px;
    padding-bottom: 40px;
  }

  .pagination button {
    padding: 10px 16px;
    border: 1px solid var(--divider);
    background: var(--white);
    border-radius: var(--radius);
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    color: var(--dark);
  }

  .pagination button:hover:not(:disabled) {
    background: var(--teal-600);
    color: var(--white);
    border-color: var(--teal-600);
  }

  .pagination button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }

  .pagination button.active {
    background: var(--teal-600);
    color: var(--white);
    border-color: var(--teal-600);
  }

  .load-more-btn {
    display: block;
    width: 100%;
    max-width: 300px;
    margin: 40px auto;
    padding: 14px 28px;
    background: var(--teal-600);
    color: var(--white);
    border: none;
    border-radius: var(--radius);
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  .load-more-btn:hover {
    background: var(--teal-500);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 184, 212, 0.3);
  }

  .load-more-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
  }

  /* Empty State */
  .empty-state {
    text-align: center;
    padding: 80px 20px;
    color: var(--muted);
    grid-column: 1 / -1;
  }

  .empty-state h3 {
    font-size: 24px;
    margin-bottom: 10px;
    color: var(--dark);
  }

  /* Responsive */
  @media (max-width: 768px) {
    .shop-container {
      padding: 20px 15px;
    }

    .filters-toolbar {
      gap: 8px;
    }

    .search-wrapper {
      min-width: 100%;
      max-width: 100%;
    }

    .filter-btn,
    .sort-btn {
      padding: 8px 14px;
      font-size: 13px;
    }

    .results-count {
      width: 100%;
      margin-left: 0;
      text-align: center;
      margin-top: 8px;
    }

    .products-grid {
      grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
      gap: 20px;
    }
  }
</style>

<!-- MAIN CONTENT -->
<div class="shop-container">
  <!-- Compact Filters & Search Toolbar -->
  <div class="filters-toolbar">
    <div class="search-wrapper">
      <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
        <circle cx="11" cy="11" r="6.2" stroke-width="1.4" />
        <path d="M21 21l-4.35-4.35" stroke-width="1.4" />
      </svg>
      <input type="text" class="search-bar" id="searchBar" placeholder="Search products...">
    </div>

    <div class="filter-dropdown">
      <button class="filter-btn" id="categoryBtn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path d="M3 6h18M7 12h10M9 18h6" stroke-width="1.5" stroke-linecap="round" />
        </svg>
        Category
      </button>
      <div class="filter-menu" id="categoryMenu">
        <div class="filter-menu-item selected" data-value="">All Categories</div>
        <div class="filter-menu-item" data-value="Hoodies">Hoodies</div>
        <div class="filter-menu-item" data-value="Jackets">Jackets</div>
        <div class="filter-menu-item" data-value="Sweaters">Sweaters</div>
        <div class="filter-menu-item" data-value="Sets">Sets</div>
        <div class="filter-menu-item" data-value="Denim">Denim</div>
        <div class="filter-menu-item" data-value="Crewnecks">Crewnecks</div>
      </div>
    </div>

    <div class="filter-dropdown">
      <button class="filter-btn" id="priceBtn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" stroke-width="1.5" stroke-linecap="round" />
        </svg>
        Price
      </button>
      <div class="filter-menu" id="priceMenu">
        <div class="price-inputs">
          <input type="number" id="minPrice" placeholder="Min" min="0">
          <span>-</span>
          <input type="number" id="maxPrice" placeholder="Max" min="0">
        </div>
      </div>
    </div>

    <button class="filter-btn" id="inStockBtn">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
        <path d="M9 12l2 2 4-4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        <circle cx="12" cy="12" r="10" stroke-width="1.5" />
      </svg>
      In Stock
    </button>

    <button class="filter-btn" id="onSaleBtn">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
        <path d="M12 2L2 7l10 5 10-5-10-5z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        <path d="M2 17l10 5 10-5M2 12l10 5 10-5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
      </svg>
      On Sale
    </button>

    <div class="filter-dropdown">
      <button class="sort-btn" id="sortBtn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path d="M3 6h18M7 12h10M9 18h6" stroke-width="1.5" stroke-linecap="round" />
        </svg>
        Sort
      </button>
      <div class="filter-menu" id="sortMenu">
        <div class="filter-menu-item selected" data-value="default">Default</div>
        <div class="filter-menu-item" data-value="price-low">Price: Low → High</div>
        <div class="filter-menu-item" data-value="price-high">Price: High → Low</div>
        <div class="filter-menu-item" data-value="newest">Newest</div>
        <div class="filter-menu-item" data-value="best-selling">Best Selling</div>
        <div class="filter-menu-item" data-value="top-rated">Top Rated</div>
      </div>
    </div>

    <div class="results-count" id="resultsCount">Showing <?= count($products ?? []) ?> products</div>
  </div>

  <!-- Products Grid -->
  <div class="products-grid" id="productsGrid">
    <?php if (empty($products)): ?>
      <div class="empty-state">
        <h3>No products found.</h3>
        <p>Try adjusting your filters or search terms.</p>
      </div>
    <?php else: ?>
      <?php foreach ($products as $p): ?>
        <a href="<?= BASE_URL ?>/product?id=<?= $p['id'] ?>" class="product-card-minimal" 
           data-name="<?= htmlspecialchars(strtolower($p['name'] . ' ' . ($p['brand'] ?? ''))) ?>"
           data-category="<?= htmlspecialchars($p['category'] ?? '') ?>"
           data-price="<?= $p['price'] ?>"
           data-original-price="<?= $p['original_price'] ?? '' ?>"
           data-in-stock="<?= !empty($p['stock']) && $p['stock'] > 0 ? '1' : '0' ?>"
           data-on-sale="<?= !empty($p['is_on_sale']) ? '1' : '0' ?>"
           data-is-new="<?= !empty($p['is_new']) ? '1' : '0' ?>"
           data-rating="<?= $p['rating'] ?? 0 ?>"
           data-sales="<?= $p['sales_count'] ?? 0 ?>">
          <div class="product-card-content">
            <div class="product-image-wrapper">
              <img src="<?= rtrim(BASE_URL, '/') ?><?= htmlspecialchars($p['image_url']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" loading="lazy" onerror="this.src='<?= rtrim(BASE_URL, '/') ?>/assets/images/shop/Harbaa.png'">
              <div class="product-badges">
                <?php if (!empty($p['stock']) && $p['stock'] <= 0): ?>
                  <span class="badge badge-out-of-stock">Out of Stock</span>
                <?php endif; ?>
                <?php if (!empty($p['is_on_sale'])): ?>
                  <span class="badge badge-sale">On Sale</span>
                <?php endif; ?>
                <?php if (!empty($p['is_new'])): ?>
                  <span class="badge badge-new">New</span>
                <?php endif; ?>
              </div>
            </div>
            <div class="product-info-minimal">
              <div class="product-name-minimal"><?= htmlspecialchars($p['name']) ?></div>
              <div class="product-price-minimal">
                <?php if (!empty($p['original_price'])): ?>
                  <span class="original-price">EGP <?= number_format($p['original_price'], 2) ?></span>
                  <span class="sale-price">EGP <?= number_format($p['price'], 2) ?></span>
                <?php else: ?>
                  EGP <?= number_format($p['price'], 2) ?>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </a>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <!-- Pagination / Load More -->
  <div id="paginationContainer"></div>
</div>

<script>
  // Product filtering and sorting
  let allProductCards = []; // Store original product cards
  let filteredProducts = [];
  let currentPage = 1;
  const productsPerPage = 12;
  let usePagination = true;
  let selectedCategory = '';
  let inStockOnly = false;
  let onSaleOnly = false;
  let currentSort = 'default';

  // Initialize
  function init() {
    // Store all original product cards before any filtering
    allProductCards = Array.from(document.querySelectorAll('.product-card-minimal'));
    filteredProducts = [...allProductCards];
    
    setupEventListeners();
    
    // Update results count with initial products
    const initialCount = allProductCards.length;
    updateResultsCount(initialCount);
    
    // Initial render with pagination
    renderProducts();
  }

  // Setup event listeners
  function setupEventListeners() {
    // Search
    const searchBar = document.getElementById('searchBar');
    if (searchBar) {
      searchBar.addEventListener('input', handleFilter);
    }

    // Category dropdown
    const categoryBtn = document.getElementById('categoryBtn');
    const categoryMenu = document.getElementById('categoryMenu');
    if (categoryBtn && categoryMenu) {
      categoryBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        toggleMenu(categoryMenu);
      });
      categoryMenu.querySelectorAll('.filter-menu-item').forEach(item => {
        item.addEventListener('click', () => {
          selectedCategory = item.dataset.value;
          categoryMenu.querySelectorAll('.filter-menu-item').forEach(i => i.classList.remove('selected'));
          item.classList.add('selected');
          categoryBtn.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M3 6h18M7 12h10M9 18h6" stroke-width="1.5" stroke-linecap="round" /></svg> ${item.textContent}`;
          closeAllMenus();
          handleFilter();
        });
      });
    }

    // Price dropdown
    const priceBtn = document.getElementById('priceBtn');
    const priceMenu = document.getElementById('priceMenu');
    if (priceBtn && priceMenu) {
      priceBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        toggleMenu(priceMenu);
      });
      const minPrice = document.getElementById('minPrice');
      const maxPrice = document.getElementById('maxPrice');
      if (minPrice) minPrice.addEventListener('input', handleFilter);
      if (maxPrice) maxPrice.addEventListener('input', handleFilter);
    }

    // In Stock toggle
    const inStockBtn = document.getElementById('inStockBtn');
    if (inStockBtn) {
      inStockBtn.addEventListener('click', (e) => {
        e.stopPropagation(); // Prevent menu close
        inStockOnly = !inStockOnly;
        inStockBtn.classList.toggle('active', inStockOnly);
        handleFilter();
      });
    }

    // On Sale toggle
    const onSaleBtn = document.getElementById('onSaleBtn');
    if (onSaleBtn) {
      onSaleBtn.addEventListener('click', (e) => {
        e.stopPropagation(); // Prevent menu close
        onSaleOnly = !onSaleOnly;
        onSaleBtn.classList.toggle('active', onSaleOnly);
        handleFilter();
      });
    }

    // Sort dropdown
    const sortBtn = document.getElementById('sortBtn');
    const sortMenu = document.getElementById('sortMenu');
    if (sortBtn && sortMenu) {
      sortBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        toggleMenu(sortMenu);
      });
      sortMenu.querySelectorAll('.filter-menu-item').forEach(item => {
        item.addEventListener('click', () => {
          currentSort = item.dataset.value;
          sortMenu.querySelectorAll('.filter-menu-item').forEach(i => i.classList.remove('selected'));
          item.classList.add('selected');
          sortBtn.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M3 6h18M7 12h10M9 18h6" stroke-width="1.5" stroke-linecap="round" /></svg> ${item.textContent}`;
          closeAllMenus();
          handleSort();
        });
      });
    }

    // Close menus when clicking outside
    document.addEventListener('click', closeAllMenus);
  }

  function toggleMenu(menu) {
    const isActive = menu.classList.contains('active');
    closeAllMenus();
    if (!isActive) {
      menu.classList.add('active');
    }
  }

  function closeAllMenus() {
    document.querySelectorAll('.filter-menu').forEach(menu => {
      menu.classList.remove('active');
    });
  }

  // Handle filtering
  function handleFilter() {
    currentPage = 1;
    renderProducts();
  }

  // Handle sorting
  function handleSort() {
    currentPage = 1;
    renderProducts();
  }

  // Render products
  function renderProducts() {
    const grid = document.getElementById('productsGrid');
    if (!grid) return;

    // Use stored original cards, not from DOM (which may have been filtered)
    if (allProductCards.length === 0) {
      grid.innerHTML = `
        <div class="empty-state">
          <h3>No products found</h3>
          <p>Try adjusting your filters or search terms.</p>
        </div>
      `;
      updateResultsCount(0);
      const paginationContainer = document.getElementById('paginationContainer');
      if (paginationContainer) paginationContainer.innerHTML = '';
      return;
    }

    // Filter cards based on current filters using original cards
    const visibleCards = allProductCards.filter(card => {
      const searchBar = document.getElementById('searchBar');
      const searchTerm = searchBar ? searchBar.value.toLowerCase() : '';
      const minPrice = parseFloat(document.getElementById('minPrice')?.value) || 0;
      const maxPrice = parseFloat(document.getElementById('maxPrice')?.value) || Infinity;

      const matchesSearch = !searchTerm || card.dataset.name.includes(searchTerm);
      const matchesCategory = !selectedCategory || card.dataset.category === selectedCategory;
      const price = parseFloat(card.dataset.price || 0);
      const matchesPrice = price >= minPrice && price <= maxPrice;
      const matchesStock = !inStockOnly || card.dataset.inStock === '1';
      const matchesSale = !onSaleOnly || card.dataset.onSale === '1';

      return matchesSearch && matchesCategory && matchesPrice && matchesStock && matchesSale;
    });

    // Sort visible cards
    const sortedCards = [...visibleCards].sort((a, b) => {
      switch(currentSort) {
        case 'price-low':
          return parseFloat(a.dataset.price || 0) - parseFloat(b.dataset.price || 0);
        case 'price-high':
          return parseFloat(b.dataset.price || 0) - parseFloat(a.dataset.price || 0);
        case 'newest':
          return (b.dataset.isNew === '1' ? 1 : 0) - (a.dataset.isNew === '1' ? 1 : 0);
        case 'best-selling':
          return parseFloat(b.dataset.sales || 0) - parseFloat(a.dataset.sales || 0);
        case 'top-rated':
          return parseFloat(b.dataset.rating || 0) - parseFloat(a.dataset.rating || 0);
        default:
          return 0;
      }
    });

    // Update filtered products
    filteredProducts = sortedCards;

    const startIndex = usePagination ? (currentPage - 1) * productsPerPage : 0;
    const endIndex = usePagination ? startIndex + productsPerPage : sortedCards.length;
    const productsToDisplay = sortedCards.slice(startIndex, endIndex);

    // Clear and add products (clone to preserve original)
    grid.innerHTML = '';
    productsToDisplay.forEach(card => {
      const clonedCard = card.cloneNode(true);
      grid.appendChild(clonedCard);
    });

    updateResultsCount(sortedCards.length, startIndex + 1, Math.min(endIndex, sortedCards.length));

    // Render pagination or load more
    if (usePagination) {
      renderPagination(sortedCards.length);
    } else {
      renderLoadMore(sortedCards.length);
    }
  }

  function updateResultsCount(total = 0, start = 0, end = 0) {
    const resultsCount = document.getElementById('resultsCount');
    if (resultsCount) {
      if (total === 0) {
        resultsCount.textContent = 'Showing 0 products';
      } else if (usePagination && start > 0) {
        resultsCount.textContent = `Showing ${start}-${end} of ${total} products`;
      } else {
        resultsCount.textContent = `Showing ${total} products`;
      }
    }
  }

  // Render pagination
  function renderPagination(totalProducts) {
    const totalPages = Math.ceil(totalProducts / productsPerPage);
    const paginationContainer = document.getElementById('paginationContainer');
    if (!paginationContainer) return;

    if (totalPages <= 1) {
      paginationContainer.innerHTML = '';
      return;
    }

    let paginationHTML = '<div class="pagination">';
    
    // Previous button
    paginationHTML += `<button ${currentPage === 1 ? 'disabled' : ''} onclick="changePage(${currentPage - 1})">Previous</button>`;
    
    // Page numbers
    for (let i = 1; i <= totalPages; i++) {
      if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
        paginationHTML += `<button class="${i === currentPage ? 'active' : ''}" onclick="changePage(${i})">${i}</button>`;
      } else if (i === currentPage - 3 || i === currentPage + 3) {
        paginationHTML += `<button disabled>...</button>`;
      }
    }
    
    // Next button
    paginationHTML += `<button ${currentPage === totalPages ? 'disabled' : ''} onclick="changePage(${currentPage + 1})">Next</button>`;
    
    paginationHTML += '</div>';
    paginationContainer.innerHTML = paginationHTML;
  }

  // Render load more button
  function renderLoadMore(totalProducts) {
    const paginationContainer = document.getElementById('paginationContainer');
    if (!paginationContainer) return;

    const displayedCount = Math.min(currentPage * productsPerPage, totalProducts);
    const hasMore = displayedCount < totalProducts;

    if (!hasMore) {
      paginationContainer.innerHTML = '';
      return;
    }

    paginationContainer.innerHTML = `
      <button class="load-more-btn" onclick="loadMore()">Load More Products</button>
    `;
  }

  // Change page
  function changePage(page) {
    currentPage = page;
    renderProducts();
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  // Load more products
  function loadMore() {
    currentPage++;
    renderProducts();
  }

  // Make functions globally accessible
  window.changePage = changePage;
  window.loadMore = loadMore;

  // Initialize on load
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
</script>

<!-- Body and HTML closing tags handled by Controller -->

</body>
</html>
