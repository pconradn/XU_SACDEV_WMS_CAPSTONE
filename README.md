
# SAcDev Project Workflow Management System

## Development Cycle 1 – System Foundation + Org Setup + Role Assignment

This repository contains the implementation for **Development Cycle 1** of the SAcDev Project Workflow Management System.
Cycle 1 focuses on establishing the **core system foundation**, including authentication, role-based access, active school year filtering, and the organization setup workflow (officers, projects, and assignments), so the system is already usable for login + encoding + role assignment before moving to full proposal workflow features in Cycle 2.

---

## Key Features Implemented (Dev Cycle 1)

### 1) Authentication + Temporary Account Setup

* Login implemented using **Laravel Breeze**
* **Forced password change** on first login for temporary accounts:

  * `must_change_password`
  * `password_changed_at`

### 2) Role-Based Access Control (RBAC)

System access depends on role:

**System Role**

* `sacdev_admin`

**Organization Roles**

* `president`
* `treasurer`
* `moderator`
* `member`

Middleware added:

* `sacdev_admin`
* `must_change_password`
* `active_sy_access`

### 3) Active School Year Logic

* Only **one school year** can be active at a time
* Org-side encoding and records are filtered by the active school year
* Admin can manage and activate school years

### 4) Organization Setup Workflow (President)

President can:

* Encode **Officer List**
* Encode **Projects**
* Assign:

  * **1 Treasurer**
  * **1 Moderator**
  * **1 Project Head per project**

Rules enforced:

* No random email typing for role assignment
* Assignments must come from the officer list
* New assignments overwrite old ones safely

### 5) Auto Account Provisioning (Only When Needed)

Accounts are created only when an officer is assigned as:

* Treasurer
* Moderator
* Project Head

Handled through `AccountProvisioner`:

* Generates temporary password
* Sends credentials (development uses `MAIL_MAILER=log`)
* Sets password-change requirement

### 6) Safe Invite Resend (Pending Accounts Only)

Invite resend works only when:

* `must_change_password = 1`
* `password_changed_at = null`

This allows correcting wrong encoded emails before the user activates.

---

## Core Tables / Models Used

* `users`
* `school_years`
* `organizations`
* `organization_school_years`
* `officer_entries`
* `projects`
* `org_memberships`
* `project_assignments`

---

## Seeder (Sprint 1)

Seeder:

* `Database\Seeders\Sprint1Seeder`

Creates demo data including:

* 1 active school year
* 2 orgs
* SacDev admin user
* 2 presidents
* officer lists + projects

Test Accounts:

* Admin: `sacdev.admin@xu.edu.ph` / `Admin1234!`
* President (XUCS): `president.xucs@xu.edu.ph` / `TempPass123!`
* President (XUTI): `president.xuti@xu.edu.ph` / `TempPass123!`

---

## Setup Instructions

### Install dependencies

```bash
composer install
npm install
npm run dev
```

### Configure `.env`

```bash
cp .env.example .env
php artisan key:generate
```

Update DB credentials in `.env`

### Migrate + seed

```bash
php artisan migrate:fresh --seed
```

### Run server

```bash
php artisan serve
```

---

## NPM Notes (Frontend)

* Use **`npm run dev`** while developing (recommended)
* Use **`npm run build`** only for production/deployment

---

## Cycle 1 Demo Flow

### Org President

1. Login
2. Change password (forced)
3. Encode officers
4. Encode projects
5. Assign treasurer/moderator
6. Assign project heads
7. Check that accounts are auto-created (email logged)

### SacDev Admin

1. Login
2. Manage school years (CRUD + activate)
3. View org data for monitoring

---
