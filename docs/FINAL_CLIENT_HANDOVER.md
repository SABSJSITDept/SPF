# SPF Backend - Client Handover Document

Date: 2026-04-29

This document is written for client review and explains:
- What is working in the system
- Major features and workflows
- Where screenshots belong (professional screenshot set)

## 1) What this system does (In simple terms)

`SPF Backend` provides:
1. **SPF Membership Registration (Frontend)**  
   A 3-step professional form where users submit their details and a required PDF document.

2. **Admin Panel (Web)**  
   Admins can view registrations, filter/search, approve/reject members, edit members, perform bulk upload, manage categories/sub-categories, and export data (Excel/PDF).

3. **Public/Admin APIs (Backend)**  
   APIs support the registration UI and admin/reporting features.

## 2) Main Working Modules (Features)

### 2.1 User Registration (Frontend)

The public page loads a **3-step wizard**:
- **Step 1: Personal** (Mobile, Full Name, DOB, Age, Gender, State/City, Anchal, optional branch)
- **Step 2: Professional** (Profession, Professional Category, Working Status, Document Type, **PDF upload**)
- **Step 3: Community & Interests** (Sadhumargi family, source, objectives, hobbies, referral)

After submit:
- Form validates required fields and file rules.
- It sends data to backend endpoint for saving the registration.

**Upload rules (as per UI validation):**
- File type: `PDF`
- File size: between `2MB–5MB` (frontend validation)  
- Backend also validates PDF type and max size.

### 2.2 Admin Login & Access Control

Admin can login via:
- `GET /admin/login`
- `POST /admin/login`

Roles:
- `super_admin`: full control (users/admins, categories, registrations, exports)
- `operator`: member operations (can view list/export depending on UI/API)
- `anchal_operator`: restricted to **their assigned anchal**

Role restrictions are enforced using middleware:
- `admin.auth`
- `admin.role:<roles>`

### 2.3 Dashboard (Admin)

Dashboard shows:
- Total registrations
- Approved / Pending / Rejected counts
- Anchal-wise member totals (grouped)

Dashboard also resolves human-readable labels (anchal/branches) via external API data (cached).

### 2.4 Member Review (Approved / Pending / Rejected)

Admin can view members in three lists:
- Approved members: **filter + export + view file + optional actions**
- Pending members: **approve/reject single**
- Rejected members: **bring back to pending** (plus optional edit based on role)

Each list supports:
- Advanced filtering (name, MID, phone, age range, profession category/sub-category, state/city/anchal/local sangh, referral, registration date range, MID presence)
- Column visibility controls
- Export:
  - **Excel**
  - **PDF**
- In tables, uploaded PDFs can be viewed directly (where available)

### 2.5 Member Edit (Admin)

Admin can open a member edit screen to update:
- personal details
- profession/category
- working status
- location & local sangh
- objectives/background
- optional document replacement

It also provides quick actions to approve/reject depending on current status.

### 2.6 Bulk Upload (Admin)

Admin can bulk import members using Excel/CSV through:
- `GET /spf-backend/members/bulk-upload`
- `POST /spf-backend/members/bulk-upload`

Bulk upload supports:
- `.xlsx`, `.xls`, `.csv`
- max size `10MB`
- Import logic creates new SPF registrations with `status = pending`

### 2.7 Category & Sub-Category Management

Admin manages:
- Categories (Active/Inactive)
- Sub-categories mapped to categories (Active/Inactive)

Public APIs provide:
- `GET /api/categories`
- `GET /api/sub-categories/{category_id}`

### 2.8 Reporting & Exports

Backend supports exports for reports:
- Excel exports via `maatwebsite/excel`
- PDF exports via `barryvdh/laravel-dompdf`

Admin export flows use location labels (state/city/anchal/branch) and selected export fields.

### 2.9 Open Member Count API

Open API endpoint:
- `GET /api/member-count`

Query param:
- `status=approved|pending|rejected|all`

Response provides anchal-wise totals and local sangh totals (nested structure).

