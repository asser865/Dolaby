<?php
require_once __DIR__ . '/../includes.php';
require_once __DIR__ . '/../header_functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . '/login');
    exit;
}
$userRepository = new UserRepository();
$user = $userRepository->findById($_SESSION['user_id']);

renderHeader();
?>

<style>
  .topbar .back-btn {
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

  .topbar .back-btn:hover {
    background: var(--panel);
    border-color: var(--teal-600);
  }

  .topbar .back-btn svg {
    width: 20px;
    height: 20px;
    stroke: var(--dark);
  }
</style>

<!-- Main content -->
<main class="main" role="main" aria-labelledby="main-heading">
    <div class="container">
        <div class="topbar">
            <div class="left">
                <a href="<?= BASE_URL ?>/menu" class="back-btn" aria-label="Back to previous">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                        <path d="M15 18L9 12l6-6" stroke="#1A1A1A" stroke-width="1.6" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </a>
                <div class="title" id="page-title">My Profile</div>
            </div>

            <div class="right">
            </div>
        </div>
        <div class="profile-card-wrapper">
            <ul class="profile-list">
                <li>
                    <a href="<?= BASE_URL ?>/account/accinfo" class="profile-item">
                        <span class="profile-item-text">
                            <i class="fas fa-user profile-item-icon"></i>
                            Account info
                        </span>
                        <i class="fas fa-chevron-right profile-arrow"></i>
                    </a>
                </li>

                <li>
                    <a href="<?= BASE_URL ?>/account/loyality" class="profile-item">
                        <span class="profile-item-text">
                            <i class="fas fa-gem profile-item-icon"></i>
                            Loyalty Points
                        </span>
                        <i class="fas fa-chevron-right profile-arrow"></i>
                    </a>
                </li>

                <li>
                    <a href="<?= BASE_URL ?>/account/changeemail" class="profile-item">
                        <span class="profile-item-text">
                            <i class="fas fa-envelope profile-item-icon"></i>
                            Change email
                        </span>
                        <i class="fas fa-chevron-right profile-arrow"></i>
                    </a>
                </li>

                <li>
                    <a href="<?= BASE_URL ?>/account/changepassword" class="profile-item">
                        <span class="profile-item-text">
                            <i class="fas fa-lock profile-item-icon"></i>
                            Change Password
                        </span>
                        <i class="fas fa-chevron-right profile-arrow"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</main>

<!-- Body and HTML closing tags handled by Controller -->

</body>
</html>
