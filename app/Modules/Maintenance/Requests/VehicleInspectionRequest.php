<?php

/**
 * @file: VehicleInspectionRequest.php
 * @description: التحقق من بيانات الفحوصات السنوية/الدورية - Maintenance Service (MT-07)
 * @module: Maintenance
 * @author: Team Leader (Khalid)
 */

namespace App\Modules\Maintenance\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VehicleInspectionRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'vehicle_id'          => 'required|integer|exists:vehicles,vehicle_id',
            'inspector_id'        => 'nullable|integer|exists:users,user_id',
            'inspection_type'     => 'required|in:annual,periodic,on_demand',
            'result'              => 'required|in:pass,fail,conditional_pass',
            'inspection_date'     => 'required|date',
            'next_inspection_date'=> 'required|date|after:inspection_date',
            'certificate_number'  => 'nullable|string|max:100',
            'failure_points'      => 'nullable|array',
            'failure_points.*'    => 'string|max:500',
            'cost'                => 'nullable|numeric|min:0',
            'notes'               => 'nullable|string|max:2000',
        ];
    }
}