### 2.10 External Membership Sync (mrm.sadhumargi.org)

When an admin changes a member status to **Approved** or **Rejected**, the system also syncs membership status to the external service `mrm.sadhumargi.org` (when `MID` is available).

Where it happens:
- Single member status update (Web): `POST /spf-backend/members/{id}/status`
- Bulk status update (Web): `POST /spf-backend/members/bulk-status`
- Approve all pending (Web): `POST /spf-backend/members/approve-all`

Sync logic (high-level):
- The system first updates `spf_registrations.status` in this app.
- If the member has a non-empty `mid`, it calls:
  - **URL:** `https://mrm.sadhumargi.org/api/member/update-membership-spf`
  - **Payload:**
    - `member_id`: the member’s `mid`
    - `action`: `accept` when status becomes `approved`, `reject` when status becomes `rejected`
- This external call is wrapped in `try/catch`, so if the external service fails, the app still completes the status update.

Important behavior:
- If `mid` is missing/empty, the external sync call is skipped.

## 3) Key API Endpoints (Quick Reference)

### Public
- `POST /api/register`  
- `GET /api/member-count?status=approved|pending|rejected|all`
- `GET /api/categories`
- `GET /api/sub-categories/{category_id}`

### Admin (Sanctum protected where applicable)
- `POST /api/admin/login`
- `POST /api/admin/logout` (auth)
- `GET /api/admin/users`
- `POST /api/member-action`
- `GET /api/admin/fetchusers`
- `GET /api/admin/export`
- `GET /api/filters`

## 4) Screenshots (Professional Set)

Place screenshots in:
- `docs/screenshots/`

Then ensure filenames are EXACTLY as below. (I used these filenames already in `docs/screenshots/README.md`.)

### Screenshot 1 - Home / Registration

![Home - Registration](./screenshots/01-home-registration.png)

### Screenshot 2 - Admin Login

![Admin Login](./screenshots/02-admin-login.png)

### Screenshot 3 - Dashboard

![Dashboard Overview](./screenshots/03-dashboard-overview.png)

### Screenshot 4 - Registrations List (Approved)

![Approved Members](./screenshots/04-registrations-list.png)

### Screenshot 5 - Approved Members Filters

![Approved Members Filters](./screenshots/05-approved-members-filters.png)

### Screenshot 6 - Pending Members Actions

![Pending Members Actions](./screenshots/06-pending-members-actions.png)

### Screenshot 7 - Member Edit

![Member Edit Form](./screenshots/07-member-edit-form.png)

### Screenshot 8 - Bulk Upload

![Bulk Upload Page](./screenshots/08-bulk-upload.png)

### Screenshot 9 - Admin Users Management

![Admin Users Management](./screenshots/09-admin-users-management.png)

### Screenshot 10 - Category Management

![Category Management](./screenshots/10-category-management.png)

### Screenshot 11 - Sub-Category Management

![Sub-Category Management](./screenshots/11-subcategory-management.png)

### Screenshot 12 - Export Options

![Export Options](./screenshots/12-export-options.png)

## 5) Screenshot Capture Checklist (So it looks professional)

Before taking screenshots:
- Browser zoom: `100%`
- Window size: same for all captures (example: `1600x900`)
- Sensitive values hide: tokens, internal IDs (if not required), etc.
- Use consistent naming and order (01 → 12)

## 6) Validation Checklist (What should be verified before client sign-off)

1. Public registration form loads properly and wizard steps work
2. PDF upload accepts only allowed PDF
3. Registration saves successfully in DB (expect JSON “Registration successful”)
4. Admin login works and redirects to dashboard
5. Role restriction works (anchal_operator scoped to their anchal)
6. Pending members can be approved/rejected
7. Approved & Rejected lists show correct status and filters
8. Bulk upload processes sample template data (status becomes pending)
9. Exports download Excel and PDF correctly

## 7) Notes / Assumptions

- Some labels (state/city/anchal/branch names) are resolved using external APIs and cached.
- Export/report outputs depend on availability of those external APIs.

