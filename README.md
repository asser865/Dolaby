# Dolaby (دولابي) — Local Fashion E-Commerce Marketplace & QA Suite

[![PHP](https://img.shields.io/badge/PHP-8.0%2B-777BB4?logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0%2B-4479A1?logo=mysql&logoColor=white)](https://www.mysql.com/)
[![PHPUnit](https://img.shields.io/badge/PHPUnit-9.6%20(85%20Passed)-3C873A?logo=php&logoColor=white)](https://phpunit.de/)
[![Postman](https://img.shields.io/badge/Postman-API%20Tests-FF6C37?logo=postman&logoColor=white)](https://www.postman.com/)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)

**Dolaby** is a specialized multi-vendor e-commerce web platform engineered to consolidate local Egyptian fashion labels into a unified digital marketplace. The platform delivers an end-to-end shopping experience for consumers alongside an analytics and promotional campaign management suite for brand owners.

This repository is structured following software engineering best practices with a **strict separation of concerns**:
* 🌐 **`website/`** — The complete, self-contained, working web application (Frontend, Backend, Database migrations, and APIs).
* 🧪 **`tests/`** — The dedicated testing and Quality Assurance suite (Automated PHPUnit Unit Tests, Postman API Tests, and Gherkin/SPIDR Test Specifications).

---

## 📁 Repository Architecture

```text
Dolaby/
├── website/                                 # 🌐 WORKING WEB APPLICATION
│   ├── api/                                 # REST API endpoints (Brand Owner, Loyalty, Reviews)
│   ├── assets/                              # Static UI assets (Stylesheets, JavaScript, Product Images)
│   ├── php/                                 # Web controllers & views (Catalog, Cart, Checkout, Dashboard)
│   ├── sql/                                 # Relational database setup (schema.sql & seed.sql)
│   ├── config.php                           # Database connection & runtime configuration
│   ├── config.example.php                   # Environment configuration template
│   ├── header_functions.php                 # Global navigation & authentication headers
│   ├── includes.php                         # Core domain logic, repositories, and DB abstraction
│   ├── index.php                            # Primary routing and application entry point
│   └── .htaccess                            # Apache URL rewriting & security headers
│
├── tests/                                   # 🧪 TESTING & QUALITY ASSURANCE SUITE
│   ├── unit/                                # Automated PHPUnit Unit Tests (85 Tests, 105 Assertions)
│   │   ├── AnalyticsServiceTest.php         # Sales KPI, demographic breakdown, returning customer tests
│   │   ├── LoyaltyServiceTest.php           # Loyalty point math, tier progression, redemption tests
│   │   ├── OrderServiceTest.php             # Order total calculation, rating eligibility status tests
│   │   ├── PaymentValidationTest.php        # Luhn algorithm checksum, card expiry, CVV verification
│   │   ├── PromoCodeRepositoryTest.php      # Percent, fixed, and capped promotional discount tests
│   │   ├── ReviewServiceTest.php            # Rating range, ownership, and review submission tests
│   │   └── bootstrap.php                    # Test runner bootstrapper with SQLite in-memory mock fallback
│   ├── api/                                 # Automated API Integration Testing
│   │   └── Dolaby_promo_flashsale.postman_collection.json  # Postman API test collection
│   └── specifications/                      # Quality Assurance Specifications & Test Matrix
│       ├── Test_Cases_and_Acceptance_Criteria.md  # 10 User Stories, Gherkin ACs & Test Steps
│       └── Dolaby_Test_Cases_Matrix.xlsx         # Agile QA Test Cases & Acceptance Matrix
│
├── composer.json                            # Dependency management & test scripts
├── composer.lock                            # Locked dependencies
├── phpunit.xml                              # PHPUnit test configuration
├── .gitignore                               # Clean git ignore definitions
└── README.md                                # Project documentation
```

---

## 🌐 Running the Website Alone

The entire web application lives inside [`website/`](./website/) and is completely decoupled from testing dependencies.

### Prerequisites
* PHP 8.0 or higher (with `pdo_mysql` extension)
* MySQL / MariaDB Server (e.g. XAMPP, WampServer, or standalone)
* Apache web server (or PHP CLI built-in server)

### 1. Database Setup
Create the MySQL database and import the schema and sample catalog:
```sql
CREATE DATABASE dolaby_v2 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```
Import [`website/sql/schema.sql`](./website/sql/schema.sql) followed by [`website/sql/seed.sql`](./website/sql/seed.sql):
```bash
mysql -u root -p dolaby_v2 < website/sql/schema.sql
mysql -u root -p dolaby_v2 < website/sql/seed.sql
```

### 2. Configure Environment
Copy the example config and verify your database credentials:
```bash
cp website/config.example.php website/config.php
```
Edit `website/config.php` to set your local database host, port, user, and password:
```php
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');
define('DB_NAME', 'dolaby_v2');
define('DB_USER', 'root');
define('DB_PASS', 'your_password');
define('BASE_URL', 'http://localhost:8000');
```

### 3. Launch Web Server
Using PHP's built-in development server pointing directly at the `website/` directory:
```bash
php -S localhost:8000 -t website
```
Open **[http://localhost:8000](http://localhost:8000)** in your browser to access the live store!

*For detailed Apache/XAMPP virtual host setup, consult [`website/README.md`](./website/README.md).*

---

## 🧪 Running the Test Suite Alone

The testing infrastructure lives inside [`tests/`](./tests/) and features automated unit tests, API collections, and formal QA documentation.

### 1. Automated PHPUnit Unit Tests
The unit test suite includes an automated SQLite in-memory database mock, allowing **all 85 tests to run immediately in under 50ms without needing a live MySQL server running!**

Install dev dependencies:
```bash
composer install
```
Execute the automated test suite:
```bash
composer test
# Or directly via PHPUnit:
./vendor/bin/phpunit
```

#### Test Execution Summary:
```text
PHPUnit 9.6.34 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.0.30
Configuration: phpunit.xml

................................................................. 65 / 85 ( 76%)
....................                                              85 / 85 (100%)

Time: 00:00.028, Memory: 6.00 MB

OK (85 tests, 105 assertions)
```

### 2. Automated API Testing (Postman)
Import the Postman collection located at:
* [`tests/api/Dolaby_promo_flashsale.postman_collection.json`](./tests/api/Dolaby_promo_flashsale.postman_collection.json)

Tests cover promotional campaign validation, price overrides, flash-sale discount application, and error response contracts.

### 3. Manual Test Cases & Acceptance Criteria
Formal QA documentation and test matrices are available under [`tests/specifications/`](./tests/specifications/):
* 📄 [`Test_Cases_and_Acceptance_Criteria.md`](./tests/specifications/Test_Cases_and_Acceptance_Criteria.md) — 10 Epics/User Stories decomposed via **SPIDR technique**, Gherkin-formatted acceptance criteria, and positive/negative test execution steps.
* 📊 [`Dolaby_Test_Cases_Matrix.xlsx`](./tests/specifications/Dolaby_Test_Cases_Matrix.xlsx) — Full Agile Quality Assurance test matrix.

---

## 👥 Contributors & Authors
* **Asser Essam** — Software Engineering & QA Test Architecture ([LinkedIn](https://linkedin.com/in/asser-essam-3886a3285) | [GitHub](https://github.com/asser865))
* **Ahmed Yasser**
* **Seif Essam**
* **Youssef Tarek**
* **Ahmed Karim**
