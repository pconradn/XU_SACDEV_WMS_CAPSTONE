
## How to Run the Project (Local Setup)

### 1. Requirements

Make sure you have the following installed:

* PHP 8.1+
* Composer
* Node.js + npm
* MySQL / MariaDB

---

### 2. Install PHP Dependencies

From the project root:

```bash
composer install
```

---

### 3. Install Frontend Dependencies

```bash
npm install
npm run dev
```

Use `npm run dev` while developing.
`npm run build` is only needed for production.

---

### 4. Environment Setup


Generate the application key:

```bash
php artisan key:generate
```

Edit `.env` and update your database credentials:

```env
DB_DATABASE=sacdev
DB_USERNAME=root
DB_PASSWORD=
```

---
## Database Migration and Seeding

### 5. Run Migrations and Seeders

This will reset the database and load demo data.

```bash
php artisan migrate:fresh --seed
```
---

## Running the Application

### 6. Start the Development Server

```bash
php artisan serve
```

---

## Quick Test Guide (Recommended Order)



---

### A) Login as SACDEV Admin

1. Login using the admin account from the seeder
2. Check **School Years**
3. Ensure there is an **active school year**
4. Create a new school year for testing re-registration

---

### B) Login as Active School Year President

1. Login as a president account
2. Change password if prompted
3. Assign the **next school year president** 


---

### C) Login as Next School Year President

1. Login as the newly provisioned next SY president
2. Select the **target school year**
3. Assign a **moderator** in the Reregistraion Hub
4. Fill out re-registration forms:

   * B1 – Strategic Plan
   * B2 – President Registration
   * B3 – Officers List
5. Submit the forms

---

### D) Login as Moderator


1. Login as moderator
2. Review **B1**
3. Complete **B5**
4. Forward submissions to SACDEV

---

### E) SACDEV Review and Activation


2. Login as SACDEV admin
3. Review B1, B2, B3, and B5
4. Approve or return forms as needed
5. Once all are approved, **activate the organization**

After activation:

* Officers and projects are created for the target school year
* The organization becomes operational

---

## Viewing Temporary Password Emails (Development)

The system uses logged emails instead of real sending.

To view temporary passwords and invites:

```
storage/logs/laravel.log
```

This is controlled by:

```env
MAIL_MAILER=log
```

---
