


# SAcDev Project Workflow Management System
## Development Cycle 1 (Sprint 1) – Foundation + Org Setup + Role Assignment

This repository contains the implementation for **Development Cycle 1** of the SAcDev Project Workflow Management System.  
Cycle 1 focuses on building the **core backend foundation**, authentication flow, role access control, and the organization setup workflow (officers, projects, and assignments).  
The goal for this cycle is to make the system functional enough for login + encoding + role assignment + admin viewing, before moving to the full proposal submission workflow in Cycle 2.

---

##  Scope of Development Cycle 1

###  Functional Features Implemented

#### 1) User Authentication (Laravel Breeze)
- Secure login using unique credentials
- Uses Laravel’s built-in authentication scaffolding (Breeze)

#### 2) Forced Password Change (Temporary Account Gate)
- Users with **temporary passwords** are required to change password on first login
- Uses the fields:
  - `must_change_password`
  - `password_changed_at`

#### 3) Role-Based Access Control (RBAC)
Access to pages/features depends on role:

**System Role**
- `sacdev_admin` (SAcDev staff)

**Organization Roles (Org Membership)**
- `president`
- `treasurer`
- `moderator`
- `member` (basic org portal access)

Middleware was added to restrict access properly:
- `sacdev_admin`
- `must_change_password`
- `active_sy_access`

#### 4) Active School Year Filtering
- Only **one school year can be active** at a time
- Org-side encoding and views are based on the selected/active school year
- Admin can manage school years and activate one

#### 5) Organization Setup Workflow (President Encoding)
Org President can encode:
 **Officer List** (required first)  
 **Project List**  
 Assign:
- Exactly **1 Treasurer**
- Exactly **1 Moderator**
- Exactly **1 Project Head per Project**

Rules enforced:
- No free typing random emails for assignment
- Assignments must come from the officer list first
- System overwrites old treasurer/moderator assignments when changed
- System overwrites old project head when assigning a new one

#### 6) Auto Account Creation (Only When Needed)
User accounts for students/officers are created only when they are assigned:
- as **treasurer**
- as **moderator**
- as **project head**

Accounts are created using `AccountProvisioner`:
- Generates temporary password
- Sends credentials through email (`MAIL_MAILER=log` during development)
- Sets must-change-password flag

#### 7) Officer Entry ↔ User Linking
- Officer entries are linked to user accounts through:
  - `officer_entries.user_id`

This makes later features safe (email corrections, role checks, reassignments).

#### 8) Invite Resend for Wrong Officer Email (Pending Invite Only)
If an officer’s email was encoded incorrectly and the system already created an account,
the invite can be resent safely ONLY when:
- user is still pending (`must_change_password = 1`)
- user has not activated yet (`password_changed_at = null`)

Resend process:
- updates the existing user email
- resets temp password safely (pending only)
- resends credentials

---

##  Database Tables / Core Models (Cycle 1)
Development Cycle 1 includes the initial database structure with core relationships:

- `users`
- `school_years`
- `organizations`
- `organization_school_years`
- `officer_entries`
- `projects`
- `org_memberships`
- `project_assignments`

---

##  Seeder for Demo / Testing
A ready-to-run seeder is included for Sprint 1 testing:

Seeder:
- `Database\Seeders\Sprint1Seeder`

Creates:
- 1 active school year
- 2 organizations
- 1 SacDev Admin user
- 2 president users
- officer lists for each org
- projects for each org

Test Accounts:
- **Admin Login**
  - Email: `sacdev.admin@xu.edu.ph`
  - Password: `Admin1234!`

- **President Login (XUCS)**
  - Email: `president.xucs@xu.edu.ph`
  - Password: `TempPass123!`

- **President Login (XUTI)**
  - Email: `president.xuti@xu.edu.ph`
  - Password: `TempPass123!`

---

##  Setup Instructions

### 1) Install dependencies
```bash
composer install
npm install
npm run dev
````

### 2) Configure `.env`

Copy `.env.example` and generate key:

```bash
cp .env.example .env
php artisan key:generate
```

Update DB credentials in `.env`

### 3) Run migrations + seed

```bash
php artisan migrate:fresh --seed
```

### 4) Run server

```bash
php artisan serve
```

---

##  What to Test (Cycle 1 Demo Flow)

### Org President Flow

1. Login as president
2. Forced password change triggers
3. Encode officer list
4. Encode projects
5. Assign treasurer + moderator
6. Assign project heads
7. Observe that assigned officers get accounts created automatically (invite logged)

### SacDev Admin Flow

1. Login as admin
2. Manage school years (CRUD + activate)
3. View organization entries (officers/projects/roles)

---

##  Next Development Cycle (Cycle 2 Preview)

Cycle 2 will focus on the actual workflow features such as:

* proposal submission forms
* approval routing (president → treasurer → moderator → sacdev)
* feedback and revision loop
* document tracking statuses
* project completion + archival logic

---

## Notes / Developer Reminders

* Temporary accounts must always pass through the password change gate
* Assignments should always use `users.id`, not `officer_entries.id`
* Always link `officer_entries.user_id` after provisioning a user
* Use `updateOrCreate()` for roles to prevent unique constraint errors
* All org data must be filtered by active/selected school year

---

 Development Cycle 1 is considered complete once:

* Authentication is stable
* Org encoding works (officers/projects)
* Assignment logic works without crashes
* Admin can view data for monitoring

```

