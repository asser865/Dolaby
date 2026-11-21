<?php
require_once __DIR__ . '/../includes.php';
require_once __DIR__ . '/../header_functions.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        // Form is valid - show success message
        $_SESSION['success'] = 'Your message has been sent successfully! We will get back to you soon.';
        header('Location: ' . BASE_URL . '/contactus');
        exit;
    }
    
    if ($error) {
        $_SESSION['error'] = $error;
    }
}

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

  .contact-section {
    margin-bottom: 40px;
  }

  .section-title {
    font-size: 24px;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 20px;
    font-family: var(--font-montserrat);
  }

  .section-subtitle {
    font-size: 16px;
    color: #666666;
    margin-bottom: 32px;
    line-height: 1.6;
  }

  /* Contact Form */
  .contact-form {
    margin-bottom: 48px;
  }

  .form-group {
    margin-bottom: 24px;
  }

  .form-label {
    display: block;
    font-size: 15px;
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 8px;
  }

  .form-input,
  .form-textarea {
    width: 100%;
    padding: 14px 16px;
    border: 1px solid var(--divider);
    border-radius: 8px;
    font-size: 15px;
    font-family: var(--font-poppins);
    color: var(--dark);
    outline: none;
    transition: border-color 0.2s;
  }

  .form-input:focus,
  .form-textarea:focus {
    border-color: var(--teal-600);
  }

  .form-textarea {
    resize: vertical;
    min-height: 120px;
  }

  .form-input::placeholder,
  .form-textarea::placeholder {
    color: #999999;
  }

  .submit-btn {
    width: 100%;
    height: 52px;
    background: var(--teal-600);
    color: var(--white);
    font-size: 18px;
    font-weight: 700;
    border-radius: 26px;
    border: none;
    cursor: pointer;
    transition: background-color 0.2s ease;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.10);
  }

  .submit-btn:hover {
    background: #00aebc;
  }

  .alert {
    padding: 14px 18px;
    border-radius: 10px;
    margin-bottom: 24px;
    font-weight: 600;
    font-size: 15px;
  }

  .alert.success {
    background: rgba(22, 163, 74, 0.1);
    color: #15803d;
    border: 1px solid rgba(22, 163, 74, 0.3);
  }

  .alert.error {
    background: rgba(220, 38, 38, 0.1);
    color: #b91c1c;
    border: 1px solid rgba(220, 38, 38, 0.3);
  }

  /* Contact Info */
  .contact-info {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 24px;
    margin-bottom: 32px;
  }

  .info-card {
    background: var(--teal-light);
    border-radius: 12px;
    padding: 24px;
  }

  .info-icon {
    font-size: 28px;
    color: var(--teal-600);
    margin-bottom: 12px;
  }

  .info-title {
    font-size: 16px;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 8px;
    font-family: var(--font-montserrat);
  }

  .info-text {
    font-size: 15px;
    color: #666666;
    line-height: 1.6;
  }

  .info-link {
    color: var(--teal-600);
    text-decoration: underline;
  }

  .info-link:hover {
    color: #00aebc;
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

    .contact-info {
      grid-template-columns: 1fr;
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

    .section-subtitle {
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
      <div class="title">Contact Us</div>
    </div>
  </div>
</div>

<!-- ======================================================= -->
<!-- -------------------- MAIN CONTENT -------------------- -->
<!-- ======================================================= -->
<main>

  <div class="contact-section">
    <h1 class="section-title">Get in Touch</h1>
    <p class="section-subtitle">
      Have a question or feedback? We'd love to hear from you! Fill out the form below or reach out to us through any of the contact methods listed.
    </p>
  </div>

  <?php if (!empty($_SESSION['success'])): ?>
    <div class="alert success">
      <?= htmlspecialchars($_SESSION['success']) ?>
    </div>
    <?php unset($_SESSION['success']); ?>
  <?php endif; ?>

  <?php if (!empty($_SESSION['error'])): ?>
    <div class="alert error">
      <?= htmlspecialchars($_SESSION['error']) ?>
    </div>
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>

  <!-- Contact Form -->
  <form class="contact-form" method="POST" action="<?= BASE_URL ?>/contactus">
    <div class="form-group">
      <label for="name" class="form-label">Full Name</label>
      <input type="text" id="name" name="name" class="form-input" placeholder="Enter your full name" 
             value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>" required>
    </div>

    <div class="form-group">
      <label for="email" class="form-label">Email Address</label>
      <input type="email" id="email" name="email" class="form-input" placeholder="Enter your email address" 
             value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" required>
    </div>

    <div class="form-group">
      <label for="subject" class="form-label">Subject</label>
      <input type="text" id="subject" name="subject" class="form-input" placeholder="What is this regarding?" 
             value="<?= isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : '' ?>" required>
    </div>

    <div class="form-group">
      <label for="message" class="form-label">Message</label>
      <textarea id="message" name="message" class="form-textarea" placeholder="Tell us more about your inquiry..." required><?= isset($_POST['message']) ? htmlspecialchars($_POST['message']) : '' ?></textarea>
    </div>

    <button type="submit" class="submit-btn">Send Message</button>
  </form>

  <!-- Contact Information -->
  <div class="contact-section">
    <h2 class="section-title">Other Ways to Reach Us</h2>
    
    <div class="contact-info">
      <div class="info-card">
        <div class="info-icon">
          <i class="fas fa-envelope"></i>
        </div>
        <div class="info-title">Email</div>
        <div class="info-text">
          <a href="mailto:support@dolaby.com" class="info-link">support@dolaby.com</a>
        </div>
      </div>

      <div class="info-card">
        <div class="info-icon">
          <i class="fas fa-phone"></i>
        </div>
        <div class="info-title">Phone</div>
        <div class="info-text">
          <a href="tel:+201234567890" class="info-link">+20 123 456 7890</a>
        </div>
      </div>

      <div class="info-card">
        <div class="info-icon">
          <i class="fas fa-clock"></i>
        </div>
        <div class="info-title">Business Hours</div>
        <div class="info-text">
          Monday - Friday: 9:00 AM - 6:00 PM<br>
          Saturday: 10:00 AM - 4:00 PM<br>
          Sunday: Closed
        </div>
      </div>
    </div>
  </div>

</main>


<!-- Body and HTML closing tags handled by Controller -->

</body>
</html>
