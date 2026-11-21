# Dolaby — Software Quality Assurance & Test Specification

This document details the end-to-end Quality Assurance (QA) specification for **Dolaby**, including Personas, Epics, User Stories, SPIDR Splitting Technique, Gherkin Acceptance Criteria, and detailed Test Cases.

---

## 👥 Personas & Target Actors

### 1. Sarah Ahmed — The Shopper / Customer
* **Demographics:** Age 21, University Student, Cairo.
* **Goals:** Discover trendy Egyptian local clothing brands in a unified mobile marketplace; track orders reliably; rate delivered purchases; earn loyalty reward points.
* **Pain Points:** Fragmented Instagram stores, lack of order status visibility, fear of poor quality, scattered checkouts.

### 2. Karim El-Sayed — The Brand Owner / Merchant
* **Demographics:** Age 34, Founder of a local apparel brand, Alexandria.
* **Goals:** Expand brand reach without building a custom tech stack; view real-time sales and demographic analytics; launch seasonal discount promotions and flash sales.
* **Pain Points:** Lack of customer demographic data, complex inventory management, difficulty measuring marketing ROI.

### 3. Mona Abdallah — The Support Representative / Admin
* **Demographics:** Age 29, Customer Support Operations, Giza.
* **Goals:** Resolve customer inquiries rapidly; reduce repetitive tickets via structured FAQs; maintain high satisfaction ratings.
* **Pain Points:** Repetitive questions on refund/exchange policies, missing customer order details on support tickets.

---

## 📌 Epics & User Stories (SPIDR Splitting)

The system requirements are broken down using the **SPIDR technique** (Spike, Path, Interface, Data, Rule):

### Epic 1: Customer Order Management & Loyalty Engagement
* **US 1.1 [Data]:** As a customer, I want to view my past orders history with date, order ID, and amount.
* **US 1.2 [Data]:** As a customer, I want to view detailed line items and pricing for any selected order.
* **US 1.3 [Path]:** As a customer, I want to track active order delivery statuses (*Processing, Shipped, Delivered*).
* **US 1.4 [Interface]:** As a customer, I want to view clear visual timeline badges representing order progression.
* **US 1.5 [Rule]:** As a customer, I can only rate and review products after order status is confirmed as *Delivered*.
* **US 1.6 [Rule]:** As a customer, I earn loyalty points on eligible completed orders (1 point per 10 EGP spent).
* **US 1.7 [Data]:** As a customer, I want to view my loyalty tier balance and redeem points for checkout discounts.

### Epic 2: Account Personalization & Security Management
* **US 2.1 [Data]:** As a registered user, I want to update my name, phone number, and delivery address.
* **US 2.2 [Rule]:** As a registered user, I want to update my password requiring current credential verification.
* **US 2.3 [Interface]:** As a user, I want to toggle language preferences and notification settings.

### Epic 3: Customer Support & Knowledge Base
* **US 3.1 [Data]:** As a user, I want to browse FAQ topics categorized by Returns, Shipping, and Payments.
* **US 3.2 [Path]:** As a user, I want to search FAQs by keywords with live query filtering.
* **US 3.3 [Interface]:** As a user, I want to submit inquiries via a structured Contact Us form with input validation.

### Epic 4: Brand Owner Analytics & Campaign Engine
* **US 4.1 [Rule]:** As a brand owner, I want to schedule discount campaigns with start/end date validation.
* **US 4.2 [Data]:** As a brand owner, I want to configure promotional percentages and maximum discount caps.
* **US 4.3 [Data]:** As a brand owner, I want to visualize sales revenue, customer retention rates, and category demographics.

---

## 📋 Acceptance Criteria (Gherkin Syntax)

### US1: Order History & Details
```gherkin
AC1: View Order History
  Given I am an authenticated customer
  When I navigate to the "My Orders" page
  Then I should see a list of my past orders sorted by most recent date
  And each card should display the Order ID, Total Cost, and Current Status badge.

AC2: Detailed Order Breakdown
  Given I am on the "My Orders" page
  When I click "View Details" on order #101
  Then I should see the product breakdown, shipping fee, service fee, and delivery address.
```

