<?php

/**
 * @file: KpiService.php
 * @description: خدمة حساب مؤشرات الأداء الرئيسية - Reporting & Analytics Service (AN-01/02/03/04)
 * @module: ReportingAnalytics
 * @author: Team Leader (Khalid)
 */

namespace App\Modules\ReportingAnalytics\Services;

use Exception;

class KpiService
{
    /**
     * حساب نسبة التسليم في الموعد (AN-04 / fn41)
     * @param string $periodStart
     * @param string $periodEnd
     * @param int|null $driverId  null = fleet-wide
     * @return array  ['on_time_percentage' => float, 'total' => int, 'on_time' => int]
     */
    public function calculateOnTimeRate(string $periodStart, string $periodEnd, ?int $driverId = null): array
    {
        // 1. Query orders in period (status=delivered)
        $query = \App\Modules\OrderManagement\Models\Order::query()
            ->where('Status', 'delivered')
            ->whereBetween('DeliveredAt', [$periodStart, $periodEnd]);

        if ($driverId) {
            $query->where('DriverID(FK)', $driverId);
        }

        // Calculate total count
        $totalCount = $query->count();

        $onTimeCount = 0;
        $onTimePercentage = 0.0;

        if ($totalCount > 0) {
            // 2. Count orders where actual_arrival <= promised_window_end
            $onTimeCount = (clone $query)
                ->whereColumn('DeliveredAt', '<=', 'PromisedWindow')
                ->count();

            // 3. on_time_percentage = (on_time_count / total_count) * 100
            $onTimePercentage = round(($onTimeCount / $totalCount) * 100, 2);
        }

        // 4. Save snapshot to kpi_snapshots table
        \App\Modules\ReportingAnalytics\Models\KpiSnapshot::create([
            'metric_name' => 'on_time_delivery_rate',
            'metric_value' => $onTimePercentage,
            'metric_unit' => 'percentage',
            'period_type' => 'custom', // Defaulting to custom for ad-hoc periods
            'period_start' => $periodStart,
            'period_end' => $periodEnd,
            'entity_type' => $driverId ? 'driver' : 'fleet',
            'entity_id' => $driverId,
            'breakdown' => [
                'total' => $totalCount,
                'on_time' => $onTimeCount
            ]
        ]);

        // 5. Return result
        return [
            'on_time_percentage' => $onTimePercentage,
            'total' => $totalCount,
            'on_time' => $onTimeCount,
        ];
    }

