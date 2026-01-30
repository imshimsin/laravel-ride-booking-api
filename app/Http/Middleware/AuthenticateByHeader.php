<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateByHeader
{
    public function handle(Request $request, Closure $next): Response
    {
        $userId = $request->header('X-User-Id');

        if (empty($userId) || ! is_numeric($userId)) {
            return response()->json(['message' => 'X-User-Id header required'], 401);
        }

        $user = User::find((int) $userId);
        if (! $user) {
            return response()->json(['message' => 'User not found. Run: php artisan db:seed'], 401);
        }

        $request->setUserResolver(fn () => $user);

        return $next($request);
    }
}
