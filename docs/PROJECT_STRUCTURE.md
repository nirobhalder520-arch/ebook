# BoiMarket - Complete Project Structure

## Directory Layout

```
boimarket/
│
├── public/                          # Web Root
│   ├── index.php                    # Main entry point
│   ├── assets/
│   │   ├── css/
│   │   │   ├── main.css            # Main stylesheet
│   │   │   ├── bootstrap.min.css   # Bootstrap
│   │   │   ├── home.css            # Home page styles
│   │   │   ├── books.css           # Books page styles
│   │   │   ├── dashboard.css       # Dashboard styles
│   │   │   └── admin.css           # Admin panel styles
│   │   ├── js/
│   │   │   ├── main.js             # Main JS file
│   │   │   ├── search.js           # Search functionality
│   │   │   ├── cart.js             # Cart system
│   │   │   ├── auth.js             # Authentication JS
│   │   │   ├── dashboard.js        # Dashboard JS
│   │   │   ├── admin.js            # Admin JS
│   │   │   ├── pdf-viewer.js       # PDF viewer
│   │   │   └── utils.js            # Utility functions
│   │   ├── images/
│   │   │   ├── logo.png
│   │   │   ├── placeholder.png
│   │   │   └── icons/
│   │   ├── fonts/
│   │   └── uploads/
│   │       ├── books/              # Book covers and PDFs
│   │       │   ├── covers/
│   │       │   ├── pdfs/
│   │       │   └── previews/
│   │       ├── authors/            # Author images
│   │       ├── users/              # User avatars
│   │       └── temp/               # Temporary files
│   │
│   ├── download.php                # Secure PDF download
│   ├── api/
│   │   ├── cart.php
│   │   ├── search.php
│   │   ├── wishlist.php
│   │   ├── reviews.php
│   │   └── payment.php
│   │
│   └── pages/                       # Frontend pages
│       ├── home.php
│       ├── books.php
│       ├── book-details.php
│       ├── category.php
│       ├── author.php
│       ├── search-results.php
│       ├── cart.php
│       ├── checkout.php
│       ├── contact.php
│       ├── about.php
│       ├── privacy.php
│       ├── terms.php
│       ├── auth/
│       │   ├── login.php
│       │   ├── register.php
│       │   └── forgot-password.php
│       └── user/
│           ├── dashboard.php
│           ├── profile.php
│           ├── orders.php
│           ├── library.php
│           ├── wishlist.php
│           ├── downloads.php
│           └── reviews.php
│
├── app/                             # Application logic
│   ├── controllers/
│   │   ├── HomeController.php
│   │   ├── BookController.php
│   │   ├── AuthController.php
│   │   ├── CartController.php
│   │   ├── CheckoutController.php
│   │   ├── UserController.php
│   │   ├── AdminController.php
│   │   ├── PaymentController.php
│   │   └── SearchController.php
│   │
│   ├── models/
│   │   ├── User.php
│   │   ├── Book.php
│   │   ├── Author.php
│   │   ├── Publisher.php
│   │   ├── Category.php
│   │   ├── Order.php
│   │   ├── OrderItem.php
│   │   ├── Review.php
│   │   ├── Cart.php
│   │   ├── Wishlist.php
│   │   ├── Payment.php
│   │   ├── Coupon.php
│   │   ├── Download.php
│   │   ├── Newsletter.php
│   │   └── Setting.php
│   │
│   ├── services/
│   │   ├── AuthService.php
│   │   ├── CartService.php
│   │   ├── OrderService.php
│   │   ├── PaymentService.php
│   │   ├── MailService.php
│   │   ├── FileService.php
│   │   ├── PDFService.php
│   │   ├── SearchService.php
│   │   └── SecurityService.php
│   │
│   ├── middleware/
│   │   ├── AuthMiddleware.php
│   │   ├── AdminMiddleware.php
│   │   ├── CSRFMiddleware.php
│   │   ├── RateLimitMiddleware.php
│   │   └── ValidationMiddleware.php
│   │
│   └── helpers/
│       ├── Database.php
│       ├── Session.php
│       ├── Validator.php
│       ├── Cache.php
│       └── Logger.php
│
├── config/                          # Configuration
│   ├── database.php                 # DB configuration
│   ├── app.php                      # App configuration
│   ├── payment.php                  # Payment gateway configs
│   ├── mail.php                     # Email configuration
│   └── constants.php                # Application constants
│
├── database/                        # Database files
│   ├── schema.sql                   # Complete DB schema
│   ├── seeders/
│   │   ├── categories.sql
│   │   ├── authors.sql
│   │   ├── publishers.sql
│   │   ├── books.sql
│   │   └── sample-data.sql
│   └── migrations/
│       ├── 001-create-tables.sql
│       └── migrations-log.txt
│
├── admin/                           # Admin panel
│   ├── index.php                    # Admin dashboard
│   ├── login.php                    # Admin login
│   ├── users.php                    # User management
│   ├── authors.php                  # Author management
│   ├── publishers.php               # Publisher management
│   ├── categories.php               # Category management
│   ├── books.php                    # Book management
│   ├── orders.php                   # Order management
│   ├── coupons.php                  # Coupon management
│   ├── reviews.php                  # Review management
│   ├── payments.php                 # Payment management
│   ├── settings.php                 # Website settings
│   ├── analytics.php                # Analytics
│   ├── assets/
│   │   ├── css/
│   │   └── js/
│   └── pages/
│       ├── users/
│       ├── books/
│       ├── orders/
│       └── reports/
│
├── payment/                         # Payment gateway integrations
│   ├── bkash.php
│   ├── nagad.php
│   ├── rocket.php
│   ├── sslcommerz.php
│   └── stripe.php
│
├── docs/                            # Documentation
│   ├── INSTALLATION.md              # Installation guide
│   ├── DEPLOYMENT.md                # Deployment guide
│   ├── API.md                       # API documentation
│   ├── DATABASE.md                  # Database documentation
│   ├── SECURITY.md                  # Security guidelines
│   └── CONTRIBUTING.md              # Contribution guidelines
│
├── .htaccess                        # Apache rewrite rules
├── composer.json                    # PHP dependencies
├── .env.example                     # Environment variables template
└── LICENSE                          # MIT License
```

## Key Directories

### `/public` - Web Root
Publicly accessible files including CSS, JavaScript, images, and page files.

### `/app` - Application Logic
Core application files including controllers, models, services, and middleware.

### `/config` - Configuration
All configuration files for database, payment gateways, and application settings.

### `/database` - Database
Database schema, migrations, and seed data.

### `/admin` - Admin Panel
Complete admin dashboard for managing the platform.

### `/payment` - Payment Integration
Payment gateway integration files.

### `/docs` - Documentation
Complete project documentation.
