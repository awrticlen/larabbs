<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ChangeLocale
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->header('accept-language')) {
            $locale = $request->getPreferredLanguage([
                'zh_CN',
                'en',
            ]);

            if ($locale) {
                app()->setLocale($locale);
            }
        }

        return $next($request);
    }
}
