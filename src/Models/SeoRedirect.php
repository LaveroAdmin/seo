<?php

namespace LaveroAdmin\Admin\Seo\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class SeoRedirect extends Model
{
    public const CACHE_KEY = 'lavero_seo_redirects_all';

    protected $fillable = [
        'source_path',
        'target_url',
        'status_code',
        'is_active',
        'hits',
        'last_hit_at',
        'note',
    ];

    protected $casts = [
        'status_code' => 'integer',
        'is_active'   => 'boolean',
        'hits'        => 'integer',
        'last_hit_at' => 'datetime',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setConnection(config('admin.database.connection') ?: config('database.default'));
        $this->setTable(config('admin.extensions.seo.redirects_table', 'seo_redirects'));
    }

    public static function normalizePath(string $path): string
    {
        $parsed = parse_url(trim($path), PHP_URL_PATH);
        $clean = '/' . ltrim((string) ($parsed ?: $path), '/');

        return $clean !== '/' ? rtrim($clean, '/') : '/';
    }

    public static function getCachedMap(): array
    {
        try {
            return Cache::rememberForever(self::CACHE_KEY, function (): array {
                $instance = new static();
                $conn = $instance->getConnectionName();
                if (!Schema::connection($conn)->hasTable($instance->getTable())) {
                    return [];
                }

                $map = [];
                foreach (static::where('is_active', true)->get(['id', 'source_path', 'target_url', 'status_code']) as $row) {
                    $map[static::normalizePath((string) $row->source_path)] = [
                        'id'          => $row->id,
                        'target_url'  => $row->target_url,
                        'status_code' => (int) $row->status_code,
                    ];
                }

                return $map;
            });
        } catch (\Throwable) {
            return [];
        }
    }

    public static function flushCache(): void
    {
        try {
            Cache::forget(self::CACHE_KEY);
        } catch (\Throwable) {
        }
    }

    protected static function booted(): void
    {
        static::saving(function (self $model) {
            $model->source_path = static::normalizePath((string) $model->source_path);
        });

        static::saved(fn () => static::flushCache());
        static::deleted(fn () => static::flushCache());
    }
}
