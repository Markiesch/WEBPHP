<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        if (session()->has('locale')) {
            $locale = session()->get('locale');
        } else {
            // Attempt to detect from browser
            $locale = $request->getPreferredLanguage(config('languages.available'));

            // If no match, use default
            if (!$locale || !in_array($locale, config('languages.available'))) {
                $locale = config('app.locale');
            }

            // Store in session
            session()->put('locale', $locale);
        }

        // Set application locale
        App::setLocale($locale);

        return $next($request);
    }
}
