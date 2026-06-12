<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetAdminLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
       if ($request->has('lang')) {
            session(['admin_lang' => $request->lang]);
        }

        $lang =  session('admin_lang') ?? 'en';

        app()->setLocale($lang);

        return $next($request);
    }
}
