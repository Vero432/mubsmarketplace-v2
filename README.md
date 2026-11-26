# MUBS Marketplace

An online campus marketplace for the Makerere University Business School (MUBS) community. Verified students and staff can post, find and arrange to trade items and services, with trust and safety features built in.

Live demo: https://mubs-marketplace.site.je  
GitHub repository: https://github.com/Vero432/mubsmarketplace-v2.git

> This is an academic project developed for a final-year project at MUBS. It is not an official MUBS service. Payments and deliveries are outside its scope: buyers and sellers agree on payment and arrange to meet in person.

---

## Why this system exists

Students often trade through WhatsApp groups and word of mouth. Posts can get lost, availability may be unclear, phone numbers can be shared with strangers, and there may be no clear way to report a problem. MUBS Marketplace provides a central place to browse listings, communicate through the system, and report suspicious activity.

## Features

### Accounts and verification

- Registration using an official "@mubs.ac.ug email address"
- Users register as a "Buyer" or a "Seller".
- Email verification link sent during registration.
- New accounts remain pending until an administrator approves them.
- Administrators can review account status and manage user access.
- Pending, rejected and suspended users are directed to an account-status page.

### Listings

- Sellers can post, edit and delete listings with a photo, price, category and condition.
- Services do not require an item condition.
- Listing availability statuses: available, reserved or sold.
- Browse listings using keyword search and category filters.
- Listings are displayed with seller and posting information.

### Communication

- Buyers can message sellers through the system.
- Phone numbers and email addresses are not displayed publicly on listings.
- Users can view and reply to messages through the messaging area.

### Trust and safety

- Users can report listings, including safety-related concerns.
- A listing with an open or under-review report is locked against editing or deletion by its seller until the review is completed.
- Sellers with an open report cannot delete their own account.
- Buyers who contacted a seller can rate that seller after a sale, subject to the system's rating rules.
- Seller profiles display listings and ratings.
- Community guidelines provide safe-trading advice.

### Administrator tools

- Dashboard with summary counts.
- User management, including reviewing and changing account statuses.
- Listing management, including removing listings.
- Report review and status updates.
- Category management.

## Roles and permissions

| Action | Buyer | Seller | Administrator |
|---|---|---|---|
| Browse, search and view listings | Yes | Yes | Yes |
| Contact a seller about a listing | Yes | Reply to messages | No |
| Post and manage own listings | No | Yes, subject to report restrictions | Yes |
| Report a listing | Yes | Yes | No |
| Rate a seller after a sale | Yes, subject to rating rules | No | No |
| Manage users, reports and categories | No | No | Yes |

Permissions are enforced on the server through middleware, validation and ownership checks, not only by hiding interface elements.

## Technology

| Area | Technology |
|---|---|
| Language | PHP, HTML, CSS and JavaScript |
| Framework | Laravel 13 |
| Authentication | Laravel Jetstream / Fortify |
| Templates and styling | Blade, Tailwind CSS and Alpine.js |
| Build tool | Vite |
| Database | MySQL |
| Development tools | Composer, Node.js, npm, Git and GitHub |
| Hosting | InfinityFree |

## Requirements

For local development, you need:

- PHP version supported by the project's `composer.json` (PHP 8.3 or newer is recommended).
- Composer.
- Node.js and npm.
- MySQL, for example through XAMPP.
- Git.

## Installation (local development)

### 1. Clone the project

```bash
git clone https://github.com/Vero432/mubsmarketplace-v2.git
cd mubsmarketplace
```

### 2. Install dependencies

```bash
composer install
npm install
```

### 3. Create the environment file

On Windows Command Prompt:

```bat
copy .env.example .env
```

Then generate an application key:

```bash
php artisan key:generate
```

### 4. Configure the database

