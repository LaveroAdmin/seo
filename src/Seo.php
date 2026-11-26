<?php

namespace LaveroAdmin\Admin\Seo;

use Illuminate\Routing\Router;
use LaveroAdmin\Admin\Admin;
use LaveroAdmin\Admin\Extension;

class Seo extends Extension
{
    public static function boot(): void
    {
        static::registerRoutes();
        Admin::extend('seo', __CLASS__);
    }

    protected static function registerRoutes(): void
    {
        parent::routes(function (Router $router) {
            $router->get('seo/robots', 'LaveroAdmin\Admin\Seo\Controllers\RobotsController@index')->name('seo.robots');
            $router->post('seo/robots', 'LaveroAdmin\Admin\Seo\Controllers\RobotsController@save')->name('seo.robots.save');
            $router->resource('seo/redirects', 'LaveroAdmin\Admin\Seo\Controllers\RedirectController', ['as' => 'seo']);
        });
    }

    public static function import(): void
    {
        parent::createMenu('SEO', 'seo', 'icon-search', 0, [
            [
                'title' => 'Redirects',
                'path'  => 'seo/redirects',
                'uri'   => 'seo/redirects',
                'icon'  => 'icon-directions',
            ],
            [
                'title' => 'robots.txt',
                'path'  => 'seo/robots',
                'uri'   => 'seo/robots',
                'icon'  => 'icon-file-alt',
            ],
        ]);

        parent::createPermission('SEO Manager', 'ext.seo', 'seo*');
    }
}
