# SPF Backend - Project Documentation

## 1) Project Overview

`spf-backend` is a Laravel 9 based application for SPF member registration and administration.

The system includes:
- Public registration flow
- Admin web panel for review and status actions
- Admin API (Sanctum token based)
- Category/sub-category management
- Member export (Excel/PDF)
- Bulk member import via Excel
- Role-based access for `super_admin`, `operator`, and `anchal_operator`

## 2) Tech Stack

- Backend: `PHP 8+`, `Laravel 9`
- Authentication: `Laravel Sanctum` (API), Session guard (Admin web)
- Database: `MySQL`
- Frontend build: `Vite`, `TailwindCSS`
- Export: `maatwebsite/excel`, `barryvdh/laravel-dompdf`

## 3) Project Structure

Key directories:
- `app/Http/Controllers` - API and web controllers
- `app/Models` - Eloquent models
- `app/Http/Middleware` - admin auth and role authorization
- `routes/api.php` - API routes
- `routes/web.php` - web routes (frontend + admin panel)
- `resources/views/spf_frontend` - frontend pages
- `resources/views/spf_backend` - admin panel pages
- `database/migrations` - schema history

## 4) Core Functional Modules

### 4.1 Member Registration

- API endpoint: `POST /api/register`
- Controller: `SpfRegistrationController@store`
- Supports:
  - profile fields (mobile, name, age, city, state, anchal, etc.)
  - document metadata (`document_type`)
  - optional PDF upload (`file`, max 5MB)
- Duplicate prevention on (`mobile`, `mid`) combination

### 4.2 Admin Authentication

- API login/logout:
  - `POST /api/admin/login`
  - `POST /api/admin/logout` (auth required)
- Web admin login/logout:
  - `GET /admin/login`
  - `POST /admin/login`
  - `POST /spf-backend/logout`

### 4.3 Dashboard & Member Management

Inside protected admin prefix `spf-backend`:
- Dashboard stats (`total`, `approved`, `pending`, `rejected`)
- Member listing and filtering
- Status update (`pending|approved|rejected`)
- Bulk status update and approve-all
- Member edit/update

### 4.4 Role-Based Access

Roles:
- `super_admin`: full access including user/category management
- `operator`: member operations
- `anchal_operator`: restricted to assigned anchal data

Authorization middleware:
- `admin.auth`
- `admin.role:<roles>`

### 4.5 Category & Sub-category Management

Web operations:
- Add category/sub-category
- Toggle `Active/Inactive` status

Public API:
- `GET /api/categories`
- `GET /api/sub-categories/{category_id}`

### 4.6 Reporting & Export

- Filtered member fetch and export endpoints
- Excel export using `MembersExport` and `UsersExport`
- PDF export for member listing

### 4.7 Bulk Upload

- Admin UI upload endpoint: `POST /spf-backend/members/bulk-upload`
- Excel import (`xlsx`, `xls`, `csv`, max 10MB)
- Import class: `MembersImport`

### 4.8 Open Member Count API

- `GET /api/member-count?status=approved|pending|rejected|all`
- Returns:
  - grand total
  - anchal-wise totals
  - nested local-sangh totals

## 5) Database Design (High-Level)

Main tables:
- `spf_registrations` - member master data + status + optional file
- `admins` - admin accounts with role and optional anchal binding
- `categories` - profession category master
- `sub_categories` - linked to categories
- default Laravel tables: `users`, `password_resets`, `personal_access_tokens`, `failed_jobs`

Important `spf_registrations` fields:
- `mobile`, `mid`, `full_name`, `father_name`, `dob`, `age`, `gender`
- `profession`, `professional_category`
- `state`, `city`, `anchal`, `local_sangh_id`
- `sadhumargi`, `working_status`, `hobbies`, `referral`, `objectives`, `source`
- `document_type`, `file`, `status`

## 6) External Integrations

- Branch and geography metadata:
  - `https://mrm.sadhumargi.org/api/branches`
  - `https://mrm.sadhumargi.org/api/cities`
- Membership status push:
  - `https://mrm.sadhumargi.org/api/member/update-membership-spf`

Integration data is cached in-app for performance (generally 1 hour cache window).

