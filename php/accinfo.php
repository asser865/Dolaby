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
    .account-info-main {
        max-width: 800px;
        margin: 0 auto;
        padding: 40px 40px;
    }

    /* Account Info Section */
    .account-section {
        margin-bottom: 36px;
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 16px;
    }

    /* Info Row */
    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 0;
        border-bottom: 1px solid var(--divider);
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        font-size: 15px;
        font-weight: 500;
        color: #666666;
    }

    .info-value {
        font-size: 16px;
        font-weight: 600;
        color: var(--dark);
    }

    /* Edit Button */
    .edit-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        background: none;
        border: none;
        color: var(--teal-600);
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        padding: 8px 12px;
        border-radius: 6px;
        transition: background-color 0.2s;
        text-decoration: none;
    }

    .edit-btn:hover {
        background-color: var(--teal-light);
    }

    /* Profile Section */
    .profile-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 24px;
        background: var(--teal-light);
        border-radius: 16px;
        margin-bottom: 32px;
    }

    .profile-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #D3D3D3;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        margin-bottom: 16px;
        object-fit: cover;
    }

    .profile-name {
        font-size: 20px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 4px;
    }

    .profile-email {
        font-size: 14px;
        color: #666666;
    }

    /* ======================================================= */
    /* -------------------- RESPONSIVE -------------------- */
    /* ======================================================= */
    @media (max-width: 768px) {
        .account-info-main {
            padding: 40px 20px;
        }

        .topbar .container {
            padding: 0 20px;
        }

        .title {
            font-size: 20px;
        }

        .info-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }
    }
</style>

<!-- ======================================================= -->
<!-- -------------------- TOPBAR ----------------------- -->
<!-- ======================================================= -->
<div class="topbar">
    <div class="container">
        <div class="top-left">
            <a class="back" href="<?= BASE_URL ?>/account" aria-label="Back">
                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M15 18L9 12l6-6" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </a>
            <div class="title">Account Info</div>
        </div>
    </div>
</div>

<!-- ======================================================= -->
<!-- -------------------- MAIN CONTENT -------------------- -->
<!-- ======================================================= -->
<main class="account-info-main">
    <?php if (isset($user) && $user): ?>
        <!-- Profile Card -->
        <div class="profile-card">
            <div class="profile-avatar">👤</div>
            <div class="profile-name"><?= htmlspecialchars(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?></div>
            <div class="profile-email"><?= htmlspecialchars($user['email'] ?? '') ?></div>
        </div>

        <!-- Personal Information Section -->
        <div class="account-section">
            <div class="section-title">Personal Information</div>

            <div class="info-row">
                <div>
                    <div class="info-label">Full Name</div>
                    <div class="info-value"><?= htmlspecialchars(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?></div>
                </div>
                <button class="edit-btn">
                    Edit
                </button>
            </div>

            <div class="info-row">
                <div>
                    <div class="info-label">Email Address</div>
                    <div class="info-value"><?= htmlspecialchars($user['email'] ?? '') ?></div>
                </div>
                <a href="<?= BASE_URL ?>/account/changeemail" class="edit-btn">
                    Edit
                </a>
            </div>

            <div class="info-row">
                <div>
                    <div class="info-label">Phone Number</div>
                    <div class="info-value"><?= htmlspecialchars($user['phone'] ?? 'Not provided') ?></div>
                </div>
                <button class="edit-btn">
                    Edit
                </button>
            </div>

            <?php if (!empty($user['date_of_birth'])): ?>
                <div class="info-row">
                    <div>
                        <div class="info-label">Date of Birth</div>
                        <div class="info-value"><?= date('F j, Y', strtotime($user['date_of_birth'])) ?></div>
                    </div>
                    <button class="edit-btn">
                        Edit
                    </button>
                </div>
            <?php endif; ?>
        </div>

        <!-- Account Settings Section -->
        <div class="account-section">
            <div class="section-title">Account Settings</div>

            <div class="info-row">
                <div>
                    <div class="info-label">Account Status</div>
                    <div class="info-value"><?= htmlspecialchars(ucfirst($user['account_status'] ?? 'Active')) ?></div>
                </div>
            </div>

            <?php if (!empty($user['created_at'])): ?>
                <div class="info-row">
                    <div>
                        <div class="info-label">Member Since</div>
                        <div class="info-value"><?= date('F Y', strtotime($user['created_at'])) ?></div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="account-section">
            <p>User information not available.</p>
        </div>
    <?php endif; ?>
</main>

<!-- Body and HTML closing tags handled by Controller -->

</body>
</html>
