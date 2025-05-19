# 📘 Job Board API

A RESTful API for a Job Board platform where **Companies** can post jobs and **Candidates** can apply. Built with Laravel 10+ and MySQL, using Passport for API authentication.

---

## 🛠 Tech Stack

- **Framework**: Laravel 10+
- **Database**: MySQL
- **Authentication**: Laravel Passport (API Token-Based)
- **Queue**: Laravel Queue (e.g. database/redis)
- **Tools**: Composer, Postman (for documentation)

---

## 📦 Installation & Setup

### 1. Clone the Repository

```bash
git clone https://github.com/ifeekz/job-board-api.git
cd job-board-api
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Configure Environment

```bash
cp .env.example .env
php artisan key:generate
```

Update `.env` with your database and mail credentials:

```env
DB_DATABASE=job_board
DB_USERNAME=root
DB_PASSWORD=yourpassword
```

### 4. Run Migrations

```bash
php artisan migrate
```

### 5. Install Laravel Passport

```bash
php artisan passport:install
```

### 6. Serve the App

```bash
php artisan serve
```

The API will be available at `http://localhost:8000`

---

## 📌 Features

### 🔐 Authentication (via Passport)
- Separate registration/login for **Company** and **Candidate**
- Token-based authentication using Laravel Passport

### 🧱 Job Management (Company Only)
- Create, update, soft-delete jobs
- View own jobs
- Job Fields: `title`, `description`, `location`, `salary_range`, `is_remote`, `published_at`

### 📃 Public Job Listing
- `GET /api/jobs` — List of published jobs (paginated)
- Filtering: `location`, `is_remote`, `keywords`

### ✍️ Job Application (Candidate Only)
- `POST /api/jobs/{id}/apply`
- Upload `resume` (file) and `cover_letter` (file)
- One application per job per candidate

### 🚀 Queueing
- Resume/cover letter uploads processed via Laravel Queues

### 📖 API Documentation
- Available as a Postman Collection (to be included in `/docs/postman_collection.json`)

---

## 🚀 Running Queues

Use the database driver (or configure redis):

```bash
php artisan queue:work
```

---

## 📂 File Structure Overview

```
app/
├── Models/
│   ├── Company.php
│   ├── Candidate.php
│   └── Job.php
routes/
├── api.php
database/
├── migrations/
docs/
├── postman_collection.json
```

---

## 🧪 Testing

Run the following command to run test cases using Laravel's built-in PHPUnit:

```bash
php artisan test
```

---

## 📄 License

This project is open-source and available under the [MIT License](LICENSE).
