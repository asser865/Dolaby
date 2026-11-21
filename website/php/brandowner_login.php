<?php
require_once __DIR__ . '/../includes.php';
require_once __DIR__ . '/../header_functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = 'Email and password are required';
    } else {
        $authService = new AuthService();
        $user = $authService->login($email, $password);
        if ($user) {
            // Only allow brand owners to login through brand owner login page
            if ($user['role'] === 'customer') {
                $_SESSION['error'] = 'Customers must use the regular customer login page';
            } elseif ($user['role'] !== 'brand_owner') {
                $_SESSION['error'] = 'Invalid account type. Only brand owners can access this page.';
            } else {
                // User is a brand owner - allow login
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                header('Location: ' . BASE_URL . '/brandowner');
                exit;
            }
        } else {
            $_SESSION['error'] = 'Invalid email or password';
        }
    }
}

renderHeader(true);
?>

<style>
    .login-page-wrapper {
        min-height: calc(100vh - 80px);
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--bg) 0%, #e0f2f1 100%);
        padding: 20px;
    }

    .login-container {
        width: 100%;
        max-width: 380px;
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-radius: 20px;
        padding: 20px 24px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.6);
        position: relative;
    }

    .logo {
        text-align: center;
        font-size: 26px;
        font-weight: 800;
        color: var(--teal-600);
        margin-bottom: 28px;
        letter-spacing: -1px;
    }

    .login-form {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .input-wrapper {
        position: relative;
        width: 100%;
    }

    .input-field {
        width: 100%;
        padding: 10px 12px 10px 38px;
        font-size: 14px;
        font-weight: 500;
        color: var(--dark);
        background: rgba(255, 255, 255, 0.7);
        border: 1px solid var(--divider);
        border-radius: 10px;
        transition: all 0.3s ease;
        outline: none;
    }

    .input-field:focus {
        border-color: var(--teal-600);
        box-shadow: 0 0 0 3px rgba(0, 184, 212, 0.1);
    }

    .input-wrapper i.fa-envelope, .input-wrapper i.fa-lock {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--muted);
        font-size: 14px;
        pointer-events: none;
    }

    .password-toggle {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--muted);
        cursor: pointer;
        padding: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login-button {
        width: 100%;
        padding: 10px;
        font-size: 15px;
        font-weight: 700;
        color: var(--white);
        background: var(--teal-600);
        border: none;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 4px;
        box-shadow: 0 4px 12px rgba(13, 148, 136, 0.2);
    }

    .login-button:hover {
        background: var(--teal-500);
    }

    .error-message {
        color: #991b1b;
        background: #fef2f2;
        padding: 8px;
        border-radius: 8px;
        margin-bottom: 12px;
        font-size: 12px;
        text-align: center;
        border: 1px solid #fee2e2;
    }

    /* Quick Test Chips */
    .test-chips {
        display: flex;
        gap: 6px;
        justify-content: center;
        margin: 10px 0 0 0;
    }

    .chip {
        background: rgba(0, 184, 212, 0.06);
        border: 1px solid rgba(0, 184, 212, 0.15);
        border-radius: 100px;
        padding: 3px 8px;
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 10px;
        font-weight: 700;
        color: var(--teal-600);
    }

    .chip button {
        background: none;
        border: none;
        cursor: pointer;
        color: var(--teal-600);
        padding: 0;
        opacity: 0.7;
    }

    .chip button:hover { opacity: 1; }

    #copyAlert {
        position: absolute;
        top: -30px;
        left: 50%;
        transform: translateX(-50%);
        font-size: 10px;
        color: var(--white);
        font-weight: 700;
        opacity: 0;
        transition: all 0.3s ease;
        pointer-events: none;
        background: var(--teal-600);
        padding: 4px 10px;
        border-radius: 100px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
</style>

<div class="login-page-wrapper">
    <div class="login-container">
        <div id="copyAlert">Copied!</div>
        <h1 class="logo">Brand Owner Login</h1>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="error-message"><?= htmlspecialchars($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>/brandowner/login" class="login-form" id="brandOwnerLoginForm">
            <div class="input-wrapper">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Brand owner email" class="input-field" required>
            </div>

            <div class="input-wrapper">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="Password" class="input-field" required>
                <button type="button" class="password-toggle" id="passwordToggle">
                    <i class="fas fa-eye"></i>
                </button>
            </div>

            <button type="submit" class="login-button" id="brandOwnerLoginBtn">Portal Login</button>

            <div class="test-chips">
                <div class="chip">
                    <i class="fas fa-user"></i>
                    <span id="testEmail">owner@harbaa.com</span>
                    <button type="button" onclick="copyToClipboard('testEmail')"><i class="far fa-copy"></i></button>
                </div>
                <div class="chip">
                    <i class="fas fa-key"></i>
                    <span id="testPass">pass123</span>
                    <button type="button" onclick="copyToClipboard('testPass')"><i class="far fa-copy"></i></button>
                </div>
            </div>
        </form>

        <div style="text-align: center; margin-top: 24px;">
            <a href="<?= BASE_URL ?>/login" style="font-size: 12px; color: var(--teal-600); text-decoration: none; font-weight: 600;">Back to customer login</a>
        </div>
    </div>
</div>

<script>
    const passwordToggle = document.getElementById('passwordToggle');
    const passwordInput = document.getElementById('password');
    if (passwordToggle && passwordInput) {
        passwordToggle.addEventListener('click', () => {
            const isPass = passwordInput.type === 'password';
            passwordInput.type = isPass ? 'text' : 'password';
            passwordToggle.querySelector('i').className = isPass ? 'fas fa-eye-slash' : 'fas fa-eye';
        });
    }

    function copyToClipboard(id) {
        const text = document.getElementById(id).innerText;
        navigator.clipboard.writeText(text).then(() => {
            const alert = document.getElementById('copyAlert');
            alert.style.top = '12px';
            alert.style.opacity = '1';
            setTimeout(() => {
                alert.style.top = '-40px';
                alert.style.opacity = '0';
            }, 1500);
        });
    }

    document.getElementById('brandOwnerLoginForm').addEventListener('submit', function() {
        const btn = document.getElementById('brandOwnerLoginBtn');
        btn.disabled = true;
        btn.innerText = 'Authorizing...';
        btn.style.opacity = '0.8';
    });
</script>

</body>
</html>