## 7) API Reference (Important Endpoints)

### Public API

- `POST /api/register`
- `GET /api/member-count`
- `GET /api/categories`
- `GET /api/sub-categories/{category_id}`

### Admin API (Sanctum protected unless specified)

- `POST /api/admin/login` (public)
- `POST /api/admin/logout`
- `GET /api/admin/users`
- `POST /api/member-action`
- `GET /api/admin/fetchusers`
- `GET /api/admin/export`
- `GET /api/filters`
- `GET /api/admin/export-users` (currently outside Sanctum group)

## 8) Web Routes (Admin Panel)

Public:
- `/`
- `/spf-registration`
- `/change-password`
- `/admin/login`

Protected prefix: `/spf-backend/*`
- dashboard
- registrations
- member status pages (`approved`, `pending`, `rejected`)
- member edit/update
- bulk upload
- category/sub-category management
- admin user management
- password change

## 9) Setup & Run (Local)

### 9.1 Prerequisites

- PHP 8+
- Composer
- Node.js + npm
- MySQL

### 9.2 Installation

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Update `.env`:
- `DB_*` credentials
- `APP_URL`
- optional mail/cache/queue values

Then:

```bash
php artisan migrate
php artisan serve
npm run dev
```

App default URL: `http://127.0.0.1:8000`

## 10) Environment Variables (Minimum)

Required minimum:
- `APP_NAME`
- `APP_ENV`
- `APP_KEY`
- `APP_DEBUG`
- `APP_URL`
- `DB_CONNECTION`
- `DB_HOST`
- `DB_PORT`
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`

## 11) Admin Roles & Permission Matrix

- `super_admin`:
  - create/delete admin users
  - manage categories/sub-categories
  - member operations
- `operator`:
  - member operations
  - bulk upload
- `anchal_operator`:
  - member operations scoped to own anchal
  - bulk upload (anchal enforced during import)

## 12) Screenshots (Professional Documentation Set)

Create a folder `docs/screenshots` and add the following images:

1. `01-home-registration.png` - public registration page
2. `02-admin-login.png` - admin login form
3. `03-dashboard-overview.png` - dashboard KPI cards + chart
4. `04-registrations-list.png` - all registrations table
5. `05-approved-members-filters.png` - approved members with filters
6. `06-pending-members-actions.png` - pending list with status actions
7. `07-member-edit-form.png` - edit member screen
8. `08-bulk-upload.png` - bulk upload page
9. `09-admin-users-management.png` - users management screen
10. `10-category-management.png` - add/toggle categories
11. `11-subcategory-management.png` - add/toggle sub-categories
12. `12-export-options.png` - export controls (excel/pdf + fields)

Embed in final client/shareable docs with markdown:

```md
![Dashboard Overview](./screenshots/03-dashboard-overview.png)
```

### Screenshot Quality Checklist

- Resolution: at least `1600x900`
- Browser zoom: `100%`
- Use same window size across all captures
- Blur/hide sensitive values (email, tokens, phone)
- Keep consistent naming convention (`01-...`, `02-...`)
- Prefer light theme unless brand guideline says otherwise

## 13) Testing & Validation

Recommended commands:

```bash
php artisan test
php artisan route:list
php artisan config:clear
php artisan cache:clear
```

Manual validation checklist:
- registration create success
- duplicate mobile+mid rejected
- admin login/logout works (API + web)
- role restrictions enforced
- member status change triggers external update call
- export excel/pdf works
- bulk import works for sample file

## 14) Deployment Notes

- Set `APP_ENV=production`, `APP_DEBUG=false`
- Configure proper DB credentials and `APP_URL`
- Run migrations
- Build frontend assets (`npm run build`)
- Ensure `storage` and `bootstrap/cache` permissions
- Setup web server (Nginx/Apache) document root to `public`

## 15) Known Observations

- Base `README.md` is still Laravel default and should be replaced by this documentation summary.
- Some report routes/method names suggest partial refactor history (`exportToExcel` vs `export` usage); verify route-controller alignment before production freeze.

---

For client handover, keep this file as the source of truth and append release-specific updates per sprint/version.
