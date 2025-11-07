# OrderManagementAPI

A modern Laravel 11 backend API designed for handling order management operations.  
This documentation explains how to install, configure, and run the project using Docker (recommended setup).

---

# Table of Contents

- [ Installation](#-installation)
  - [1. Requirements](#1-requirements)
  - [2. Clone the Repository](#2-clone-the-repository)
  - [3. Docker Environment Files](#3-docker-environment-files)
  - [4. Build and Start Containers](#4-build-and-start-containers)
  - [5. Install PHP Dependencies](#5-install-php-dependencies)
  - [6. Create the Environment File](#6-create-the-environment-file)
  - [7. Environment Configuration](#7-environment-configuration)
  - [8. Generate Application Key](#8-generate-application-key)
  - [9. Database Migrations](#9-database-migrations)
  - [10. Fixing File Permissions (WSL2)](#10-fixing-file-permissions-wsl2)
  - [11. Accessing the Application](#11-accessing-the-application)

- [ Project Structure](#-project-structure)
- [ Docker Commands](#-docker-commands)
- [ Testing](#-testing)

---

# Installation

This project uses **Docker + Apache + PHP 8.3 + MySQL + Redis** as its development environment.  
All source code lives inside your local machine (WSL2 recommended on Windows), while Docker provides the runtime.

---

## 1. Requirements

Make sure the following tools are installed:

- Docker Desktop (with WSL2 backend enabled)
- WSL2 + Ubuntu (Windows users)
- Git
- Visual Studio Code (recommended)
- Composer (optional, Docker can run it)
- Node.js + NPM (optional for frontend assets)

---

## 2. Clone the Repository

```bash
git clone https://github.com/your-user/OrderManagementAPI.git
cd OrderManagementAPI
```

If you're using WSL2, place the project under:

```
~/projects/OrderManagementAPI
```

(This ensures maximum performance.)

---

## 3. Docker Environment Files

Ensure the project root contains:

- `Dockerfile` → Apache + PHP 8.3 setup
- `docker-compose.yml` → App + MySQL + Redis stack

These files define your full development environment.

---

## 4. Build and Start Containers

```bash
docker compose up -d --build
```

This will:

- Build the PHP 8.3 + Apache container  
- Start MySQL and Redis  
- Mount your project into `/var/www/html`  

---

## 5. Install PHP Dependencies

Enter the app container:

```bash
docker exec -it order_management_app bash
composer install
exit
```

---

## 6. Create the Environment File

```bash
cp .env.example .env
```

---

## 7. Environment Configuration

Update `.env`:

```
APP_NAME=OrderManagementAPI
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=order_management
DB_USERNAME=orderuser
DB_PASSWORD=orderpass

REDIS_HOST=redis
REDIS_PORT=6379
```

---

## 8. Generate Application Key

```bash
docker exec -it order_management_app php artisan key:generate
```

---

## 9. Database Migrations

```bash
docker exec -it order_management_app php artisan migrate
```

---

## 10. Fixing File Permissions (WSL2)

If VS Code cannot save files:

Run in WSL2:

```bash
sudo chown -R $USER:$USER .
```

Then inside Docker:

```bash
docker exec -it order_management_app chown -R www-data:www-data /var/www/html/storage
docker exec -it order_management_app chown -R www-data:www-data /var/www/html/bootstrap/cache
```

---

## 11. Accessing the Application

Apache automatically serves the Laravel application.

Visit:

http://localhost:8000

You should see the Laravel welcome page.

---

# Project Structure

```
OrderManagementAPI/
│── app/
│── bootstrap/
│── config/
│── database/
│── public/
│── resources/
│── routes/
│── storage/
│── vendor/
│── Dockerfile
│── docker-compose.yml
│── .env
└── README.md
```

---

# Docker Commands

| Command | Description |
|--------|-------------|
| docker compose up -d | Start environment |
| docker compose down | Stop containers |
| docker compose up -d --build | Rebuild containers |
| docker ps | View running containers |
| docker exec -it order_management_app bash | Enter app container |

---

# Testing

```bash
docker exec -it order_management_app php artisan test
```

---


