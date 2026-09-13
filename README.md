# Hardware Expert System

A web-based expert system for diagnosing computer hardware problems from user-selected symptoms. The application is built with native PHP and MySQL/MariaDB, uses role-based access for administrators and regular users, and can generate PDF reports with Dompdf.

This repository is intended as a learning, portfolio, and reusable reference project for a traditional PHP application with authentication, diagnosis rules, reporting, and basic application security hardening.

## Features

- User registration and authentication
- Automatic migration of legacy MD5 passwords to `password_hash()` after a successful login
- Role-based access control for `admin` and `pengguna`
- Dashboard for authenticated users
- Symptom management
- Hardware problem management
- Rule-base management
- Rule-based hardware diagnosis from selected symptoms
- Diagnosis history
- User-specific diagnosis history access
- User management for administrators
- Report generation
- PDF export with Dompdf
- CSRF protection for state-changing requests
- Prepared statements for database operations
- Session hardening
- Server-side authorization checks
- Output escaping to reduce XSS risk
- Apache `.htaccess` rules for sensitive application paths

## Tech Stack

- PHP
- MySQL / MariaDB
- `mysqli`
- HTML
- CSS
- JavaScript
- AdminLTE
- Composer
- Dompdf
- Apache-compatible `.htaccess`

## Requirements

Recommended environment:

- PHP 8.0 or newer
- MySQL or MariaDB
- Apache
- Composer

Required PHP extensions:

- `mysqli`
- `dom`
- `mbstring`

Dompdf can optionally benefit from image-related extensions such as GD.

## Installation

Clone the repository:

```bash
git clone https://github.com/AlfirgiawanRasikh/sistem-pakar-hardware.git
cd sistem-pakar-hardware
```

Install PHP dependencies:

```bash
composer install
```

## Database Setup

The application expects a MySQL or MariaDB database named:

```text
db_sistem_pakar_hardware
```

The repository intentionally does **not** include a real database dump because database dumps may contain user accounts, password hashes, diagnosis history, or other private data.

Core tables used by the application include:

- `pengguna`
- `gejala`
- `kerusakan`
- `aturan`
- `detail_aturan`
- `riwayat_diagnosa`

> **Important:** a sanitized database schema or seed is not included yet. You must prepare a compatible schema before running a fresh installation.
>
> If you publish your own SQL file, make sure it contains no real users, password hashes, diagnosis history, database credentials, or other sensitive information.

## Database Configuration

The application reads the following environment variables when they are available:

```text
DB_HOST
DB_PORT
DB_USER
DB_PASS
DB_NAME
```

See `.env.example` for the expected values.

Example configuration:

```text
DB_HOST=127.0.0.1
DB_PORT=3306
DB_USER=your_database_user
DB_PASS=your_database_password
DB_NAME=db_sistem_pakar_hardware
```

`.env.example` is documentation only.

The application does **not** automatically load a `.env` file. Configure these values through your PHP, Apache, server, or hosting environment.

When no environment variables are provided, the current local-development fallbacks are compatible with a common Laragon setup:

```text
Host: 127.0.0.1
Port: 3306
User: root
Password: empty
Database: db_sistem_pakar_hardware
```

Never commit real production database credentials to Git.

## Running Locally with Laragon

A simple Windows development setup:

1. Install Laragon.
2. Clone or place the project inside Laragon's `www` directory.
3. Start Apache and MySQL from Laragon.
4. Create the `db_sistem_pakar_hardware` database.
5. Prepare or import a compatible sanitized database schema.
6. Open a terminal in the project directory.
7. Install Composer dependencies:

```bash
composer install
```

8. Open the application through Laragon's local URL:

```text
http://sistem-pakar-hardware.test
```

Depending on your Laragon configuration, the project may also be accessible through a localhost path.

## Running with XAMPP

The project can also run with XAMPP.

1. Clone or copy the project into:

```text
C:\xampp\htdocs\
```

2. Start Apache and MySQL from XAMPP.
3. Create the database:

```text
db_sistem_pakar_hardware
```

4. Prepare or import the required database schema.
5. Install Composer dependencies:

```bash
composer install
```

6. Open the project in your browser through your localhost URL.

Apache is recommended because the repository contains `.htaccess` protection rules.

If you use Nginx or another web server, configure equivalent access restrictions manually.

## User Roles

The application currently uses two roles.

### Admin

Administrators can access:

- Dashboard
- Symptom management
- Hardware problem management
- Rule-base management
- User management
- Reports
- PDF reports
- Diagnosis
- Diagnosis history