    /**
     * حساب نقاط أداء السائق (AN-02 / fn22)
     * Score = (delivery_speed × A) + (fuel_efficiency × B) + (customer_rating × C)
     * Weights A, B, C configurable via config file
     * @param int $driverId
     * @param string $periodStart
     * @param string $periodEnd
     * @return array  ['composite_score' => float, 'breakdown' => array]
     */
    public function calculateDriverPerformanceScore(int $driverId, string $periodStart, string $periodEnd): array
    {
        // 1. Get configurable weights (default: delivery 40%, fuel 30%, rating 30%)
        $weights = config('analytics.performance_weights', [
            'delivery_speed'   => 0.4,
            'fuel_efficiency'  => 0.3,
            'customer_rating'  => 0.3,
        ]);

        // 2. Pull raw metrics from driver_performance table for this period
        $rawMetrics = \App\Modules\ReportingAnalytics\Models\DriverPerformance::query()
            ->forDriver($driverId)
            ->forPeriod($periodStart, $periodEnd)
            ->latest('period_start')
            ->first();

        // Defaults when no data exists for the period
        $onTimeRate          = $rawMetrics?->on_time_delivery_pct ?? 0.0;   // already 0–100
        $fuelPer100km        = $rawMetrics?->fuel_per_100km ?? 0.0;
        $customerRatingAvg   = $rawMetrics?->avg_customer_rating ?? 0.0;    // 0–5 scale
        $totalDeliveries     = $rawMetrics?->completed_trips ?? 0;
        $successfulDeliveries = $rawMetrics?->on_time_deliveries ?? 0;

        // 3. Normalize each component to 0–1 scale
        //    on_time_rate: already percentage → divide by 100
        $onTimeNormalized = min($onTimeRate / 100, 1.0);

        //    fuel_efficiency: lower fuel_per_100km is better
        //    baseline fleet average = 12.5 L/100km; perfect = 0 L/100km
        $fleetAvgFuelPer100km = 12.5;
        $fuelNormalized = ($fuelPer100km > 0)
            ? max(0, min(1, 1 - ($fuelPer100km / ($fleetAvgFuelPer100km * 2))))
            : 0.0;

        //    customer_rating: 0–5 scale → divide by 5
        $ratingNormalized = min($customerRatingAvg / 5, 1.0);

        // 4. composite_score = sum of (component × weight) × 100
        $compositeScore = round(
            ($onTimeNormalized * $weights['delivery_speed']
            + $fuelNormalized  * $weights['fuel_efficiency']
            + $ratingNormalized * $weights['customer_rating']) * 100,
            2
        );

        // 5. Build breakdown
        $breakdown = [
            'on_time_rate' => [
                'raw'        => $onTimeRate,
                'normalized' => round($onTimeNormalized, 4),
                'weight'     => $weights['delivery_speed'],
                'weighted'   => round($onTimeNormalized * $weights['delivery_speed'] * 100, 2),
            ],
            'fuel_efficiency' => [
                'raw'        => $fuelPer100km,
                'normalized' => round($fuelNormalized, 4),
                'weight'     => $weights['fuel_efficiency'],
                'weighted'   => round($fuelNormalized * $weights['fuel_efficiency'] * 100, 2),
            ],
            'customer_rating' => [
                'raw'        => $customerRatingAvg,
                'normalized' => round($ratingNormalized, 4),
                'weight'     => $weights['customer_rating'],
                'weighted'   => round($ratingNormalized * $weights['customer_rating'] * 100, 2),
            ],
        ];

        // 6. Persist to driver_performance_scores table (upsert by driver + period)
        \App\Modules\ReportingAnalytics\Models\DriverPerformanceScore::updateOrCreate(
            [
                'driver_id'    => $driverId,
                'period_start' => $periodStart,
                'period_end'   => $periodEnd,
            ],
            [
                'on_time_rate'          => $onTimeRate,
                'fuel_efficiency_score' => round($fuelNormalized * 100, 2),
                'customer_rating_avg'   => $customerRatingAvg,
                'composite_score'       => $compositeScore,
                'total_deliveries'      => $totalDeliveries,
                'successful_deliveries' => $successfulDeliveries,
                'breakdown'             => $breakdown,
            ]
        );

        // 7. Return score with breakdown
        return [
            'composite_score' => $compositeScore,
            'breakdown'       => $breakdown,
        ];
    }

    /**
     * تقرير انبعاثات CO2 (AN-03 / fn40)
     * @param string $period  (monthly | quarterly)
     * @return array  per-vehicle CO2 data with reduction suggestions
     */
    public function generateCO2Report(string $period): array
    {
        // TODO: Generate CO2 sustainability report
        // Formula: CO2 = distance_km × emission_factor (per vehicle type)
        // emission_factors: light=0.21 kg/km, heavy=0.37 kg/km, refrigerated=0.43 kg/km
        // Return sorted by emissions with reduction suggestions
    }

    /**
     * كشف الشذوذات (AN-07)
     * @param string $date
     * @return array  list of detected anomalies
     */
    public function detectAnomalies(string $date): array
    {
        // TODO: Detect anomalies
        // 1. Missing fuel: compare fuel invoices vs GPS distance traveled (fn24)
        // 2. Unusual speeds: GPS pings with speed > threshold
        // 3. Excessive stop durations: stops > 2x average stop time
        // 4. Return list of anomalies with severity and vehicle/driver info
    }
}
