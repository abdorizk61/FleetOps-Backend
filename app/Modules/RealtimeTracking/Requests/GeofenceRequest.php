<?php

/**
 * @file: GeofenceRequest.php
 * @description: التحقق من بيانات المناطق الجغرافية - Real-time Tracking & GPS Service
 * @module: RealtimeTracking
 * @author: Team Leader (Khalid)
 */

namespace App\Modules\RealtimeTracking\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GeofenceRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'type'        => 'required|in:circle,polygon,rectangle',
            'center_lat'  => 'required_if:type,circle|numeric|between:-90,90',
            'center_lng'  => 'required_if:type,circle|numeric|between:-180,180',
            'radius_m'    => 'required_if:type,circle|numeric|min:1',
            'coordinates' => 'required_if:type,polygon|array|min:3',
            'coordinates.*.lat' => 'numeric|between:-90,90',
            'coordinates.*.lng' => 'numeric|between:-180,180',
            'is_active'   => 'boolean',
            'trigger_on'  => 'required|in:entry,exit,both',
            'description' => 'nullable|string|max:1000',
        ];
    }
}
