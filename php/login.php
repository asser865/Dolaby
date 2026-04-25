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
            // Only allow customers to login through customer login page
            if ($user['role'] === 'brand_owner') {
                $_SESSION['error'] = 'Brand owners must use the Brand Owner login page';
            } elseif ($user['role'] !== 'customer') {
                $_SESSION['error'] = 'Invalid account type. Please use the correct login page.';
            } else {
                // User is a customer - allow login
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                header('Location: ' . BASE_URL);
                exit;
            }
        } else {
            $_SESSION['error'] = 'Invalid email or password';
        }
    }
}

renderHeader();
?>

<style>
    .login-page-wrapper {
        min-height: calc(100vh - 80px);
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--bg);
        padding: 20px;
        margin-top: 20px;
    }

    .login-container {
        width: 100%;
        max-width: 450px;
        background: var(--white);
        border-radius: 20px;
        padding: 30px 30px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        position: relative;
        z-index: 1;
    }

    .logo {
        text-align: center;
        font-size: 42px;
        font-weight: 900;
        color: var(--teal-600);
        margin-bottom: 40px;
        letter-spacing: -1px;
    }

    .login-form {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 24px;
    }

    .input-wrapper {
        position: relative;
        width: 100%;
        max-width: 380px;
    }

    .input-field {
        width: 100%;
        padding: 16px 18px 16px 52px;
        font-size: 15px;
        font-weight: 500;
        color: var(--dark);
        background: var(--white);
        border: 1px solid var(--divider);
        border-radius: 12px;
        transition: all 0.3s ease;
        outline: none;
    }

    .input-field:focus {
        border-color: var(--teal-600);
        box-shadow: 0 0 0 3px rgba(0, 184, 212, 0.1);
    }

    .password-toggle {
        position: absolute;
        right: 18px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--muted);
        cursor: pointer;
        font-size: 18px;
        padding: 0;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        z-index: 2;
    }

    .password-toggle:hover {
        color: var(--teal-600);
    }

    .password-toggle i {
        transition: transform 0.3s ease, opacity 0.3s ease;
    }

    .password-toggle.active i.fa-eye {
        transform: scale(0) rotate(180deg);
        opacity: 0;
    }

    .password-toggle.active i.fa-eye-slash {
        transform: scale(1) rotate(0deg);
        opacity: 1;
    }

    .password-toggle:not(.active) i.fa-eye {
        transform: scale(1) rotate(0deg);
        opacity: 1;
    }

    .password-toggle:not(.active) i.fa-eye-slash {
        transform: scale(0) rotate(-180deg);
        opacity: 0;
    }

    .login-button {
        width: 100%;
        max-width: 380px;
        padding: 16px 32px;
        font-size: 16px;
        font-weight: 700;
        color: white;
        background: var(--teal-600);
        border: none;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .login-button:hover:not(:disabled) {
        background: var(--teal-500);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 184, 212, 0.3);
    }

    .login-button:disabled {
        opacity: 0.8;
        cursor: not-allowed;
        transform: none;
    }
    
    @keyframes pulse {
        0%, 100% {
            opacity: 0.8;
            transform: scale(1);
        }
        50% {
            opacity: 1;
            transform: scale(1.02);
        }
    }
    
    @keyframes spin {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }
    
    .login-button.loading {
        animation: pulse 2s ease-in-out infinite;
    }

    .error-message {
        color: var(--danger);
        background: rgba(220, 38, 38, 0.1);
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 20px;
        text-align: center;
    }

    .success-message {
        color: var(--success);
        background: rgba(22, 163, 74, 0.1);
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 20px;
        text-align: center;
    }

    .footer-text {
        text-align: center;
        font-size: 14px;
        color: var(--muted);
        margin-top: 1px;
    }

    .footer-text a {
        color: var(--teal-600);
        text-decoration: none;
        font-weight: 600;
    }

    .social-login {
        display: flex;
        gap: 12px;
        margin: 28px 0;
        justify-content: center;
    }

    .social-button {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: white;
        cursor: pointer;
        border: none;
        transition: all 0.3s ease;
    }

    .social-button:hover {
        transform: translateY(-2px);
        opacity: 0.9;
    }

    .social-button.facebook {
        background: #1877f2;
    }

    .social-button.google {
        background: #db4437;
    }

    .social-button.apple {
        background: var(--dark);
    }
</style>

<div class="login-page-wrapper">
<div class="login-container">
    <h1 class="logo">Dolaby</h1>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="error-message"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="success-message"><?= htmlspecialchars($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/login" class="login-form" id="loginForm">
        <div class="input-wrapper">
            <i class="fas fa-envelope" style="position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: var(--muted); font-size: 18px;"></i>
            <input type="email" name="email" placeholder="Email" class="input-field" required>
        </div>

        <div class="input-wrapper">
            <i class="fas fa-lock" style="position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: var(--muted); font-size: 18px;"></i>
            <input type="password" name="password" id="password" placeholder="Password" class="input-field" required>
            <button type="button" class="password-toggle" id="passwordToggle" aria-label="Toggle password visibility">
                <i class="fas fa-eye"></i>
                <i class="fas fa-eye-slash" style="position: absolute;"></i>
            </button>
        </div>

        <button type="submit" class="login-button" id="loginBtn">
            <span class="btn-text">Login</span>
        </button>
    </form>

    <script>
        // Password toggle functionality
        const passwordToggle = document.getElementById('passwordToggle');
        const passwordInput = document.getElementById('password');
        
        if (passwordToggle && passwordInput) {
            passwordToggle.addEventListener('click', function() {
                const isPassword = passwordInput.type === 'password';
                passwordInput.type = isPassword ? 'text' : 'password';
                passwordToggle.classList.toggle('active');
            });
        }

        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const loginBtn = document.getElementById('loginBtn');
            const btnText = loginBtn.querySelector('.btn-text');
            if (btnText) {
                loginBtn.disabled = true;
                loginBtn.classList.add('loading');
                loginBtn.style.opacity = '0.8';
                loginBtn.style.cursor = 'not-allowed';
                btnText.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right: 8px; animation: spin 1s linear infinite;"></i>Logging in...';
            }
        });
    </script>

    <div class="social-login">
        <button type="button" class="social-button facebook" aria-label="Login with Facebook">
            <i class="fab fa-facebook-f"></i>
        </button>
        <button type="button" class="social-button google" aria-label="Login with Google">
            <i class="fab fa-google"></i>
        </button>
        <button type="button" class="social-button apple" aria-label="Login with Apple">
            <i class="fab fa-apple"></i>
        </button>
    </div>

    <div class="footer-text">
        <p>Doesn't have an account? <a href="<?= BASE_URL ?>/register">Sign up</a></p>
        <p style="margin-top: 12px;"><a href="<?= BASE_URL ?>/forgotpassword">Forgot Password?</a></p>
        <a href="<?= BASE_URL ?>/brandowner/login">Login as Brand Owner</a>
    </div>
</div>
</div>

<!-- Body and HTML closing tags handled by Controller -->

</body>
</html>
