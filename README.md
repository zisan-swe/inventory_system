# Inventory & Sales Management System

A comprehensive Laravel-based inventory and sales management system with financial reporting capabilities.

## Features

- Product management with stock tracking
- Sales processing with discounts and VAT
- Payment tracking and due management
- Financial reporting and analytics
- Invoice generation (HTML & PDF)
- Dashboard with key performance metrics

## Requirements

- PHP 8.0 or higher
- Composer
- MySQL 5.7+ or MariaDB 10.3+
- Node.js 14+ (for frontend assets)
- NPM or Yarn

## Installation

### 1. Clone the repository

```bash
git clone https://github.com/your-repository/inventory-system.git
```
```bash
cd inventory-system
```


### 2.Install PHP dependencies

```bash
composer install
```

 ### 3.Install JavaScript dependencies

```bash
npm install
npm run build
```

### 4.Configure environment

Copy the example environment file and configure your database:

```bash
cp .env.example .env
```

### 5.Edit the .env file with your database credentials:

env
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventory_system
DB_USERNAME=root
DB_PASSWORD=
```
 ###  6.Generate application key

```bash
php artisan key:generate
```
###  7.Run database migrations and seeders

```bash
php artisan migrate --seed
```

Admin user (email: admin@gamil.com, password: admin#123)


###  8.Running the Application
Development Mode
```bash
php artisan serve
```
The application will be available at http://localhost:8000

Production Mode
For production, configure your web server (Apache/Nginx) to point to the public directory.