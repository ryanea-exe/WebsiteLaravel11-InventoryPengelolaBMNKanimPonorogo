<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            abort(403, 'Unauthorized');
        }

        if (!in_array(Auth::user()->role, $roles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini');
        }

        return $next($request);
    }
}
