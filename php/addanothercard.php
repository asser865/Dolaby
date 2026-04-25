<?php
require_once __DIR__ . '/../includes.php';
require_once __DIR__ . '/../header_functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . '/login');
    exit;
}

$error = '';
$formData = [
    'card_type' => $_POST['card_type'] ?? 'VISA',
    'card_number' => $_POST['card_number'] ?? '',
    'cardholder_name' => $_POST['cardholder_name'] ?? '',
    'expiry_month' => $_POST['expiry_month'] ?? '',
    'expiry_year' => $_POST['expiry_year'] ?? '',
    'is_default' => isset($_POST['is_default']) ? 1 : 0,
];

function luhnValid(string $number): bool {
    $sum = 0;
    $alt = false;
    for ($i = strlen($number) - 1; $i >= 0; $i--) {
        $n = intval($number[$i]);
        if ($alt) {
            $n *= 2;
            if ($n > 9) {
                $n -= 9;
            }
        }
        $sum += $n;
        $alt = !$alt;
    }
    return $sum % 10 === 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $allowedTypes = ['VISA', 'MasterCard', 'American Express'];
    if (!in_array($formData['card_type'], $allowedTypes, true)) {
        $error = 'Please select a valid card type.';
    }

    $digitsOnly = preg_replace('/\D/', '', $formData['card_number']);
    if (empty($error) && (strlen($digitsOnly) < 13 || strlen($digitsOnly) > 19)) {
        $error = 'Card number must be 13-19 digits.';
    }
    if (empty($error) && !luhnValid($digitsOnly)) {
        $error = 'Card number is invalid.';
    }

    if (empty($error) && !preg_match('/^[A-Za-z ]{2,50}$/', trim($formData['cardholder_name']))) {
        $error = 'Cardholder name must be letters and spaces only (2-50 characters).';
    }

    $month = (int)$formData['expiry_month'];
    $year = (int)$formData['expiry_year'];
    if (empty($error) && ($month < 1 || $month > 12 || $year < (int)date('Y'))) {
        $error = 'Please select a valid expiry month and year.';
    }
    if (empty($error)) {
        $expiry = DateTime::createFromFormat('Y-m', sprintf('%04d-%02d', $year, $month));
        $expiry->modify('last day of this month 23:59:59');
        if ($expiry < new DateTime()) {
            $error = 'Card is expired.';
        }
    }

    if (empty($error)) {
        $data = [
            'user_id' => $_SESSION['user_id'],
            'card_type' => $formData['card_type'],
            'last_four_digits' => substr($digitsOnly, -4),
            'cardholder_name' => trim($formData['cardholder_name']),
            'expiry_month' => $month,
            'expiry_year' => $year,
            'is_default' => $formData['is_default'] ? 1 : 0
        ];

        $paymentMethodRepository = new PaymentMethodRepository();
        $paymentMethodRepository->create($data);
        $_SESSION['success'] = 'Payment method added successfully';
        header('Location: ' . BASE_URL . '/paymentmethod');
        exit;
    } else {
        $_SESSION['error'] = $error;
    }
}

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

  .add-card-page {
    max-width: 600px;
    margin: 0 auto;
    padding: 40px 20px;
  }

  .topbar {
    width: 100%;
    padding: 18px 0;
    border-bottom: 1px solid var(--divider);
    margin-bottom: 30px;
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
    background: var(--teal-light);
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

.alert {
  padding: 12px 14px;
  border-radius: 10px;
  margin-bottom: 16px;
  font-weight: 600;
}

.alert.error {
  background: rgba(220, 38, 38, 0.1);
  color: #b91c1c;
  border: 1px solid rgba(220, 38, 38, 0.3);
}

.alert.success {
  background: rgba(22, 163, 74, 0.1);
  color: #15803d;
  border: 1px solid rgba(22, 163, 74, 0.3);
}

  .form-group {
    margin-bottom: 20px;
  }

  .form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: var(--dark);
  }

  .form-group input,
  .form-group select {
    width: 100%;
    padding: 14px;
    border: 1px solid var(--divider);
    border-radius: 12px;
    font-size: 16px;
    transition: all 0.3s ease;
  }

  .form-group input:focus,
  .form-group select:focus {
    outline: none;
    border-color: var(--teal-600);
    box-shadow: 0 0 0 3px rgba(0, 184, 212, 0.1);
  }

  .form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
  }

  .submit-btn {
    width: 100%;
    padding: 16px;
    background: var(--teal-600);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 18px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 20px;
  }

  .submit-btn:hover {
    background: #00aebc;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 184, 212, 0.3);
  }

  @media (max-width: 640px) {
    .topbar .container {
      padding: 0 20px;
    }
    .title {
      font-size: 20px;
    }
    .form-row {
      grid-template-columns: 1fr;
    }
  }
