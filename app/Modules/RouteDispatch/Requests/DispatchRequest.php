<?php

/**
 * @file: DispatchRequest.php
 * @description: التحقق من بيانات تعيين السائق والمركبة - Route & Dispatch Service
 * @module: RouteDispatch
 * @author: Team Leader (Khalid)
 */

namespace App\Modules\RouteDispatch\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DispatchRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'route_id'   => 'required|integer|exists:routes,route_id',
            'driver_id'  => 'required|integer|exists:users,user_id',
            'vehicle_id' => 'required|integer|exists:vehicles,vehicle_id',
        ];
    }
}
