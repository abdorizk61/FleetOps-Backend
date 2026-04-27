<?php

/**
 * @file: WorkOrderRequest.php
 * @description: التحقق من بيانات أوامر العمل - Maintenance Service
 * @module: Maintenance
 * @author: Team Leader (Khalid)
 */

namespace App\Modules\Maintenance\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkOrderRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'vehicle_id'  => 'required|integer|exists:vehicles,vehicle_id',
            'mechanic_id' => 'nullable|integer|exists:users,user_id',
            'type'        => 'required|in:routine,emergency,breakdown',
            'description' => 'required|string|max:2000',
            'priority'    => 'nullable|in:low,medium,high,critical',
            'odometer_at_service' => 'nullable|numeric|min:0',
            'notes'       => 'nullable|string|max:1000',
        ];
    }
}
