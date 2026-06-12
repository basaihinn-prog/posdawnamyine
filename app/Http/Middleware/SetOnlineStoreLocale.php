<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetOnlineStoreLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('lang')) {
            session(['store_lang' => $request->lang]);
        }

        $lang = session('store_lang', 'en');

        app()->setLocale($lang);

        return $next($request);
    }
}
