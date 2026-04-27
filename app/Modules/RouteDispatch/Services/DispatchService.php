<?php

/**
 * @file: DispatchService.php
 * @description: خدمة التعيين والتوزيع - ربط السائقين بالمسارات (RD-01 / fn01)
 * @module: RouteDispatch
 * @author: Team Leader (Khalid)
 */

namespace App\Modules\RouteDispatch\Services;

use App\Modules\RouteDispatch\Repositories\RouteRepository;
use App\Modules\RouteDispatch\Repositories\VehicleRepository;
use Exception;

class DispatchService
{
    protected RouteRepository $routeRepository;
    protected VehicleRepository $vehicleRepository;

    public function __construct(RouteRepository $routeRepository, VehicleRepository $vehicleRepository)
    {
        $this->routeRepository   = $routeRepository;
        $this->vehicleRepository = $vehicleRepository;
    }

    /**
     * تعيين سائق ومركبة لمسار (RD-01 / fn01)
     * @param int $routeId
     * @param int $driverId
     * @param int $vehicleId
     * @return mixed  updated Route
     * @throws Exception
     */
    public function assignDriverAndVehicle(int $routeId, int $driverId, int $vehicleId)
    {
        // TODO: Assign driver and vehicle to route
        // 1. Get route: must be in 'planned' status
        // 2. Get vehicle: must be 'available'
        // 3. Get driver: must be active, role = 'driver'
        // 4. Validate license match (RD-09 / fn08):
        //    vehicle->required_license_type === driver->license_type OR driver has heavy license for any vehicle
        // 5. Check driver not already active on another route
        // 6. Update route: driver_id, vehicle_id
        // 7. Update vehicle status to 'in_service' (tentative — confirmed on startRoute)
        // 8. Fire event: DriverAssigned
        // 9. Return updated route
    }

    /**
     * التحقق من تطابق الرخصة (RD-09 / fn08)
     * @param string $vehicleType  (light | heavy | refrigerated)
     * @param string $licenseType  (light | heavy)
     * @return bool
     */
    public function isLicenseCompatible(string $vehicleType, string $licenseType): bool
    {
        // TODO: Check license compatibility
        // Heavy license can drive light vehicles
        // Light license can only drive light vehicles
        // Heavy license required for: heavy, refrigerated
        // if ($licenseType === 'heavy') return true; // heavy can drive everything
        // return $vehicleType === 'light'; // light license only for light vehicles
    }

    /**
     * التحقق من أن السائق غير مشغول
     * @param int $driverId
     * @return bool  true = available
     */
    public function isDriverAvailable(int $driverId): bool
    {
        // TODO: Check driver has no active route
        // $activeRoute = $this->routeRepository->getDriverActiveRoute($driverId);
        // return $activeRoute === null;
    }

    /**
     * إعادة توزيع الطلبات عند تعطل مركبة (RD-07 / fn04)
     * @param int $brokenRouteId
     * @param array $availableRouteIds
     * @return array redistribution summary
     * @throws Exception
     */
    public function redistributeOnBreakdown(int $brokenRouteId, array $availableRouteIds): array
    {
        // TODO: Redistribute stops from broken route
        // 1. Get remaining stops from broken route (status = pending)
        // 2. Validate at least one available route exists
        // 3. Check capacity of each available route's vehicle
        // 4. Distribute stops load-balanced across available routes
        // 5. Recalculate ETAs for each affected route
        // 6. Set broken route status to 'cancelled'
        // 7. Notify customers of new ETAs via NotificationService
        // 8. Return redistribution summary per route
    }
}
