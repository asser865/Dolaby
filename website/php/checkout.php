<?php
require_once __DIR__ . '/../includes.php';
require_once __DIR__ . '/../header_functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . '/login');
    exit;
}
$cartService = new CartService();
$cart = $cartService->getCart($_SESSION['user_id']);
$addressRepository = new AddressRepository();
$addresses = $addressRepository->getByUserId($_SESSION['user_id']);
$paymentMethodRepository = new PaymentMethodRepository();
$paymentMethods = $paymentMethodRepository->getByUserId($_SESSION['user_id']);
if (empty($cart['items'])) {
    $_SESSION['error'] = 'Your cart is empty';
    header('Location: ' . BASE_URL . '/cart');
    exit;
}

renderHeader();
?>

<style>
    .checkout-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .checkout-form {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
    }

    .checkout-section {
        background: var(--white);
        border-radius: var(--card-radius);
        padding: 30px;
        margin-bottom: 24px;
    }

    .checkout-section:last-child {
        margin-bottom: 0;
    }

    .checkout-section h2 {
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 12px;
        border: 1px solid var(--divider);
        border-radius: 8px;
    }

    .order-summary {
        background: var(--white);
        border-radius: var(--card-radius);
        padding: 30px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
    }

    .checkout-btn {
        width: 100%;
        padding: 16px;
        background: var(--teal-600);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 18px;
        font-weight: 700;
        cursor: pointer;
        margin-top: 20px;
    }
</style>

