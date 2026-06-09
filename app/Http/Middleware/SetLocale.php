<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /** @var list<string> */
    public const SUPPORTED = ['fr', 'en', 'ar'];

    public function handle(Request $request, Closure $next): Response
    {
        $locale = session('locale', config('app.locale', 'fr'));

        if (! in_array($locale, self::SUPPORTED, true)) {
            $locale = 'fr';
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
