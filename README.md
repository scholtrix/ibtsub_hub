# Ibtsub Hub

A complete PHP MVC registration and management system for a physical training center.

## Features

- Public website: home, about, courses, contact, apply.
- Student portal: registration, dashboard, profile edit, Paystack payment integration.
- Admin portal: secure login, manage students, courses, registrations, site settings, Paystack keys.
- MySQL database with normalized tables, foreign keys, timestamps.
- Secure input validation, CSRF tokens, session authentication, prepared statements.

## Installation

1. Import the database schema:
   - `mysql -u root -p < database/schema.sql`

2. Configure database settings in `config/config.php`. The project is pre-configured for:
   - Database: `snhgnltn_hub`
   - Username: `snhgnltn_hub`
   - Password: `snhgnltn_hub`

3. Set your web server document root to `public/`.

4. Ensure `public/uploads/passports` is writable by PHP.

If your host cannot set `public/` as the document root, use the root `index.php` redirect and root `.htaccess` to forward requests into `public/`.

5. Access the site in your browser:
   - Public: `/public/index.php`
   - Admin: `/public/index.php?page=admin_login`

## Default admin account

The initial admin user is seeded in `database/schema.sql` for demonstration. Update the password hash before production.

## Paystack Integration

- Set `paystack_public_key` and `paystack_secret_key` from the admin settings page.
- Students can pay via Paystack on their dashboard once a registration exists.

## GitHub Actions deployment

This repository includes a GitHub Actions workflow at `.github/workflows/deploy.yml` that deploys the project to cPanel on every push to `main`.

### Required repository secrets

- `CPANEL_HOST` — your cPanel FTP hostname
- `CPANEL_USERNAME` — your FTP username
- `CPANEL_PASSWORD` — your FTP password

If your cPanel uses a different FTP port, update the workflow accordingly.

## Project structure

- `/app/controllers` — controller logic
- `/app/models` — database models
- `/app/views` — HTML templates
- `/config` — configuration and bootstrap
- `/public` — web entry point and uploads
- `/assets` — CSS, JS, images
