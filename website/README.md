# Dolaby Web Application

This folder contains the complete, standalone source code for the **Dolaby E-Commerce Platform**.

## Quickstart Guide

### 1. Requirements
* PHP 8.0+
* MySQL 8.0+
* Apache or PHP CLI

### 2. Database Initialization
```bash
mysql -u root -p -e "CREATE DATABASE dolaby_v2 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -p dolaby_v2 < sql/schema.sql
mysql -u root -p dolaby_v2 < sql/seed.sql
```

### 3. Configuration
Ensure `config.php` has valid database credentials:
```php
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306'); // Or 3307 for custom XAMPP ports
define('DB_NAME', 'dolaby_v2');
define('DB_USER', 'root');
define('DB_PASS', '');
define('BASE_URL', 'http://localhost:8000');
```

### 4. Running the Site
From the root of the repository:
```bash
php -S localhost:8000 -t website
```
Or when using **XAMPP**:
Place the `Dolaby` repository inside `htdocs/` and navigate to:
`http://localhost/Dolaby/website/`