### Regular User

Users with the `pengguna` role can access:

- Dashboard
- Hardware diagnosis
- Their own diagnosis history

Admin-only pages and controllers are protected on the server side.

Hiding a menu item is **not** used as the only authorization mechanism.

## Creating an Admin for Local Development

The repository does not ship with default administrator credentials.

For local development, register a normal account first.

Then update the role directly in your own local database.

Example:

```sql
UPDATE pengguna
SET role = 'admin'
WHERE username = 'your_username';
```

Do this only on a database you control.

Do not publish real administrator credentials in the repository.

## Authentication

New accounts use PHP's built-in password hashing:

```php
password_hash()
```

Login verification uses:

```php
password_verify()
```

Older installations may still contain MD5 password hashes.

For migration purposes, the application temporarily supports legacy MD5 authentication.

When a legacy account successfully logs in, the application automatically replaces the old MD5 hash with a modern `password_hash()` value.

This allows existing users to migrate without requiring an immediate manual password reset.

## Security

The application includes several security improvements.

### Password Security

- New passwords use `password_hash()`
- Login uses `password_verify()`
- Legacy MD5 hashes are only supported temporarily for migration
- Legacy passwords are automatically upgraded after successful authentication

### SQL Injection Protection

Database operations handling user-controlled values use prepared statements with:

```php
mysqli_prepare()
mysqli_stmt_bind_param()
mysqli_stmt_execute()
```

### CSRF Protection

State-changing operations use POST requests and CSRF tokens.

CSRF tokens are generated using:

```php
random_bytes()
```

and compared using:

```php
hash_equals()
```

### Authorization

The application performs server-side authorization checks.

Admin-only controllers require an administrator role.

Regular users cannot gain access simply by manually entering an administrator URL.

### Session Security

The application includes:

- Session ID regeneration after login
- `HttpOnly` session cookies
- `SameSite=Lax`
- Secure cookies when HTTPS is enabled
- Server-side session validation

### XSS Protection

Untrusted user and database values are escaped before being rendered into HTML where appropriate.

The application uses `htmlspecialchars()` with UTF-8 encoding.

### Protected Application Files

Apache `.htaccess` rules restrict access to sensitive application files and directories.

Examples include:

- Configuration directories
- Helper directories
- Models
- Vendor files
- SQL files
- Log files
- Environment files
- Backup archives

For production deployments, also enable HTTPS, use strong database credentials, and keep PHP and dependencies updated.

## Diagnosis Flow

The general diagnosis process is:

```text
User selects symptoms
        ↓
Selected symptom IDs are validated
        ↓
Application evaluates the rule base
        ↓
Matching rule is identified
        ↓
Hardware problem is determined
        ↓
Recommended solution is displayed
        ↓
Diagnosis is stored in history
```

The application's diagnosis system is based on stored relationships between:

```text
gejala
   ↓
detail_aturan
   ↓
aturan
   ↓
kerusakan
```

## PDF Reports

PDF generation is provided by Dompdf.

Install dependencies with:

```bash
composer install
```

For production environments:

```bash
composer install --no-dev --optimize-autoloader
```

Reports are generated through the application's reporting functionality and protected by administrator authorization.

## Project Structure

```text
sistem-pakar-hardware/
├── adminlte/
│   └── AdminLTE frontend assets
│
├── assets/
│   └── Application assets
│
├── config/
│   ├── auth.php
│   └── database.php
│
├── controllers/
│   ├── AturanController.php
│   ├── DashboardController.php
│   ├── DiagnosaController.php
│   ├── GejalaController.php
│   ├── KerusakanController.php
│   ├── LaporanController.php
│   ├── LoginController.php
│   ├── PenggunaController.php
│   ├── RegisterController.php
│   └── RiwayatController.php
│
├── helpers/
│   ├── auth.php
│   ├── csrf.php
│   ├── fungsi.php
│   ├── forward_chaining.php
│   └── pdf_helper.php
│
├── models/
│   └── Application models
│
├── routes/
│   └── Application routes
│
├── views/
│   ├── aturan/
│   ├── auth/
│   ├── dashboard/
│   ├── diagnosa/
│   ├── gejala/
│   ├── kerusakan/
│   ├── laporan/
│   ├── layouts/
│   ├── pengguna/
│   └── riwayat/
│
├── vendor/
│   └── Composer dependencies
│
├── .env.example
├── .gitignore
├── .htaccess
├── composer.json
├── composer.lock
├── index.php
├── laporan_pdf.php
├── logout.php
└── register.php
```