<div class="checkout-container">
    <h1>Checkout</h1>

    <?php if (isset($_SESSION['error'])): ?>
        <div style="background: rgba(220, 38, 38, 0.1); color: var(--danger); padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <?= htmlspecialchars($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/checkout_process" class="checkout-form">
        <div>
            <div class="checkout-section">
                <h2>Shipping Address</h2>
                <?php if (!empty($addresses)): ?>
                    <?php foreach ($addresses as $addr): ?>
                        <div class="form-group">
                            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; padding: 12px; border: 1px solid var(--divider); border-radius: 8px; transition: all 0.3s ease;">
                                <input type="radio" name="address_id" value="<?= $addr['address_id'] ?>" required style="width: auto; margin: 0;">
                                <div>
                                    <div style="font-weight: 600;"><?= htmlspecialchars($addr['address_type'] ?? 'Home') ?></div>
                                    <div style="font-size: 14px; color: var(--muted);"><?= htmlspecialchars($addr['area']) ?><?= !empty($addr['street']) ? ', ' . htmlspecialchars($addr['street']) : '' ?></div>
                                </div>
                            </label>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                <a href="<?= BASE_URL ?>/account/addnewadd" style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 20px; background: var(--teal-light); color: var(--teal-600); border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.3s ease; border: 2px solid transparent;">
                    <i class="fas fa-plus"></i>
                    <span>Add New Address</span>
                </a>
            </div>

            <div class="checkout-section">
                <h2>Payment Method</h2>
                <div class="form-group">
                    <label>Select Payment Method</label>
                    <select name="payment_method_id" style="margin-bottom: 12px;">
                        <option value="">Cash on Delivery</option>
                        <?php if (!empty($paymentMethods)): ?>
                            <?php foreach ($paymentMethods as $pm): ?>
                                <option value="<?= $pm['payment_method_id'] ?>">
                                    <?= htmlspecialchars($pm['card_type']) ?> •••• <?= htmlspecialchars($pm['last_four_digits']) ?>
                                    <?= $pm['is_default'] ? ' (Default)' : '' ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <a href="<?= BASE_URL ?>/account/addanothercard" style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 20px; background: var(--teal-light); color: var(--teal-600); border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.3s ease; border: 2px solid transparent;">
                        <i class="fas fa-plus"></i>
                        <span>Add New Card</span>
                    </a>
                </div>
            </div>

            <div class="checkout-section">
                <h2>Promo Code</h2>
                <div class="form-group">
                    <label>Enter Promo Code (Optional)</label>
                    <div style="display: flex; gap: 8px;">
                        <input type="text" name="promo_code" id="promo_code" placeholder="Enter promo code" style="flex: 1;">
                        <button type="button" id="applyPromoBtn" style="padding: 12px 24px; background: var(--teal-600); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">Apply</button>
                    </div>
                    <div id="promoMessage" style="margin-top: 8px; font-size: 14px;"></div>
                </div>
            </div>
        </div>

        <div class="order-summary">
            <h2>Order Summary</h2>
            <?php foreach ($cart['items'] as $item): ?>
                <div style="margin-bottom: 15px;">
                    <div><?= htmlspecialchars($item['name']) ?> x <?= $item['quantity'] ?></div>
                    <div>EGP <?= number_format($item['line_total'], 2) ?></div>
                </div>
            <?php endforeach; ?>
            
            <div class="summary-row">
                <span>Subtotal:</span>
                <span>EGP <?= number_format($cart['subtotal'], 2) ?></span>
            </div>
            <div class="summary-row">
                <span>Shipping:</span>
                <span>EGP <?= number_format($cart['shipping'], 2) ?></span>
            </div>
            <div class="summary-row">
                <span>Service Fee:</span>
                <span>EGP <?= number_format($cart['service_fee'], 2) ?></span>
            </div>
            <div class="summary-row" id="discountRow" style="display: none;">
                <span style="color: var(--success);">Discount:</span>
                <span style="color: var(--success);">-EGP <span id="discountAmount">0.00</span></span>
            </div>
            <input type="hidden" name="promo_code_id" id="promo_code_id" value="">
            <input type="hidden" name="discount_amount" id="discount_amount" value="0">
            <div class="summary-row" style="font-size: 20px; font-weight: 700; border-top: 2px solid var(--divider); padding-top: 15px; margin-top: 15px;">
                <span>Total:</span>
                <span>EGP <span id="totalAmount"><?= number_format($cart['total'], 2) ?></span></span>
            </div>
            
            <button type="submit" class="checkout-btn">Place Order</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const promoCodeInput = document.getElementById('promo_code');
    const applyPromoBtn = document.getElementById('applyPromoBtn');
    const promoMessage = document.getElementById('promoMessage');
    const discountRow = document.getElementById('discountRow');
    const discountAmount = document.getElementById('discountAmount');
    const totalAmount = document.getElementById('totalAmount');
    const promoCodeIdInput = document.getElementById('promo_code_id');
    const discountAmountInput = document.getElementById('discount_amount');
    
    const originalTotal = <?= $cart['total'] ?>;
    let appliedDiscount = 0;
    let appliedPromoCodeId = '';

    applyPromoBtn.addEventListener('click', function() {
        const code = promoCodeInput.value.trim().toUpperCase();
        
        if (!code) {
            promoMessage.textContent = 'Please enter a promo code';
            promoMessage.style.color = 'var(--danger)';
            return;
        }

        // Validate promo code via AJAX
        fetch('<?= BASE_URL ?>/validate_promo', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `promo_code=${encodeURIComponent(code)}&order_amount=<?= $cart['subtotal'] + $cart['shipping'] + $cart['service_fee'] ?>`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.valid) {
                appliedDiscount = parseFloat(data.discount_amount);
                appliedPromoCodeId = data.promo_code_id;
                
                promoMessage.textContent = 'Promo code applied successfully!';
                promoMessage.style.color = 'var(--success)';
                
                discountRow.style.display = 'flex';
                discountAmount.textContent = appliedDiscount.toFixed(2);
                discountAmountInput.value = appliedDiscount;
                promoCodeIdInput.value = appliedPromoCodeId;
                
                const newTotal = originalTotal - appliedDiscount;
                totalAmount.textContent = newTotal.toFixed(2);
            } else {
                appliedDiscount = 0;
                appliedPromoCodeId = '';
                
                promoMessage.textContent = data.message || 'Invalid promo code';
                promoMessage.style.color = 'var(--danger)';
                
                discountRow.style.display = 'none';
                discountAmountInput.value = 0;
                promoCodeIdInput.value = '';
                totalAmount.textContent = originalTotal.toFixed(2);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            promoMessage.textContent = 'Error validating promo code';
            promoMessage.style.color = 'var(--danger)';
        });
    });
});
</script>

<!-- Body and HTML closing tags handled by Controller -->

</body>
</html>
