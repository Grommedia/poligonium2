# Poligonium HOSTiQ Deployment

Target domain: https://poligonium.com

## Important

This is a Laravel/Botble project. The web document root must point to the
`public` directory, not to the project root.

Do not expose these files to the web:

- `.env`
- `composer.json`
- `storage`
- `vendor`
- `app`
- `platform`
- `database`

## Recommended Hosting Structure

Preferred structure:

```text
/home/USER/poligonium/
  app/
  bootstrap/
  config/
  database/
  platform/
  public/
  storage/
  vendor/
  .env

Document root for poligonium.com:
/home/USER/poligonium/public
```

If cPanel does not allow setting the document root to `public`, use this
fallback:

```text
/home/USER/poligonium-app/
  app/
  bootstrap/
  config/
  database/
  platform/
  storage/
  vendor/
  .env

/home/USER/public_html/
  index.php
  .htaccess
  favicon.*
  storage -> ../poligonium-app/storage/app/public
```

In the fallback case, `public_html/index.php` must reference the real app path:

```php
require __DIR__ . '/../poligonium-app/vendor/autoload.php';
$app = require_once __DIR__ . '/../poligonium-app/bootstrap/app.php';
```

## Server Requirements

- PHP 8.2 or 8.3
- MySQL or MariaDB
- Composer 2
- PHP extensions: `curl`, `gd`, `json`, `pdo_mysql`, `zip`, `mbstring`,
  `openssl`, `fileinfo`, `tokenizer`, `xml`, `ctype`
- HTTPS enabled for `poligonium.com`

## First Deploy

Run in SSH:

```bash
cd ~
git clone https://github.com/Grommedia/poligonium2.git poligonium
cd poligonium
composer install --no-dev --optimize-autoloader
cp .env.example .env
php artisan key:generate --force
```

Edit `.env` on the hosting:

```env
APP_NAME="Poligonium"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://poligonium.com
ADMIN_DIR=admin
CMS_ENABLE_INSTALLER=false

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=YOUR_DATABASE
DB_USERNAME=YOUR_DATABASE_USER
DB_PASSWORD=YOUR_DATABASE_PASSWORD

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

Then run:

```bash
php artisan migrate --force
php artisan storage:link
php artisan cms:publish:assets
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan config:cache
php artisan route:cache
```

Set writable permissions:

```bash
chmod -R 775 storage bootstrap/cache
```

## Database And Media

The Git repository does not include the local database dump or uploaded media.
This is intentional.

Before production launch:

1. Export local MySQL database.
2. Import it into the hosting database.
3. Upload `public/storage` media to production storage.
4. Verify that `public/storage` symlink points to `storage/app/public`.

## Update Deploy

For later updates:

```bash
cd ~/poligonium
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan cms:publish:assets
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan config:cache
php artisan route:cache
```

## Smoke Test

Check these pages after deploy:

- `https://poligonium.com/`
- `https://poligonium.com/portfolio`
- `https://poligonium.com/vfx-showreel`
- `https://poligonium.com/courses`
- `https://poligonium.com/school/login`
- `https://poligonium.com/admin`
- `https://poligonium.com/sitemap.xml`
- `https://poligonium.com/robots.txt`

## Security Checklist

- `.env` is not accessible from the browser.
- Installer is disabled: `CMS_ENABLE_INSTALLER=false`.
- `APP_DEBUG=false`.
- HTTPS works.
- Admin password changed after import.
- GitHub repository made private after hosting has access configured.
- Backups enabled in HOSTiQ/cPanel.
