<?php
/**
 * BoiMarket - Application Configuration
 * Core application settings and constants
 */

// ============================================
// SITE INFORMATION
// ============================================
define('SITE_NAME', 'BoiMarket');
define('SITE_TAGLINE', 'Your Ultimate Ebook Marketplace');
define('SITE_URL', getenv('SITE_URL') ?: 'http://localhost/boimarket');
define('ADMIN_URL', SITE_URL . '/admin');
define('API_URL', SITE_URL . '/api');

// ============================================
// FILE PATHS
// ============================================
define('ROOT_PATH', dirname(dirname(__FILE__)));
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('UPLOADS_PATH', PUBLIC_PATH . '/assets/uploads');
define('BOOKS_PATH', UPLOADS_PATH . '/books');
define('COVERS_PATH', BOOKS_PATH . '/covers');
define('PDFS_PATH', BOOKS_PATH . '/pdfs');
define('PREVIEWS_PATH', BOOKS_PATH . '/previews');
define('TEMP_PATH', UPLOADS_PATH . '/temp');
define('LOGS_PATH', ROOT_PATH . '/logs');

// ============================================
// URL PATHS
// ============================================
define('UPLOADS_URL', SITE_URL . '/assets/uploads');
define('BOOKS_URL', UPLOADS_URL . '/books');
define('COVERS_URL', BOOKS_URL . '/covers');
define('PDFS_URL', BOOKS_URL . '/pdfs');
define('PREVIEWS_URL', BOOKS_URL . '/previews');
define('ASSETS_URL', SITE_URL . '/assets');
define('CSS_URL', ASSETS_URL . '/css');
define('JS_URL', ASSETS_URL . '/js');
define('IMG_URL', ASSETS_URL . '/images');

// ============================================
// SESSION CONFIGURATION
// ============================================
define('SESSION_NAME', 'BOIMARKET_SESSION');
define('SESSION_LIFETIME', 86400); // 24 hours
define('REMEMBER_ME_LIFETIME', 2592000); // 30 days
define('SESSION_PATH', '/');
define('SESSION_DOMAIN', getenv('SESSION_DOMAIN') ?: '');
define('SESSION_SECURE', false);
define('SESSION_HTTPONLY', true);

// ============================================
// SECURITY
// ============================================
define('HASH_ALGORITHM', 'sha256');
define('CSRF_TOKEN_NAME', 'csrf_token');
define('CSRF_TOKEN_LIFETIME', 3600); // 1 hour
define('PASSWORD_MIN_LENGTH', 8);
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_ATTEMPT_TIMEOUT', 900); // 15 minutes

// ============================================
// PAGINATION
// ============================================
define('ITEMS_PER_PAGE', 12);
define('ADMIN_ITEMS_PER_PAGE', 20);
define('BOOKS_GRID_COLS', 4);
define('BOOKS_GRID_MOBILE_COLS', 2);
define('BOOKS_GRID_TABLET_COLS', 3);

