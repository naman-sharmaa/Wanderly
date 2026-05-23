# ✈️ Wanderly — Travel Planner AI

> **Your Trip, Your Vibe — Our AI's on It**
> Solo? Couple? Group? We Plan Like It's Just for You — Because It Is.

A premium, full-stack **Laravel 11 + MongoDB** travel planning web application with a luxury AI-travel-website aesthetic.

---

## ✨ Features

- 🔐 **Authentication** — Register, Login, Logout with session-based auth
- 🗺️ **Trip Management** — Full CRUD with search, filter, and sort
- 📍 **Destination Tracking** — Multi-destination trips with date ranges
- 🏨 **Accommodation Manager** — Hotels with booking references & star ratings
- 🎯 **Activity Planner** — Categorized activities with costs & timings
- 📅 **Day-by-Day Itinerary** — Beautiful chronological timeline view
- 🌙 **Dark Mode** — System-aware with localStorage persistence
- 🎨 **Premium UI** — Glassmorphism, animations, floating cards, parallax
- 📱 **Fully Responsive** — Mobile-first design

## 🛠️ Tech Stack

| Layer        | Technology                     |
|--------------|-------------------------------|
| Backend      | Laravel 11, PHP 8.2+           |
| Database     | MongoDB via `mongodb/laravel-mongodb` |
| Frontend     | Blade Templates, Vanilla JS, CSS3 |
| Fonts        | Cormorant Garamond + DM Sans   |
| Icons        | Bootstrap Icons                |

## 🚀 Quick Start

```bash
composer install
cp .env.example .env
php artisan key:generate
# Configure MongoDB in .env
php artisan db:seed
php artisan serve
```

**Demo Login:** `demo@wanderly.app` / `password`

See [INSTALL.md](INSTALL.md) for complete setup instructions.

## 📸 Pages

| Page         | Route              |
|--------------|--------------------|
| Landing      | `/`                |
| Register     | `/register`        |
| Login        | `/login`           |
| Dashboard    | `/dashboard`       |
| Trips        | `/trips`           |
| New Trip     | `/trips/create`    |
| Trip Detail  | `/trips/{id}`      |
| Itinerary    | `/trips/{id}/itinerary` |

## 📄 License

MIT License — Free to use and modify.

---

*Built with ❤️ — Wanderly, Your AI Travel Companion*
