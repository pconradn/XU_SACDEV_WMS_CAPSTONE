

# SAcDev Project Workflow Management System

## Development Cycle 2 – Re-Registration Workflow, Multi-School-Year Logic, and Activation

This repository contains the implementation of the **SAcDev Project Workflow Management System up to Development Cycle 2**.

Development Cycle 1 established the system foundation (authentication, role-based access, officer and project encoding, and role assignment).
**Development Cycle 2 builds on this foundation by introducing the full re-registration lifecycle**, supporting multiple school years, structured form submissions (B1–B5), review and approval flows, notifications, and controlled organization activation.

The system is now designed to follow the **actual SAcDev process**, from preparing the next school year up to activating organizations for operation.

---

## Core System Idea 

The system separates **current operations** from **next school year preparation**.

* **Active School Year**

  * Used for operational modules (current officers, projects, role assignments after activation)
* **Target School Year**

  * Used for re-registration
  * Stored in session as `encode_sy_id`
  * Becomes the primary context when set

This allows **incoming officers to prepare requirements before the school year becomes active**.

---

## Re-Registration Forms Overview

| Form                        | Owner     | Purpose                                 |
| --------------------------- | --------- | --------------------------------------- |
| B1 – Strategic Plan         | President | Org plans, projects, funding, direction |
| B2 – President Registration | President | Confirms presidency for target SY       |
| B3 – Officers List          | President | Proposed officers for target SY         |
| B5 – Moderator Submission   | Moderator | Moderator confirmation / notation       |

---

## Correct End-to-End System Flow

This is the **intended flow** and should be followed when testing.

### Step 1: SACDEV creates a new School Year

* SACDEV admin adds a new school year entry
* Only one school year can be active at a time
* The new school year exists as a **target SY** for re-registration

---

### Step 2: **Previous / Active SY President assigns the Next SY President**

This step is critical.

* The **current (active SY) president** provisions the president for the **next school year**
* This creates or updates:

  * The user account
  * Organization membership for the target SY
* This step enables the next SY president to log in and access re-registration

Without this step, the next SY president cannot proceed.

---

### Step 3: Next SY President logs in (Target SY context)

After provisioning, the **next SY president**:

1. Logs in
2. Selects the **target school year**
3. Assigns the **moderator for that target SY**
4. Completes re-registration forms:

   * **B1 – Strategic Plan**
   * **B2 – President Registration**
   * **B3 – Officers List**

Notes:

* Forms support draft saving
* Forms can be edited until submitted
* Officer entries here are proposals only (not yet operational)

---

### Step 4: Moderator logs in

The moderator:

1. Logs in
2. Sees only organizations assigned to them
3. Reviews :

   * Can return it with remarks, or
   * Forward it to SACDEV
4. Completes **B5 – Moderator Submission**

Moderators:

* Cannot edit president-owned forms
* Only operate within the target school year

---

### Step 5: SACDEV reviews all forms

SACDEV reviews the full set of re-registration forms:

* B1 – Strategic Plan
* B2 – President Registration
* B3 – Officers List
* B5 – Moderator Submission

Possible SACDEV actions:

* Return with remarks (editing re-enabled)
* Approve (editing locked)
* Revert approval with remarks (audit-safe)

---

### Step 6: Organization Activation

Once **all required forms are approved**, SACDEV can activate the organization for the target school year.

Activation will:

* Create **operational officer records** from approved B3
* Create **projects** from approved B1
* Link all records to the organization and target SY
* Preserve traceability back to the source submissions

After activation, the organization becomes operational for that school year.

---

## How to Test the System 

This section is for groupmates testing the program.

### A) SACDEV Admin 

1. Login as SACDEV admin
2. Create a new school year
3. Ensure there is an active school year

---

### B) Active SY President (Provisioning Step)

1. Login as the **current / active SY president**
2. Assign or provision the **next SY president**
3. Confirm the next SY president account exists

---

### C) Next SY President (Re-Registration)

1. Login as next SY president
2. Select the target school year
3. Assign a moderator for that SY
4. Fill out B1, B2, and B3
5. Submit forms

---

### D) Moderator

1. Login as moderator
2. Review B1
3. Complete B5
4. Forward to SACDEV

---

### E) SACDEV Admin (Review and Activation)

1. Review all submitted forms
2. Return forms with remarks if needed
3. Approve all required forms
4. Activate the organization

---

## Account Provisioning Notes

* Accounts are created **only when required**

  * President provisioning
  * Moderator assignment
  * Treasurer / project head assignment (post-activation)
* Temporary passwords are generated automatically

### Viewing Password Emails (Development)

Email sending is logged instead of sent.

Passwords and invites can be found in:

```
storage/logs/laravel.log
```

The project uses:

```
MAIL_MAILER=log
```

---

## Core Tables / Models

* `users`
* `school_years`
* `organizations`
* `organization_school_years`
* `org_memberships`
* `officer_entries`
* `projects`
* `strategic_plans`
* `notifications`

---

## Setup Instructions

```bash
composer install
npm install
npm run dev
```

```bash
cp .env.example .env
php artisan key:generate
```

```bash
php artisan migrate:fresh --seed
php artisan serve
```

