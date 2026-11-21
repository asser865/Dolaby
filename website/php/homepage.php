<?php
require_once __DIR__ . '/../includes.php';
require_once __DIR__ . '/../header_functions.php';

renderHeader();
?>
<style>
    /* ======================================================= */
    /* ---------------- ENHANCED HOMEPAGE STYLES -------------- */
    /* ======================================================= */
    
    /* Smooth scroll behavior */
    html {
      scroll-behavior: smooth;
    }

    /* Enhanced Featured Section */
    .featured-section {
      position: relative;
      margin-bottom: 60px;
    }

    .featured-slider {
      position: relative;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 20px 60px rgba(0, 184, 212, 0.15);
      width: 100%;
    }

    .featured-slider-wrapper {
      display: flex;
      gap: 20px;
      width: fit-content;
      animation: scroll 15s linear infinite;
      will-change: transform;
    }

    .featured-slider:hover .featured-slider-wrapper {
      animation-play-state: paused;
    }

    .featured-item {
      flex: 0 0 80%;
      width: 80%;
      max-width: 850px;
      height: 260px;
      border-radius: 16px;
      overflow: hidden;
      scroll-snap-align: center;
      position: relative;
      transition: transform 0.3s ease;
    }

    @keyframes scroll {
      0% {
        transform: translateX(0);
      }
      100% {
        /* Use CSS variable set by JavaScript for precise calculation */
        transform: translateX(var(--scroll-distance, -50%));
      }
    }

    @media (max-width: 768px) {
      .featured-item {
        height: 200px;
        flex: 0 0 85%;
      }
    }

    .featured-item::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(180deg, transparent 0%, rgba(0, 0, 0, 0.1) 100%);
      pointer-events: none;
    }

    .featured-item img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.5s ease;
    }

    .featured-item:hover img {
      transform: scale(1.05);
    }

    /* Slider Indicators - Hidden for endless scroll */
    .slider-indicators {
      display: none;
    }

    .indicator {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: var(--divider);
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .indicator.active {
      width: 24px;
      border-radius: 4px;
      background: var(--teal-600);
    }

    /* Enhanced Section Titles */
    .section-title {
      font-size: 28px;
      font-weight: 800;
      margin-bottom: 8px;
      position: relative;
      display: inline-block;
      color: var(--dark);
    }

    .section-title::after {
      content: '';
      position: absolute;
      bottom: -8px;
      left: 0;
      width: 60px;
      height: 4px;
      background: linear-gradient(90deg, var(--teal-600), var(--teal-300));
      border-radius: 2px;
    }

    .highlight-line {
      height: 2px;
      background: linear-gradient(90deg, var(--teal-600), transparent);
      width: 100%;
      margin: 16px 0 32px 0;
      border-radius: 1px;
    }

    /* Best Sellers Section Specific Styling */
    .best-sellers-section .section-title {
      font-size: 32px;
      font-weight: 900;
      letter-spacing: -0.5px;
    }

    .best-sellers-section .highlight-line {
      height: 3px;
      background: linear-gradient(90deg, var(--teal-600), var(--teal-300));
      margin: 20px 0 28px 0;
    }

    /* Best Sellers Carousel Section - Single Row Only */
    .best-sellers-wrapper {
      position: relative;
      margin-top: 24px;
      width: 100%;
      padding: 14px 23px;
      box-sizing: border-box;
      overflow: visible;
      margin-bottom: 40px;
    }

    .best-grid {
      display: flex;
      flex-direction: row;
      flex-wrap: nowrap;
      gap: 20px;
      overflow-x: auto;
      overflow-y: visible;
      padding: 20px 20px 35px 20px;
      scroll-behavior: smooth;
      -webkit-overflow-scrolling: touch;
      scroll-snap-type: x mandatory;
      scrollbar-width: thin;
      scrollbar-color: var(--teal-600) #f1f1f1;
      position: relative;
      width: 100%;
      /* Prevent wrapping at all costs */
      white-space: nowrap;
      /* Allow hover effects to be visible */
      overflow-y: hidden;
    }

    /* Hide scrollbar on some browsers but keep functionality */
    .best-grid::-webkit-scrollbar {
      height: 8px;
      display: block;
    }

    .best-grid::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 10px;
    }

    .best-grid::-webkit-scrollbar-thumb {
      background: var(--teal-600);
      border-radius: 10px;
      transition: background 0.3s ease;
    }

    .best-grid::-webkit-scrollbar-thumb:hover {
      background: var(--teal-500);
    }

    .best-grid .product-card {
      flex: 0 0 auto;
      flex-shrink: 0;
      width: 280px;
      min-width: 280px;
      max-width: 280px;
      scroll-snap-align: start;
      position: relative;
      /* Prevent any wrapping */
      display: inline-block;
      vertical-align: top;
      /* Ensure hover effects have space */
      margin: 15px 5px;
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }


    /* Responsive Card Sizing - Always Single Row, Never Wraps */
    /* Cards adjust size but always stay in one horizontal row */
    @media (min-width: 1400px) {
      .best-grid .product-card {
        width: 260px;
        min-width: 260px;
        max-width: 260px;
      }
    }

    @media (min-width: 1200px) and (max-width: 1399px) {
      .best-grid .product-card {
        width: 250px;
        min-width: 250px;
        max-width: 250px;
      }
    }

    @media (min-width: 992px) and (max-width: 1199px) {
      .best-grid .product-card {
        width: 240px;
        min-width: 240px;
        max-width: 240px;
      }
    }

    @media (min-width: 768px) and (max-width: 991px) {
      .best-sellers-wrapper {
        padding: 30px 60px;
      }

      .best-grid {
        gap: 16px;
        padding: 20px 15px 35px 15px;
        flex-wrap: nowrap !important;
      }

      .best-grid .product-card {
        width: 220px;
        min-width: 220px;
        max-width: 220px;
        flex-shrink: 0 !important;
        margin: 15px 5px;
      }
    }

    @media (max-width: 767px) {
      .best-sellers-wrapper {
        padding: 30px 50px;
      }

      .best-grid {
        gap: 14px;
        padding: 20px 15px 35px 15px;
        flex-wrap: nowrap !important;
      }

      .best-grid .product-card {
        width: 200px;
        min-width: 200px;
        max-width: 200px;
        flex-shrink: 0 !important;
        margin: 15px 5px;
      }
    }

    @media (max-width: 480px) {
      .best-sellers-wrapper {
        padding: 25px 40px;
      }

      .best-grid {
        gap: 12px;
        padding: 20px 10px 35px 10px;
        flex-wrap: nowrap !important;
      }

      .best-grid .product-card {
        width: 180px;
        min-width: 180px;
        max-width: 180px;
        flex-shrink: 0 !important;
        margin: 15px 3px;
      }
    }

    /* Force single row on all screen sizes - no exceptions */
    .best-grid,
    .best-grid * {
      box-sizing: border-box;
    }

    .best-grid .product-card {
      will-change: transform;
    }

    /* Enhanced Product Cards */
    .product-card {
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      overflow: hidden;
      position: relative;
      flex-shrink: 0;
      box-sizing: border-box;
    }

    .product-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(135deg, rgba(0, 184, 212, 0.05), transparent);
      opacity: 0;
      transition: opacity 0.3s ease;
      pointer-events: none;
      z-index: 1;
    }

    .product-card:hover {
      transform: translateY(-12px) scale(1.02);
      box-shadow: 0 20px 40px rgba(0, 184, 212, 0.2);
      border-color: var(--teal-600);
    }

    .product-card:hover::before {
      opacity: 1;
    }

    .product-card:hover .product-img img {
      transform: scale(1.1);
    }

    .product-img {
      overflow: hidden;
      border-radius: 14px;
      position: relative;
    }

    .product-img img {
      transition: transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      width: 100%;
      height: 260px;
      object-fit: cover;
    }

    /* Enhanced Heart Icon */
    .heart {
      z-index: 2;
      cursor: pointer;
      transition: all 0.3s ease;
      font-size: 24px;
      color: rgba(0, 0, 0, 0.3);
    }

    .heart:hover {
      transform: scale(1.2);
      color: #ff1744;
    }

    /* Enhanced Color Dots */
    .color-dots {
      z-index: 2;
    }

    .dot {
      transition: all 0.3s ease;
      cursor: pointer;
      border: 2px solid transparent;
    }

    .dot:hover {
      transform: scale(1.3);
      border-color: var(--white);
      box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.1);
    }

    /* Enhanced Cart Button */
    .cart-btn {
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .cart-btn::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 0;
      height: 0;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.3);
      transform: translate(-50%, -50%);
      transition: width 0.6s, height 0.6s;
    }

    .cart-btn:hover {
      background: var(--teal-500);
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(0, 184, 212, 0.4);
    }

    .cart-btn:hover::before {
      width: 300px;
      height: 300px;
    }

    .cart-btn:active {
      transform: translateY(0);
    }

    /* Enhanced Brand Cards */
    .brand-card {
      transition: all 0.3s ease;
      cursor: pointer;
      position: relative;
      overflow: hidden;
 
    }

    .brand-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(0, 184, 212, 0.1), transparent);
      transition: left 0.5s ease;
    }

    .brand-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 12px 30px rgba(0, 184, 212, 0.15);
      border-color: var(--teal-600);
    }

    .brand-card:hover::before {
      left: 100%;
    }

    .brand-card img {
      transition: transform 0.3s ease;
    }

    .brand-card:hover img {
      transform: scale(1.1);
    }

    /* Enhanced Category Cards */
    .category-card {
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
      background: linear-gradient(135deg, var(--white), #f8f9fa);
    }

    .category-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(135deg, var(--teal-600), var(--teal-300));
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .category-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 10px 25px rgba(0, 184, 212, 0.2);
      border-color: var(--teal-600);
      color: var(--white);
    }

    .category-card:hover::before {
      opacity: 1;
    }

    .category-card {
      position: relative;
      z-index: 1;
    }

    /* Fade-in Animation for Sections */
    .section {
      animation: fadeInUp 0.6s ease-out;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Stagger animation for product cards */
    .product-card:nth-child(1) {
      animation-delay: 0.1s;
    }

    .product-card:nth-child(2) {
      animation-delay: 0.2s;
    }

    /* Enhanced Header Icons */
    .header-icon-btn {
      transition: all 0.3s ease;
    }

    .header-icon-btn:hover {
      transform: scale(1.15);
      color: var(--teal-600);
    }

    .cart-icon:hover {
      transform: scale(1.1);
    }

    /* Better spacing */
    .section {
      margin-top: 60px;
      margin-bottom: 40px;
      width: 100%;
    }

    /* Ensure best sellers section takes full width */
    .section .best-sellers-wrapper {
      width: 100%;
      overflow: visible;
    }

    /* Responsive Enhancements */
    @media (max-width: 768px) {
      .section-title {
        font-size: 24px;
      }

      .product-card:hover {
        transform: translateY(-6px) scale(1.01);
      }

      .featured-item {
        height: 200px;
      }
    }

    /* Loading state for images */
    .product-img img,
    .featured-item img {
      background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
      background-size: 200% 100%;
      animation: loading 1.5s infinite;
    }

    @keyframes loading {
      0% {
        background-position: 200% 0;
      }
      100% {
        background-position: -200% 0;
      }
    }

    /* Remove loading animation once image loads */
    .product-img img[src],
    .featured-item img[src] {
      animation: none;
      background: none;
    }
  </style>

  <!-- ============================ MAIN CONTENT ============================ -->
  <!-- Header is already included by Controller -->
  <main class="home-container">

    <!-- FEATURED SECTION -->
<section class="section featured-section">
  <h2 class="section-title">Featured</h2>

  <div class="featured-slider" id="featuredSlider">
    <div class="featured-slider-wrapper">
      <!-- First set of slides -->
      <div class="featured-item">
        <img src="<?= BASE_URL ?>/assets/images/images/HarbaaBrand.png" alt="Knit Restock Collection">
      </div>

      <div class="featured-item">
        <img src="<?= BASE_URL ?>/assets/images/images/AsiliBrand.png" alt="Asili Collection">
      </div>

      <div class="featured-item">
        <img src="<?= BASE_URL ?>/assets/images/images/27Brand.png" alt="Harbaa Collection">
      </div>

      <!-- Duplicate set for seamless loop -->
      <div class="featured-item">
        <img src="<?= BASE_URL ?>/assets/images/images/ZedzeeBrand.png" alt="Knit Restock Collection">
      </div>

      <div class="featured-item">
      <img src="<?= BASE_URL ?>/assets/images/images/27Brand.png" alt="Harbaa Collection">

      </div>

      <div class="featured-item">
      <img src="<?= BASE_URL ?>/assets/images/images/HarbaaBrand.png" alt="Knit Restock Collection">

      </div>
    </div>
  </div>
  
  <div class="slider-indicators" id="sliderIndicators">
    <span class="indicator active" onclick="goToSlide(0)"></span>
    <span class="indicator" onclick="goToSlide(1)"></span>
    <span class="indicator" onclick="goToSlide(2)"></span>
  </div>
</section>


    <!-- BEST SELLERS -->
    <section class="section best-sellers-section">
      <h2 class="section-title">Best-Sellers</h2>
      <div class="highlight-line"></div>

      <div class="best-sellers-wrapper">
        <div class="best-grid" id="bestGrid">


        <!-- PRODUCT 3 -->
        <div class="product-card">
          <div class="product-img">
            <img src="<?= BASE_URL ?>/assets/images/images/HarbaaJacket.png">
          </div>

          <div class="brand">Harbaa</div>
          <div class="name">Blue Jacket</div>

          <div class="meta">
            <div class="rating">★★★★★ 4.7</div>
            <div class="price">EGP 1200</div>
          </div>

          <button class="cart-btn">Add to Cart</button>
        </div>

        <!-- PRODUCT 4 -->
        <div class="product-card">
          <div class="product-img">
            <img src="<?= BASE_URL ?>/assets/images/images/HarbaaSet.png">
          </div>

          <div class="brand">Harbaa</div>
          <div class="name">ROBBY SET</div>

          <div class="meta">
            <div class="rating">★★★★★ 4.9</div>
            <div class="price">EGP 1100</div>
          </div>

          <button class="cart-btn">Add to Cart</button>
        </div>

        <!-- PRODUCT 5 -->
        <div class="product-card">
          <div class="product-img">
            <img src="<?= BASE_URL ?>/assets/images/images/CieloDenim.png">
          </div>

          <div class="brand">Cielo</div>
          <div class="name">Denim Uniform</div>

          <div class="meta">
            <div class="rating">★★★★★ 4.8</div>
            <div class="price">EGP 1300</div>
          </div>

          <button class="cart-btn">Add to Cart</button>
        </div>

        <!-- PRODUCT 6 -->
        <div class="product-card">
          <div class="product-img">
            <img src="<?= BASE_URL ?>/assets/images/images/knit crewneck.Png">
          </div>

          <div class="brand">Zedzee</div>
          <div class="name">knit crewneck</div>

          <div class="meta">
            <div class="rating">★★★★★ 4.9</div>
            <div class="price">EGP 1180</div>
          </div>

          <button class="cart-btn">Add to Cart</button>
        </div>

        <!-- PRODUCT 7 -->
        <div class="product-card">
          <div class="product-img">
            <img src="<?= BASE_URL ?>/assets/images/images/HarbaaJacket.png">
          </div>

          <div class="brand">Harbaa</div>
          <div class="name">Grey Jacket</div>

          <div class="meta">
            <div class="rating">★★★★★ 4.6</div>
            <div class="price">EGP 1280</div>
          </div>

          <button class="cart-btn">Add to Cart</button>
        </div>

        <!-- PRODUCT 8 -->
        <div class="product-card">
          <div class="product-img">
            <img src="<?= BASE_URL ?>/assets/images/images/Basic Hoodie.png">
          </div>

          <div class="brand">TwentySeven</div>
          <div class="name">Basic Hoodie</div>

          <div class="meta">
            <div class="rating">★★★★★ 4.7</div>
            <div class="price">EGP 1120</div>
          </div>

          <button class="cart-btn">Add to Cart</button>
        </div>

        </div>
      </div>
    </section>

    <!-- BRANDS -->
    <section class="section">
      <h2 class="section-title">Brands</h2>
      <div class="highlight-line"></div>

      <div class="brands-grid">
        <a href="<?= BASE_URL ?>/harbaabrand" class="brand-card"><img src="<?= BASE_URL ?>/assets/images/images/Harbaa2.png"></a>
        <div class="brand-card"><img src="<?= BASE_URL ?>/assets/images/images/Asili.png"></div>
        <div class="brand-card"><img src="<?= BASE_URL ?>/assets/images/images/Mavin.png"></div>
        <div class="brand-card"><img src="<?= BASE_URL ?>/assets/images/images/Cielo.png"></div>
        <div class="brand-card"><img src="<?= BASE_URL ?>/assets/images/images/TwentySeven.png"></div>
      </div>
    </section>

    <!-- CATEGORIES -->
    <section class="section">
      <h2 class="section-title">Categories</h2>
      <div class="highlight-line"></div>

      <div class="categories-grid">
        <div class="category-card">Jeans</div>
        <div class="category-card">T-shirts</div>
        <div class="category-card">Pyjamas</div>
        <div class="category-card">Kids wear</div>
        <div class="category-card">Knit wear</div>
        <div class="category-card">Sports wear</div>
        <div class="category-card">Sweaters</div>
        <div class="category-card">Denim</div>
        <div class="category-card">Casual</div>
        <div class="category-card">Street wear</div>
        <div class="category-card">Basics</div>
        <div class="category-card">Swim wear</div>
        <div class="category-card">Men</div>
        <div class="category-card">Women</div>
      </div>
    </section>

  </main>
<script>
  // Enhanced Featured Slider - Calculate exact scroll distance for seamless loop
  const featuredSlider = document.getElementById('featuredSlider');
  const featuredWrapper = featuredSlider?.querySelector('.featured-slider-wrapper');
  
  if (featuredSlider && featuredWrapper) {
    function calculateScrollDistance() {
      const items = featuredWrapper.querySelectorAll('.featured-item');
      if (items.length >= 6) {
        // Calculate the actual width of the first 3 items including gaps
        let totalWidth = 0;
        const gap = 20; // Gap between items
        
        // Sum up the actual rendered widths of first 3 items
        for (let i = 0; i < 3; i++) {
          const item = items[i];
          totalWidth += item.offsetWidth;
          if (i < 2) { // Add gap after each item except the last
            totalWidth += gap;
          }
        }
        
        // Set CSS custom property for animation
        featuredWrapper.style.setProperty('--scroll-distance', `-${totalWidth}px`);
        
        // Force reflow to apply the new value
        featuredWrapper.offsetHeight;
      }
    }
    
    // Calculate on load
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', calculateScrollDistance);
    } else {
      // DOM already loaded
      setTimeout(calculateScrollDistance, 100);
    }
    
    // Recalculate on resize
    let resizeTimeout;
    window.addEventListener('resize', () => {
      clearTimeout(resizeTimeout);
      resizeTimeout = setTimeout(calculateScrollDistance, 150);
    });
  }

  // Enhanced heart icon functionality
  document.querySelectorAll('.heart').forEach(heart => {
    heart.addEventListener('click', function() {
      this.textContent = this.textContent === '♡' ? '♥' : '♡';
      this.style.color = this.textContent === '♥' ? '#ff1744' : 'rgba(0, 0, 0, 0.3)';
    });
  });

  // Enhanced Horizontal Carousel for Best Sellers
  const bestGrid = document.getElementById('bestGrid');
  const scrollLeftBtn = document.getElementById('scrollLeft');
  const scrollRightBtn = document.getElementById('scrollRight');

  function getScrollAmount() {
    if (!bestGrid) return 300;
    // Calculate scroll amount based on card width + gap
    const firstCard = bestGrid.querySelector('.product-card');
    if (firstCard) {
      const cardWidth = firstCard.offsetWidth;
      const gap = 20; // Match CSS gap
      // Scroll by approximately 3-4 cards at a time
      return (cardWidth + gap) * 3;
    }
    return 300;
  }

  function updateScrollButtons() {
    if (!bestGrid) return;
    
    const scrollThreshold = 5;
    const isAtStart = bestGrid.scrollLeft <= scrollThreshold;
    const isAtEnd = bestGrid.scrollLeft >= bestGrid.scrollWidth - bestGrid.clientWidth - scrollThreshold;
    
    if (scrollLeftBtn) {
      scrollLeftBtn.classList.toggle('hidden', isAtStart);
    }
    if (scrollRightBtn) {
      scrollRightBtn.classList.toggle('hidden', isAtEnd);
    }
  }

  if (bestGrid) {
    // Initial check
    updateScrollButtons();

    // Update on scroll with throttling for better performance
    let scrollTimeout;
    bestGrid.addEventListener('scroll', () => {
      clearTimeout(scrollTimeout);
      scrollTimeout = setTimeout(updateScrollButtons, 50);
    }, { passive: true });

    // Scroll left - move by multiple cards
    if (scrollLeftBtn) {
      scrollLeftBtn.addEventListener('click', () => {
        const scrollAmount = getScrollAmount();
        bestGrid.scrollBy({ 
          left: -scrollAmount, 
          behavior: 'smooth' 
        });
      });
    }

    // Scroll right - move by multiple cards
    if (scrollRightBtn) {
      scrollRightBtn.addEventListener('click', () => {
        const scrollAmount = getScrollAmount();
        bestGrid.scrollBy({ 
          left: scrollAmount, 
          behavior: 'smooth' 
        });
      });
    }

    // Touch/swipe support for mobile
    let touchStartX = 0;
    let touchEndX = 0;

    bestGrid.addEventListener('touchstart', (e) => {
      touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });

    bestGrid.addEventListener('touchend', (e) => {
      touchEndX = e.changedTouches[0].screenX;
      handleSwipe();
    }, { passive: true });

    function handleSwipe() {
      const swipeThreshold = 50;
      const diff = touchStartX - touchEndX;
      
      if (Math.abs(diff) > swipeThreshold) {
        const scrollAmount = getScrollAmount();
        if (diff > 0) {
          // Swipe left - scroll right
          bestGrid.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        } else {
          // Swipe right - scroll left
          bestGrid.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        }
      }
    }

    // Update on resize
    let resizeTimeout;
    window.addEventListener('resize', () => {
      clearTimeout(resizeTimeout);
      resizeTimeout = setTimeout(() => {
        updateScrollButtons();
      }, 150);
    });

    // Keyboard navigation support
    bestGrid.addEventListener('keydown', (e) => {
      if (e.key === 'ArrowLeft') {
        e.preventDefault();
        scrollLeftBtn?.click();
      } else if (e.key === 'ArrowRight') {
        e.preventDefault();
        scrollRightBtn?.click();
      }
    });

    // Make carousel focusable for keyboard navigation
    bestGrid.setAttribute('tabindex', '0');
  }
</script>

<!-- Body and HTML closing tags handled by Controller -->

</body>
</html>
