<?php
require_once __DIR__ . '/../includes.php';
require_once __DIR__ . '/../header_functions.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = 'Your session has expired. Please log in again.';
    header('Location: ' . BASE_URL . '/login');
    exit;
}
$cartService = new CartService();
$cart = $cartService->getCart($_SESSION['user_id']);

renderHeader();
?>

<style>
    .cart-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .cart-items {
        background: var(--white);
        border-radius: var(--card-radius);
        padding: 30px;
        margin-bottom: 30px;
    }

    .cart-item {
        display: flex;
        gap: 20px;
        padding: 20px;
        border-bottom: 1px solid var(--divider);
    }

    .cart-item:last-child {
        border-bottom: none;
    }

    .cart-item-image-wrapper {
        width: 170px;
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border-radius: 12px;
        background: #f5f5f5;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .cart-item-image-wrapper img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        object-position: center;
    }

    .cart-item-info {
        flex: 1;
    }

    .cart-item-name {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .cart-item-price {
        font-size: 20px;
        font-weight: 700;
        color: var(--teal-600);
        margin-top: 10px;
    }

    .cart-summary {
        background: var(--white);
        border-radius: var(--card-radius);
        padding: 30px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
    }

    .summary-total {
        font-size: 24px;
        font-weight: 700;
        border-top: 2px solid var(--divider);
        padding-top: 15px;
        margin-top: 15px;
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

<div class="cart-container">
    <h1>Shopping Cart</h1>

    <?php if (isset($_SESSION['success'])): ?>
        <div style="background: rgba(34, 197, 94, 0.1); color: var(--success); padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <?= htmlspecialchars($_SESSION['success']) ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div style="background: rgba(220, 38, 38, 0.1); color: var(--danger); padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <?= htmlspecialchars($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (empty($cart['items'])): ?>
        <div class="cart-items">
            <p>Your cart is empty.</p>
            <a href="<?= BASE_URL ?>/shop">Continue Shopping</a>
        </div>
    <?php else: ?>
        <div class="cart-items">
            <?php foreach ($cart['items'] as $item): ?>
                <div class="cart-item">
                    <div class="cart-item-image-wrapper">
                        <img src="<?= BASE_URL ?><?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" onerror="this.src='<?= BASE_URL ?>/assets/images/shop/Harbaa.png'">
                    </div>
                    <div class="cart-item-info">
                        <div class="cart-item-name"><?= htmlspecialchars($item['name']) ?></div>
                        <div>Brand: <?= htmlspecialchars($item['brand']) ?></div>
                        <div>Size: <?= htmlspecialchars($item['size']) ?></div>
                        <div class="cart-item-price">EGP <?= number_format($item['line_total'], 2) ?></div>
                        
                        <div style="display: flex; align-items: center; gap: 12px; margin-top: 16px; flex-wrap: wrap;">
                            <form method="POST" action="<?= BASE_URL ?>/cart/update" style="display: flex; align-items: center; gap: 8px;">
                                <input type="hidden" name="cart_item_id" value="<?= $item['cart_item_id'] ?>">
                                <label style="font-size: 14px; font-weight: 600; color: var(--dark);">Quantity:</label>
                                <input type="number" 
                                       name="quantity" 
                                       value="<?= $item['quantity'] ?>" 
                                       min="1" 
                                       style="width: 70px; padding: 10px 12px; border: 2px solid var(--divider); border-radius: 8px; font-size: 15px; font-weight: 600; text-align: center; transition: all 0.3s ease;"
                                       onfocus="this.style.borderColor='var(--teal-600)'; this.style.boxShadow='0 0 0 3px rgba(0, 184, 212, 0.1)';"
                                       onblur="this.style.borderColor='var(--divider)'; this.style.boxShadow='none';">
                                <button type="submit" 
                                        style="padding: 10px 20px; background: var(--teal-600); color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;"
                                        onmouseover="this.style.background='#00aebc'; this.style.transform='translateY(-2px)';"
                                        onmouseout="this.style.background='var(--teal-600)'; this.style.transform='translateY(0)';">
                                    <i class="fas fa-sync-alt"></i> Update
                                </button>
                            </form>
                            
                            <form method="POST" action="<?= BASE_URL ?>/cart/remove" style="display: inline;">
                                <input type="hidden" name="cart_item_id" value="<?= $item['cart_item_id'] ?>">
                                <button type="submit" 
                                        style="padding: 10px 20px; background: #dc2626; color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;"
                                        onmouseover="this.style.background='#b91c1c'; this.style.transform='translateY(-2px)';"
                                        onmouseout="this.style.background='#dc2626'; this.style.transform='translateY(0)';"
                                        onclick="return confirm('Are you sure you want to remove this item from your cart?');">
                                    <i class="fas fa-trash-alt"></i> Remove
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="cart-summary">
            <h2>Order Summary</h2>
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
            <div class="summary-row summary-total">
                <span>Total:</span>
                <span>EGP <?= number_format($cart['total'], 2) ?></span>
            </div>
            <a href="<?= BASE_URL ?>/checkout">
                <button class="checkout-btn">Proceed to Checkout</button>
            </a>
        </div>
    <?php endif; ?>
</div>

<!-- Body and HTML closing tags handled by Controller -->

</body>
</html>
