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

Update `.env` with your database credentials:

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
- Company jobs stats

### 📃 Public Job Listing
- `GET /api/v1/jobs/list` — List of published jobs (paginated)
- Filtering: `location`, `is_remote`, `keywords`

### ✍️ Job Application (Candidate Only)
- `POST /api/v1/jobs/{id}/apply`
    - Upload `resume` (file) and `cover_letter` (file)
    - One application per job per candidate
- Candidate applied job count
    - `Get /api/v1/candidate/jobs/stats`

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
├── Helpers/
│   ├── ApiResponse.php
│   └── helpers.php
├── Http/
│   ├── Controllers/
│   │   ├── Auth/
│   │   │   ├── CompanyAuthController.php
│   │   │   └── CandidateAuthController.php
│   │   ├── Job/
│   │   │   ├── CandidateJobController.php
│   │   │   └── JobController.php
│   └── Middlewares/
│       └── CheckIfJobApplied.php
│       └── EnsureCompanyOwnsJob.php
│   ├── Requests/
│   │   └── Auth/
│   │   │   ├── CompanyRegisterRequest.php
│   │   │   └── CompanyLoginRequest.php
│   │   │   ├── CandidateRegisterRequest.php
│   │   │   └── CandidateLoginRequest.php
│   │   └── Job/
│   │       ├── JobApplicationRequest.php
│   │       └── JobRequest.php
│   └── Resources/
│       └── JobResource.php
├── Jobs/
│   ├── ProcessCoverLetterUpload.php
│   └── ProcessResumeUpload.php
├── Models/
│   ├── Company.php
│   ├── Candidate.php
│   ├── JobApplication.php
│   └── JobPost.php
├── Services/
│   ├── Auth
│   │   ├── CandidateAuthService.php
│   │   └── CompanyAuthService.php
│   └── Job
│       ├── CandidateJobService.php
│       └── JobService.php
routes/
├── api.php
database/
├── migrations/
docs/
├── postman_collection.json
```

---

## 📐 Design Choices

#### Separation of Concerns

The application uses a layered architecture to separate responsibilities:

- **Controllers** handle HTTP input/output.
- **Services** encapsulate business logic for modularity and reusability.
- **Requests** handle validation.
- **Jobs** handle asynchronous processing for file uploads (resume, cover letter).

This makes the codebase easier to test, maintain, and scale.

#### Role-Based Guards
Laravel Passport is configured with multiple guards (`company` and `candidate`) to cleanly separate authentication logic for different user types. Each role has its own model, provider, and login flow, enabling clear boundaries in behavior and permissions.

#### Custom API Response Format
A standardized API response structure (`statusCode`, `success`, `message`, `data`) in `app/Helpers/ApiResponse.php` is used across the application for consistency and easier frontend integration.

#### Middleware & Authorization
Middleware is used to handle job application constraints (e.g., prevent duplicate applications, `app/Http/Middleware/CheckIfJobApplied.php`), and service-level authorization ensures companies can only manage their own job posts, `app/Http/Middleware/EnsureCompanyOwnsJob.php`.

#### Public Job Listing with Filtering & Caching
Public job listings support keyword, location, and remote filters. [Laravel Scout](https://laravel.com/docs/10.x/scout) is used with the [database driver](https://laravel.com/docs/10.x/scout#database-engine) for full-text search. Results are cached for **5 minutes** to reduce database load and improve performance.

#### Queues for File Processing
Resume and cover letter uploads are handled asynchronously using Laravel queues, improving user experience by offloading heavy operations from the request cycle.

## 🚀 Assumptions & Improvements

#### Assumptions

- Token is returned on registration. Candidate or company can be logged in once registered
- Jobs are published at the point of creation. (No moderation required)
- Job expiration not a criteria
- Files are stored locally, not best for production environment

#### Improvements

- **Cloud-Based File Storage**: Move resume and cover letter uploads from local disk storage to cloud services like AWS S3 for better scalability and availability.
- **Automated Testing**: Add PHPUnit test coverage for core functionality
- **Security Hardening**: Introduce throttling and stricter validation for high-risk endpoints (e.g., login, apply) to mitigate abuse.
 - **Data Redundancy Concern**: Although seperate models with dedicated guards offer better separation of concerns for **Company** and **Candidate** authentication it might introduce data redundancy. 
   - Shared fields like name, email, password, timestamps, and auth tokens can exist in a single table, distinguished by a `role` field. You can then create two `polymorphic profile` tables if needed
   - One Passport guard, one login/register system.
   - Centralized authentication, password reset, and token management.
   - Use a middleware like `CheckRole` to restrict routes
   - If needed, create interfaces or traits for `CompanyActions` and `CandidateActions`.
   - Future roles like admin can be added without duplicating logic.

**Note**: You will need to consider some trade-offs
- Yes, this will reduce duplication, but give room for more complex role checking in business logic
- Centralized user management, but might mix concerns if roles diverge too much over time
- Easier to extend with more roles, but profile-specific logic could clutter the `User` model

## 🧪 Testing

Run the following command to run test cases using Laravel's built-in PHPUnit:

```bash
php artisan test
```

---

## 📄 License

This project is open-source and available under the [MIT License](LICENSE).
