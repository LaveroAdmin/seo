# SEO & Redirects for LaveroAdmin

Manage `301`/`302`/`410` redirects with hit tracking, `robots.txt` editor, and global SEO meta tags.

## Requirements

- PHP >= 8.1
- `laveroadmin/laveroadmin`: `^1.0`

## Installation

```bash
composer require laveroadmin/seo
php artisan migrate
php artisan admin:import seo
```

Register `RedirectMiddleware` in your `web` middleware group (e.g., `bootstrap/app.php`):

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->web(append: [
        \LaveroAdmin\Admin\Seo\Middleware\RedirectMiddleware::class,
    ]);
})
```

## Features

- `301`/`302`/`410` redirect rules (`seo_redirects` table) with hit counter and `Cache::rememberForever` caching
- Built-in `robots.txt` editor
- Global SEO meta settings
- Full English and Russian localization

## License

The MIT License (MIT). See [LICENSE](LICENSE).
