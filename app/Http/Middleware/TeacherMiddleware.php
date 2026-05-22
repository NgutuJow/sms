<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TeacherMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Check if user has teacher role/type or has teacher record
        if (($user->type !== 'teacher' && !$user->hasRole('teacher')) && !$user->teacher) {
            abort(403, 'Unauthorized. Teacher access required.');
        }

        return $next($request);
    }
}
