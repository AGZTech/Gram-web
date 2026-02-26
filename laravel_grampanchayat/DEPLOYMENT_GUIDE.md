# डिजिटल ग्रामपंचायत - Hostinger Deployment Guide

## 📋 पूर्व-आवश्यकता

1. Hostinger Shared Hosting Account
2. cPanel Access
3. PHP 8.1+ सक्षम
4. MySQL Database

---

## 🚀 Step-by-Step Deployment

### Step 1: Database Setup

1. Hostinger cPanel मध्ये लॉगिन करा
2. **MySQL Databases** वर क्लिक करा
3. नवीन database तयार करा: `grampanchayat_db`
4. नवीन user तयार करा आणि database ला assign करा
5. **ALL PRIVILEGES** द्या

### Step 2: Files Upload

1. cPanel मधून **File Manager** उघडा
2. `public_html` folder मध्ये जा
3. सर्व Laravel files upload करा (zip करून upload करा आणि extract करा)

### Step 3: Folder Structure

Hostinger वर folder structure असे असावे:

```
/home/username/
├── public_html/           # यात public folder मधील सर्व files
│   ├── index.php
│   ├── .htaccess
│   ├── css/
│   ├── js/
│   └── uploads/
│
└── laravel/               # public_html च्या बाहेर (secure location)
    ├── app/
    ├── bootstrap/
    ├── config/
    ├── database/
    ├── resources/
    ├── routes/
    ├── storage/
    ├── vendor/
    ├── .env
    └── composer.json
```

### Step 4: public/index.php Update

`public_html/index.php` मध्ये paths बदला:

```php
<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Paths for shared hosting
if (file_exists($maintenance = __DIR__.'/../laravel/storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/../laravel/vendor/autoload.php';

$app = require_once __DIR__.'/../laravel/bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
```

### Step 5: .env Configuration

`laravel/.env` file तयार करा:

```env
APP_NAME="डिजिटल ग्रामपंचायत"
APP_ENV=production
APP_KEY=base64:your-generated-key-here
APP_DEBUG=false
APP_URL=https://your-domain.com

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_cpanel_username_grampanchayat_db
DB_USERNAME=your_cpanel_username_dbuser
DB_PASSWORD=your_db_password

SESSION_DRIVER=file
SESSION_LIFETIME=120

CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```

### Step 6: Permissions

SSH किंवा File Manager द्वारे permissions सेट करा:

```bash
chmod -R 755 laravel/
chmod -R 775 laravel/storage/
chmod -R 775 laravel/bootstrap/cache/
chmod -R 775 public_html/uploads/
```

### Step 7: Composer Install

Hostinger Terminal किंवा SSH द्वारे:

```bash
cd ~/laravel
composer install --optimize-autoloader --no-dev
```

### Step 8: Laravel Setup

```bash
cd ~/laravel
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 9: Storage Link

```bash
cd ~/laravel
php artisan storage:link
```

किंवा manually symbolic link तयार करा:

```bash
ln -s ~/laravel/storage/app/public ~/public_html/storage
```

---

## 🔒 Security Checklist

- [ ] APP_DEBUG=false सेट करा
- [ ] APP_ENV=production सेट करा
- [ ] Strong database password वापरा
- [ ] Default admin password बदला
- [ ] .htaccess files योग्य आहेत का तपासा
- [ ] SSL Certificate active करा (Hostinger free SSL देते)

---

## 👤 Default Admin Login

- **Email:** admin@grampanchayat.gov.in
- **Password:** Admin@123

⚠️ **महत्वाचे:** पहिल्या लॉगिन नंतर लगेच password बदला!

---

## 🔧 Common Issues & Solutions

### Issue 1: 500 Internal Server Error

```bash
# Permissions fix
chmod -R 755 laravel/
chmod -R 775 laravel/storage/
chmod -R 775 laravel/bootstrap/cache/

# Clear cache
php artisan config:clear
php artisan cache:clear
```

### Issue 2: Class not found

```bash
composer dump-autoload
php artisan config:cache
```

### Issue 3: Storage link not working

Manually symbolic link तयार करा cPanel File Manager मधून.

### Issue 4: CSRF Token Mismatch

.env मध्ये SESSION_DOMAIN सेट करा:

```env
SESSION_DOMAIN=.your-domain.com
```

---

## 📞 Support

कोणतीही समस्या असल्यास Hostinger Support शी संपर्क साधा.

---

## 📝 Files Checklist

Upload करायच्या files:

1. ✅ app/ folder
2. ✅ bootstrap/ folder
3. ✅ config/ folder
4. ✅ database/ folder
5. ✅ resources/ folder
6. ✅ routes/ folder
7. ✅ storage/ folder
8. ✅ public/ folder contents → public_html/
9. ✅ composer.json
10. ✅ .env.example → .env (edit करा)
11. ✅ .htaccess files

---

**Last Updated:** January 2025
