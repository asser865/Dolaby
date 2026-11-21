<?php
require_once __DIR__ . '/../includes.php';
require_once __DIR__ . '/../header_functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . '/login');
    exit;
}

// Handle account deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    $userId = $_SESSION['user_id'];
    $deleteReason = $_POST['delete_reason'] ?? 'other';
    
    // Delete the user account from database
    $userRepository = new UserRepository();
    $deleted = $userRepository->deleteById($userId);
    
    if ($deleted) {
        // Destroy session
        session_destroy();
        $_SESSION = [];
        
        // Redirect to homepage with success message
        $_SESSION['success'] = 'Your account has been successfully deleted.';
        header('Location: ' . BASE_URL);
        exit;
    } else {
        $_SESSION['error'] = 'Failed to delete account. Please try again.';
    }
}

renderHeader();
?>

<style>
        :root {
            --color-white: #FFFFFF;
            --color-red: #E53935;
            --color-grey-soft: #E8E8E8;
            --color-grey-darker: #DFDFDF;
            --color-text-dark: #333333;
            --color-dark: #1A1A1A;
            --color-teal: #00C2D1;
            --shadow-primary: 0 3px 10px rgba(0, 0, 0, 0.10);
            --font-inter: 'Inter', sans-serif;
            --font-montserrat: 'Montserrat', sans-serif;
            --divider: #E5E5E5;
        }

        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
        }

        body {
            font-family: var(--font-inter);
            background-color: var(--color-white);
            color: var(--color-dark);
        }

        a { 
            text-decoration: none; 
            color: inherit; 
        }

        button { 
            border: 0; 
            background: none; 
            cursor: pointer; 
        }

        /* ========================================== */
        /*                 HEADER BAR                 */
        /* ========================================== */
        .app-header {
            height: 80px;
            background: var(--color-white);
            border-bottom: 1px solid var(--divider);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 40px;
        }

        .header-logo {
            font-weight: 800;
            font-size: 24px;
            color: var(--color-dark);
            white-space: nowrap;
            margin-right: 32px;
        }

        .header-nav {
            display: flex;
            gap: 30px;
            flex: 1;
        }

        .header-nav a {
            font-size: 16px;
            font-weight: 500;
        }

        .header-nav a:hover {
            color: var(--color-teal);
        }

        .header-icons {
            display: flex;
            align-items: center;
            gap: 24px;
            margin-left: auto;
        }

        .header-icon-btn {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .header-icon-btn svg {
            width: 24px;
            height: 24px;
            stroke: currentColor;
        }

        /* Cart */
        .cart-icon {
            position: relative;
            color: var(--color-teal);
        }

        .cart-icon svg {
            stroke: var(--color-teal) !important;
            width: 26px;
            height: 26px;
            transition: transform 0.2s ease, opacity 0.2s ease;
        }

        .cart-icon:hover svg {
            transform: scale(1.1);
            opacity: 0.8;
        }

        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            width: 16px;
            height: 16px;
            font-size: 10px;
            font-weight: 700;
            border-radius: 50%;
            background: var(--color-teal);
            color: var(--color-white);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Menu Hamburger */
        .background {
            width: 50px;
            height: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .menu__icon {
            width: 32px;
            height: 32px;
            padding: 4px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: end;
            transition: transform .35s;
        }

        .menu__icon span {
            width: 100%;
            height: 0.25rem;
            background: var(--color-teal);
            border-radius: 2px;
            transition: width .25s, transform .25s, background .25s;
        }

        .menu__icon span:nth-child(2) { 
            width: 75%; 
        }

        .menu__icon span:nth-child(3) { 
            width: 50%; 
        }

        .menu__icon:hover {
            transform: rotate(-90deg);
        }

        .menu__icon:hover span {
            width: 0.25rem;
            transform: translateX(-10px);
            background: #000;
        }

        /* ========================================== */
        /*            TOPBAR (BACK + TITLE)           */
        /* ========================================== */
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

        /* Back button */
        .back {
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--color-white);
            border: 1px solid var(--divider);
            border-radius: 12px;
        }

        .back svg {
            width: 20px;
            height: 20px;
            stroke: var(--color-dark);
        }

        /* Page title */
        .title {
            font-size: 22px;
            font-weight: 800;
            color: var(--color-dark);
            font-family: var(--font-montserrat);
        }

        /* ========================================== */
        /*            MAIN CONTENT                    */
        /* ========================================== */
        .content-container {
            width: 100%;
            max-width: 800px;
            padding: 40px 180px;
            margin: 0 auto;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .instruction-text {
            color: var(--color-dark);
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 30px;
            text-align: left;
            width: 100%;
        }

        /* Reason Selection Styles */
        .reason-list {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .reason-input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .reason-label {
            display: flex;
            align-items: center;
            height: 52px;
            width: 100%;
            padding: 0 18px;
            box-sizing: border-box;
            background-color: var(--color-grey-soft);
            border-radius: 13px;
            font-size: 17px;
            font-weight: 500;
            color: var(--color-text-dark);
            cursor: pointer;
            transition: background-color 0.2s, border 0.2s;
            border: 2px solid transparent;
        }

        .reason-label:hover {
            background-color: var(--color-grey-darker);
        }

        .reason-input:checked + .reason-label {
            background-color: var(--color-white);
            font-weight: 600;
            border: 2px solid var(--color-red);
        }

        /* Continue Button */
        .continue-wrapper {
            width: 100%;
            display: flex;
            justify-content: center;
            margin-top: 45px;
        }

        .continue-button {
            width: 190px;
            height: 52px;
            border-radius: 27px;
            background-color: var(--color-teal);
            color: var(--color-white);
            font-size: 19px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: var(--shadow-primary);
            transition: background-color 0.2s, box-shadow 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .continue-button:hover {
            background-color: #00D5E5;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }

        @media (max-width: 1200px) {
            .content-container { 
                padding: 40px 100px; 
            }
        }

        @media (max-width: 768px) {
            .content-container { 
                padding: 40px 20px; 
            }
            .app-header { 
                padding: 0 20px; 
            }
            .topbar .container {
                padding: 0 20px;
            }
            .header-nav { 
                display: none; 
            }
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
                <a class="back" href="<?= BASE_URL ?>/account/settings" aria-label="Back">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M15 18L9 12l6-6" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
                <div class="title">Delete Account</div>
            </div>
        </div>
    </div>

    <!-- ========================= -->
    <!--      MAIN CONTENT         -->
    <!-- ========================= -->
    <div class="content-container">
        <p class="instruction-text">
            Tell us why you want to delete your account:
        </p>

        <?php if (!empty($_SESSION['error'])): ?>
            <div style="background: rgba(220, 38, 38, 0.1); color: #b91c1c; padding: 12px 14px; border-radius: 10px; margin-bottom: 20px; font-weight: 600;">
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>/account/deleteacc" class="reason-list" id="deleteForm">
            <div>
                <input type="radio" id="reason1" name="delete_reason" value="different_mobile" class="reason-input" required>
                <label for="reason1" class="reason-label">I want to use a different mobile number</label>
            </div>

            <div>
                <input type="radio" id="reason2" name="delete_reason" value="another_account" class="reason-input" required>
                <label for="reason2" class="reason-label">I have another account</label>
            </div>

            <div>
                <input type="radio" id="reason3" name="delete_reason" value="no_value" class="reason-input" required>
                <label for="reason3" class="reason-label">I don't get value from Dolaby's promotions</label>
            </div>

            <div>
                <input type="radio" id="reason4" name="delete_reason" value="not_available" class="reason-input" required>
                <label for="reason4" class="reason-label">Dolaby is not available in my area</label>
            </div>

            <div>
                <input type="radio" id="reason5" name="delete_reason" value="not_satisfied" class="reason-input" required>
                <label for="reason5" class="reason-label">I'm not satisfied with Dolaby's services</label>
            </div>

            <div>
                <input type="radio" id="reason6" name="delete_reason" value="other" class="reason-input" required>
                <label for="reason6" class="reason-label">Other</label>
            </div>
        </form>

        <div class="continue-wrapper">
            <button type="button" class="continue-button" onclick="confirmDelete()">Continue</button>
        </div>

        <script>
        function confirmDelete() {
            // Check if a reason is selected
            const form = document.getElementById('deleteForm');
            const selectedReason = form.querySelector('input[name="delete_reason"]:checked');
            
            if (!selectedReason) {
                alert('Please select a reason for deleting your account.');
                return;
            }
            
            // Show confirmation dialog
            if (confirm('Are you sure you want to delete your account? This action cannot be undone. You will not be able to sign in with this account after deletion.')) {
                // Create hidden input for confirmation
                const confirmInput = document.createElement('input');
                confirmInput.type = 'hidden';
                confirmInput.name = 'confirm_delete';
                confirmInput.value = '1';
                form.appendChild(confirmInput);
                
                // Submit the form
                form.submit();
            }
        }
        </script>
    </div>

<!-- Body and HTML closing tags handled by Controller -->

</body>
</html>
