# IIAQATAR - Islamic Institute of Advanced Quality Assurance Training & Research

A comprehensive Laravel-based web application for managing blogs, events, courses, and member subscriptions. This application was converted from WordPress to Laravel with enhanced features and modern architecture.

## Features

### 🎓 Course Management
- Comprehensive course catalog with categories
- Course lessons and curriculum management
- Student enrollment tracking
- Progress monitoring
- Support for free and paid courses
- Featured courses highlighting
- Multiple difficulty levels (Beginner, Intermediate, Advanced)

### 📅 Event Management
- Event calendar and listings
- Event registration system
- Support for online, offline, and hybrid events
- Payment integration ready
- Registration deadline management
- Attendee capacity limits

### 📝 Blog System
- Rich blog post management
- Categories and tags
- Featured images
- Comment system with moderation
- Post views tracking
- SEO-friendly URLs

### 👥 Membership & Subscriptions
- User registration and authentication
- Role-based access control (Admin, Instructor, Member, User)
- Subscription plans (Monthly, Quarterly, Yearly)
- Stripe integration ready
- Member dashboard

### 🔐 Security & Permissions
- Role-based permissions using Spatie Laravel Permission
- Secure authentication with Laravel Sanctum
- CSRF protection
- XSS protection
- SQL injection prevention

## Technology Stack

- **Framework:** Laravel 10.x
- **PHP:** 8.1+
- **Database:** MySQL 8.0+
- **Frontend:** Tailwind CSS, Alpine.js
- **Build Tool:** Vite
- **Authentication:** Laravel Sanctum
- **Permissions:** Spatie Laravel Permission
- **Payments:** Laravel Cashier (Stripe)

## Installation

### Prerequisites

- PHP 8.1 or higher
- Composer
- Node.js 18+ and NPM
- MySQL 8.0+
- Git

### Step 1: Clone the Repository

```bash
git clone https://github.com/Idris7umed/iiaqatar.git
cd iiaqatar
```

### Step 2: Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### Step 3: Environment Configuration

```bash
# Copy the example environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

Edit the `.env` file and configure your database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=iiaqatar
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Step 4: Database Setup

```bash
# Run migrations
php artisan migrate

# Seed the database with sample data
php artisan db:seed
```

This will create:
- Sample users (admin, instructor, member, user)
- Categories and tags
- Sample blog posts
- Sample events
- Sample courses with lessons

**Default Admin Credentials:**
- Email: admin@iiaqatar.org
- Password: password

### Step 5: Build Frontend Assets

```bash
# Development
npm run dev

# Production
npm run build
```

### Step 6: Start the Development Server

```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## Configuration

### Stripe Payment Integration (Optional)

To enable payment features for courses and events:

1. Sign up for a Stripe account at https://stripe.com
2. Get your API keys from the Stripe dashboard
3. Add to your `.env` file:

```env
STRIPE_KEY=your_publishable_key
STRIPE_SECRET=your_secret_key
STRIPE_WEBHOOK_SECRET=your_webhook_secret
```

### Email Configuration

Configure your mail settings in `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="hello@iiaqatar.org"
MAIL_FROM_NAME="${APP_NAME}"
```

## User Roles & Permissions

### Admin
- Full access to all features
- User management
- Content management (posts, events, courses)
- Subscription management

### Instructor
- Create and manage courses
- Create blog posts
- View enrollment data

### Member
- Access to all published content
- Enroll in courses
- Register for events
- Comment on posts

### User
- Basic access to public content
- Can register for events and courses (with payment)

## API Documentation

The application provides a RESTful API for external integrations:

### Public Endpoints

```
GET /api/v1/posts          - List all published posts
GET /api/v1/posts/{slug}   - Get single post
GET /api/v1/events         - List all upcoming events
GET /api/v1/events/{slug}  - Get single event
GET /api/v1/courses        - List all published courses
GET /api/v1/courses/{slug} - Get single course
```

### Protected Endpoints (Require Authentication)

```
POST /api/v1/events/{event}/register  - Register for an event
POST /api/v1/courses/{course}/enroll  - Enroll in a course
```

Use Laravel Sanctum tokens for API authentication.

## Development

### Running Tests

```bash
php artisan test
```

### Code Style

```bash
# Format code with Laravel Pint
./vendor/bin/pint
```

### Database Refresh

To reset and reseed the database:

```bash
php artisan migrate:fresh --seed
```

## Deployment

### Production Checklist

1. Set `APP_ENV=production` and `APP_DEBUG=false` in `.env`
2. Run `composer install --optimize-autoloader --no-dev`
3. Run `npm run build`
4. Run `php artisan config:cache`
5. Run `php artisan route:cache`
6. Run `php artisan view:cache`
7. Set up proper file permissions for storage and bootstrap/cache
8. Configure your web server (Apache/Nginx)
9. Set up SSL certificate
10. Configure regular backups

### Server Requirements

- PHP 8.1+
- MySQL 8.0+ or PostgreSQL
- Composer
- Node.js and NPM (for building assets)
- Supervisor (for queue workers, if using)

## Project Structure

```
iiaqatar/
├── app/
│   ├── Console/          # Artisan commands
│   ├── Exceptions/       # Exception handlers
│   ├── Http/
│   │   ├── Controllers/  # Application controllers
│   │   ├── Middleware/   # HTTP middleware
│   │   └── Kernel.php
│   ├── Models/           # Eloquent models
│   └── Providers/        # Service providers
├── database/
│   ├── migrations/       # Database migrations
│   ├── seeders/         # Database seeders
│   └── factories/       # Model factories
├── resources/
│   ├── css/             # CSS files
│   ├── js/              # JavaScript files
│   └── views/           # Blade templates
├── routes/
│   ├── api.php          # API routes
│   ├── web.php          # Web routes
│   └── console.php      # Console routes
└── public/              # Public assets
```

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Support

For support, email support@iiaqatar.org or visit our contact page.

## License

This project is licensed under the MIT License.

## Credits

Developed for IIAQATAR - Islamic Institute of Advanced Quality Assurance Training & Research

---

© 2024 IIAQATAR. All rights reserved.