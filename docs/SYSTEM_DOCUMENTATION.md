**System Documentation — CBE Student System**

**Prepared:** 2026-07-15

**Contents**
- Problem Background
- Objectives
- Requirements Analysis
- Database Design (schema + import)
- Major Interfaces (files + screenshot placeholders)
- Testing Evidence & Verification Steps
- Challenges Encountered (and how they were resolved)
- Recommendations & Next Steps

**Problem Background**
Educational institutions need a lightweight student information system to manage student records, course catalogs, enrollments, and fee payments. This project aims to provide a web-based system that runs on a standard LAMP/XAMPP stack and supports administrative workflows (students, courses, enrollments, fees).

**Objectives**
- Provide CRUD for students, courses, enrollments, fees, and payments.
- Offer an admin dashboard with key metrics and recent activity.
- Secure login and role-based access (admin/user).
- Simple, responsive UI suitable for desktop and mobile.

**Requirements Analysis**
- Functional Requirements
  - User authentication and role management.
  - Manage students: add/edit/delete and view profiles.
  - Manage courses and fee structures per semester/year.
  - Enroll students in courses and record fee payments.
  - Generate payment reports and compute balances.
- Non-functional Requirements
  - Runs on PHP 8+, MySQL, and uses PDO for DB access.
  - Minimal dependencies; assets served from `public/assets`.
  - Responsive layout (CSS in `public/assets/css/main.css`).

**Database Design**
The schema file is `database.sql` in the project root. Key tables:

- `users` — store user accounts (id, username, email, password, created_at)
- `roles` and `user_roles` — simple role mapping (admin/user)
- `students` — student profile details and enrollment date
- `courses` — course_code, course_name, credits
- `enrollments` — student/course relationships with semester and academic_year
- `fee_structures` — fee amount per course/semester/year
- `fee_payments` — payments recorded (student_id, fee_structure_id, amount_paid, receipt_number)
- `settings` — key/value store used for system_name and other metadata
- `activity_logs` — actions performed for audit

Schema file: [database.sql](database.sql)

Quick import (MySQL):

```bash
# create database (if not exists) and import
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS school_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -p school_db < database.sql
```

Notes: Default DB credentials are in `config/database.php` (host: `localhost`, db: `school_db`, user: `root`, empty password). Adjust as needed.

**Major Interfaces**
(Reference files shown with workspace paths.)

- Public landing page: [public/index.php](public/index.php)
- Authentication and header: [includes/header.php](includes/header.php)
- Dashboard: [public/dashboard.php](public/dashboard.php)
- Students module: [public/modules/students](public/modules/students)
- Courses module: [public/modules/courses](public/modules/courses)
- Enrollments module: [public/modules/enrollments](public/modules/enrollments)
- Fees module: [public/modules/fees](public/modules/fees)
- API endpoints: [public/api](public/api)

Screenshots — placeholders

Add screenshots to `docs/images/` and use these markdown references (replace placeholders with actual images after capture):

- Landing page: ![Landing page](images/landing-page.png)
- Dashboard: ![Dashboard](images/dashboard.png)
- Students list/profile: ![Student profile](images/student-profile.png)
- Fee structures/report: ![Fees](images/fees.png)

How to capture screenshots (examples):
- Desktop: open `http://localhost/studentsystem/public/` in browser and use OS screenshot tool to save to `docs/images/`.
- Headless (Windows with PowerShell + Chromium):

```powershell
# Example using chrome in headless mode (adjust path)
"C:\Program Files\Google\Chrome\Application\chrome.exe" --headless --screenshot="docs/images/dashboard.png" --window-size=1200,900 "http://localhost/studentsystem/public/dashboard.php"
```

**Testing Evidence & Verification Steps**
- Static checks: No editor diagnostics were reported by the workspace analysis.
- Database import verification queries (run after import):

```sql
SELECT COUNT(*) FROM users;
SELECT COUNT(*) FROM students;
SELECT SUM(amount_paid) FROM fee_payments;
```

- Application checks completed during development
  - Verified login flow and session handling via `config/auth.php`.
  - Fixed header redirect issue where `header()` was called after output in `public/modules/fees/payments.php` by moving POST handling before including `includes/header.php`.
  - Confirmed currency labels updated from `$` to `TZS` across these files:
    - [public/modules/fees/structures.php](public/modules/fees/structures.php)
    - [public/modules/fees/payments.php](public/modules/fees/payments.php)
    - [public/modules/fees/reports.php](public/modules/fees/reports.php)
    - [public/modules/students/view.php](public/modules/students/view.php)
    - [public/dashboard.php](public/dashboard.php)

Example test case: Record a payment and verify it appears in Recent Payments
after redirect to Reports.

**Challenges Encountered**
- Header already sent error (fixed): caused by including `includes/header.php` before POST redirect. Resolved by processing POST data and redirecting before including header.
- Inconsistent path linking for mobile/smart deployment: some module links used relative paths (e.g., `logout.php`) which break when the app is hosted under a subpath. Resolved by using `BASE_URL` in `logout.php` and other places where necessary.
- Duplicate fee structures: added `structureExists()` in `includes/classes/Fee.php` to prevent duplicate fee structure insertions and provide friendly errors.

**Recommendations & Next Steps**
- Add automated browser tests (e.g., Playwright) to capture screenshots and verify flows (login, add student, enroll, payment).
- Centralize currency formatting (helper function) so switching currencies is single-point change.
- Add unit and integration tests for key class methods (Student, Course, Enrollment, Fee).
- Secure database credentials and move sensitive config to environment variables.
- Add input validation and CSRF protection on forms.

**Appendix**
- Key files:
  - [config/database.php](config/database.php)
  - [config/theme.php](config/theme.php)
  - [includes/classes/Student.php](includes/classes/Student.php)
  - [includes/classes/Course.php](includes/classes/Course.php)
  - [includes/classes/Fee.php](includes/classes/Fee.php)

---

For next steps I can:
- Populate `docs/images/` with live screenshots (I can run headless captures if you want and provide the images),
- Generate a PDF export of this documentation,
- Or extend the doc with a deployment checklist and backup/restore steps.

Please tell me which next step you prefer.