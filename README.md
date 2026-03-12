
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


### 1. Login as SACDEV Admin

1. Login as the SACDEV admin.
2. Create a new school year.
3. Set the new school year as **Active**.

---

### 2. Assign Organization President

4. Assign a **President** to an organization for the active school year.

---

### 3. Login as Organization President

5. Login using the president account.
6. Change the temporary password if prompted.
7. Select the **school year context** for the active school year.

---

### 4. Complete Re-Registration Setup

8. Open the **Re-Registration Hub**.
9. Assign a **Moderator** from the officer list.

---

### 5. Fill Out Re-Registration Forms

10. Complete the **Strategic Plan**.
11. Complete the **President Registration**.
12. Complete the **Officers List**.
13. Upload the **Organization Constitution**.
14. Submit all completed forms.

---

### 6. Moderator Review

15. Login as the **Moderator**.
16. Review the submitted forms.
17. Approve or return forms if necessary.
18. Complete the **Moderator Submission Form**.
19. Submit the moderator form.

---

### 7. SACDEV Review

20. Login again as **SACDEV Admin**.
21. Review all submitted requirements.
22. Approve the forms if complete.

---

### 8. Register Organization

23. Register the organization for the selected school year.

---

### 9. Assign Project Head

24. Login again as the **Organization President**.
25. Assign a **Project Head** from the officer list.

---

### 10. Project Implementation Workflow

26. Login as the **Project Head**.
27. Create or open a project.
28. Fill out the required **project implementation forms**.
29. Submit the forms for approval.

---

### 11. Document Approval Workflow

30. Approvals proceed in the following order:

Project Head → Treasurer → President → Moderator → SACDEV Admin

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
