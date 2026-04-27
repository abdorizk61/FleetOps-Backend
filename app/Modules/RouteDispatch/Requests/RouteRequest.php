<?php

/**
 * @file: RouteRequest.php
 * @description: التحقق من بيانات المسارات - Route & Dispatch Service
 * @module: RouteDispatch
 * @author: Team Leader (Khalid)
 */

namespace App\Modules\RouteDispatch\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RouteRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'vehicle_id'  => 'required|integer|exists:vehicles,vehicle_id',
            'driver_id'   => 'required|integer|exists:users,user_id',
            'shift'       => 'required|in:morning,evening,night',
            'notes'       => 'nullable|string|max:1000',
        ];
    }
}