Create a MySQL database using phpMyAdmin, then update the database settings in your `.env` file. For a typical local XAMPP setup:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mubsmarketplace
DB_USERNAME=root
DB_PASSWORD=
```

Use the database name and credentials that you actually created on your computer.

### 5. Configure email

Email verification requires outgoing email. For local testing, you can write outgoing messages to the Laravel log:

```env
MAIL_MAILER=log
```

The generated verification message can then be checked in `storage/logs/laravel.log`.

For real email delivery, configure an SMTP provider and use the credentials supplied by that provider. Do not commit SMTP passwords or other secrets to GitHub.

### 6. Create the database tables

Run the migrations:

```bash
php artisan migrate
```

If the project uses the category seeder and you need to add the starting categories, run:

```bash
php artisan db:seed --class=CategorySeeder
```

Do not run migrations or seeders against a database containing important data unless you understand the changes they will make.

### 7. Build frontend assets

```bash
npm run build
```

### 8. Run the application

In one terminal:

```bash
php artisan serve
```

Open the local address shown by Laravel, usually:

```text
http://127.0.0.1:8000
```

## Creating the first administrator

Register an account using an official MUBS email address and complete email verification. Then, using a trusted local development environment, promote the intended account through Laravel Tinker:

```bash
php artisan tinker
```

Example:

```php
App\Models\User::where('email', 'you@mubs.ac.ug')->update([
    'role' => 'admin',
    'status' => 'active',
    'email_verified_at' => now(),
]);
```

Replace the example email address with the intended administrator account. You can also manage the relevant user fields through phpMyAdmin if appropriate.

Only grant administrator access to an account you control and trust.

## Configuration and security notes

- Never commit `.env`. It contains the application key, database credentials and other private configuration.
- In production, set `APP_ENV=production` and `APP_DEBUG=false`.
- Use the production database host, database name, username and password supplied by your hosting provider.
- Use an SMTP provider for verification emails. Do not publish SMTP credentials.
- Back up the database before applying schema changes or replacing data.
- Do not expose private user information, application keys or passwords in screenshots, logs or public documentation.

## Deployment and updating the live site (InfinityFree)

Pushing changes to GitHub does not automatically update the InfinityFree website. To deploy an update:

1. Test the changes on your local computer.
2. Build the frontend assets with `npm run build` when frontend files have changed.
3. Commit and push the changes to the GitHub repository.
4. Upload the required changed files to the correct locations on InfinityFree using FTP/FileZilla or the hosting File Manager.
5. Do not overwrite the production `.env` file or the `public/uploads` folder when they are not part of the update.
6. Back up the production database before making database changes. Apply required database changes carefully through phpMyAdmin.
7. Test the live website after uploading.

InfinityFree hosting is case-sensitive, so file and folder names must match exactly. Keep production debugging disabled by setting `APP_DEBUG=false`.

## Testing

The system should be tested for its main workflows, including:

- Registration with an official MUBS email address.
- Email verification and administrator approval.
- Login, logout and account-status restrictions.
- Creating, editing, searching and viewing listings.
- Messaging between buyers and sellers.
- Reporting a listing and checking the review restrictions.
- Seller ratings.
- Administrator management of users, listings, reports and categories.
- Access restrictions, including attempts to edit another user's listing.

Automated tests can be run with:

```bash
php artisan test
```

Record screenshots or other evidence of the tests carried out for the project report and demonstration.

## Limitations and future work

- User eligibility is based on MUBS email verification and administrator approval; it is not automatically checked against official university student or staff records.
- Payments and delivery arrangements are outside the system.
- Free hosting may limit email delivery, storage and performance.
- Messaging is a basic in-system messaging feature, not a real-time chat service.
- Possible future improvements include integration with official university records, enhanced notifications, audit logging and integration with a suitable payment provider.


## Acknowledgements and credits

The MUBS name, logo and campus photo belong to Makerere University Business School and are used here for an academic project.

Built with [Laravel](https://laravel.com), [Jetstream](https://jetstream.laravel.com), [Livewire](https://livewire.laravel.com), [Tailwind CSS](https://tailwindcss.com) and [Alpine.js](https://alpinejs.dev).

