<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;

class Link extends Model
{
    use HasFactory;

    public const CACHE_KEY = 'larabbs_links';

    protected $fillable = ['title', 'link'];

    public function getAllCached()
    {
        $linkIds = Cache::remember(self::CACHE_KEY, 1440 * 60, fn (): array => static::query()
            ->orderBy('id')
            ->pluck('id')
            ->map(fn ($id): int => (int) $id)
            ->all());

        return static::query()
            ->whereKey($linkIds)
            ->get()
            ->sortBy(fn (self $link): int => array_search($link->getKey(), $linkIds, true))
            ->values();
    }

    public static function forgetCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
