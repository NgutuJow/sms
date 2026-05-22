<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StudentMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Check if user has student role/type or has student record
        if (($user->type !== 'student' && !$user->hasRole('student')) && !$user->student) {
            abort(403, 'Unauthorized. Student access required.');
        }

        return $next($request);
    }
}
