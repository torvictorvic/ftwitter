# Twitter Clone

A functional Twitter/X clone built as a full-stack technical challenge using **Laravel 13**, **Vue 3**, **Inertia.js**, **Tailwind CSS 4**, and **SQLite**.

The application implements first-party authentication, an authenticated timeline, tweet publishing and deletion, likes, follow/unfollow, user profiles, user search, realistic seed data, and a backend test suite with **94.5% coverage**.

---

## 1. Project Goal

This project was built to solve The Flock **Twitter Clone** full-stack challenge, prioritizing:

- complete end-to-end functionality
- clear architecture
- simple and reproducible setup
- strong backend testing
- maintainable and review-friendly code
- progressive commits and operational documentation

The goal was to deliver a usable product without over-engineering, using a stack that allowed fast iteration while preserving solid technical quality.

---

## 2. Chosen Stack and Rationale

### Backend
- **Laravel 13**
- **Laravel Fortify** for first-party authentication
- **SQLite** as the relational database
- **Pest** for testing

### Frontend
- **Vue 3**
- **Inertia.js**
- **Vite**
- **Tailwind CSS 4**
- UI components from the Laravel/Vue starter kit

### Why Laravel + Vue + SQLite?

This combination was selected for both practical and technical reasons.

#### Laravel 13
- It enables a solid backend with validation, middleware, sessions, protected routes, and Eloquent with very low friction.
- It supports first-party authentication without relying on third-party providers, which was an explicit requirement of the challenge.
- It provides excellent built-in support for testing, factories, seeders, and consistent conventions.

#### Vue 3 + Inertia
- It makes it possible to build a modern interface without splitting the frontend and backend into separate projects.
- It reduces operational complexity and speeds up development.
- It is a strong option when SPA-like interactivity is needed without increasing stack complexity unnecessarily.

#### SQLite
- It was chosen to minimize installation friction and improve runbook reproducibility.
- It is a fully valid relational database for the scope of this challenge.
- It allows the repository to be cloned, migrated, seeded, and reviewed immediately with minimal setup.

In short, the stack was not selected because it is trendy, but because it optimizes **delivery speed, maintainability, ease of setup, and the ability to execute the challenge well within the available time**.

---

## 3. Implemented Features

### Authentication
- user registration
- login / logout
- session-protected routes
- basic user profile with:
  - name
  - unique username
  - bio
  - initials-based avatar placeholder

### Tweets
- tweet creation
- maximum 280-character validation
- rejection of empty or whitespace-only tweets
- deletion of the authenticated user's own tweets
- authenticated timeline
- pagination

### Social Interactions
- follow / unfollow
- like / unlike
- visible like counter per tweet
- followers / following relationship data on the profile page

### Search
- user search by name or username

### UX / UI
- responsive design
- mobile-first layout
- usable interface on both mobile and desktop

### Seed Data
- seed with 10 users
- cross-linked tweets, follows, and likes
- demo user ready for manual validation

### Testing
- unit tests
- feature tests
- backend coverage: **94.5%**

---

## 4. Key Technical Decisions

### Timeline
The timeline is resolved **on-read** by combining:
- tweets created by the authenticated user
- tweets created by the users they follow

Advantages of this decision:
- easy to explain
- appropriate for the challenge's data volume
- avoids unnecessary complexity such as feed fan-out on write

### Social Graph
The follow relationship is modeled with a directed table:
- `follower_id`
- `followed_id`

This correctly represents the “A follows B” relationship and simplifies timeline, profile, and social list queries.

### Authentication
The project uses **Laravel Fortify** with first-party session-based authentication. No third-party authentication solutions such as Firebase Auth or Supabase Auth were used.

### Validation
Critical validations were kept on the backend to guarantee integrity even if the frontend changes.

### Testing Strategy
The testing strategy focuses on:
- models and relationships
- validations
- critical endpoints
- timeline behavior
- follows / likes / tweets
- profile and search
- seed data

---

## 5. Project Structure

```text
app/
  Actions/
    Fortify/
    Timeline/
  Concerns/
  Http/
    Controllers/
    Middleware/
    Requests/
  Models/
  Providers/

database/
  factories/
  migrations/
  seeders/

resources/js/
  components/
    tweets/
    users/
    shared/
    ui/
  layouts/
  pages/
    auth/
    Profile/
    Search/
    settings/
  routes/
  composables/

routes/
  web.php
  settings.php

tests/
  Unit/
  Feature/
```

