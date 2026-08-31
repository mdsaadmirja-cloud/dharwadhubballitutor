# LMS System

This is a Learning Management System (LMS) module for the DharwadHubballiTutor project.

## Features
- Student login via Google authentication
- Administrator login (local, not Google)
- Admin can add lessons and upload video links for completed lessons
- Students can view lessons and watch videos
- Tracks lesson completions

## Folder Structure
- `controller/` - Business logic
- `model/` - Database models
- `views/` - UI (login, dashboard, lessons, admin panel)
- `js/`, `css/`, `img/` - Assets
- `uploads/` - For any uploaded files
- `migrations/` - SQL migration scripts
- `Utilities/` - Helper functions

## Setup
1. Run the SQL in `migrations/001_create_tables.sql` to create the required tables.
2. Install Composer dependencies: `composer require google/apiclient`
3. Configure Google OAuth credentials in `config.php`.
4. Set up your web server to serve the `lms/` directory.

## Usage
- Students: Login with Google, view lessons, mark as completed.
- Admin: Login with username/password, add/edit lessons and videos. 