## Important Files

### `index.php`

Main application entry point and page router.

### `config/database.php`

Handles the MySQL/MariaDB connection and environment-based database configuration.

### `helpers/auth.php`

Handles authentication and role authorization.

### `helpers/csrf.php`

Provides CSRF token generation and validation.

### `controllers/LoginController.php`

Handles authentication, password verification, and legacy password migration.

### `controllers/DiagnosaController.php`

Processes symptom selections and stores diagnosis results.

### `laporan_pdf.php`

Generates administrator PDF reports using Dompdf.

## Production Deployment

The application can be deployed to traditional PHP hosting that supports:

- PHP
- MySQL or MariaDB
- Apache
- Composer dependencies
- Sessions
- `.htaccess`

Suitable deployment environments include:

- Shared hosting
- cPanel hosting
- VPS
- Apache-based PHP servers
- Local company servers

Serverless platforms that do not natively support traditional PHP applications may require additional adaptation.

## Deployment Checklist

Before deploying publicly:

1. Use a supported and maintained PHP version.
2. Create a dedicated production database.
3. Create a dedicated production database user.
4. Use a strong database password.
5. Configure database credentials through server environment variables.
6. Install Composer dependencies.
7. Enable HTTPS.
8. Verify Apache `.htaccess` rules.
9. Verify login and logout.
10. Verify registration.
11. Verify administrator authorization.
12. Verify regular-user authorization.
13. Test symptom CRUD operations.
14. Test hardware problem CRUD operations.
15. Test rule-base CRUD operations.
16. Test user management.
17. Test diagnosis.
18. Test diagnosis history.
19. Test reports.
20. Test PDF generation.
21. Ensure normal users cannot access administrator controllers.
22. Ensure no `.env`, SQL dump, backup archive, or production credentials are publicly accessible.

## Sensitive Files

Do not commit files containing real secrets or production data.

Examples:

```text
.env
*.sql
*.log
*.bak
*.zip
*.tar
*.gz
```

The repository's `.gitignore` is configured to exclude sensitive environment and SQL files.

Before pushing changes to a public repository, always check:

```bash
git status
```

and inspect your staged changes:

```bash
git diff --cached
```

## Updating Dependencies

Install dependencies from the lock file:

```bash
composer install
```

Inspect outdated dependencies:

```bash
composer outdated
```

Update dependencies carefully:

```bash
composer update
```

After updating dependencies, test the application before committing changes.

## Development Workflow

Create a new branch for changes:

```bash
git checkout -b feature/your-change
```

Make and test your changes.

Check the working tree:

```bash
git status
```

Commit the change:

```bash
git add .
git commit -m "feat: describe your change"
```

Push the branch:

```bash
git push -u origin feature/your-change
```

Then open a pull request into `main`.

## Contributing

Contributions are welcome.

When submitting changes:

- Keep changes focused
- Avoid unrelated refactors
- Do not commit credentials
- Do not commit database dumps containing real data
- Use prepared statements for user-controlled database values
- Protect state-changing actions with CSRF tokens
- Preserve server-side role authorization
- Test authentication and authorization changes
- Test diagnosis logic after modifying rules

For security-sensitive changes, verify:

- Authentication
- Authorization
- CSRF handling
- SQL queries
- Session behavior
- Role restrictions
- Output escaping

before opening a pull request.

## Known Limitation

A sanitized database schema or seed is not currently included in this repository.

This means a completely new installation still requires a compatible database schema to be prepared manually.

A future improvement is to provide something such as:

```text
database/
├── schema.sql
└── seed.example.sql
```

containing only safe, sanitized sample data.

## Roadmap

Potential future improvements include:

- Sanitized database schema and seed data
- Automated installation script
- Automated tests
- GitHub Actions for PHP syntax checking
- Improved diagnosis algorithm test coverage
- Password reset functionality
- User profile management
- Improved audit logging
- Better deployment configuration
- Docker-based development environment
- Additional hardware diagnosis rules

## License

A project license has not been added yet.

If this repository is intended to be freely reused, modified, and redistributed by other people, consider adding an open-source license such as the MIT License.

Without an explicit license, normal copyright rules apply even if the source code is publicly visible.

## Author

Developed and maintained by:

**Alfirgiawan Rasikh**

GitHub:

https://github.com/AlfirgiawanRasikh

## Repository

https://github.com/AlfirgiawanRasikh/sistem-pakar-hardware