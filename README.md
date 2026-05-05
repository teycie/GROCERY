# Online Grocery System (Laravel)

A simple and clean beginner-friendly Laravel web application with:

- Authentication (register/login/logout)
- Role-based access (Buyer and Seller)
- Seller product management (CRUD)
- Predefined product categories
- Buyer product listing and category filter
- Session-based shopping cart
- Seller announcements
- Buyer and Seller dashboards

## Tech Stack

- Laravel (generated from latest available for current PHP)
- PHP
- MySQL
- Blade templates
- Plain CSS

## Important Note About Version

This project is intentionally kept on Laravel 8 so it can run on PHP 7.4+.
Composer is configured for PHP compatibility (`php ^7.4|^8.0`) so local setup does not require PHP 8.1+.

If you upgrade PHP to 8.3+, you can create a Laravel 13 project directly.

## Database Tables

Migrations included for:

- users (with role)
- products
- carts
- cart_items
- announcements

Note: Cart feature in this student version is session-based for simplicity, even though cart tables are also created for learning/reference.

## Step-by-Step Setup (MySQL)

1. Install prerequisites:
   - PHP
   - Composer
   - MySQL

2. Go to project folder:

   ```bash
   cd c:\Users\USER\Grocery
   ```

3. Install dependencies (if needed):

   ```bash
   composer install
   ```

4. Create environment file (if not present):

   ```bash
   copy .env.example .env
   ```

5. Update database settings in `.env`:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=online_grocery_system
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. Generate app key:

   ```bash
   php artisan key:generate
   ```

7. Run migrations:

   ```bash
   php artisan migrate
   ```

8. Create storage symlink for product images:

   ```bash
   php artisan storage:link
   ```

9. Start the app:

   ```bash
   php artisan serve
   ```

10. Open in browser:

   - http://127.0.0.1:8000

## Main Routes

- `/register` - Register account (choose Buyer or Seller)
- `/login` - Login
- `/buyer/dashboard` - Buyer dashboard
- `/seller/dashboard` - Seller dashboard
- `/products` - Buyer product list
- `/cart` - Buyer cart
- `/announcements` - Authenticated announcements list

## Learning Notes

- Controllers include simple comments for key parts.
- Middleware `EnsureRole` handles role-based page access.
- Product images are uploaded to `storage/app/public/products`.
- UI is intentionally simple and readable.