### Main Directories

#### `app/`
Contains backend business logic:
- controllers
- models
- actions
- middleware
- requests
- providers

#### `database/`
Contains:
- migrations
- factories
- seeders
- local SQLite database file

#### `resources/js/`
Contains the Vue frontend:
- pages
- layouts
- reusable components
- tweet and user components

#### `routes/`
Defines the main HTTP routes and settings routes.

#### `tests/`
Contains the backend test suite, organized into Unit and Feature tests.

---

## 6. Environment Requirements

This project was prepared to run locally on Linux Ubuntu 22.04, but it can run on equivalent environments as well.

### Recommended Versions
- **PHP 8.3+**
- **Composer 2.8+**
- **Node.js 22+**
- **npm 10+**
- **SQLite 3+**

### Main PHP Dependencies
- `laravel/framework ^13.0`
- `laravel/fortify ^1.34`
- `inertiajs/inertia-laravel ^3.0`
- `laravel/wayfinder ^0.1.14`
- `laravel/tinker ^3.0`

### Main Development Dependencies
- `pestphp/pest ^4.4`
- `pestphp/pest-plugin-laravel ^4.1`
- `laravel/pint ^1.27`
- `laravel/pail ^1.2.5`
- `vite ^8`
- `vue ^3.5`
- `tailwindcss ^4.1`

---

## 7. Environment Variables

The project includes a `.env.example` file.

Important variables:

```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=sqlite

SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database
MAIL_MAILER=log
```

### Notes
- The default database connection uses **SQLite**.
- If `DB_DATABASE` is not explicitly defined, Laravel uses `database/database.sqlite`.
- `MAIL_MAILER=log` avoids relying on a real SMTP provider during development.

---

## 8. Step-by-Step Installation

### Recommended Setup

```bash
git clone https://github.com/torvictorvic/ftwitter.git
cd ftwitter
cp .env.example .env
mkdir -p database
touch database/database.sqlite
composer install
php artisan key:generate
npm install
php artisan migrate:fresh --seed
```

### Alternative Using the Composer Script

The project includes a `setup` script:

```bash
composer setup
```

Then run the seed manually:

```bash
php artisan migrate:fresh --seed
```

> Note: `composer setup` installs dependencies, generates the app key, runs migrations, and builds assets, but it does not execute the full challenge seed automatically.

---

## 9. Running the Project in Development

### Recommended Option

```bash
composer run dev
```

This command runs the following processes concurrently:
- Laravel development server
- queue listener
- log tailing with Laravel Pail
- Vite development server

### Common URLs
- Laravel app: `http://127.0.0.1:8000`
- Vite dev server: `http://localhost:5173`

### Manual Alternative

Terminal 1:
```bash
php artisan serve
```

Terminal 2:
```bash
npm run dev
```

---

## 10. Demo User

After seeding the database, the following credentials can be used:

```text
Email: demo@example.com
Password: password
Username: demouser
```

---

## 11. Running Migrations and Seed Data

### Run Migrations

```bash
php artisan migrate
```

### Reset Everything and Seed Demo Data

```bash
php artisan migrate:fresh --seed
```

This creates:
- 10 users
- tweets per user
- cross-linked follows
- cross-linked likes
- a clearly identifiable demo user for manual validation

---

## 12. Running the Tests

### Full Backend Test Suite

```bash
./vendor/bin/pest tests/Unit tests/Feature
```

### Project Test Script

```bash
composer test
```

This command performs:
1. configuration cache clearing
2. PHP style validation with Pint
3. test execution through `php artisan test`

### Format PHP Code

```bash
./vendor/bin/pint
```

### Check Style Without Modifying Files

```bash
./vendor/bin/pint --test
```

---

## 13. Test Coverage

The current backend coverage is:

```text
Total backend coverage: 94.5%
```

### Generate Coverage with PCOV

First, verify whether PCOV is installed:

```bash
php -m | grep pcov
```

If it is not available on Ubuntu:

```bash
sudo apt install php8.3-pcov
```

Then run:

```bash
php -d pcov.enabled=1 -d pcov.directory=app ./vendor/bin/pest tests/Unit tests/Feature --coverage
```

### Generate an HTML Coverage Report

