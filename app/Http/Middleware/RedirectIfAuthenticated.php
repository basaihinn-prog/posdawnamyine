<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Nwidart\Modules\Facades\Module;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {

                $user = auth()->user();

                // Roles that depend on RestaurantWebAddon
                if (in_array($user->role, ['shop-owner', 'staff', 'chef', 'kitchen'])) {

                    $module = Module::find('RestaurantWebAddon');

                    if (!$module) {
                        Auth::logout();
                        return redirect(route('login'))
                            ->with('warning', 'Web addon is not installed.');
                    }

                    if (!$module->isEnabled()) {
                        Auth::logout();
                        return redirect(route('login'))
                            ->with('warning', 'Web addon is not active.');
                    }

                    // Redirect by role
                    if (in_array($user->role, ['chef', 'kitchen'])) {
                        return redirect(route('kitchen.kots.index', ['status' => 'pending']))
                            ->with('warning', 'You are already logged in!');
                    }

                    // shop-owner & staff
                    return redirect(route('business.dashboard.index'))
                        ->with('warning', 'You are already logged in!');
                }

                // Admin or other roles
                return redirect(route('admin.dashboard.index'))
                    ->with('warning', 'You are already logged in!');
            }
        }

        return $next($request);
    }

}
