<?php
require_once __DIR__ . '/../includes.php';
require_once __DIR__ . '/../header_functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'first_name' => $_POST['first_name'] ?? '',
        'last_name' => $_POST['last_name'] ?? '',
        'email' => $_POST['email'] ?? '',
        'password' => $_POST['password'] ?? '',
        'phone' => $_POST['phone'] ?? null,
        'date_of_birth' => $_POST['date_of_birth'] ?? null
    ];
    if (empty($data['first_name']) || empty($data['last_name']) || empty($data['email']) || empty($data['password'])) {
        $_SESSION['error'] = 'All required fields must be filled';
    } elseif (strlen($data['password']) < 6) {
        $_SESSION['error'] = 'Password must be at least 6 characters';
    } else {
        try {
            $authService = new AuthService();
            $userId = $authService->register($data);
            $_SESSION['success'] = 'Registration successful! Please login.';
            header('Location: ' . BASE_URL . '/login');
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = 'Registration failed: ' . $e->getMessage();
        }
    }
}

renderHeader();
?>

<style>
    .register-wrapper {
        min-height: calc(100vh - 80px);
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--bg);
        padding: 40px 20px;
        margin-top: 0;
    }

    .register-container {
        width: 100%;
        max-width: 500px;
        background: var(--white);
        border-radius: 20px;
        padding: 50px 40px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .logo {
        text-align: center;
        font-size: 42px;
        font-weight: 900;
        color: var(--teal-600);
        margin-bottom: 16px;
    }

    .register-form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .input-wrapper {
        position: relative;
        width: 100%;
    }

    .input-field {
        width: 100%;
        padding: 16px 18px 16px 52px;
        font-size: 15px;
        border: 1px solid var(--divider);
        border-radius: 12px;
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

    .register-button {
        width: 100%;
        padding: 16px 32px;
        font-size: 16px;
        font-weight: 700;
        color: white;
        background: var(--teal-600);
        border: none;
        border-radius: 12px;
        cursor: pointer;
    }

    .register-button:hover {
        background: var(--teal-500);
    }

    .error-message {
        color: var(--danger);
        background: rgba(220, 38, 38, 0.1);
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .footer-text {
        text-align: center;
        font-size: 14px;
        color: var(--muted);
        margin-top: 28px;
    }

    .footer-text a {
        color: var(--teal-600);
        text-decoration: none;
        font-weight: 600;
    }
</style>

<div class="register-wrapper">
<div class="register-container">
    <h1 class="logo">Dolaby</h1>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="error-message"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/register" class="register-form">
        <div class="input-wrapper">
            <i class="fas fa-user" style="position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: var(--muted); font-size: 18px;"></i>
            <input type="text" name="first_name" placeholder="First Name" class="input-field" required>
        </div>
        <div class="input-wrapper">
            <i class="fas fa-user" style="position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: var(--muted); font-size: 18px;"></i>
            <input type="text" name="last_name" placeholder="Last Name" class="input-field" required>
        </div>
        <div class="input-wrapper">
            <i class="fas fa-envelope" style="position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: var(--muted); font-size: 18px;"></i>
            <input type="email" name="email" placeholder="Email" class="input-field" required>
        </div>
        <div class="input-wrapper">
            <i class="fas fa-lock" style="position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: var(--muted); font-size: 18px;"></i>
            <input type="password" name="password" id="password" placeholder="Password (min 6 characters)" class="input-field" required minlength="6">
            <button type="button" class="password-toggle" id="passwordToggle" aria-label="Toggle password visibility">
                <i class="fas fa-eye"></i>
                <i class="fas fa-eye-slash" style="position: absolute;"></i>
            </button>
        </div>
        <div class="input-wrapper">
            <i class="fas fa-phone" style="position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: var(--muted); font-size: 18px;"></i>
            <input type="tel" name="phone" placeholder="Phone (optional)" class="input-field">
        </div>
        <div class="input-wrapper">
            <i class="fas fa-calendar" style="position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: var(--muted); font-size: 18px;"></i>
            <input type="date" name="date_of_birth" placeholder="Date of Birth (optional)" class="input-field">
        </div>
        <button type="submit" class="register-button">
            <i class="fas fa-user-plus" style="margin-right: 8px;"></i>Register
        </button>
    </form>

    <div class="footer-text">
        <p>Already have an account? <a href="<?= BASE_URL ?>/login">Login</a></p>
    </div>
</div>
</div>

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
</script>

<!-- Body and HTML closing tags handled by Controller -->

</body>
</html>
