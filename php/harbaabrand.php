<?php
require_once __DIR__ . '/../includes.php';
require_once __DIR__ . '/../header_functions.php';

renderHeader();
?>

<style>
  :root {
    --white: #FFFFFF;
    --dark: #1A1A1A;
    --divider: #E5E5E5;
    --teal-600: #00B8D4;
    --teal-light: #DDFAFF;
    --grey-light: #D3D3D3;
    --font-poppins: 'Poppins', sans-serif;
    --font-montserrat: 'Montserrat', sans-serif;
  }

  .harbaa-page {
    font-family: var(--font-poppins);
    background: var(--white);
    color: var(--dark);
  }

  /* Topbar */
  .topbar {
    width: 100%;
    padding: 18px 0;
    border-bottom: 1px solid var(--divider);
  }

  .topbar .container {
    max-width: 1100px;
    width: 100%;
    margin: 0 auto;
    padding: 0 40px;
    display: flex;
    align-items: center;
    gap: 20px;
  }

  .top-left {
    display: flex;
    align-items: center;
    gap: 14px;
  }

  .back {
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

  .back:hover {
    background: var(--panel);
    border-color: var(--teal-600);
  }

  .back svg {
    width: 20px;
    height: 20px;
    stroke: var(--dark);
  }

  .title {
    font-size: 22px;
    font-weight: 800;
    color: var(--dark);
    font-family: var(--font-montserrat);
  }

  /* Brand Hero Section */
  .brand-hero {
    background: linear-gradient(135deg, var(--teal-light) 0%, rgba(221, 250, 255, 0.5) 100%);
    padding: 60px 40px;
    margin-bottom: 40px;
  }

  .brand-hero-content {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    gap: 40px;
  }

  .brand-logo-section {
    flex-shrink: 0;
  }

  .brand-logo {
    width: 180px;
    height: 180px;
    background: var(--white);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 10px 30px rgba(0, 184, 212, 0.15);
    padding: 20px;
  }

  .brand-logo img {
    width: 100%;
    height: 100%;
    object-fit: contain;
  }

  .brand-info {
    flex: 1;
  }

  .brand-name {
    font-family: var(--font-montserrat);
    font-size: 48px;
    font-weight: 800;
    color: var(--dark);
    margin-bottom: 12px;
  }

  .brand-tagline {
    font-size: 18px;
    color: #666666;
    margin-bottom: 24px;
    font-weight: 500;
  }

  .brand-stats {
    display: flex;
    gap: 32px;
    margin-bottom: 20px;
  }

  .stat-item {
    display: flex;
    flex-direction: column;
  }

  .stat-value {
    font-family: var(--font-montserrat);
    font-size: 28px;
    font-weight: 800;
    color: var(--teal-600);
  }

  .stat-label {
    font-size: 14px;
    color: #666666;
    margin-top: 4px;
  }

  .brand-actions {
    display: flex;
    gap: 16px;
    align-items: center;
    flex-wrap: wrap;
  }

  .follow-btn {
    padding: 14px 32px;
    background: var(--teal-600);
    color: var(--white);
    border-radius: 26px;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0, 184, 212, 0.3);
    display: flex;
    align-items: center;
    gap: 8px;
    border: none;
    font-family: var(--font-poppins);
    white-space: nowrap;
    outline: none;
  }

  .follow-btn i {
    font-size: 14px;
  }

  .follow-btn:hover {
    background: #00aebc;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 184, 212, 0.4);
  }

  .follow-btn:active {
    transform: translateY(0);
  }

  .follow-btn.following {
    background: #16A34A;
    box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);
  }

  .follow-btn.following:hover {
    background: #15803d;
    box-shadow: 0 6px 20px rgba(22, 163, 74, 0.4);
  }

  .share-btn {
    width: 48px;
    height: 48px;
    background: var(--white);
    border: 2px solid var(--divider);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    flex-shrink: 0;
    outline: none;
  }

  .share-btn i {
    font-size: 18px;
    color: var(--dark);
  }

  .share-btn:hover {
    border-color: var(--teal-600);
    background: var(--teal-light);
    transform: scale(1.1);
  }

  .share-btn:active {
    transform: scale(1.05);
  }

  /* Main Content */
  main {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 40px 60px 40px;
  }

  .content-section {
    margin-bottom: 60px;
  }

  .section-title {
    font-size: 28px;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 24px;
    font-family: var(--font-montserrat);
  }

  /* Brand Story */
  .brand-story {
    background: var(--white);
    border: 1px solid var(--divider);
    border-radius: 16px;
    padding: 40px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  }

  .story-text {
    font-size: 16px;
    line-height: 1.8;
    color: #666666;
    margin-bottom: 20px;
  }

  .story-text:last-child {
    margin-bottom: 0;
  }

  /* Featured Products */
  .products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 12px;
  }

  .product-card {
    background: var(--white);
    border: 1px solid var(--divider);
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    cursor: pointer;
    text-decoration: none;
    color: inherit;
    position: relative;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
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
    transform: translateY(-6px);
    box-shadow: 0 16px 40px rgba(0, 184, 212, 0.25);
    border-color: var(--teal-600);
  }

  .product-card:hover::before {
    opacity: 1;
  }

  .product-image-wrapper {
    position: relative;
    overflow: hidden;
    background: #f8f9fa;
  }

  .product-image {
    width: 100%;
    height: 300px;
    object-fit: cover;
    transition: transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  }

  .product-card:hover .product-image {
    transform: scale(1.08);
  }

  .product-info {
    padding: 16px 18px 18px 18px;
    position: relative;
    z-index: 2;
  }

  .product-brand {
    font-size: 13px;
    color: var(--teal-600);
    font-weight: 600;
    margin-bottom: 6px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .product-name {
    font-size: 17px;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 10px;
    line-height: 1.4;
    font-family: var(--font-montserrat);
  }

  .product-price {
    font-size: 20px;
    font-weight: 800;
    color: var(--dark);
    font-family: var(--font-montserrat);
  }

  /* Social Links */
  .social-links {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
  }

  .social-link {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 20px;
    background: var(--white);
    border: 1px solid var(--divider);
    border-radius: 12px;
    transition: all 0.3s ease;
    color: var(--dark);
    text-decoration: none;
  }

  .social-link:hover {
    background: var(--teal-light);
    border-color: var(--teal-600);
    transform: translateY(-2px);
  }

  .social-link i {
    font-size: 20px;
    color: var(--teal-600);
  }

  @media (max-width: 1024px) {
    .brand-hero-content {
      flex-direction: column;
      text-align: center;
    }

    .brand-stats {
      justify-content: center;
    }

    .brand-actions {
      justify-content: center;
    }
  }

  @media (max-width: 768px) {
    main {
      padding: 0 20px 40px 20px;
    }

    .brand-hero {
      padding: 40px 20px;
    }

    .brand-name {
      font-size: 36px;
    }

    .brand-stats {
      flex-wrap: wrap;
      gap: 20px;
    }

    .products-grid {
      grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    }
  }

  @media (max-width: 640px) {
    .topbar .container {
      padding: 0 20px;
    }

    .title {
      font-size: 20px;
    }

    .brand-name {
      font-size: 28px;
    }

    .brand-logo {
      width: 120px;
      height: 120px;
    }

    .products-grid {
      grid-template-columns: 1fr;
    }
  }
</style>

<div class="harbaa-page">
  <!-- Topbar -->
  <div class="topbar">
    <div class="container">
      <div class="top-left">
        <a class="back" href="<?= BASE_URL ?>/home" aria-label="Back">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M15 18L9 12l6-6" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </a>
        <div class="title">Harbaa Brand</div>
      </div>
    </div>
  </div>

  <!-- Brand Hero -->
  <div class="brand-hero">
    <div class="brand-hero-content">
      <div class="brand-logo-section">
        <div class="brand-logo">
          <img src="<?= BASE_URL ?>/assets/images/images/Harbaa2.png" alt="Harbaa Logo" onerror="this.src='<?= BASE_URL ?>/assets/images/shop/Harbaa.png'">
        </div>
      </div>
      
      <div class="brand-info">
        <h1 class="brand-name">Harbaa</h1>
        <p class="brand-tagline">Elevating Streetwear with Premium Quality & Contemporary Style</p>
        
        <div class="brand-stats">
          <div class="stat-item">
            <div class="stat-value">50K+</div>
            <div class="stat-label">Followers</div>
          </div>
          <div class="stat-item">
            <div class="stat-value">200+</div>
            <div class="stat-label">Products</div>
          </div>
          <div class="stat-item">
            <div class="stat-value">4.9</div>
            <div class="stat-label">Rating</div>
          </div>
        </div>

        <div class="brand-actions">
          <button class="follow-btn" id="followBtn" onclick="toggleFollow()">
            <i class="fas fa-plus"></i>
            <span>Follow</span>
          </button>
          <button class="share-btn" aria-label="Share">
            <i class="fas fa-share-alt"></i>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <main>
    <!-- Brand Story -->
    <section class="content-section">
      <h2 class="section-title">Our Story</h2>
      <div class="brand-story">
        <p class="story-text">
          Harbaa was born from a passion for creating high-quality streetwear that seamlessly blends comfort, style, and functionality. Founded with a vision to redefine contemporary fashion, we've become a trusted name in the urban fashion landscape.
        </p>
        <p class="story-text">
          Our commitment to excellence is reflected in every piece we create. From premium hoodies and jackets to versatile sets, each product is carefully designed and crafted to meet the highest standards of quality and style. We believe that fashion should be accessible, comfortable, and expressive.
        </p>
        <p class="story-text">
          At Harbaa, we're not just creating clothing – we're building a community of style-conscious individuals who value quality, authenticity, and self-expression. Join us on this journey as we continue to push boundaries and set new trends in streetwear fashion.
        </p>
      </div>
    </section>

    <!-- Featured Products -->
    <section class="content-section">
      <h2 class="section-title">Featured Products</h2>
      <div class="products-grid">
        <a href="<?= BASE_URL ?>/shop" class="product-card">
          <div class="product-image-wrapper">
            <img src="<?= BASE_URL ?>/assets/images/harbaa/1.png" alt="Harbaa Product" class="product-image" onerror="this.src='<?= BASE_URL ?>/assets/images/shop/1.png'">
          </div>
          <div class="product-info">
            <div class="product-brand">Harbaa</div>
            <div class="product-name">Premium Collection</div>
            <div class="product-price">EGP 1,250</div>
          </div>
        </a>

        <a href="<?= BASE_URL ?>/shop" class="product-card">
          <div class="product-image-wrapper">
            <img src="<?= BASE_URL ?>/assets/images/harbaa/3.png" alt="Harbaa Product" class="product-image" onerror="this.src='<?= BASE_URL ?>/assets/images/shop/3.png'">
          </div>
          <div class="product-info">
            <div class="product-brand">Harbaa</div>
            <div class="product-name">Streetwear Essentials</div>
            <div class="product-price">EGP 1,150</div>
          </div>
        </a>

        <a href="<?= BASE_URL ?>/shop" class="product-card">
          <div class="product-image-wrapper">
            <img src="<?= BASE_URL ?>/assets/images/harbaa/5.png" alt="Harbaa Product" class="product-image" onerror="this.src='<?= BASE_URL ?>/assets/images/shop/5.png'">
          </div>
          <div class="product-info">
            <div class="product-brand">Harbaa</div>
            <div class="product-name">Urban Style</div>
            <div class="product-price">EGP 1,100</div>
          </div>
        </a>

        <a href="<?= BASE_URL ?>/shop" class="product-card">
          <div class="product-image-wrapper">
            <img src="<?= BASE_URL ?>/assets/images/harbaa/6.png" alt="Harbaa Product" class="product-image" onerror="this.src='<?= BASE_URL ?>/assets/images/shop/6.png'">
          </div>
          <div class="product-info">
            <div class="product-brand">Harbaa</div>
            <div class="product-name">Contemporary Design</div>
            <div class="product-price">EGP 1,100</div>
          </div>
        </a>

        <a href="<?= BASE_URL ?>/shop" class="product-card">
          <div class="product-image-wrapper">
            <img src="<?= BASE_URL ?>/assets/images/harbaa/HarbaaSet.png" alt="Robby Set" class="product-image" onerror="this.src='<?= BASE_URL ?>/assets/images/shop/HarbaaSet.png'">
          </div>
          <div class="product-info">
            <div class="product-brand">Harbaa</div>
            <div class="product-name">Robby Set</div>
            <div class="product-price">EGP 1,100</div>
          </div>
        </a>

        <a href="<?= BASE_URL ?>/shop" class="product-card">
          <div class="product-image-wrapper">
            <img src="<?= BASE_URL ?>/assets/images/harbaa/HarbaaJacket.png" alt="Harbaa Jacket" class="product-image" onerror="this.src='<?= BASE_URL ?>/assets/images/shop/HarbaaJacket.png'">
          </div>
          <div class="product-info">
            <div class="product-brand">Harbaa</div>
            <div class="product-name">Premium Jacket</div>
            <div class="product-price">EGP 1,200</div>
          </div>
        </a>
      </div>
    </section>

    <!-- Social Media -->
    <section class="content-section">
      <h2 class="section-title">Connect With Us</h2>
      <div class="social-links">
        <a href="#" class="social-link">
          <i class="fab fa-instagram"></i>
          <span>@harbaa_official</span>
        </a>
        <a href="#" class="social-link">
          <i class="fab fa-facebook"></i>
          <span>Harbaa Brand</span>
        </a>
        <a href="#" class="social-link">
          <i class="fab fa-twitter"></i>
          <span>@harbaa</span>
        </a>
        <a href="#" class="social-link">
          <i class="fab fa-tiktok"></i>
          <span>@harbaa</span>
        </a>
      </div>
    </section>
  </main>
</div>

<script>
  function toggleFollow() {
    const followBtn = document.getElementById('followBtn');
    const icon = followBtn.querySelector('i');
    const text = followBtn.querySelector('span');
    
    if (followBtn.classList.contains('following')) {
      followBtn.classList.remove('following');
      icon.className = 'fas fa-plus';
      text.textContent = 'Follow';
    } else {
      followBtn.classList.add('following');
      icon.className = 'fas fa-check';
      text.textContent = 'Following';
    }
  }
</script>

<!-- Body and HTML closing tags handled by Controller -->

</body>
</html>
