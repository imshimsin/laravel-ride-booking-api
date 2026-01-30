<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsPassenger
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->isPassenger()) {
            return response()->json(['message' => 'Only passengers can access this resource'], 403);
        }

        return $next($request);
    }
}
