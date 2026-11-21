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
    --font-poppins: 'Poppins', sans-serif;
    --font-montserrat: 'Montserrat', sans-serif;
  }

  /* ======================================================= */
  /* -------------------- TOPBAR ----------------------- */
  /* ======================================================= */
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

  /* ======================================================= */
  /* -------------------- MAIN CONTENT -------------------- */
  /* ======================================================= */
  main {
    max-width: 800px;
    margin: 0 auto;
    padding: 40px 40px;
  }

  .about-section {
    margin-bottom: 40px;
  }

  .section-title {
    font-size: 24px;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 20px;
    font-family: var(--font-montserrat);
  }

  .section-content {
    font-size: 16px;
    line-height: 1.8;
    color: #666666;
    margin-bottom: 16px;
  }

  .highlight-box {
    background: var(--teal-light);
    border-radius: 12px;
    padding: 24px;
    margin: 24px 0;
  }

  .highlight-box .section-content {
    color: var(--dark);
    font-weight: 500;
    margin-bottom: 0;
  }

  .values-list {
    list-style: none;
    padding: 0;
  }

  .values-list li {
    padding: 12px 0;
    border-bottom: 1px solid var(--divider);
    font-size: 16px;
    color: #666666;
    display: flex;
    align-items: flex-start;
    gap: 12px;
  }

  .values-list li:last-child {
    border-bottom: none;
  }

  .values-list li::before {
    content: "✓";
    color: var(--teal-600);
    font-weight: 700;
    font-size: 18px;
    flex-shrink: 0;
  }

  /* ======================================================= */
  /* -------------------- RESPONSIVE -------------------- */
  /* ======================================================= */
  @media (max-width: 1024px) {
    main {
      padding: 40px 80px;
    }
  }

  @media (max-width: 768px) {
    main {
      padding: 40px 40px;
    }
  }

  @media (max-width: 640px) {
    .topbar .container {
      padding: 0 20px;
    }

    .title {
      font-size: 20px;
    }

    main {
      padding: 20px 16px;
    }

    .section-title {
      font-size: 20px;
    }

    .section-content {
      font-size: 15px;
    }
  }
</style>

<!-- ======================================================= -->
<!-- -------------------- TOPBAR ----------------------- -->
<!-- ======================================================= -->
<div class="topbar">
  <div class="container">
    <div class="top-left">
      <a class="back" href="<?= BASE_URL ?>/menu" aria-label="Back">
        <svg viewBox="0 0 24 24" fill="none">
          <path d="M15 18L9 12l6-6" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </a>
      <div class="title">About Us</div>
    </div>
  </div>
</div>

<!-- ======================================================= -->
<!-- -------------------- MAIN CONTENT -------------------- -->
<!-- ======================================================= -->
<main>

  <div class="about-section">
    <h1 class="section-title">Welcome to Dolaby</h1>
    <p class="section-content">
      Dolaby is your trusted destination for quality fashion and lifestyle products. We are committed to providing our customers with exceptional service, premium products, and a seamless shopping experience.
    </p>
    <p class="section-content">
      Since our founding, we have been dedicated to bringing you the latest trends and timeless classics that fit your unique style and needs.
    </p>
  </div>

  <div class="highlight-box">
    <p class="section-content">
      Our mission is to make quality fashion accessible to everyone while maintaining the highest standards of customer satisfaction and service excellence.
    </p>
  </div>

  <div class="about-section">
    <h2 class="section-title">Our Values</h2>
    <ul class="values-list">
      <li>Quality: We source only the finest products to ensure your satisfaction</li>
      <li>Customer First: Your needs and satisfaction are our top priority</li>
      <li>Innovation: We continuously improve our services and offerings</li>
      <li>Integrity: We conduct business with honesty and transparency</li>
      <li>Sustainability: We are committed to responsible and ethical practices</li>
    </ul>
  </div>

  <div class="about-section">
    <h2 class="section-title">What We Offer</h2>
    <p class="section-content">
      At Dolaby, we offer a wide range of products including clothing, accessories, and lifestyle items. Our carefully curated collection ensures that you'll find something that matches your style and preferences.
    </p>
    <p class="section-content">
      We pride ourselves on fast and reliable delivery, secure payment options, and a customer support team that's always ready to help you with any questions or concerns.
    </p>
  </div>

  <div class="about-section">
    <h2 class="section-title">Contact Us</h2>
    <p class="section-content">
      Have questions or feedback? We'd love to hear from you! Reach out to us through our contact page or customer support channels, and we'll be happy to assist you.
    </p>
  </div>

</main>

<!-- Body and HTML closing tags handled by Controller -->

</body>
</html>
