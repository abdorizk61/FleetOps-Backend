<?php

/**
 * @file: RouteStopRequest.php
 * @description: التحقق من بيانات محطات المسار - Route & Dispatch Service
 * @module: RouteDispatch
 * @author: Team Leader (Khalid)
 */

namespace App\Modules\RouteDispatch\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RouteStopRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'route_id'              => 'required|integer|exists:routes,route_id',
            'order_id'              => 'required|integer|exists:orders,order_id',
            'sequence'              => 'nullable|integer|min:1',
            'stop_duration_min'     => 'nullable|integer|min:0',
            'distance_from_prev_km' => 'nullable|numeric|min:0',
            'notes'                 => 'nullable|string|max:500',
        ];
    }
}
