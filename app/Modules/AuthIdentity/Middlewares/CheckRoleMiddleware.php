<?php

/**
 * @file CheckRoleMiddleware.php
 * @description Gate-keeps routes by the authenticated user's role.
 *              Usage: Route::middleware('role:FleetManager,Dispatcher')
 * @module AuthIdentity
 * @author Team Leader (Khalid)
 */

namespace App\Modules\AuthIdentity\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param string ...$roles  One or more allowed roles (e.g., 'FleetManager', 'Dispatcher')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $userRole = Auth::user()->role;

        if (!in_array($userRole, $roles, true)) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. Insufficient role privileges.',
            ], 403);
        }

        return $next($request);
    }
}
