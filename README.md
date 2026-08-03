# Online Food Ordering System

A simple PHP/MySQL online food ordering application designed for XAMPP.

## Features
- Customer registration and login
- Product browsing by category
- Shopping cart and checkout flow
- Order history for customers
- Admin dashboard for product, category, and order management
- Responsive user interface with a clean style
- Restaurant Manager section for menu, orders, reviews, and analytics
- Delivery Agent workflow for accepting deliveries, updating status, and tracking earnings
- Platform Admin tools for user management, platform oversight, and reports

## Setup
1. Place the project in your XAMPP `htdocs` folder.
2. Create a MySQL database named `online_food_ordering`.
3. Import `data/init.sql` into the database.
4. Update database settings in `config/database.php` if required.
5. Open the app in your browser: `http://localhost/Online-Food-Ordering-System/public/index.php?route=home`

## Admin Credentials
- Email: `admin@foodapp.local`
- Password: `Password123`

## Notes
- This project uses plain PHP and procedural MySQL access.
- For production, enable secure password storage, HTTPS, and validation.
