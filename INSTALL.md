# 🌍 Wanderly — Travel Planner AI
## Complete Installation Guide

---

## Prerequisites

Before installing, ensure you have:

| Requirement | Version |
|-------------|---------|
| PHP         | 8.2+    |
| Composer    | 2.x     |
| MongoDB     | 6.0+    |
| Node.js     | 18+     |
| npm         | 9+      |

---

## Step 1 — MongoDB Setup

### Option A: Local MongoDB

```bash
# macOS (Homebrew)
brew tap mongodb/brew
brew install mongodb-community@7.0
brew services start mongodb-community@7.0

# Ubuntu/Debian
curl -fsSL https://www.mongodb.org/static/pgp/server-7.0.asc | sudo gpg -o /usr/share/keyrings/mongodb-server-7.0.gpg --dearmor
echo "deb [ arch=amd64,arm64 signed-by=/usr/share/keyrings/mongodb-server-7.0.gpg ] https://repo.mongodb.org/apt/ubuntu jammy/mongodb-org/7.0 multiverse" | sudo tee /etc/apt/sources.list.d/mongodb-org-7.0.list
sudo apt-get update && sudo apt-get install -y mongodb-org
sudo systemctl start mongod

# Windows — download installer from:
# https://www.mongodb.com/try/download/community
```

### Option B: MongoDB Atlas (Free Cloud)

