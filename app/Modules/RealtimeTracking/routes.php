<?php

/**
 * @file: routes.php
 * @description: Real-time Tracking & GPS Service Routes
 * @module: RealtimeTracking
 * @author: Team Leader (Khalid)
 */

use Illuminate\Support\Facades\Route;
use App\Modules\RealtimeTracking\Controllers\LocationController;
use App\Modules\RealtimeTracking\Controllers\GeofenceController;
use App\Modules\RealtimeTracking\Controllers\TrackingLinkController;

// =====================================================================
// Public Route (No Auth - Customer Tracking Link)
// =====================================================================
// GET /api/v1/tracking/public/{token}
Route::get('api/v1/tracking/public/{token}', [TrackingLinkController::class, 'publicTracking'])
    ->name('tracking.public')
    ->middleware('throttle:30,1');

// =====================================================================
// Protected Routes (Sanctum Auth Required)
// =====================================================================
Route::prefix('api/v1/tracking')->middleware('auth:sanctum')->group(function () {

    // ── GPS Location Updates ──────────────────────────────────────────

    // POST /api/v1/tracking/location  (Driver posts GPS ping every 5 sec)
    Route::post('/location', [LocationController::class, 'ingest'])
        ->name('tracking.location.ingest');

    // GET  /api/v1/tracking/drivers/{driverId}/last-location  (Last Known Location RT-07)
    Route::get('/drivers/{driverId}/last-location', [LocationController::class, 'lastKnown'])
        ->name('tracking.driver.last-location')
        ->where('driverId', '[0-9]+');

    // GET  /api/v1/tracking/drivers/{driverId}/status  (Heartbeat check RT-05)
    Route::get('/drivers/{driverId}/status', [LocationController::class, 'driverStatus'])
        ->name('tracking.driver.status')
        ->where('driverId', '[0-9]+');

    // GET  /api/v1/tracking/routes/{routeId}/trail  (Historical Playback fn38)
    Route::get('/routes/{routeId}/trail', [LocationController::class, 'routeTrail'])
        ->name('tracking.route.trail')
        ->where('routeId', '[0-9]+');

    // ── Geofence Management (RT-04) ───────────────────────────────────

    Route::prefix('geofences')->group(function () {
        Route::get('/',     [GeofenceController::class, 'index'])->name('geofences.index');
        Route::post('/',    [GeofenceController::class, 'store'])->name('geofences.store');
        Route::get('/{id}', [GeofenceController::class, 'show'])->name('geofences.show')->where('id', '[0-9]+');
        Route::put('/{id}', [GeofenceController::class, 'update'])->name('geofences.update')->where('id', '[0-9]+');
        Route::delete('/{id}', [GeofenceController::class, 'destroy'])->name('geofences.destroy')->where('id', '[0-9]+');
    });

    // ── Tracking Links (RT-01 / fn33) ─────────────────────────────────

    // POST /api/v1/tracking/links  (Generate tracking link for an order)
    Route::post('/links', [TrackingLinkController::class, 'generate'])
        ->name('tracking.links.generate');

    // DELETE /api/v1/tracking/links/{id}  (Revoke tracking link)
    Route::delete('/links/{id}', [TrackingLinkController::class, 'revoke'])
        ->name('tracking.links.revoke')
        ->where('id', '[0-9]+');
});