### US2: Real-Time Order Tracking
```gherkin
AC1: Status Timeline Display
  Given my order is in "Shipped" status
  When I open the order tracking screen
  Then the timeline should indicate "Processing" and "Shipped" as complete
  And "Delivered" should be in pending state.
```

### US3: Product Review & Rating Guardrails
```gherkin
AC1: Rating Eligibility Restriction
  Given an order is currently in "Processing" state
  When I open the order details
  Then the "Submit Rating" button should be disabled with a prompt "Available upon delivery".

AC2: Rating Submission
  Given an order is marked as "Delivered"
  When I select a star rating between 1 and 5 and enter review text
  Then the review should be persisted to the database and reflected in product average ratings.
```

### US4: Loyalty Points Calculation & Redemption
```gherkin
AC1: Automatic Points Accumulation
  Given I complete a purchase with a subtotal of 850.00 EGP
  When the payment transaction succeeds
  Then 85 loyalty points should be credited to my account balance.

AC2: Points Redemption at Checkout
  Given I have 100 loyalty points (value = 50.00 EGP)
  When I apply loyalty points on a 500.00 EGP cart
  Then my payable balance should adjust to 450.00 EGP.
```

### US9 & US10: Merchant Analytics & Promotional Campaigns
```gherkin
AC1: Analytics Aggregation
  Given I am logged in as a verified Brand Owner
  When I access the Analytics Dashboard
  Then total sales revenue, unique customer counts, and repeat customer rates should render dynamically.

AC2: Date Range Validation for Promotions
  Given I create a promotional campaign
  When I enter an end date that precedes the start date
  Then the system must reject the submission and highlight the date error.
```

---

## 🧪 Detailed Test Cases Matrix

| Test ID | Module | Scenario / Objective | Test Steps | Expected Result |
| :--- | :--- | :--- | :--- | :--- |
| **TC-ORD-01** | Orders | View order history while logged in | 1. Log in with customer account.<br>2. Navigate to `/php/myorders.php`. | Order records display with accurate dates, totals, and statuses. |
| **TC-ORD-02** | Orders | Unauthorized order history access | 1. Clear session / logout.<br>2. Direct access to `/php/myorders.php`. | Redirects to `/php/login.php` with authorization warning. |
| **TC-ORD-03** | Orders | Order total calculation logic | 1. Calculate order total with Subtotal=1000, Shipping=40, Fee=10, Discount=150. | Final total equals 900.00 EGP exactly. |
| **TC-REV-01** | Reviews | Rating delivered product | 1. Select delivered order.<br>2. Enter 5-star rating and comment.<br>3. Submit. | Review is saved; success message confirmed. |
| **TC-REV-02** | Reviews | Rating boundary check (Invalid rating) | 1. Send rating value of 0 or 6 via API. | API returns `400 Bad Request` with range validation error. |
| **TC-LOY-01** | Loyalty | Calculate points earned | 1. Subtotal of 1,250.00 EGP processed. | 125 loyalty points computed and stored. |
| **TC-PAY-01** | Payments | Luhn algorithm card validation | 1. Enter valid 16-digit Visa/Mastercard number. | Card passes checksum validation. |
| **TC-PAY-02** | Payments | Invalid credit card checksum | 1. Enter corrupted card number with invalid Luhn checksum. | System blocks submission with invalid card format error. |
| **TC-PRM-01** | Promo | Percentage discount calculation | 1. Apply code `SAVE20` (20% off) to 1,000 EGP cart. | Discount of 200.00 EGP applied. |
| **TC-PRM-02** | Promo | Capped promo code limit | 1. Apply 50% discount capped at 100 EGP to 500 EGP order. | Discount clamped to maximum limit of 100.00 EGP. |
| **TC-ANL-01** | Analytics | Returning customer rate metric | 1. Ingest order history with 2 repeat buyers and 3 single buyers. | Returning rate correctly computes to 40%. |
