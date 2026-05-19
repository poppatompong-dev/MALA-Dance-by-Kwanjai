# Thai Localization and Mala Drink Shop Readiness Design

## Goal

Make the QPOS project feel like a Thai-first POS and shop management system for a small mala and drinks shop, while keeping the underlying setup flexible enough for more branches, more product categories, and future cloud deployment.

## Recommended Direction

Use the approved hybrid approach:

1. Translate the main application UI directly in the current Blade, React, and shared view files so the local version becomes usable quickly.
2. Add Thai manuals under `docs/th/` for staff, cashiers, and admins.
3. Replace generic placeholders and demo examples with mala and drink shop examples.
4. Keep product, category, branch, unit, user, supplier, and report workflows generic so the shop can expand later.

This is intentionally not a full multi-language framework yet. The immediate target is a Thai-only operational version that is easy to test locally.

## Product and Shop Context

Default examples should fit a mala and drink shop:

- Shop name: `QPOS ร้านหม่าล่า`
- Main categories:
  - `หม่าล่าเสียบไม้`
  - `ชุดหม่าล่า`
  - `เครื่องดื่มเย็น`
  - `เครื่องดื่มปั่น`
  - `ท็อปปิ้ง`
  - `ซอส/น้ำจิ้ม`
  - `ของทานเล่น`
- Units:
  - `ไม้`
  - `แก้ว`
  - `ขวด`
  - `ชุด`
  - `ถุง`
  - `กรัม`
- Example products:
  - `หมูสามชั้นหม่าล่า`
  - `เนื้อวัวหม่าล่า`
  - `ไส้กรอกชีส`
  - `เห็ดเข็มทอง`
  - `เต้าหู้ปลา`
  - `ชุดหม่าล่าเล็ก`
  - `ชุดหม่าล่ากลาง`
  - `ชุดหม่าล่าใหญ่`
  - `ชานมเย็น`
  - `ชามะนาว`
  - `น้ำลำไย`
  - `น้ำเปล่า`

These examples should be seed/demo/placeholder values only. The admin must still be able to create arbitrary categories such as bakery, snacks, frozen food, catering sets, or new drink lines.

## UI Scope

Translate and adapt the main user-facing surfaces:

- Authentication pages:
  - Login
  - Register/reset password if present
  - Auth validation messages visible in the app
- Admin shell:
  - Sidebar
  - Navbar
  - Dashboard cards
  - Common actions such as add, edit, delete, save, cancel, search, filter, export, print
- Master data:
  - Products
  - Categories
  - Brands
  - Units
  - Customers
  - Suppliers
  - Users
  - Roles and permissions
- Operations:
  - POS/order screen
  - Sales
  - Purchases
  - Stock/inventory
  - Payments
  - Expenses if present
- Reporting:
  - Sales reports
  - Purchase reports
  - Stock reports
  - Profit/dashboard summaries
- System/configuration:
  - Shop settings
  - Tax/discount/settings labels where present
  - Error and empty states that affect daily use

## Placeholder and Mockup Rules

Use realistic Thai placeholder text:

- Product name: `เช่น หมูสามชั้นหม่าล่า`
- Category name: `เช่น หม่าล่าเสียบไม้`
- Unit name: `เช่น ไม้, แก้ว, ชุด`
- Customer name: `เช่น ลูกค้าหน้าร้าน`
- Supplier name: `เช่น ร้านวัตถุดิบสด`
- Search text: `ค้นหาสินค้า ชื่อลูกค้า หรือเลขที่บิล`
- POS empty state: `ยังไม่มีสินค้าในบิล`

Avoid placeholders that lock the system into only mala products. Text should make it obvious that admins can add more categories and products later.

## Documentation Scope

Create Thai documentation under `docs/th/`:

- `quick-start.md`
  - How to open the local app
  - Demo login
  - First setup checklist
  - Basic sale flow
- `user-manual.md`
  - Cashier/staff guide
  - POS sales flow
  - Searching products
  - Adding customers
  - Discounts, payment, receipt printing where supported
  - Common mistakes and fixes
- `admin-manual.md`
  - Initial shop setup
  - Categories, units, products
  - Stock and purchase setup
  - Users and permissions
  - Reports
  - Branch/product expansion guidance
  - Local vs cloud deployment notes

The manuals should be practical and step-by-step, written for non-technical shop owners and staff.

## Technical Approach

- Prefer minimal source changes that preserve the QPOS architecture.
- Keep local SQLite compatibility fixes already added for development.
- Do not modify generated dependency folders.
- Do not hard-code business rules for mala shops in controllers unless the existing project already expects seed/demo defaults.
- Update seed/demo values only when they are clearly demo content.
- Keep production/cloud notes separate from local `.env` details.

## Acceptance Criteria

- The login page and admin dashboard are understandable in Thai.
- Main menu/navigation labels are Thai.
- Product, category, unit, supplier, customer, sales, purchase, and report workflows use Thai wording in the visible UI.
- POS/order-related screens use Thai wording and mala/drinks-friendly placeholders.
- Manuals exist under `docs/th/` and are detailed enough for staff/admin onboarding.
- Local app still runs at `http://127.0.0.1:8000/login`.
- Demo login remains usable unless explicitly changed:
  - Email: `demo@qtecsolution.net`
  - Password: `87654321`

## Out of Scope for This Pass

- Building a language switcher.
- Translating every third-party package message.
- Replacing the database engine for production.
- Redesigning the entire UI layout.
- Implementing payment gateway integrations.
- Deploying to cloud.

## Risks

- Some UI strings may be scattered across Blade, React, PHP controllers, and JavaScript files.
- Some labels may be generated from database values or package defaults.
- If seed data is changed after a local database already exists, reseeding may be needed to see all demo changes.
- A full Thai-only conversion may expose layout issues if buttons or table columns are too narrow.

## Proposed Implementation Phases

1. Build a Thai terminology map and identify the main string locations.
2. Translate the authentication and admin shell first.
3. Translate master data and operation pages.
4. Translate POS/order components and placeholders.
5. Add Thai manuals.
6. Run build/smoke verification and open the app locally.