</style>

<div class="add-card-page">
  <!-- TOPBAR -->
  <div class="topbar">
    <div class="container">
      <div class="top-left">
        <a class="back" href="<?= BASE_URL ?>/account/paymentmethod" aria-label="Back">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M15 18L9 12l6-6" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </a>
        <div class="title">Add New Card</div>
      </div>
    </div>
  </div>

  <?php if (!empty($_SESSION['error'])): ?>
    <div class="alert error"><?= htmlspecialchars($_SESSION['error']) ?></div>
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>

  <?php if (!empty($_SESSION['success'])): ?>
    <div class="alert success"><?= htmlspecialchars($_SESSION['success']) ?></div>
    <?php unset($_SESSION['success']); ?>
  <?php endif; ?>

  <!-- FORM -->
  <form method="POST" action="<?= BASE_URL ?>/account/addanothercard">
    <div class="form-group">
      <label for="card_type">Card Type *</label>
      <select id="card_type" name="card_type" required>
        <option value="VISA" <?= $formData['card_type'] === 'VISA' ? 'selected' : '' ?>>VISA</option>
        <option value="MasterCard" <?= $formData['card_type'] === 'MasterCard' ? 'selected' : '' ?>>MasterCard</option>
        <option value="American Express" <?= $formData['card_type'] === 'American Express' ? 'selected' : '' ?>>American Express</option>
      </select>
    </div>

    <div class="form-group">
      <label for="card_number">Card Number *</label>
      <input type="text" id="card_number" name="card_number" required 
             placeholder="1234 5678 9012 3456" 
             maxlength="19"
             pattern="[0-9\s]{13,19}"
             inputmode="numeric"
             value="<?= htmlspecialchars($formData['card_number']) ?>">
    </div>

    <div class="form-row">
      <div class="form-group">
        <label for="expiry_month">Expiry Month *</label>
        <select id="expiry_month" name="expiry_month" required>
          <option value="">Month</option>
          <?php for ($i = 1; $i <= 12; $i++): ?>
            <option value="<?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>" <?= $formData['expiry_month'] === str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' ?>>
              <?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>
            </option>
          <?php endfor; ?>
        </select>
      </div>
      <div class="form-group">
        <label for="expiry_year">Expiry Year *</label>
        <select id="expiry_year" name="expiry_year" required>
          <option value="">Year</option>
          <?php for ($i = date('Y'); $i <= date('Y') + 10; $i++): ?>
            <option value="<?= $i ?>" <?= (string)$formData['expiry_year'] === (string)$i ? 'selected' : '' ?>><?= $i ?></option>
          <?php endfor; ?>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label for="cardholder_name">Cardholder Name *</label>
      <input type="text" id="cardholder_name" name="cardholder_name" required 
             placeholder="John Doe"
             pattern="[A-Za-z ]{2,50}"
             title="Letters and spaces only"
             value="<?= htmlspecialchars($formData['cardholder_name']) ?>">
    </div>

    <div class="form-group">
      <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
        <input type="checkbox" name="is_default" value="1" style="width: auto;" <?= $formData['is_default'] ? 'checked' : '' ?>>
        <span>Set as default payment method</span>
      </label>
    </div>

    <button type="submit" class="submit-btn">Add Card</button>
  </form>
</div>

<script>
// Format card number with spaces
document.getElementById('card_number').addEventListener('input', function(e) {
  let value = e.target.value.replace(/\s/g, '');
  let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
  e.target.value = formattedValue;
});

// Prevent non-letter characters in cardholder name
document.getElementById('cardholder_name').addEventListener('input', function(e) {
  const cleaned = e.target.value.replace(/[^A-Za-z ]/g, '');
  if (cleaned !== e.target.value) {
    e.target.value = cleaned;
  }
});
</script>

</body>
</html>
