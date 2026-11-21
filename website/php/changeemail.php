<?php
require_once __DIR__ . '/../includes.php';
require_once __DIR__ . '/../header_functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . '/login');
    exit;
}

// Get current user data
$userRepository = new UserRepository();
$user = $userRepository->findById($_SESSION['user_id']);

if (!$user) {
    $_SESSION['error'] = 'User not found. Please log in again.';
    header('Location: ' . BASE_URL . '/login');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentEmail = trim($_POST['current_email'] ?? '');
    $newEmail = trim($_POST['new_email'] ?? '');
    $confirmEmail = trim($_POST['confirm_email'] ?? '');
    
    if (empty($currentEmail) || empty($newEmail) || empty($confirmEmail)) {
        $error = 'All fields are required.';
    } elseif ($user['email'] !== $currentEmail) {
        $error = 'Current email address does not match your account.';
    } elseif (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif ($newEmail !== $confirmEmail) {
        $error = 'New email and confirmation email do not match.';
    } elseif ($newEmail === $currentEmail) {
        $error = 'New email must be different from your current email.';
    } else {
        // Check if email is already in use
        $existingUser = $userRepository->findByEmail($newEmail);
        if ($existingUser && $existingUser['user_id'] != $_SESSION['user_id']) {
            $error = 'This email is already associated with another account.';
        } else {
            // Update email
            $updated = $userRepository->updateEmail($_SESSION['user_id'], $newEmail);
            if ($updated) {
                $_SESSION['success'] = 'Email address has been successfully updated.';
                header('Location: ' . BASE_URL . '/account/changeemail');
                exit;
            } else {
                $error = 'Failed to update email address. Please try again.';
            }
        }
    }
    
    if ($error) {
        $_SESSION['error'] = $error;
    }
}

renderHeader();
?>

<style>
  .topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 18px 0;
    border-bottom: 1px solid var(--divider);
    width: 100%;
  }

  .topbar .container {
    max-width: 1100px;
    width: 100%;
    margin: 0 auto;
    padding: 0 20px;
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
    background: var(--panel);
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
  }

  .form-wrapper {
    width: 100%;
    max-width: 650px;
    margin: 20px auto 0 auto;
    padding: 40px 20px;
    text-align: left;
  }

  .field-group {
    display: flex;
    flex-direction: column;
    gap: 20px;
  }

  .field-wrapper {
    height: 54px;
    border-radius: 14px;
    background: #DDFBFF;
    border: 1px solid #CCF3F8;
    padding: 0 18px;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .field-wrapper input {
    background: transparent;
    border: none;
    outline: none;
    font-size: 16px;
    width: 100%;
  }

  .field-icon {
    font-size: 20px;
    cursor: pointer;
  }

  .rules {
    margin-top: 20px;
  }

  .rules-title {
    font-weight: 600;
    margin-bottom: 8px;
  }

  .rules ul {
    margin-left: 20px;
    line-height: 1.6;
  }

  .button-row {
    display: flex;
    justify-content: center;
    margin-top: 35px;
  }

  .primary-btn {
    width: 200px;
    height: 52px;
    border-radius: 26px;
    border: none;
    background: var(--teal-600);
    color: var(--white);
    font-size: 18px;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    transition: transform .2s, background .2s;
  }

  .primary-btn:hover {
    background: var(--teal-500);
    transform: translateY(-2px);
  }

  @media(max-width:640px) {
    .title {
      font-size: 20px;
    }
  }
</style>

<!-- ========================= -->
<!--   BACK BUTTON + TITLE     -->
<!-- ========================= -->
<div class="topbar">
  <div class="container">
    <div class="top-left">
      <a class="back" href="<?= BASE_URL ?>/account" aria-label="Back">
        <svg viewBox="0 0 24 24" fill="none">
          <path d="M15 18L9 12l6-6" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </a>
      <div class="title">Change Email</div>
    </div>
  </div>
</div>

<!-- ======================= MAIN ======================= -->
<main class="form-wrapper">
  <?php if (!empty($_SESSION['error'])): ?>
    <div style="background: rgba(220, 38, 38, 0.1); color: #b91c1c; padding: 12px 14px; border-radius: 10px; margin-bottom: 20px; font-weight: 600;">
      <?= htmlspecialchars($_SESSION['error']) ?>
    </div>
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>

  <?php if (!empty($_SESSION['success'])): ?>
    <div style="background: rgba(22, 163, 74, 0.1); color: #15803d; padding: 12px 14px; border-radius: 10px; margin-bottom: 20px; font-weight: 600;">
      <?= htmlspecialchars($_SESSION['success']) ?>
    </div>
    <?php unset($_SESSION['success']); ?>
  <?php endif; ?>

  <form method="POST" action="<?= BASE_URL ?>/account/changeemail">
    <div class="field-group">
      <div class="field-wrapper">
        <input type="email" name="current_email" placeholder="Current Email Address" required 
               value="<?= isset($user) ? htmlspecialchars($user['email']) : '' ?>" />
        <span class="field-icon">📧</span>
      </div>

      <div class="field-wrapper">
        <input type="email" name="new_email" placeholder="New Email Address" required />
        <span class="field-icon">📧</span>
      </div>

      <div class="field-wrapper">
        <input type="email" name="confirm_email" placeholder="Confirm New Email Address" required />
        <span class="field-icon">📧</span>
      </div>
    </div>

    <div class="rules">
      <ul>
        <li>Be a valid email format (example@domain.com)</li>
        <li>Not be already associated with another account</li>
        <li>Match the confirmation email</li>
      </ul>
    </div>

    <div class="button-row">
      <button type="submit" class="primary-btn">Submit</button>
    </div>
  </form>
</main>

<!-- Body and HTML closing tags handled by Controller -->

</body>
</html>
