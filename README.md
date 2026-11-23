# Real Estate Platform

Real Estate Platform is a property discovery and administration application built to demonstrate an end-to-end real estate workflow: public property search, enquiry capture, and back-office management of listings and supporting data.

It presents an agency-style user experience while keeping the application structure clear and easy to extend.

## Highlights

- Responsive public landing page with featured properties and agency content
- Database-driven property search by location, property type, and budget
- Detailed listing cards with pricing, bedrooms, bathrooms, agent information, and descriptions
- Contact flow that stores buyer enquiries in MySQL
- Protected admin area for listings, agents, users, locations, property types, amenities, and contacts
- Role-aware authentication using secure password hashes
- Shared-hosting-compatible deployment path using a lightweight PDO entry point

## Product Experience

Visitors can browse the available inventory, filter properties by their needs, review key attributes, and submit a request for a tour or further information. The design emphasizes readable listing data, agent trust, and a simple enquiry path.

For internal users, the administration area centralizes the operational data behind the public website, making it possible to maintain property inventory and supporting taxonomies without editing page files.

## Technical Architecture

```text
realestate/
├── App/
│   ├── Controllers/   Application request handlers
│   ├── Models/        Database models and relationships
│   ├── Views/         Public and admin templates
│   └── Helper/        Session and shared utilities
├── Core/              Router, controller, error, and view infrastructure
├── database/          Schema and seeded demo data
├── public/            Images, styles, JavaScript, and web assets
├── standalone.php     Lightweight shared-hosting application entry point
└── index.php          Root front controller
```

## Technology

- PHP 8
- MySQL / MariaDB
- PDO for portable shared-hosting access
- Twig and Eloquent architecture in the full application source
- Bootstrap and custom responsive CSS
- Apache rewrite rules for clean application routes

## Engineering Notes

- Listing data is loaded from the database rather than hardcoded in views.
- Search input is parameterized and filtered server-side.
- Passwords use secure hashes and authentication relies on PHP sessions.
- Public, administrative, and database responsibilities are separated into dedicated layers.
- The shared-hosting entry point keeps the project deployable even in environments where Composer packages cannot be installed on the server.

## Author

Built by [Arlind Hyseni](https://www.arlindhyseni.online) as a full-stack portfolio project.

## Live Demo
https://realestate-ks.infinityfreeapp.com