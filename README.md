# PoleDB / PolePosition

## Overview

PoleDB is a web application built on CodeIgniter 4 for managing telecommunication poles and related infrastructure assets.
It provides authenticated user access, role-based menu privileges, a dashboard with analytics, and CRUD workflows for:

- districts and regions
- poles and pole types
- infrastructure elements (poles, manholes, buildings, OLTEs)
- media/carry types and capacities
- leasor management
- user and role administration

The application is designed for use in a network operations or field asset management context.

## Key Features

- secure login using PF number and password
- forced password change flow
- session-based access control
- dashboard graphs for poles by region, size, and status
- district management
- pole registration, update, deletion, and type management
- infrastructure asset tracking with dependency-aware deletion
- media and carry type/capacity management
- user and role management with menu rights
- element detail view with upstream/downstream linkage

## Requirements

- PHP 7.4 or higher
- CodeIgniter 4 framework
- Composer
- MySQL or another supported database
- PHP extensions:
  - intl
  - mbstring
  - json
  - mysqlnd
  - curl

> Note: The app's web root is the `public/` folder. Configure your web server or development server to serve from `public/`.

## Installation

1. Clone the repository into your web root:

   ```bash
   git clone <repo-url> poleDB
   cd poleDB
   ```

2. Install PHP dependencies:

   ```bash
   composer install
   ```

3. Copy the environment file and customize it:

   ```bash
   copy env .env
   ```

4. Edit `.env` and set at minimum:

   - `app.baseURL`
   - database connection details (`database.default.hostname`, `database.default.database`, `database.default.username`, `database.default.password`)

5. Ensure the following directories are writable by the web server:

   - `writable/`
   - `writable/cache/`
   - `writable/logs/`
   - `writable/session/`
   - `writable/uploads/`

6. If you have database schema SQL available, import it into your database.

   This repository does not include automated schema migration scripts for the application data, so populate the database from your existing SQL dump or manual schema setup.

## Running Locally

Option 1: Use CodeIgniter's built-in development server:

```bash
php spark serve
```

Then open the URL shown in your browser.

Option 2: Use Apache / Nginx and point the virtual host document root to:

```text
/path/to/poleDB/public
```

## Application Usage

### Authentication

- Login page: `/`
- Login endpoint: `/pole-position/login`
- Logout endpoint: `/pole-position/logout`
- Change password page: `/administration/change-pass`
- Self-service password update: `/user/save-new-password`

### Main pages

- Home: `/home`
- Dashboard: `/dashboard`
- District management: `/districts`
- Pole management: `/poles`
- Infrastructure management: `/infrastructure`
- User administration: `/administration/usr-admin`
- Role administration: `/administration/usr-roles`
- Leasor management: `/infrastructure/leasor-management`

### Pole workflows

- Add or edit a pole: POST `/pole-management/store`
- Delete a pole: POST `/pole-management/delete`
- List pole types: GET `/pole-management/pole-types`
- Save pole type: POST `/pole-management/save-pole-type`
- Delete pole type: POST `/pole-management/delete-pole-type`

### Infrastructure workflows

- View infrastructure: `/infrastructure`
- Add or update element: POST `/infrastructure/save`
- Delete infrastructure element: POST `/infrastructure/delete`
- View element details: `/infrastructure/element-details/{id}`
- List media types: GET `/infrastructure/media-types`
- List media capacities: GET `/infrastructure/media-capacities`
- Save media details: POST `/infrastructure/save-media`
- Delete media type: POST `/infrastructure/delete-media-type`
- Delete media capacity: POST `/infrastructure/delete-media-capacity`

### Administration

- Create or update users: POST `/administration/usr-admin`
- Reset password: POST `/administration/reset-pwd`
- Save new password: POST `/administration/save-new-pwd`
- Fetch rights menus: GET `/administration/fetch-rights`
- Save role rights: POST `/administration/save-role-rights`

## Project Structure

- `app/Controllers/` — application controllers for auth, home, poles, infrastructure, users, leases
- `app/Models/` — models interacting with tables such as poles, districts, infra elements, users, roles, and rights
- `app/Views/` — UI templates and forms used by the app
- `public/` — web root and front controller
- `writable/` — runtime files, uploads, cache, logs, sessions
- `vendor/` — Composer dependencies

## Testing

Run PHPUnit tests with:

```bash
composer test
```

## Notes

- The app uses a soft-delete strategy for poles and infrastructure elements, marking deleted records before actual deletion.
- The project does not ship with automated database migration content for app-specific tables; prepare or import your schema manually.
- Update `public/index.php` only if you need to change bootstrap behavior, but keep the web root pointed at `public/`.

## License

This project is licensed under the MIT License.
