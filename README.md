# Must Négoce Academy

Must Négoce Academy is a Laravel web application for managing the day-to-day operations of a training center. Administrators can manage learners, training programs, groups, sessions, attendance, and payment tracking from one interface.

## Features

- **Dashboard** — learner and program totals, payment summaries, charts, and follow-up alerts.
- **Learner management** — create and update learner records, search and filter the directory, track payment balances, and import or export Excel files.
- **Training programs** — manage program names and trainers.
- **Groups and schedules** — assign learners to groups, manage weekly time slots, and check for schedule conflicts.
- **Sessions and attendance** — plan sessions, record present, absent, or late status, and review attendance history for each learner.
- **Authentication** — application pages are protected by Laravel authentication.

## Technology

- PHP 8.2+
- Laravel 12
- MySQL or SQLite
- Blade templates and Tailwind CSS
- Vite and Node.js for frontend assets
- Laravel Excel (`maatwebsite/excel`) for learner imports and exports
- Chart.js for dashboard charts

## Requirements

Install the following before setting up the application:

- PHP 8.2 or later and Composer
- Node.js and npm
- A database supported by the Laravel configuration, such as SQLite or MySQL
- PHP extensions required by Laravel and the selected database driver

## Local setup

1. Install backend dependencies:

   ```bash
   composer install
   ```

2. Create a local `.env` file using your team's environment template. **This repository does not currently include an `.env.example` file.** For a local SQLite setup, configure at least:

   ```dotenv
   APP_NAME="Must Négoce Academy"
   APP_ENV=local
   APP_KEY=
   APP_DEBUG=true
   APP_URL=http://127.0.0.1:8000
   DB_CONNECTION=sqlite
   ```

   Then create the SQLite database and generate the application key:

   ```bash
   touch database/database.sqlite
   php artisan key:generate
   ```

   If you use MySQL, set `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` in `.env` instead, and make sure the database is running.

3. Run database migrations:

   ```bash
   php artisan migrate
   ```

   To load the development seed data as well:

   ```bash
   php artisan db:seed
   ```

4. Install frontend dependencies and build the assets:

   ```bash
   npm install
   npm run build
   ```

5. Start the application:

   ```bash
   php artisan serve
   ```

   Open [http://127.0.0.1:8000](http://127.0.0.1:8000). For frontend development with Vite hot reload, run `npm run dev` in a second terminal.

## Development commands

| Command | Description |
| --- | --- |
| `php artisan serve` | Start the Laravel development server. |
| `npm run dev` | Start Vite with hot reload. |
| `npm run build` | Compile production frontend assets. |
| `php artisan migrate` | Apply database migrations. |
| `php artisan db:seed` | Run the configured database seeders. |
| `php artisan test` | Run the Laravel test suite. |

The Composer `dev` script can also start the Laravel server, queue listener, log viewer, and Vite together:

```bash
composer run dev
```

## Excel learner imports

The learner import expects an `.xlsx` or `.xls` workbook with a heading row containing these column names:

| Column | Required |
| --- | --- |
| `name` | Yes |
| `cin` | Yes |
| `phone` | Yes |
| `formation_id` | Yes |
| `start_date` | Yes |
| `engagement` | Yes |
| `email` | No |

`formation_id` must match an existing training program's database ID. Review the file before importing it into a live database.

## Project structure

```text
app/
  Exports/       Excel export definitions
  Http/          Controllers and request handling
  Imports/       Excel import definitions
  Models/        Eloquent models
database/
  migrations/    Database schema changes
  seeders/       Development seed data
resources/
  css/           Tailwind styles
  js/            Frontend entry points
  views/         Blade pages and components
routes/
  web.php        Authenticated web routes
  auth.php       Authentication routes
```

## Deployment notes

- Set `APP_ENV=production` and `APP_DEBUG=false` in production.
- Use a strong, unique `APP_KEY` and keep `.env` out of version control.
- Configure a persistent production database and run migrations as part of deployment.
- Build frontend assets with `npm run build` before serving the application.
- Serve the `public/` directory through a web server configured for Laravel's `public/index.php` entry point.

## License

The project is currently declared under the MIT License in `composer.json`.
