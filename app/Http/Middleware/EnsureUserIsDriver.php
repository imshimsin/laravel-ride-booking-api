<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsDriver
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->isDriver()) {
            return response()->json(['message' => 'Only drivers can access this resource'], 403);
        }

        return $next($request);
    }
}
