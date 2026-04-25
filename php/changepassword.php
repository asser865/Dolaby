<?php
require_once __DIR__ . '/../src/includes.php';
require_once __DIR__ . '/../src/header_functions.php';

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        $error = 'All fields are required.';
    } else {
        // Verify current password
        if ($user['password_hash'] !== $currentPassword) {
            $error = 'Current password is incorrect.';
        } elseif ($newPassword !== $confirmPassword) {
            $error = 'New password and confirmation password do not match.';
        } elseif ($newPassword === $currentPassword) {
            $error = 'New password must be different from your current password.';
        } elseif (strlen($newPassword) < 8) {
            $error = 'Password must be at least 8 characters long.';
        } elseif (!preg_match('/[A-Z]/', $newPassword)) {
            $error = 'Password must contain at least one uppercase letter.';
        } elseif (!preg_match('/[a-z]/', $newPassword)) {
            $error = 'Password must contain at least one lowercase letter.';
        } elseif (!preg_match('/[0-9]/', $newPassword)) {
            $error = 'Password must contain at least one number.';
        } elseif (!preg_match('/[~@#$%^&*+=]/', $newPassword)) {
            $error = 'Password must contain at least one special character (~@#$%^&*+=).';
        } else {
            // Update password
            $updated = $userRepository->updatePassword($_SESSION['user_id'], $newPassword);
            if ($updated) {
                $_SESSION['success'] = 'Password has been successfully updated.';
                header('Location: ' . BASE_URL . '/account/changepassword');
                exit;
            } else {
                $error = 'Failed to update password. Please try again.';
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
    transition: transform 0.3s ease, opacity 0.3s ease;
    user-select: none;
  }

  .field-icon:hover {
    transform: scale(1.1);
  }

  .field-wrapper.password-visible .field-icon {
    opacity: 0.7;
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
      <div class="title">Change Password</div>
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

  <form method="POST" action="<?= BASE_URL ?>/account/changepassword">
    <div class="field-group">
      <div class="field-wrapper" id="currentPasswordWrapper">
        <input type="password" name="current_password" id="current_password" placeholder="Current Password" required />
        <span class="field-icon" id="currentPasswordToggle" role="button" tabindex="0" aria-label="Toggle password visibility">
          <i class="fas fa-eye"></i>
        </span>
      </div>

      <div class="field-wrapper" id="newPasswordWrapper">
        <input type="password" name="new_password" id="new_password" placeholder="New Password" required />
        <span class="field-icon" id="newPasswordToggle" role="button" tabindex="0" aria-label="Toggle password visibility">
          <i class="fas fa-eye"></i>
        </span>
      </div>

      <div class="field-wrapper" id="confirmPasswordWrapper">
        <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm New Password" required />
        <span class="field-icon" id="confirmPasswordToggle" role="button" tabindex="0" aria-label="Toggle password visibility">
          <i class="fas fa-eye"></i>
        </span>
      </div>
    </div>

    <div class="rules">
      <div class="rules-title">Password must be at least 8 characters and should include:</div>
      <ul>
        <li>1 uppercase letter (A–Z)</li>
        <li>1 lowercase letter (a–z)</li>
        <li>1 number (0–9)</li>
        <li>1 special character (~@#$%^&*+=)</li>
      </ul>
    </div>

    <div class="button-row">
      <button type="submit" class="primary-btn">Submit</button>
    </div>
  </form>
</main>

<script>
  // Password toggle functionality for all password fields
  function setupPasswordToggle(toggleId, inputId, wrapperId) {
    const toggle = document.getElementById(toggleId);
    const input = document.getElementById(inputId);
    const wrapper = document.getElementById(wrapperId);
    
    if (toggle && input && wrapper) {
      const toggleHandler = function() {
        const isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';
        const icon = toggle.querySelector('i');
        if (icon) {
          icon.className = isPassword ? 'fas fa-eye-slash' : 'fas fa-eye';
        }
        wrapper.classList.toggle('password-visible', !isPassword);
      };
      
      toggle.addEventListener('click', toggleHandler);
      toggle.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          toggleHandler();
        }
      });
    }
  }

  // Setup toggles for all password fields
  setupPasswordToggle('currentPasswordToggle', 'current_password', 'currentPasswordWrapper');
  setupPasswordToggle('newPasswordToggle', 'new_password', 'newPasswordWrapper');
  setupPasswordToggle('confirmPasswordToggle', 'confirm_password', 'confirmPasswordWrapper');
</script>

<!-- Body and HTML closing tags handled by Controller -->

</body>
</html>
