# J&M Laundry Lounge

**J&M Laundry Lounge** is a modern, user-friendly web application designed to streamline laundry service operations. It provides a seamless experience for both staff and customers, handling everything from order placement and tracking to payment processing and customer management.

## Features

### For Customers
- **Easy Ordering**: Place laundry orders with detailed specifications.
- **Real-time Tracking**: Monitor the status of laundry orders as they move through the cleaning process.
- **Customer Dashboard**: View order history and manage personal information.

### For Staff & Management
- **Order Management**: Efficiently manage incoming and ongoing laundry orders.
- **Customer Management**: Maintain a comprehensive database of customers and their preferences.
- **Payment Processing**: Handle payments and track transactions.
- **Service Management**: Manage different laundry services and pricing.
- **Reporting**: Generate insightful reports on operations and sales.

## Tech Stack

- **Backend**: Laravel
- **Frontend**: Vue.js
- **Database**: MySQL
- **Styling**: Tailwind CSS

## Getting Started

### Prerequisites
- PHP >= 8.2
- MySQL
- Node.js & npm

### Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd L-M-Laundry
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Configure environment variables**
   Copy the `.env.example` file to `.env` and fill in your database credentials:
   ```bash
   cp .env.example .env
   ```

5. **Generate application key**
   ```bash
   php artisan key:generate
   ```

6. **Run database migrations**
   ```bash
   php artisan migrate
   ```

7. **Serve the application**
   ```bash
   php artisan serve
   ```

## Usage

- **Admin Panel**: Access the admin dashboard at `http://localhost:8000/admin`
- **Customer Portal**: Access the customer portal at `http://localhost:8000`

## License

This project is licensed under the MIT License.
