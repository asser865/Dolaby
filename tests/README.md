# Dolaby Quality Assurance & Test Suite

This folder contains the comprehensive QA suite, automated test suites, and test case documentation for **Dolaby**.

## Structure

* **`unit/`** — Automated unit tests written with PHPUnit 9.6.
  * `AnalyticsServiceTest.php`: Tests merchant KPI aggregations and customer retention formulas.
  * `LoyaltyServiceTest.php`: Tests point accumulation equations and tier upgrade thresholds.
  * `OrderServiceTest.php`: Tests subtotal/fee arithmetic and status-gated review eligibility.
  * `PaymentValidationTest.php`: Tests Luhn algorithm card validation, expiry dates, and CVVs.
  * `PromoCodeRepositoryTest.php`: Tests promotional discounts (fixed, percentage, and capped).
  * `ReviewServiceTest.php`: Tests star rating boundary checks and ownership verification.
  * `bootstrap.php`: Test bootstrapper with SQLite in-memory test database.

* **`api/`** — Automated API integration test collections:
  * `Dolaby_promo_flashsale.postman_collection.json`: Comprehensive Postman test collection for discount and promotional endpoints.

* **`specifications/`** — Formal QA documentation:
  * `Test_Cases_and_Acceptance_Criteria.md`: Complete specification detailing Personas, Epics, User Stories, SPIDR decomposition, Gherkin Acceptance Criteria, and Test Steps.
  * `Dolaby_Test_Cases_Matrix.xlsx`: Comprehensive spreadsheet test matrix.

## Running Unit Tests
From the root directory of the repository:
```bash
composer test
# Or:
./vendor/bin/phpunit
```
