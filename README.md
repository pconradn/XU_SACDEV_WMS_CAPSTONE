## How to Run the Project (Local Setup)

### 1. Requirements

* PHP 8.1+
* Composer
* Node.js + npm
* MySQL / MariaDB
* Git

---

### 2. Clone Project

```bash
git clone <your-repo-url>
cd <project-folder>
```

---

### 3. Install Dependencies

```bash
composer install
npm install
```

---

### 4. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

Update `.env`:

```env
APP_NAME=SACDEV
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sacdev
DB_USERNAME=root
DB_PASSWORD=

QUEUE_CONNECTION=database

BROADCAST_CONNECTION=reverb

MAIL_MAILER=log
```

---

### 5. Database Setup

Create database:

```sql
CREATE DATABASE sacdev;
```

Run:

```bash
php artisan migrate:fresh --seed
```

Check seeders for default accounts and passwords.

---

### 6. Queue Setup

```bash
php artisan queue:table
php artisan migrate
php artisan queue:work
```

---

### 7. Reverb Setup (Realtime)

Install:

```bash
php artisan install:broadcasting
```

Run Reverb:

```bash
php artisan reverb:start
```

---

### 8. Frontend Build

```bash
npm run dev
```

For production:

```bash
npm run build
```

---

### 9. Run App

```bash
php artisan serve
```

---

### 10. Open App

```
http://127.0.0.1:8000
```

---

### 11. View Emails (Dev)

```
storage/logs/laravel.log
```

---

## Running Services (3 terminals)

Terminal 1:

```bash
php artisan serve
```

Terminal 2:

```bash
php artisan queue:work
```

Terminal 3:

```bash
php artisan reverb:start
```

Terminal 4:

```bash
npm run dev
```
