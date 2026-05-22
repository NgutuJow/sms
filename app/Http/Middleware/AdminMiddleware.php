<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Check if user has admin role/type
        if ($user->type !== 'admin' && !$user->hasRole('admin')) {
            abort(403, 'Unauthorized. Admin access required.');
        }

        return $next($request);
    }
}
