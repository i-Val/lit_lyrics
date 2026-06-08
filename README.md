# Lit Lyrics

Lit Lyrics is a Laravel-based web application for managing and publishing liturgical song lyrics. It includes a public site for searching and viewing lyrics and an admin dashboard for managing songs, categories, music sheets, users, and site settings.

## Features

- Public lyric search with live autocomplete on the home page
- Lyric detail page with copy-to-clipboard and downloads (TXT, DOCX)
- Lyric Builder for Mass parts (Entrance, Kyrie, etc.) with compiled text download
- Admin dashboard with basic statistics and charts
- Song management (create, edit, delete)
- Category management
- Music sheet management (upload and attach to songs)
- Settings management
  - Site logo upload
  - Social media links (Facebook, Twitter, Instagram)
  - Maintenance mode toggle and maintenance page
- Authentication and email verification

## Tech Stack

- PHP `^8.1`
- Laravel `^10.10`
- MySQL/MariaDB (recommended)
- Vite `^5` (frontend tooling)
- `phpoffice/phpword` for DOCX generation

## Local Setup

### Prerequisites

- PHP 8.1+
- Composer
- Node.js + npm
- MySQL/MariaDB
- Local server (Laragon, XAMPP, Valet, etc.)

### Install

1. Install dependencies:
   - `composer install`
   - `npm install`

2. Create environment config:
   - Copy `.env.example` to `.env`
   - `php artisan key:generate`

3. Configure `.env`:
   - `APP_URL`
   - `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
   - Mail settings (required for email verification flows)

4. Run migrations and seeders:
   - `php artisan migrate`
   - `php artisan db:seed`

5. Create the storage symlink (required for uploaded files like site logo and music sheets):
   - `php artisan storage:link`

### Run

- Backend:
  - `php artisan serve`
- Frontend (development):
  - `npm run dev`
- Frontend (production build):
  - `npm run build`

## Key Routes

### Public

- Home: `/`
- About: `/about`
- Lyric Builder: `/lyric-builder`
- View lyric: `/lyric/{id}`
- Download lyric:
  - `/lyric/download/{id}?type=txt`
  - `/lyric/download/{id}?type=docx`

### Live Search (used by homepage autocomplete)

- `/api/songs/search?q={query}`

### Admin (requires authentication)

- Dashboard: `/dashboard`
- Songs:
  - Create: `/lyric`
  - List: `/lyrics`
  - Edit: `/lyric/{id}/edit`
- Settings: `/settings`
- Categories: `/categories`
- Music Sheets: `/music-sheets`
- Users: `/users`

## Maintenance Mode

Maintenance mode is configured via the dashboard settings:

- Toggle: `maintenance_mode` in Settings
- Middleware: `app/Http/Middleware/CheckMaintenanceMode.php`
- View: `resources/views/errors/maintenance.blade.php`

When enabled, public routes show the maintenance page while allowing admins/authenticated users to access the dashboard.

## File Uploads

Uploads are stored under `storage/app/public/...` and served via `public/storage` (requires `php artisan storage:link`).

- Site logo: `storage/app/public/settings`
- Music sheets: `storage/app/public/music-sheets`

## Testing

- `php artisan test`

## License

MIT (update if your project uses a different license).
