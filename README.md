# TaskFlow API  
**A production-ready Laravel 12 REST API** for personal task management.

Live Demo: `http://127.0.0.1:8000/api/tasks`  


---

## Features
- Register / Login (Sanctum token auth)  
- CRUD tasks (only you can touch your tasks)  
- Filter by status: `?status=completed`  
- Paginated list: `?page=2&per_page=10`  
- Full validation + clean JSON errors  
- 100% tested with PHPUnit  
- Zero-config SQLite (dev)  

---

## Tech Stack
- Laravel 11  
- Sanctum (token auth)  
- SQLite (dev) / MySQL (prod)  
- PHP 8.2+  
- PHPUnit + Factories  

---

## One-Click Setup (30 seconds)

# ONE LINE TO RULE THEM ALL
composer install --no-dev && \
cp .env.example .env && \
php artisan key:generate && \
php artisan migrate --seed && \
php artisan test --quiet && \
php artisan serve
