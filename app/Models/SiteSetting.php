<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    use HasFactory;

    public const CACHE_KEY = 'site-settings.current';

    protected $fillable = [
        'site_name',
        'contact_email',
        'seo_description',
        'seo_keyword',
    ];

    public static function current(): self
    {
        return static::query()->first() ?? tap(new static(), fn (self $settings) => $settings->save());
    }

    public static function values(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, fn (): array => static::current()->only([
            'site_name',
            'contact_email',
            'seo_description',
            'seo_keyword',
        ]));
    }

    public static function forgetCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    protected static function booted(): void
    {
        static::saved(fn (): bool => Cache::forget(self::CACHE_KEY));
        static::deleted(fn (): bool => Cache::forget(self::CACHE_KEY));
    }
}