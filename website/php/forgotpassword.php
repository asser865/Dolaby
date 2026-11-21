<?php
require_once __DIR__ . '/../includes.php';
require_once __DIR__ . '/../header_functions.php';

renderHeader();
?>

<style>
    .forgot-page-wrapper {
        min-height: calc(100vh - 80px);
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--bg);
        padding: 20px;
        margin-top: 20px;
    }

    .forgot-container {
        width: 100%;
        max-width: 450px;
        background: var(--white);
        border-radius: 20px;
        padding: 50px 40px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        position: relative;
        z-index: 1;
        animation: slideUp 0.6s ease-out;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .forgot-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--teal-600) 0%, var(--teal-300) 100%);
        border-radius: 24px 24px 0 0;
    }

    .logo {
        text-align: center;
        font-size: 42px;
        font-weight: 900;
        color: var(--teal-600);
        margin-bottom: 16px;
        letter-spacing: -1px;
    }

    .page-title {
        text-align: center;
        font-size: 28px;
        font-weight: 800;
        color: var(--dark);
        margin-bottom: 12px;
    }

    .page-subtitle {
        text-align: center;
        color: var(--muted);
        font-size: 14px;
        margin-bottom: 40px;
    }

    .step-indicator {
        display: flex;
        justify-content: center;
        gap: 12px;
        margin-bottom: 40px;
    }

    .step {
        width: 40px;
        height: 4px;
        border-radius: 2px;
        background: var(--divider);
        transition: all 0.3s ease;
    }

    .step.active {
        background: var(--teal-600);
        width: 60px;
    }

    .form-section {
        display: none;
        animation: fadeIn 0.5s ease;
    }

    .form-section.active {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .input-wrapper {
        position: relative;
        width: 100%;
        max-width: 380px;
        margin: 0 auto 24px;
    }

    .input-wrapper i {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--muted);
        font-size: 18px;
        z-index: 1;
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
        text-align: left;
    }

    .input-field::placeholder {
        color: var(--muted);
        font-weight: 400;
    }

    .input-field:hover {
        border-color: var(--teal-600);
    }

    .input-field:focus {
        border-color: var(--teal-600);
        box-shadow: 0 0 0 3px rgba(0, 184, 212, 0.1);
    }

    .input-wrapper:focus-within i {
        color: var(--teal-600);
    }

    .otp-container {
        display: flex;
        justify-content: center;
        gap: 12px;
        margin-bottom: 32px;
    }

    .otp-input {
        width: 56px;
        height: 64px;
        text-align: center;
        font-size: 24px;
        font-weight: 700;
        border: 1px solid var(--divider);
        border-radius: 12px;
        background: var(--white);
        transition: all 0.3s ease;
        outline: none;
    }

    .otp-input:hover {
        border-color: var(--teal-600);
    }

    .otp-input:focus {
        border-color: var(--teal-600);
        box-shadow: 0 0 0 3px rgba(0, 184, 212, 0.1);
    }

    .submit-button {
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
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin: 0 auto;
    }

    .submit-button:hover:not(:disabled) {
        background: var(--teal-500);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 184, 212, 0.3);
    }

    .submit-button:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }

    .back-to-login {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        color: var(--teal-600);
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        margin-top: 32px;
        transition: color 0.3s ease;
        width: fit-content;
        margin-left: auto;
        margin-right: auto;
    }

    .back-to-login:hover {
        color: var(--teal-500);
    }

    .back-to-login i {
        font-size: 14px;
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

    @media (max-width: 640px) {
        .forgot-container {
            padding: 40px 30px;
        }

        .otp-input {
            width: 48px;
            height: 56px;
            font-size: 20px;
        }
    }
</style>

<div class="forgot-page-wrapper">
    <div class="forgot-container">
        <h1 class="logo">Dolaby</h1>
        <h2 class="page-title">Reset Password</h2>
        <p class="page-subtitle">Enter your email to receive a verification code</p>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="error-message"><?= htmlspecialchars($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="success-message"><?= htmlspecialchars($_SESSION['success']) ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <div class="step-indicator">
            <div class="step active" id="step1"></div>
            <div class="step" id="step2"></div>
        </div>

        <form id="forgotPasswordForm">
            <!-- Step 1: Email Input -->
            <div class="form-section active" id="emailSection">
                <div class="input-wrapper">
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="resetEmail" placeholder="Enter your email" class="input-field" required>
                </div>
                <button type="button" class="submit-button" id="sendCodeBtn">
                    <span class="btn-text">
                        <i class="fas fa-paper-plane"></i>
                        <span>Send Verification Code</span>
                    </span>
                </button>
            </div>

            <!-- Step 2: OTP Input -->
            <div class="form-section" id="otpSection">
                <p style="text-align: center; color: var(--muted); margin-bottom: 24px; font-size: 14px;">
                    Enter the 6-digit code sent to your email
                </p>
                <div class="otp-container">
                    <input type="text" class="otp-input" maxlength="1" id="otp1" autocomplete="off">
                    <input type="text" class="otp-input" maxlength="1" id="otp2" autocomplete="off">
                    <input type="text" class="otp-input" maxlength="1" id="otp3" autocomplete="off">
                    <input type="text" class="otp-input" maxlength="1" id="otp4" autocomplete="off">
                    <input type="text" class="otp-input" maxlength="1" id="otp5" autocomplete="off">
                    <input type="text" class="otp-input" maxlength="1" id="otp6" autocomplete="off">
                </div>
                <button type="button" class="submit-button" id="verifyCodeBtn">
                    <span class="btn-text">
                        <i class="fas fa-check"></i>
                        <span>Verify Code</span>
                    </span>
                </button>
            </div>
        </form>

        <a href="<?= BASE_URL ?>/login" class="back-to-login">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Login</span>
        </a>
    </div>
</div>

<script>
    const sendCodeBtn = document.getElementById('sendCodeBtn');
    const verifyCodeBtn = document.getElementById('verifyCodeBtn');
    const emailSection = document.getElementById('emailSection');
    const otpSection = document.getElementById('otpSection');
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    const resetEmail = document.getElementById('resetEmail');
    const otpInputs = document.querySelectorAll('.otp-input');

    // Send code button
    sendCodeBtn.addEventListener('click', function() {
        const email = resetEmail.value.trim();
        if (!email) {
            alert('Please enter your email address');
            return;
        }

        // Disable button and show loading
        sendCodeBtn.disabled = true;
        const btnText = sendCodeBtn.querySelector('.btn-text');
        const originalContent = btnText ? btnText.innerHTML : sendCodeBtn.innerHTML;
        if (btnText) {
            btnText.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Sending...</span>';
        } else {
            sendCodeBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Sending...</span>';
        }

        // Simulate sending code (replace with actual API call when backend is ready)
        setTimeout(function() {
            // Switch to OTP section
            emailSection.classList.remove('active');
            otpSection.classList.add('active');
            step1.classList.remove('active');
            step2.classList.add('active');
            
            // Focus first OTP input
            otpInputs[0].focus();
        }, 1500);
    });

    // OTP input handling
    otpInputs.forEach((input, index) => {
        input.addEventListener('input', function(e) {
            if (e.target.value.length === 1 && index < otpInputs.length - 1) {
                otpInputs[index + 1].focus();
            }
        });

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && !e.target.value && index > 0) {
                otpInputs[index - 1].focus();
            }
        });

        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const pastedData = e.clipboardData.getData('text').trim();
            if (pastedData.length === 6 && /^\d+$/.test(pastedData)) {
                pastedData.split('').forEach((char, i) => {
                    if (otpInputs[i]) {
                        otpInputs[i].value = char;
                    }
                });
                otpInputs[5].focus();
            }
        });
    });

    // Verify code button
    verifyCodeBtn.addEventListener('click', function() {
        const code = Array.from(otpInputs).map(input => input.value).join('');
        
        if (code.length !== 6) {
            alert('Please enter the complete 6-digit code');
            return;
        }

        verifyCodeBtn.disabled = true;
        const verifyBtnText = verifyCodeBtn.querySelector('.btn-text');
        const originalVerifyContent = verifyBtnText ? verifyBtnText.innerHTML : verifyCodeBtn.innerHTML;
        if (verifyBtnText) {
            verifyBtnText.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Verifying...</span>';
        } else {
            verifyCodeBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Verifying...</span>';
        }

        // Simulate verification (replace with actual API call when backend is ready)
        setTimeout(function() {
            alert('Code verified! Redirecting to reset password page...');
            window.location.href = '<?= BASE_URL ?>/login';
        }, 1500);
    });
</script>

</body>
</html>

