# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Turkish Course Schedule Management System for academic institutions. Manages course schedules, faculty assignments, classroom allocations, and conflict detection for university programs.

**Stack:**
- PHP 8.2+ with PDO
- MySQL/MariaDB (XAMPP)
- Bootstrap 5, vanilla JavaScript
- Custom MVC architecture with dependency injection

## MVC Architecture

**Entry Point:**
- `index.php` → `routes.php` → `bootstrap.php`
- All requests flow through the routing system
- Bootstrap initializes autoloader, database, container, and services

**Core Components:**
- `core/Router.php` - Request routing with GET/POST support
- `core/Controller.php` - Base controller with view rendering, auth, and permissions
- `core/Container.php` - Dependency injection container with singleton support

**Directory Structure:**
- `app/Controllers/` - Request handlers (HomeController, AuthController, DashboardController)
- `app/Services/` - Business logic (StatisticsService, ProgramService, AcademicTermService)
- `app/Repositories/` - Data access layer with BaseRepository pattern
- `app/Views/` - View templates with layouts/
- `app/Models/` - Data models
- `app/Middleware/` - Authentication and authorization
- `config/` - Configuration files (database, auth, environment)

**Key Design Patterns:**
- Repository pattern for database access
- Service layer for business logic
- Container for dependency injection (bootstrap.php:48-66)
- View-data extraction pattern (Controller::view method)

## Configuration & Environment

**Environment Management (config/environment.php):**
- Three environments: development, production, staging
- Environment detection via `$_ENV['APP_ENV']` or defaults to 'development'
- Use `getConfig($key)` to access environment-specific settings
- Production server: 46.182.69.9 (LAN-based, no SSL required)

**Database (config/database.php):**
- PDO with prepared statements
- Charset: utf8mb4_turkish_ci
- Timezone: Europe/Istanbul
- Helper functions: `clean()`, `checkConflicts()`, `getAvailableClassrooms()`

**Google OAuth (config/google-oauth.php):**
- OAuth 2.0 authentication flow
- Redirect URIs configured per environment
- Setup instructions in setup-google-oauth.md

## Database Schema

**Core Tables:**
- `admin_users` - User accounts with role-based access (super_admin, admin, instructor, guest)
- `admin_permissions` - Granular CRUD permissions per role
- `programlar` - Academic programs (2-year associate degrees)
- `ogretim_elemanlari` - Faculty with color coding for schedule visualization
- `derslikler` - Classrooms (capacity, type, status)
- `dersler` - Courses (credit hours, requirements, year/semester)
- `akademik_donemler` - Academic terms with active status
- `ders_atamalari` - Course-faculty-classroom assignments
- `haftalik_program` - Weekly schedule entries (day, time slots, conflicts)
- `cakisma_loglari` - Conflict detection audit log

**Import Latest Schema:**
```bash
mysql -u root -p ders_programi < database/final-database-setup.sql
```

## Development Commands

**Start Development:**
1. Ensure XAMPP is running (Apache + MySQL)
2. Navigate to `http://localhost/ders_programi/`
3. Debug mode enabled in development environment

**Database Access:**
- phpMyAdmin: `http://localhost/phpmyadmin`
- Database: `ders_programi`
- User: `root` (no password in development)

**Composer:**
```bash
composer require package-name     # Add dependency
composer require --dev package    # Add dev dependency
composer dump-autoload            # Regenerate autoloader
```

**Code Quality:**
```bash
./vendor/bin/phpstan analyse app/ core/ --level=5   # Static analysis
php -l file.php                                     # Syntax check
```

## Routing System

**Adding Routes (routes.php):**
```php
$router->get('/path', [ControllerClass::class, 'method']);
$router->post('/path', [ControllerClass::class, 'method']);
$router->get('/api/endpoint', function() use ($container) {
    // Inline route handlers
});
```

**URL Structure:**
- Base path handling: `/ders_programi/` is stripped automatically
- Query parameters: `?debug=1` shows routing debug info in development
- Redirects: Use `$this->redirect('/path')` in controllers

## Authentication & Permissions

**Session-Based Auth:**
- Login flow: `AuthController::login()` → sets `$_SESSION['admin_logged_in']`, `$_SESSION['admin_user_id']`
- Google OAuth: `AuthController::googleAuth()` → `googleCallback()`
- Check auth: `$this->requireAuth()` in controllers

**Role Hierarchy:**
- `super_admin` - Full system access
- `admin` - Limited administrative access (check permissions table)
- `instructor` - Faculty member access
- `guest` - Read-only access

**Permission Checks:**
```php
$this->requirePermission('programs', 'write');  // Enforces permission or 403
$this->hasPermission('schedules', 'delete');    // Returns boolean
```

## Schedule Conflict Detection

**Conflict Logic (config/database.php:90-107):**
- Faculty double-booking: Same instructor, overlapping time slots
- Classroom overlap: Same room, overlapping time slots
- Time slot precision: 30-minute intervals from 08:30 to 18:00
- Lunch break: 12:30-14:00 (90-minute block)

**Available Classrooms (config/database.php:109-128):**
- Queries rooms not in use during specified time range
- Orders by capacity (largest first)
- Filters inactive classrooms

**Teacher Colors (config/database.php:131-144):**
- Each faculty has unique color + pattern (dots/stripes)
- Used for visual schedule differentiation
- Colors: hex codes, patterns: 'dots' or 'stripes'

## Key Features

**Schedule Management:**
- Interactive drag-and-drop interface (drag-drop-schedule-editor.html)
- Real-time conflict detection before save
- Faculty workload tracking (default: 20 hours/week)
- Course assignment form (course-assignment-form.html)

**Academic Structure:**
- 2-year programs with 1st/2nd year separation
- Semester-based terms with active/inactive status
- Turkish/English dual naming support
- Classroom type classification (lecture, lab, seminar)

**Service Layer Architecture:**
- `StatisticsService` - Dashboard metrics and system stats
- `ProgramService` - Active program queries and filtering
- `AcademicTermService` - Current term management

## Security

- PDO prepared statements (no SQL injection)
- CSRF tokens: `generateCSRFToken()`, `verifyCSRFToken()` (config/database.php:70-82)
- Input sanitization: `clean()` function (htmlspecialchars + trim)
- Session management: Secure session start in bootstrap
- Password hashing: Use `password_hash()` and `password_verify()`
- Permission middleware: Controller-level authorization checks

## Migration Notes

The system was migrated from monolithic PHP files to MVC architecture. Legacy HTML files exist for reference:
- `ders-programi-database.html`
- `course-assignment-form.html`
- `drag-drop-schedule-editor.html`
- `weekly-schedule-templates.html`

Refer to `MVC-MIGRATION-PLAN.md` for migration strategy and progress tracking.