# Real Estate MVC

Real Estate MVC is a PHP real estate web application built with a simple MVC structure, Twig templates, and Illuminate/Eloquent for database access. The project runs locally with XAMPP and uses MySQL through phpMyAdmin.

The public landing page is designed for visitors to browse properties, search listings, learn about services and contact the agency. The protected admin dashboard lets logged-in users manage listings, agents, users, locations, property types and amenities.

## Features

- Modern public landing page with hero section, search panel, featured listings, services, agents and contact form.
- Public search for listings by location/address, property type and budget.
- Admin dashboard protected by login/session checks.
- Dashboard listing search by address, description, agent or property type.
- CRUD modules for:
  - Listings
  - Agents
  - Users
  - Locations
  - Property types
  - Amenities
- Contact form submissions saved in the database.
- Twig-based views with reusable layouts.
- Eloquent models and relationships for listings, agents, property types and amenities.

## Tech Stack

- PHP 8+
- XAMPP / Apache
- MySQL / phpMyAdmin
- Composer
- Twig
- Illuminate Database / Eloquent
- Bootstrap
- Custom CSS

## Project Structure

```text
realestate/
+-- App/
|   +-- Controllers/      # Application controllers
|   +-- Helper/           # Session helper
|   +-- Models/           # Eloquent models
|   +-- Views/            # Twig templates and layouts
+-- Core/                 # Router, Controller, View, Error handling
+-- database/             # Migration scripts and SQL import file
+-- public/               # Entry point, routes, CSS, JS, images
+-- Bootstrap.php         # Eloquent/Capsule bootstrapping
+-- composer.json
+-- README.md
```

## Local Setup with XAMPP

1. Copy the project folder to:

   ```text
   C:\xampp\htdocs\realestate
   ```

2. Start Apache and MySQL from XAMPP Control Panel.

3. Open phpMyAdmin:

   ```text
   http://localhost/phpmyadmin
   ```

4. Import the prepared SQL file:

   ```text
   database/realestate_import.sql
   ```

   This creates the `realestate` database, tables and demo data.

5. Open the app:

   ```text
   http://localhost/realestate/public/
   ```

## Admin Login

After importing `database/realestate_import.sql`, use:

```text
Email: admin@realestate.test
Password: admin123
```

## Important URLs

```text
Public landing page:
http://localhost/realestate/public/

Public listings/search:
http://localhost/realestate/public/buy

Login:
http://localhost/realestate/public/login

Admin dashboard/listings:
http://localhost/realestate/public/listings
```

## Database Configuration

Database settings are in:

```text
App/Config.php
```

Default local configuration:

```php
const DB_HOST = '127.0.0.1';
const DB_NAME = 'realestate';
const DB_USER = 'root';
const DB_PASSWORD = '';
```

## Notes for GitHub

The repository includes a `.gitignore` that excludes local/runtime files such as:

- `vendor/`
- `.idea/`
- logs
- runtime uploads
- ZIP exports
- OS/editor files

After cloning the project, install PHP dependencies with:

```bash
composer install
```

## InfinityFree / Shared Hosting Deployment

This project is prepared for shared PHP hosting where the domain points to
`htdocs`. The live shared-hosting entry point is `standalone.php`, which uses
plain PDO/MySQL and does not require Composer/vendor packages on the server.

1. Create a MySQL database from the InfinityFree dashboard.
2. Open phpMyAdmin for that database and import:

   ```text
   database/infinityfree_import.sql
   ```

3. Copy this file:

   ```text
   App/database.sample.php
   ```

   to:

   ```text
   App/database.php
   ```

   Then fill in the InfinityFree host, database name, username and password.
   `App/database.php` is ignored by Git because it contains private credentials.

4. Upload the deploy files into the domain `htdocs`:

   ```text
   index.php
   standalone.php
   .htaccess
   App/database.php
   public/
   ```

   You can upload the full project too, but `vendor/` is not required for the
   live standalone version.

5. Open the website root:

   ```text
   https://your-domain/
   ```

The root `index.php` and `.htaccess` forward clean URLs to the standalone app,
so the site works as `/`, `/buy`, `/login`, `/dashboard`, `/listings`,
`/agents`, `/contacts`, etc. Locally you can also use:

```text
http://localhost/realestate/
```

instead of only:

```text
http://localhost/realestate/public/
```

## MVC Diagram

![MVC Diagram](mvc.png)
