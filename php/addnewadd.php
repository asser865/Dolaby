<?php
require_once __DIR__ . '/../src/includes.php';
require_once __DIR__ . '/../src/header_functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . '/login');
    exit;
}

$error = '';
$formData = [
    'address_type' => $_POST['address_type'] ?? 'Home',
    'area' => $_POST['area'] ?? '',
    'building_name' => $_POST['building_name'] ?? '',
    'apartment_number' => $_POST['apartment_number'] ?? '',
    'floor' => $_POST['floor'] ?? '',
    'street' => $_POST['street'] ?? '',
    'phone' => $_POST['phone'] ?? '',
    'address_label' => $_POST['address_label'] ?? '',
    'is_default' => isset($_POST['is_default']) ? 1 : 0
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $allowedTypes = ['Home', 'Work'];
    if (!in_array($formData['address_type'], $allowedTypes, true)) {
        $error = 'Invalid address type.';
    }

    if (empty($error) && !preg_match('/^[A-Za-z ]{2,60}$/', trim($formData['area']))) {
        $error = 'Area must be letters and spaces only (2-60 characters).';
    }

    if (empty($error) && !preg_match('/^[A-Za-z ]{2,80}$/', trim($formData['building_name']))) {
        $error = 'Building name must be letters and spaces only (2-80 characters).';
    }

    if (empty($error) && !preg_match('/^[A-Za-z0-9 -]{1,20}$/', trim($formData['apartment_number']))) {
        $error = 'Apartment number must be 1-20 characters (letters, numbers, dash).';
    }

    if (empty($error) && !empty($formData['floor']) && !preg_match('/^[0-9]{1,10}$/', trim($formData['floor']))) {
        $error = 'Floor must be numbers only.';
    }

    if (empty($error) && !empty($formData['street']) && !preg_match('/^[A-Za-z0-9 ,.-]{2,80}$/', trim($formData['street']))) {
        $error = 'Street can include letters, numbers, commas, periods, and dashes.';
    }

    if (empty($error) && !preg_match('/^[0-9]{10,15}$/', preg_replace('/\D/', '', $formData['phone']))) {
        $error = 'Phone must be 10-15 digits.';
    }

    if (empty($error) && !empty($formData['address_label']) && !preg_match('/^[A-Za-z0-9 ]{2,40}$/', trim($formData['address_label']))) {
        $error = 'Address label must be 2-40 characters (letters, numbers, spaces).';
    }

    if (empty($error)) {
        $addressRepository = new AddressRepository();
        $addressRepository->create([
            'user_id' => $_SESSION['user_id'],
            'address_type' => $formData['address_type'],
            'area' => trim($formData['area']),
            'building_name' => trim($formData['building_name']),
            'apartment_number' => trim($formData['apartment_number']),
            'floor' => trim($formData['floor']),
            'street' => trim($formData['street']),
            'phone' => preg_replace('/\D/', '', $formData['phone']),
            'address_label' => trim($formData['address_label']),
            'is_default' => $formData['is_default'] ? 1 : 0
        ]);
        $_SESSION['success'] = 'Address added successfully';
        header('Location: ' . BASE_URL . '/delivery');
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

  .add-address-page {
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

  .type-toggle {
    display: flex;
    gap: 12px;
    margin-bottom: 24px;
  }

  .type-btn {
    flex: 1;
    padding: 14px;
    border: 2px solid var(--divider);
    border-radius: 12px;
    background: var(--white);
    cursor: pointer;
    font-size: 16px;
    font-weight: 600;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
  }

  .type-btn.active {
    border-color: var(--teal-600);
    background: var(--teal-light);
    color: var(--teal-600);
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
  }
</style>

<div class="add-address-page">
  <!-- TOPBAR -->
  <div class="topbar">
    <div class="container">
      <div class="top-left">
        <a class="back" href="<?= BASE_URL ?>/account/delivery" aria-label="Back">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M15 18L9 12l6-6" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </a>
        <div class="title">Add New Address</div>
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
  <form method="POST" action="<?= BASE_URL ?>/account/addnewadd">
    <div class="type-toggle">
      <button type="button" class="type-btn <?= $formData['address_type'] === 'Home' ? 'active' : '' ?>" data-type="Home">
        <i class="fas fa-house"></i> Home
      </button>
      <button type="button" class="type-btn <?= $formData['address_type'] === 'Work' ? 'active' : '' ?>" data-type="Work">
        <i class="fas fa-briefcase"></i> Work
      </button>
    </div>
    <input type="hidden" name="address_type" id="address_type" value="<?= htmlspecialchars($formData['address_type']) ?>" required>

    <div class="form-group">
      <label for="area">Area *</label>
      <input type="text" id="area" name="area" required placeholder="Enter area"
             pattern="[A-Za-z ]{2,60}" title="Letters and spaces only (2-60 characters)"
             value="<?= htmlspecialchars($formData['area']) ?>">
    </div>

    <div class="form-group">
      <label for="building_name">Building Name *</label>
      <input type="text" id="building_name" name="building_name" required placeholder="Enter building name"
             pattern="[A-Za-z ]{2,80}" title="Letters and spaces only (2-80 characters)"
             value="<?= htmlspecialchars($formData['building_name']) ?>">
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
      <div class="form-group">
        <label for="apartment_number">Apartment Number *</label>
        <input type="text" id="apartment_number" name="apartment_number" required placeholder="Apt. No."
               pattern="[A-Za-z0-9 -]{1,20}" title="Letters, numbers, dash (max 20)"
               value="<?= htmlspecialchars($formData['apartment_number']) ?>">
      </div>
      <div class="form-group">
        <label for="floor">Floor (Optional)</label>
        <input type="text" id="floor" name="floor" placeholder="Floor"
               pattern="[0-9]{0,10}" title="Numbers only"
               value="<?= htmlspecialchars($formData['floor']) ?>">
      </div>
    </div>

    <div class="form-group">
      <label for="street">Street</label>
      <input type="text" id="street" name="street" placeholder="Enter street"
             pattern="[A-Za-z0-9 ,.-]{2,80}" title="Letters, numbers, comma, period, dash"
             value="<?= htmlspecialchars($formData['street']) ?>">
    </div>

    <div class="form-group">
      <label for="phone">Phone Number *</label>
      <input type="tel" id="phone" name="phone" required placeholder="Enter phone number"
             pattern="[0-9]{10,15}" title="10-15 digits"
             value="<?= htmlspecialchars($formData['phone']) ?>">
    </div>

    <div class="form-group">
      <label for="address_label">Address Label (Optional)</label>
      <input type="text" id="address_label" name="address_label" placeholder="e.g., My Home"
             pattern="[A-Za-z0-9 ]{0,40}" title="Letters, numbers, spaces (max 40)"
             value="<?= htmlspecialchars($formData['address_label']) ?>">
    </div>

    <div class="form-group">
      <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
        <input type="checkbox" name="is_default" value="1" style="width: auto;" <?= $formData['is_default'] ? 'checked' : '' ?>>
        <span>Set as default address</span>
      </label>
    </div>

    <button type="submit" class="submit-btn">Save Address</button>
  </form>
</div>

<script>
document.querySelectorAll('.type-btn').forEach(btn => {
  btn.addEventListener('click', function() {
    document.querySelectorAll('.type-btn').forEach(b => b.classList.remove('active'));
    this.classList.add('active');
    document.getElementById('address_type').value = this.dataset.type;
  });
});

// Enforce character rules on key fields
const alphaFields = ['area'];
alphaFields.forEach(id => {
  const el = document.getElementById(id);
  el && el.addEventListener('input', (e) => {
    e.target.value = e.target.value.replace(/[^A-Za-z ]/g, '');
  });
});

// Building name - letters and spaces only
const buildingNameField = document.getElementById('building_name');
buildingNameField && buildingNameField.addEventListener('input', (e) => {
  e.target.value = e.target.value.replace(/[^A-Za-z ]/g, '');
});

// Floor - numbers only
const floorField = document.getElementById('floor');
floorField && floorField.addEventListener('input', (e) => {
  e.target.value = e.target.value.replace(/[^0-9]/g, '');
});

const alphaNumFields = ['apartment_number', 'address_label'];
alphaNumFields.forEach(id => {
  const el = document.getElementById(id);
  el && el.addEventListener('input', (e) => {
    e.target.value = e.target.value.replace(/[^A-Za-z0-9 \-]/g, '');
  });
});

const streetField = document.getElementById('street');
streetField && streetField.addEventListener('input', (e) => {
  e.target.value = e.target.value.replace(/[^A-Za-z0-9 ,.\-]/g, '');
});

const phoneField = document.getElementById('phone');
phoneField && phoneField.addEventListener('input', (e) => {
  e.target.value = e.target.value.replace(/[^0-9]/g, '').slice(0, 15);
});
</script>

</body>
</html>
