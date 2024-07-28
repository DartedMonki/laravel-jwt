# Laravel JWT

## Introduction

Laravel template with JWT Auth

## Prerequisites

Before you begin, ensure you have met the following requirements:

- PHP 8.3
- Composer 2.7.7
- Laravel 11
- MySQL 8.4.2 LTS

## Installation

Follow these steps to install and set up the project locally:

### 1. Clone the repository

```bash
git clone https://github.com/DartedMonki/laravel-jwt.git
cd laravel-jwt
```

### 2. Install dependencies

```bash
composer install
```

### 3. Set up the environment

Copy the `.env.example` file to `.env` and configure your environment variables.

```bash
cp .env.example .env
```

Edit the `.env` file to match your local environment settings:

- Set your database credentials
- Set other required environment variables

### 4. Generate application key

```bash
php artisan key:generate
php artisan jwt:secret
```

### 5. Run database migrations

```bash
php artisan migrate
```

### 6. Seed the database (optional)

If you have database seeders, run the following command to seed your database:

```bash
php artisan db:seed
```

## Running the Project Locally

### 1. Start the local development server

```bash
php artisan serve
```

By default, the application will be accessible at `http://localhost:8000`.

## Testing

To run the project's test suite, use the following command:

```bash
php artisan test
```

## Additional Commands

Here are some additional Artisan commands that might be useful:

- `php artisan migrate:rollback` - Rollback the last database migration
- `php artisan cache:clear` - Clear the application cache
- `php artisan route:list` - List all registered routes