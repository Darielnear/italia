# Cicli Volante - E-Commerce Platform

## Overview

Cicli Volante is a luxury e-commerce website for selling bicycles (E-MTB, MTB, Road) and accessories. The site is designed with a premium "Apple Style" aesthetic and is entirely in Italian. It serves as a "Sales Machine" with a product catalog of 75 products loaded from a JSON file into a SQLite database, category filtering, product detail pages with color variant selectors, a checkout flow with bank transfer (BBVA) payment instructions, and proof-of-payment upload.

The project is a traditional server-rendered PHP application — no JavaScript frameworks, no Node.js backend. The frontend uses vanilla JavaScript, HTML5, and Tailwind CSS via CDN.

## User Preferences

Preferred communication style: Simple, everyday language.

## System Architecture

### Backend: PHP 8.x (Built-in Server)

- **Web Server**: PHP's built-in development server running on port 5000 (`php -S 0.0.0.0:5000 -t .`). The document root is the project root directory.
- **Routing**: PHP files serve as individual pages/endpoints. There is no MVC framework — routing is file-based.
- **Database**: SQLite (`database.sqlite` file). No external database server needed.
- **Migration**: `migrate.php` reads `products.json` (75 products) and imports all data into the SQLite database.
- **File Uploads**: The checkout process requires uploading a screenshot of a bank transfer as proof of payment. Uploaded files should be stored on the server filesystem.

### Database Schema (SQLite)

**Table: `products`**
| Column | Type | Description |
|---|---|---|
| id | INTEGER PRIMARY KEY | Product ID from JSON |
| prezzo | INTEGER | Price in euros (whole numbers) |
| nome_modello | TEXT | Product/model name |
| brand | TEXT | Brand name (e.g., Specialized) |
| categoria | TEXT | Category: E-MTB, MTB, Road, Accessori |
| descrizione_lunga | TEXT | Long description |
| caratteristiche_tecniche | TEXT (JSON) | Technical specs stored as JSON string |
| varianti | TEXT (JSON) | Color/variant options stored as JSON array |

**Table: `orders`**
| Column | Type | Description |
|---|---|---|
| id | INTEGER PRIMARY KEY | Order ID |
| (customer details) | TEXT | Name, email, address, etc. |
| (order items) | TEXT (JSON) | Products ordered |
| upload_filename | TEXT | Filename of uploaded payment proof screenshot |
| created_at | DATETIME | Order timestamp |

### Frontend Architecture

- **Styling**: Tailwind CSS loaded via CDN. Custom styles in `assets/js/css/style.css`. Design uses white background, anthracite text (`#2C3E50` or similar), and green accent (`#2ECC71`).
- **JavaScript**: Vanilla JS in `assets/js/main.js`. Handles category filtering with fade animations, cart count updates, and color variant switching on product pages.
- **Images**: Product images follow the naming convention `/public/img/{id}_{color}.webp` for variant-specific images.
- **Header**: Sticky with glassmorphism (backdrop blur effect), logo on left, cart icon on right.
- **Language**: Everything must be in Italian — all labels, buttons, descriptions, error messages.

### Key Pages & Features

1. **Index/Catalog** (`index.php`): Displays all products with 4 filter tabs (E-MTB, MTB, Road, Accessori). Product cards in a grid layout.
2. **Product Detail** (`product.php?id=X`): Shows full product info, technical specs, and clickable color variant circles (pastilles) that swap the main product image.
3. **Cart**: Client-side cart management (localStorage or session-based). Cart icon in header shows item count.
4. **Checkout** (`checkout.php`): Ultra-clean payment page displaying BBVA bank transfer details (IBAN: IT52 PO35 7601 6010 1000 8072 943, BIC: BBVAITM2XXX, Beneficiary: Cicli Volante). Includes mandatory file upload field for payment proof screenshot.
5. **Customer Reviews Section**: Before the footer, a "Cosa dicono i nostri clienti" section with testimonials featuring gold star ratings.

### Important Notes

- The `script/build.ts` file in the repo is a leftover/template artifact and is NOT part of this project's architecture. This project does not use Node.js, TypeScript, Vite, Express, React, or Drizzle. Ignore that file entirely.
- The project runs exclusively on PHP. Do not introduce Node.js or any JS build tools.
- The dev server command is `php -S 0.0.0.0:5000 -t .` and must serve on port 5000.

## External Dependencies

### Payment Integration
- **BBVA Bank Transfer**: No API integration — the checkout page displays static bank transfer instructions. Payment verification is manual via uploaded screenshot.
  - Beneficiario: Cicli Volante
  - IBAN: IT52 PO35 7601 6010 1000 8072 943
  - BIC: BBVAITM2XXX
  - Banca: BBVA

### CDN Dependencies
- **Tailwind CSS**: Loaded via CDN (`<script src="https://cdn.tailwindcss.com"></script>` or similar CDN link). No local build process.

### Database
- **SQLite**: File-based database (`database.sqlite`). No external database server. PHP's built-in `SQLite3` or `PDO` with SQLite driver is used.

### Data Source
- **products.json**: Local JSON file containing all 75 products with pricing, descriptions, technical specs, and color variants. Imported into SQLite via `migrate.php`.