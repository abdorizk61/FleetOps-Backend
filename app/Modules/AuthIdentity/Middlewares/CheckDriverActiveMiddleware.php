<?php

/**
 * @file CheckDriverActiveMiddleware.php
 * @description Ensures the authenticated user has an active Driver profile
 *              with a status that permits route operations (OnShift / Available).
 *              Usage: Route::middleware('driver.active')
 * @module AuthIdentity
 * @author Team Leader (Khalid)
 */

namespace App\Modules\AuthIdentity\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Modules\AuthIdentity\Models\Driver;
use Symfony\Component\HttpFoundation\Response;

class CheckDriverActiveMiddleware
{
    /**
     * Handle an incoming request.
     * Rejects requests from drivers who are not in an active state.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $driver = Driver::find(Auth::id());

        if (!$driver) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. No driver profile found.',
            ], 403);
        }

        // Only OnShift and Available drivers may access protected routes
        $activeStatuses = ['Available', 'OnShift'];

        if (!in_array($driver->status, $activeStatuses, true)) {
            return response()->json([
                'success' => false,
                'message' => sprintf(
                    'Forbidden. Driver status "%s" does not permit this action.',
                    $driver->status
                ),
            ], 403);
        }

        return $next($request);
    }
}
