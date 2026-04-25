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

  .faq-section {
    margin-bottom: 24px;
  }

  .section-title {
    font-size: 20px;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 24px;
    font-family: var(--font-montserrat);
  }

  .faq-item {
    border-bottom: 1px solid var(--divider);
    margin-bottom: 0;
  }

  .faq-question {
    width: 100%;
    padding: 20px 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    background: none;
    text-align: left;
    font-size: 16px;
    font-weight: 600;
    color: var(--dark);
    transition: color 0.2s;
    border: none;
    font-family: var(--font-poppins);
  }

  .faq-question:hover {
    color: var(--teal-600);
  }

  .faq-question i {
    font-size: 14px;
    color: var(--teal-600);
    transition: transform 0.3s ease;
  }

  .faq-question.active i {
    transform: rotate(180deg);
  }

  .faq-answer {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease, padding 0.3s ease;
    padding: 0 0;
  }

  .faq-answer.active {
    max-height: 500px;
    padding: 0 0 20px 0;
  }

  .faq-answer-content {
    font-size: 15px;
    line-height: 1.7;
    color: #666666;
  }

  .help-section {
    background: var(--teal-light);
    border-radius: 12px;
    padding: 24px;
    margin-top: 40px;
  }

  .help-section-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 12px;
    font-family: var(--font-montserrat);
  }

  .help-section-content {
    font-size: 15px;
    line-height: 1.7;
    color: #666666;
    margin-bottom: 16px;
  }

  .contact-btn {
    display: inline-block;
    padding: 12px 24px;
    background: var(--teal-600);
    color: var(--white);
    border-radius: 8px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.2s;
    margin-top: 8px;
    text-decoration: none;
  }

  .contact-btn:hover {
    background: #00aebc;
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
      font-size: 18px;
    }

    .faq-question {
      font-size: 15px;
      padding: 16px 0;
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
      <div class="title">Help & FAQs</div>
    </div>
  </div>
</div>

<!-- ======================================================= -->
<!-- -------------------- MAIN CONTENT -------------------- -->
<!-- ======================================================= -->
<main>

  <div class="faq-section">
    <h1 class="section-title">Frequently Asked Questions</h1>

    <div class="faq-item">
      <button class="faq-question" onclick="toggleFaq(this)">
        <span>How do I place an order?</span>
        <i class="fas fa-chevron-down"></i>
      </button>
      <div class="faq-answer">
        <div class="faq-answer-content">
          To place an order, simply browse our products, add items to your cart, and proceed to checkout. You'll need to provide your delivery address and payment information to complete your purchase.
        </div>
      </div>
    </div>

    <div class="faq-item">
      <button class="faq-question" onclick="toggleFaq(this)">
        <span>What payment methods do you accept?</span>
        <i class="fas fa-chevron-down"></i>
      </button>
      <div class="faq-answer">
        <div class="faq-answer-content">
          We accept various payment methods including credit cards, debit cards, and digital wallets. You can manage your payment methods in your account settings.
        </div>
      </div>
    </div>

    <div class="faq-item">
      <button class="faq-question" onclick="toggleFaq(this)">
        <span>How long does delivery take?</span>
        <i class="fas fa-chevron-down"></i>
      </button>
      <div class="faq-answer">
        <div class="faq-answer-content">
          Delivery times vary depending on your location. Typically, orders are processed within 1-2 business days and delivered within 3-7 business days. You can track your order status in the "My Orders" section.
        </div>
      </div>
    </div>

    <div class="faq-item">
      <button class="faq-question" onclick="toggleFaq(this)">
        <span>Can I change or cancel my order?</span>
        <i class="fas fa-chevron-down"></i>
      </button>
      <div class="faq-answer">
        <div class="faq-answer-content">
          You can modify or cancel your order within 24 hours of placing it, provided it hasn't been shipped yet. Once shipped, you can initiate a return through the "My Orders" section.
        </div>
      </div>
    </div>

    <div class="faq-item">
      <button class="faq-question" onclick="toggleFaq(this)">
        <span>How do I track my order?</span>
        <i class="fas fa-chevron-down"></i>
      </button>
      <div class="faq-answer">
        <div class="faq-answer-content">
          You can track your order by going to "My Orders" in your account. Once your order is shipped, you'll receive a tracking number that you can use to monitor your package's progress.
        </div>
      </div>
    </div>

    <div class="faq-item">
      <button class="faq-question" onclick="toggleFaq(this)">
        <span>What is your return policy?</span>
        <i class="fas fa-chevron-down"></i>
      </button>
      <div class="faq-answer">
        <div class="faq-answer-content">
          We offer a 30-day return policy for unused items in their original packaging. Items must be in the same condition as when received. Please contact our support team to initiate a return.
        </div>
      </div>
    </div>

    <div class="faq-item">
      <button class="faq-question" onclick="toggleFaq(this)">
        <span>How do I update my account information?</span>
        <i class="fas fa-chevron-down"></i>
      </button>
      <div class="faq-answer">
        <div class="faq-answer-content">
          You can update your account information by going to "My Profile" in the menu. From there, you can edit your personal details, delivery addresses, and payment methods.
        </div>
      </div>
    </div>

    <div class="faq-item">
      <button class="faq-question" onclick="toggleFaq(this)">
        <span>Do you ship internationally?</span>
        <i class="fas fa-chevron-down"></i>
      </button>
      <div class="faq-answer">
        <div class="faq-answer-content">
          Currently, we ship to select countries. Please check the delivery address section during checkout to see if we deliver to your location. We're continuously expanding our delivery areas.
        </div>
      </div>
    </div>

    <div class="faq-item">
      <button class="faq-question" onclick="toggleFaq(this)">
        <span>How do I apply a promo code?</span>
        <i class="fas fa-chevron-down"></i>
      </button>
      <div class="faq-answer">
        <div class="faq-answer-content">
          You can apply a promo code during checkout. Enter your code in the "Promo Code" field and click "Apply". Valid codes will be automatically applied to your order total.
        </div>
      </div>
    </div>

    <div class="faq-item">
      <button class="faq-question" onclick="toggleFaq(this)">
        <span>What should I do if I receive a damaged item?</span>
        <i class="fas fa-chevron-down"></i>
      </button>
      <div class="faq-answer">
        <div class="faq-answer-content">
          If you receive a damaged item, please contact our customer support immediately with photos of the damage. We'll arrange for a replacement or refund as quickly as possible.
        </div>
      </div>
    </div>

  </div>

  <div class="help-section">
    <h2 class="help-section-title">Still Need Help?</h2>
    <p class="help-section-content">
      If you couldn't find the answer you're looking for, our customer support team is here to help. Don't hesitate to reach out to us!
    </p>
    <a href="<?= BASE_URL ?>/contactus" class="contact-btn">Contact Support</a>
  </div>

</main>

<script>
  function toggleFaq(button) {
    const faqItem = button.parentElement;
    const answer = faqItem.querySelector('.faq-answer');
    const isActive = button.classList.contains('active');

    // Close all other FAQs
    document.querySelectorAll('.faq-question').forEach(q => {
      if (q !== button) {
        q.classList.remove('active');
        q.parentElement.querySelector('.faq-answer').classList.remove('active');
      }
    });

    // Toggle current FAQ
    if (isActive) {
      button.classList.remove('active');
      answer.classList.remove('active');
    } else {
      button.classList.add('active');
      answer.classList.add('active');
    }
  }
</script>

<!-- Body and HTML closing tags handled by Controller -->

</body>
</html>