1. Go to [MongoDB Atlas](https://www.mongodb.com/atlas/database)
2. Create a free M0 cluster
3. Get your connection string (looks like `mongodb+srv://...`)
4. Set `DB_DSN=<your-connection-string>` in `.env`
5. Set `DB_DATABASE=travel_planner`
6. If your Atlas user uses authentication, keep the password URL-encoded if it contains special characters

---

## Step 2 — Project Installation

### 2.1 Clone / Extract

```bash
# If using git
git clone <repository-url> travel-planner
cd travel-planner

# OR extract the zip
unzip wanderly-travel-planner.zip
cd wanderly-travel-planner
```

### 2.2 Install PHP Dependencies

```bash
composer install
```

> **Note:** This installs Laravel 11 and `mongodb/laravel-mongodb` automatically.

### 2.3 Install the MongoDB PHP Extension

```bash
# macOS
pecl install mongodb
echo "extension=mongodb.so" >> $(php --ini | grep "Loaded Configuration" | sed -e "s|.*:\s*||")

# Ubuntu/Debian
sudo apt install php-mongodb

# Windows — download from https://pecl.php.net/package/mongodb
# Add "extension=php_mongodb.dll" to php.ini
```

Verify the extension is active:
```bash
php -m | grep mongodb  # should print: mongodb
```

---

## Step 3 — Environment Configuration

### 3.1 Copy .env file

```bash
cp .env.example .env
```

### 3.2 Generate Application Key

```bash
php artisan key:generate
```

### 3.3 Configure MongoDB in .env

**Local MongoDB (no auth):**
```env
DB_CONNECTION=mongodb
DB_HOST=127.0.0.1
DB_PORT=27017
DB_DATABASE=travel_planner
DB_USERNAME=
DB_PASSWORD=
```

**MongoDB with authentication:**
```env
DB_CONNECTION=mongodb
DB_HOST=127.0.0.1
DB_PORT=27017
DB_DATABASE=travel_planner
DB_USERNAME=your_mongo_user
DB_PASSWORD=your_mongo_password
```

**MongoDB Atlas (cloud):**
```env
DB_CONNECTION=mongodb
DB_DSN=mongodb+srv://username:password@cluster0.xxxxx.mongodb.net/travel_planner?retryWrites=true&w=majority
DB_DATABASE=travel_planner
DB_AUTHENTICATION_DATABASE=admin
```

The app already reads `DB_DSN` in `config/database.php`, so just set the value in `.env` or in your Vercel environment variables.

---

## Step 4 — Create Storage Symlink

```bash
php artisan storage:link
```

---

## Step 5 — Seed Demo Data (Optional but recommended)

```bash
php artisan db:seed
```

This creates:
- **Demo user:** `demo@wanderly.app` / `password`
- 3 sample trips (Paris & Rome, Bali Retreat, Tokyo Family)
- Destinations, accommodations, and activities

---

## Step 6 — Run the Application

### Development Server

```bash
php artisan serve
```

Application will be available at: **http://localhost:8000**

### With custom host/port

```bash
php artisan serve --host=0.0.0.0 --port=8080
```

---

## Production Deployment

### Nginx Configuration

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/travel-planner/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### Apache (using .htaccess — already included)

### Vercel Deployment

If you are deploying from GitHub to Vercel, add these environment variables in the Vercel project settings:

```env
APP_NAME=Travel Planner AI
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-project.vercel.app

DB_CONNECTION=mongodb
DB_DSN=mongodb+srv://username:password@cluster0.xxxxx.mongodb.net/travel_planner?retryWrites=true&w=majority
DB_DATABASE=travel_planner
DB_AUTHENTICATION_DATABASE=admin

VITE_FIREBASE_API_KEY=your_firebase_api_key
VITE_FIREBASE_AUTH_DOMAIN=your_project.firebaseapp.com
VITE_FIREBASE_PROJECT_ID=your_project_id
VITE_FIREBASE_STORAGE_BUCKET=your_project.firebasestorage.app
VITE_FIREBASE_MESSAGING_SENDER_ID=your_sender_id
VITE_FIREBASE_APP_ID=your_app_id
VITE_FIREBASE_MEASUREMENT_ID=your_measurement_id
```

Important:
- Do not commit your Atlas password or `.env` file to GitHub.
- URL-encode special characters in the MongoDB password if needed.
- After changing env vars in Vercel, redeploy the project.

The `public/.htaccess` handles URL rewriting automatically.

### Production Optimizations

```bash
# Set environment
APP_ENV=production
APP_DEBUG=false

# Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## Quick Reference: Artisan Commands

```bash
# Start server
php artisan serve

# Seed database
php artisan db:seed

# Re-seed (clear + seed)
php artisan migrate:fresh --seed    # Not needed for MongoDB — just re-seed

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Generate key
php artisan key:generate

# Storage symlink
php artisan storage:link

# Tinker (interactive shell)
php artisan tinker

# List all routes
php artisan route:list
```

---

## MongoDB Collections Created

| Collection      | Description                        |
|-----------------|------------------------------------|
| `users`         | User accounts                      |
| `trips`         | Trip plans                         |
| `destinations`  | Places within trips                |
| `accommodations`| Hotels & stays                     |
| `activities`    | Activities & excursions            |

---

## Troubleshooting

### "Class 'MongoDB\Driver\Manager' not found"

The MongoDB PHP extension is not installed or enabled.

```bash
php -m | grep mongodb
# If no output:
pecl install mongodb
# Then add extension=mongodb.so to php.ini
```

### "Connection refused" to MongoDB

```bash
# Check if MongoDB is running
mongosh --eval "db.adminCommand('ping')"
# Or
sudo systemctl status mongod
```

### "Failed to open stream: No such file or directory"

```bash
php artisan storage:link
chmod -R 777 storage bootstrap/cache
```

### Sessions not working

Ensure `SESSION_DRIVER=file` in `.env` and the `storage/framework/sessions` directory is writable.

---

## Login Details (after seeding)

| Email                  | Password | Role     |
|------------------------|----------|----------|
| demo@wanderly.app      | password | Demo     |
| test@example.com       | password | Test     |

---

## Project Structure

```
travel-planner/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── HomeController.php
│   │   │   ├── AuthController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── TripController.php
│   │   │   ├── DestinationController.php
│   │   │   ├── AccommodationController.php
│   │   │   ├── ActivityController.php
│   │   │   └── ItineraryController.php
│   │   └── Middleware/
│   │       └── Authenticate.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Trip.php
│   │   ├── Destination.php
│   │   ├── Accommodation.php
│   │   └── Activity.php
│   └── Providers/
│       └── AppServiceProvider.php
├── bootstrap/
│   ├── app.php
│   ├── providers.php
│   └── cache/
├── config/
│   ├── app.php
│   ├── auth.php
│   ├── cache.php
│   ├── database.php     ← MongoDB config here
│   ├── filesystems.php
│   ├── logging.php
│   ├── mail.php
│   ├── queue.php
│   ├── services.php
│   └── session.php
├── database/
│   ├── factories/
│   │   ├── UserFactory.php
│   │   ├── TripFactory.php
│   │   ├── DestinationFactory.php
│   │   ├── AccommodationFactory.php
│   │   └── ActivityFactory.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── UserSeeder.php
│       └── TripSeeder.php
├── public/
│   ├── css/app.css       ← All styles
│   ├── js/app.js         ← All JavaScript
│   ├── index.php
│   └── .htaccess
├── resources/views/
│   ├── layouts/
│   │   ├── app.blade.php
│   │   ├── navbar.blade.php
│   │   └── footer.blade.php
│   ├── components/
│   │   ├── toast.blade.php
│   │   └── errors.blade.php
│   ├── auth/
│   │   ├── login.blade.php
│   │   └── register.blade.php
│   ├── home.blade.php
│   ├── dashboard/index.blade.php
│   ├── trips/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   ├── edit.blade.php
│   │   └── show.blade.php
│   ├── destinations/
│   │   ├── create.blade.php
│   │   └── edit.blade.php
│   ├── accommodations/
│   │   ├── create.blade.php
│   │   └── edit.blade.php
│   ├── activities/
│   │   ├── create.blade.php
│   │   └── edit.blade.php
│   └── itinerary/show.blade.php
├── routes/
│   ├── web.php
│   └── console.php
├── storage/
├── .env.example
├── artisan
├── composer.json
├── INSTALL.md
└── README.md
```

---

Built with ❤️ by Wanderly — *Your AI-Powered Travel Companion*
