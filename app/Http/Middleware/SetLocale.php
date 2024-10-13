<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        // Check if the 'Accept-Language' header is present in the request
        $locale = $request->header('Accept-Language');

        // If a locale is provided and supported, set the application locale
        if (in_array($locale, ['az', 'en', 'ru'])) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}
