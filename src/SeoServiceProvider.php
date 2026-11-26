<?php

namespace LaveroAdmin\Admin\Seo;

use Illuminate\Support\ServiceProvider;

class SeoServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'lavero-admin-seo');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'lavero-admin-seo');

        Seo::boot();
    }
}
