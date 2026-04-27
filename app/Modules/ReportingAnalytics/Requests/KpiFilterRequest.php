<?php

/**
 * @file: KpiFilterRequest.php
 * @description: التحقق من فلاتر بيانات التقارير - Reporting & Analytics Service
 * @module: ReportingAnalytics
 * @author: Team Leader (Khalid)
 */

namespace App\Modules\ReportingAnalytics\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KpiFilterRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'period_start' => 'required|date',
            'period_end'   => 'required|date|after:period_start',
            'period_type'  => 'nullable|in:daily,weekly,monthly',
            'driver_id'    => 'nullable|integer|exists:users,user_id',
            'vehicle_id'   => 'nullable|integer|exists:vehicles,vehicle_id',
            'metric'       => 'nullable|string|in:on_time_rate,co2_emissions,driver_score,fuel_efficiency',
        ];
    }
}
