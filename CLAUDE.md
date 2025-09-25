# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a **Turkish Course Schedule Management System** built for academic institutions. The system manages course schedules, faculty assignments, classroom allocations, and conflict detection for university programs.

**Technology Stack:**
- **Backend**: PHP with PDO (MySQL)
- **Database**: MySQL/MariaDB (XAMPP)
- **Frontend**: HTML5, Bootstrap 5, JavaScript (Vanilla)
- **Architecture**: MVC-style separation with dedicated config files

## Database Architecture

**Core Tables:**
- `programlar` - Academic programs (associates degree programs)
- `ogretim_elemanlari` - Faculty/teaching staff with color coding for schedule visualization
- `derslikler` - Classrooms with capacity and type classification
- `dersler` - Courses with credit hours and requirements
- `akademik_donemler` - Academic terms/semesters
- `ders_atamalari` - Course assignments linking courses to faculty and classrooms
- `haftalik_program` - Weekly schedule grid (main schedule data)
- `cakisma_loglari` - Conflict detection logs

**Key Relationships:**
- Programs have multiple courses by class year (1st/2nd year)
- Faculty can teach multiple courses with weekly hour limits
- Conflicts are automatically detected for faculty double-booking and classroom overlaps
- Schedule visualization uses color-coded faculty assignments

## File Structure

**Main Files:**
- `index.php` - Dashboard with statistics and quick program views
- `admin-panel.php` - Administrative interface (requires login)
- `database-config.php` - Database connection and utility functions

**HTML Interfaces:**
- `course-assignment-form.html` - Course assignment interface
- `drag-drop-schedule-editor.html` - Interactive schedule editing
- `weekly-schedule-templates.html` - Schedule template system

**Database:**
- `database/course-schedule-database.sql` - Complete database schema

**Templates Directory:**
- Contains academic data templates (faculty lists, program names, classroom data)
- Time slot configurations (8:30 start, 9:00 start, 30-minute intervals)

## Development Environment

**XAMPP Setup:**
- Place project in `C:\xampp\htdocs\ders_programi\`
- Import `database/course-schedule-database.sql` into MySQL
- Default database connection: `localhost`, user: `root`, no password
- Database name: `ders_programi`

**Configuration:**
- Database settings in `database-config.php:12-16`
- Turkish timezone: Europe/Istanbul
- Character encoding: UTF-8 (utf8mb4_turkish_ci)

## Common Development Tasks

**Database Operations:**
- Import schema: `mysql -u root -p ders_programi < database/course-schedule-database.sql`
- Access database: Connect to `ders_programi` database via phpMyAdmin or MySQL client

**Testing Schedule Conflicts:**
- Use `checkConflicts()` function in database-config.php:81-98
- Test faculty double-booking and classroom overlap scenarios
- Verify time slot calculations (30-minute intervals from 08:30 to 18:00)

**Faculty Color System:**
- Each faculty member has assigned colors and patterns for schedule visualization
- Colors defined in `database-config.php:122-135`
- Pattern types: 'dots' or 'stripes' for visual differentiation

## Key Features

**Schedule Management:**
- Drag-and-drop schedule editing interface
- Real-time conflict detection
- Faculty workload tracking (default limit: 20 hours/week)
- Multi-format export capabilities

**Academic Structure:**
- Support for 2-year associate degree programs
- Class-based course organization (1st/2nd year)
- Semester-based academic terms
- Turkish/English dual naming for international programs

**Time Management:**
- 30-minute time slots from 08:30-18:00
- Lunch break handling (12:30-14:00)
- Flexible classroom and faculty scheduling

## Security Notes

- Simple session-based admin authentication
- CSRF token implementation for forms
- Input sanitization via `clean()` function
- PDO prepared statements for database queries