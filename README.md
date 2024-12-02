# Tiens Management System

Tiens web application built with Laravel for managing payments, expenses, and distributors. The system includes user management, role-based access control, and reporting features.

## Features

- **Payment Management**
  - Create, view, edit, and delete payments
  - Import payment data
  - Payment categorization
  - Payment tracking

- **Expense Management**
  - Track and manage expenses
  - Expense type categorization
  - Expense reporting

- **User Management**
  - Role-based access control
  - User authentication with Laravel Sanctum
  - User verification system

- **Distributor Management**
  - Manage distributor information
  - Track distributor-related transactions

- **Reporting System**
  - Generate detailed reports
  - Export data functionality
  - PDF generation using DomPDF

## Technology Stack

- PHP 8.2+
- Laravel 11.x
- Laravel Jetstream
- Livewire 3.0
- MySQL/PostgreSQL
- Tailwind CSS

## Requirements

- PHP >= 8.2
- Composer
- Node.js & NPM
- Database (MySQL/PostgreSQL)

## Installation

1. Clone the repository:
```bash
git clone [repository-url]
cd tienslaravel
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install NPM packages:
```bash
npm install
```

4. Create environment file:
```bash
cp .env.example .env
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Configure your database in the .env file:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

7. Run database migrations:
```bash
php artisan migrate
```

8. Build assets:
```bash
npm run build
```

9. Start the development server:
```bash
php artisan serve
```

## Key Packages Used

- **barryvdh/laravel-dompdf**: PDF generation
- **maatwebsite/excel**: Excel file handling
- **spatie/laravel-query-builder**: Advanced query building
- **yo-uganda/yopaymentsphp8x**: Payment processing
- **box/spout**: Spreadsheet handling
