# Digital Mart - PHP E-Commerce Web Application

**Project Documentation (Version 1.0)**  
*June 2026*

---

## 1. Project Overview
Digital Mart is a PHP/MySQL-based e-commerce web application designed for selling daily-use consumer products online. It provides a customer-facing storefront with product browsing, cart management, and checkout, as well as a protected admin panel for managing products, categories, orders, and administrators.

### 1.1 Technology Stack
| Component | Technology |
| :--- | :--- |
| **Backend Language** | PHP (procedural + OOP via MySQLi prepared statements) |
| **Database** | MySQL (`utf8mb4`) |
| **Frontend** | HTML5, Bootstrap 5.3.3 |
| **Session Management** | PHP native sessions |
| **Password Hashing** | PHP `password_hash` / `password_verify` (bcrypt) |
| **Email** | PHP `mail()` (native) |

### 1.2 Key Features
- Product catalogue with search and category filtering
- Session-based shopping cart with quantity management
- Checkout with name, email, phone, address, and payment method
- Automatic shipping calculation (Free on orders PKR 3,000+, else PKR 200)
- Order confirmation with email notification
- Customer feedback form
- Admin panel: dashboard stats, product/category/admin CRUD
- Role-based admin access (`main_admin` vs `admin`)

---

## 2. Database Design
- **Database Name:** `digital_mart`
- **Character Set:** `utf8mb4` / `utf8mb4_unicode_ci`

### 2.1 Entity-Relationship Summary
| Table | Primary Key | Relationships |
| :--- | :--- | :--- |
| `admins` | `id` (INT AI) | Standalone |
| `categories` | `id` (INT AI) | One-to-many: `products` |
| `products` | `id` (INT AI) | FK: `category_id` -> `categories.id` (SET NULL) |
| `orders` | `id` (INT AI) | One-to-many: `order_items` |
| `order_items` | `id` (INT AI) | FK: `order_id` -> `orders.id` (CASCADE), FK: `product_id` -> `products.id` (SET NULL) |
| `feedback` | `id` (INT AI) | Standalone |

---

## 3. File & Directory Structure
```text
config/
  └── db.php               # Database connection (MySQLi, utf8mb4)
includes/
  ├── header.php           # Site-wide HTML head + Bootstrap navbar
  └── footer.php           # Bootstrap JS bundle + closing tags
admin/
  ├── login.php            # Admin authentication
  ├── logout.php           # Destroy session, redirect to login
  ├── dashboard.php        # Stats: total products, orders, categories
  ├── products.php         # CRUD: add and delete products
  ├── categories.php       # CRUD: add and delete categories
  └── users.php            # Add new admins (main_admin role only)
index.php                  # Homepage: product grid with search
categories.php             # Browse all categories
product-details.php        # Single product page + Add to Cart form
cart.php                   # Cart view: update quantities, proceed to checkout
checkout.php               # Order form: customer details + shipping total
place-order.php            # Order processing: DB insert, stock update, email
feedback.php               # Customer feedback submission form
hash.php                   # Utility: generate bcrypt hash (dev use only)
digital_mart.sql           # Full DB schema + seed data
