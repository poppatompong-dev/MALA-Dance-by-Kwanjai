# MALA Dance by Kwanjai - POS & Management System

A modern, fast, and secure Point-of-Sale (POS) and Restaurant Management System custom-built for **MALA Dance by Kwanjai**. 

This system has been upgraded from a generic template into a production-ready application tailored for Thai retail/restaurant environments.

## Core Features

- **Blazing Fast POS**: Optimized checkout flow designed for high-volume, rapid-fire orders typical of a skewer/mala shop.
- **Smart Inventory**: Real-time stock deductions tied into a comprehensive ledger (`stock_movements` table) to prevent stock mismatches.
- **Security & Auditing**: Role-based access control (Owner, Admin, Cashier) and permanent `audit_logs` for tracking sensitive actions like voiding orders.
- **Performance Optimized**: Memory-optimized, lightning-fast dashboard aggregation for sales and reporting. Heavy SQL queries replace sluggish PHP collection loops.
- **Void/Refund Handling**: "Soft deletes" for orders ensure that voided receipts are safely hidden from sales totals but permanently retained for auditing purposes.

## Requirements
- PHP 8.1+
- Composer
- Node.js & NPM
- MySQL or SQLite (Default configuration uses SQLite)

## Local Development Setup

1. **Clone the repository**
   ```bash
   git clone https://github.com/poppatompong-dev/MALA-Dance-by-Kwanjai.git
   cd MALA-Dance-by-Kwanjai
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Setup**
   Ensure your `.env` specifies `DB_CONNECTION=sqlite`.
   ```bash
   touch database/database.sqlite
   php artisan migrate:fresh --seed
   ```
   *(Note: This automatically runs the `RolesAndPermissionsSeeder` to set up security roles, and the `ThaiShopSeeder` to populate the POS with default mala items and pricing).*

5. **Start the Application**
   ```bash
   php artisan serve
   ```
   In a separate terminal, run the asset bundler:
   ```bash
   npm run dev
   ```

## Roles & Access
The default seeder will configure:
- **Owner**: Full system access.
- **Admin**: Full access except User Management.
- **Cashier**: Isolated access to the POS checkout system only.

## Deployment (Production)
For production environments, ensure you cache the framework configuration for maximum performance:
```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
```