// ============================================
// FILE UPLOAD
// ============================================
define('MAX_UPLOAD_SIZE', 52428800); // 50MB
define('MAX_IMAGE_SIZE', 5242880); // 5MB
define('MAX_PDF_SIZE', 52428800); // 50MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
define('ALLOWED_PDF_TYPES', ['application/pdf']);
define('IMAGE_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
define('PDF_EXTENSIONS', ['pdf']);
define('MAX_IMAGE_WIDTH', 2000);
define('MAX_IMAGE_HEIGHT', 2000);
define('IMAGE_QUALITY', 85);

// ============================================
// PAYMENT CONFIGURATION
// ============================================
define('CURRENCY', 'BDT');
define('CURRENCY_SYMBOL', '৳');
define('TAX_PERCENTAGE', 0);
define('PAYMENT_METHODS', ['bkash', 'nagad', 'rocket', 'sslcommerz', 'stripe']);

// ============================================
// EMAIL CONFIGURATION
// ============================================
define('MAIL_FROM', getenv('MAIL_FROM') ?: 'noreply@boimarket.local');
define('MAIL_FROM_NAME', SITE_NAME);
define('MAIL_HOST', getenv('MAIL_HOST') ?: 'smtp.gmail.com');
define('MAIL_PORT', getenv('MAIL_PORT') ?: 587);
define('MAIL_USERNAME', getenv('MAIL_USERNAME') ?: '');
define('MAIL_PASSWORD', getenv('MAIL_PASSWORD') ?: '');
define('MAIL_ENCRYPTION', getenv('MAIL_ENCRYPTION') ?: 'tls');

// ============================================
// FEATURE FLAGS
// ============================================
define('ENABLE_REVIEWS', true);
define('ENABLE_WISHLISTS', true);
define('ENABLE_NEWSLETTER', true);
define('ENABLE_RATINGS', true);
define('ENABLE_SOCIAL_SHARING', true);
define('ENABLE_DARK_MODE', true);
define('ENABLE_WISHLIST_NOTIFICATIONS', true);
define('ENABLE_PRICE_DROP_ALERTS', true);

// ============================================
// PDF CONFIGURATION
// ============================================
define('MAX_PDF_DOWNLOADS', 5);
define('PDF_DOWNLOAD_EXPIRY', 30); // days
define('PDF_WATERMARK_ENABLED', true);
define('PDF_WATERMARK_TYPE', 'text'); // text or image
define('PDF_PROTECTION_ENABLED', true);
define('PDF_ENCRYPTION_PASSWORD', getenv('PDF_PASSWORD') ?: 'BoiMarket2024');

// ============================================
// SEARCH CONFIGURATION
// ============================================
define('SEARCH_MIN_CHARS', 2);
define('SEARCH_MAX_RESULTS', 50);
define('ENABLE_LIVE_SEARCH', true);
define('ENABLE_SEARCH_SUGGESTIONS', true);

// ============================================
// REVIEWS CONFIGURATION
// ============================================
define('MIN_REVIEW_LENGTH', 10);
define('MAX_REVIEW_LENGTH', 1000);
define('AUTO_APPROVE_REVIEWS', false);
define('VERIFIED_PURCHASE_REVIEWS', true);

// ============================================
// COUPONS CONFIGURATION
// ============================================
define('COUPON_MIN_DISCOUNT', 0);
define('COUPON_MAX_DISCOUNT', 100);
define('COUPON_MAX_USAGE_PER_USER', 1);

// ============================================
// IMAGE OPTIMIZATION
// ============================================
define('ENABLE_IMAGE_LAZY_LOADING', true);
define('ENABLE_IMAGE_COMPRESSION', true);
define('ENABLE_WEBP_CONVERSION', true);

// ============================================
// DEBUG & LOGGING
// ============================================
define('DEBUG_MODE', getenv('DEBUG_MODE') ?: false);
define('LOG_ERRORS', true);
define('LOG_QUERIES', false);
define('LOG_LEVEL', 'info'); // debug, info, warning, error

// ============================================
// TIMEZONE
// ============================================
define('DEFAULT_TIMEZONE', 'Asia/Dhaka');
date_default_timezone_set(DEFAULT_TIMEZONE);

// ============================================
// API CONFIGURATION
// ============================================
define('API_RATE_LIMIT', 1000); // requests per hour
define('API_TIMEOUT', 30); // seconds
define('API_VERSION', '1.0');

// ============================================
// CACHE CONFIGURATION
// ============================================
define('ENABLE_CACHE', true);
define('CACHE_DRIVER', 'file'); // file or redis
define('CACHE_TTL', 3600); // 1 hour
define('CACHE_PATH', TEMP_PATH . '/cache');

// ============================================
// SEO CONFIGURATION
// ============================================
define('ENABLE_SEO_FRIENDLY_URLS', true);
define('ENABLE_SITEMAP', true);
define('ENABLE_ROBOTS_TXT', true);
define('ENABLE_OPEN_GRAPH', true);
define('ENABLE_TWITTER_CARDS', true);

// ============================================
// SOCIAL CONFIGURATION
// ============================================
define('SOCIAL_FACEBOOK', getenv('SOCIAL_FACEBOOK') ?: '');
define('SOCIAL_TWITTER', getenv('SOCIAL_TWITTER') ?: '');
define('SOCIAL_INSTAGRAM', getenv('SOCIAL_INSTAGRAM') ?: '');
define('SOCIAL_LINKEDIN', getenv('SOCIAL_LINKEDIN') ?: '');

// ============================================
// ANALYTICS
// ============================================
define('ENABLE_ANALYTICS', true);
define('GOOGLE_ANALYTICS_ID', getenv('GOOGLE_ANALYTICS_ID') ?: '');

// ============================================
// COLOR PALETTE
// ============================================
define('COLOR_PRIMARY', '#2563eb');
define('COLOR_SECONDARY', '#1e293b');
define('COLOR_SUCCESS', '#22c55e');
define('COLOR_DANGER', '#ef4444');
define('COLOR_WARNING', '#f59e0b');
define('COLOR_INFO', '#3b82f6');
define('COLOR_LIGHT', '#f8fafc');
define('COLOR_DARK', '#1e293b');

?>