```bash
php -d pcov.enabled=1 -d pcov.directory=app ./vendor/bin/pest tests/Unit tests/Feature --coverage-html coverage-report
```

You can then open the report at `coverage-report/index.html`.

---

## 14. Useful Project Scripts

### Composer

```bash
composer setup
composer run dev
composer test
```

### NPM

```bash
npm install
npm run dev
npm run build
npm run lint
npm run lint:check
npm run format
npm run format:check
npm run types:check
```

---

## 15. Main Routes

### Social Application
- `GET /` → authenticated timeline
- `POST /tweets` → create tweet
- `DELETE /tweets/{tweet}` → delete own tweet
- `POST /tweets/{tweet}/likes` → like tweet
- `DELETE /tweets/{tweet}/likes` → unlike tweet
- `GET /users/search` → user search
- `GET /users/{username}` → user profile
- `POST /users/{username}/follow` → follow user
- `DELETE /users/{username}/follow` → unfollow user

### Settings
- `GET /settings/profile`
- `PATCH /settings/profile`
- `DELETE /settings/profile`
- `GET /settings/security`
- `PUT /settings/password`
- `GET /settings/appearance`

---

## 16. Main Libraries and Tooling

### Backend
- **Laravel 13**
- **Fortify** for first-party authentication
- **Inertia Laravel**
- **Wayfinder** for typed frontend route helpers
- **Tinker** for quick application inspection
- **Pail** for development log tailing

### Frontend
- **Vue 3**
- **Inertia.js**
- **Vite**
- **Tailwind CSS 4**
- **Lucide Vue** for icons
- **VueUse** for reactive utilities
- **TypeScript**

### Quality / Testing
- **Pest**
- **Pest Laravel Plugin**
- **Pint**
- **ESLint**
- **Prettier**
- **vue-tsc**

---

## 17. Inspecting the SQLite Database

You can open the local database with:

```bash
sqlite3 database/database.sqlite
```

Useful SQLite examples:

```sql
.tables
select id, name, username, email from users;
select id, user_id, body, created_at from tweets limit 20;
select follower_id, followed_id from follows limit 20;
select user_id, tweet_id from likes limit 20;
.quit
```

You can also use Tinker:

```bash
php artisan tinker
```

Examples:

```php
App\Models\User::count();
App\Models\Tweet::count();
App\Models\User::select('id', 'name', 'username', 'email')->get();
```

---

## 18. Trade-offs and Known Limitations

- **SQLite** was prioritized for operational simplicity and reproducible local setup.
- The timeline is designed for the challenge scale, not for a large-scale production feed architecture.
- **Classic pagination** was chosen over infinite scroll to reduce complexity and delivery risk.
- Bonus features such as image uploads, WebSockets, or notifications were intentionally not implemented in order to preserve core stability.
- Some optional starter-kit capabilities, especially more advanced security flows, are not the primary focus of this challenge.

---

## 19. AI Usage During Development

This project was developed using a combination of:

- the author's own engineering knowledge
- **ChatGPT / Codex (OpenAI ChatGPT)**
- assistance through **Visual Studio Code**

AI was used to support:
- scaffolding acceleration
- validation review
- test generation and refinement
- naming and structural consistency
- targeted debugging
- strengthening the runbook and documentation

All final decisions, corrections, and validations were manually reviewed before being consolidated into the project.

---

## 20. Technical Summary

This project delivers a functional Twitter/X clone with a modern full-stack setup that is simple to run and well tested.

Highlights:
- Laravel 13 + Vue 3 + Inertia
- SQLite for fast setup
- first-party authentication
- timeline, follows, likes, profiles, and search
- realistic seed data
- strong backend testing
- **94.5% coverage**
- clear, reproducible runbook

---

## 21. Quick Commands

### Full Setup

```bash
git clone https://github.com/torvictorvic/ftwitter.git
cd ftwitter
cp .env.example .env
touch database/database.sqlite
composer install
php artisan key:generate
npm install
php artisan migrate:fresh --seed
composer run dev
```

### Quick Test Run

```bash
./vendor/bin/pest tests/Unit tests/Feature
```

### Coverage

```bash
php -d pcov.enabled=1 -d pcov.directory=app ./vendor/bin/pest tests/Unit tests/Feature --coverage
```

---

## 22. Author

Developed by **Victor Manuel Suarez Torres**.
