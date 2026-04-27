<?php

/**
 * @file: SparePartRequest.php
 * @description: التحقق من بيانات قطع الغيار - Maintenance Service
 * @module: Maintenance
 * @author: Team Leader (Khalid)
 */

namespace App\Modules\Maintenance\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SparePartRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $partId = $this->route('id') ?? null;
        return [
            'name'               => 'required|string|max:255',
            'sku'                => 'nullable|string|max:100|unique:spare_parts,sku,' . $partId . ',part_id',
            'category'           => 'required|in:oil,filter,tire,brake,battery,bulb,other',
            'unit_price'         => 'required|numeric|min:0',
            'stock_quantity'     => 'required|integer|min:0',
            'minimum_stock'      => 'required|integer|min:0',
            'reorder_level'      => 'nullable|integer|min:0',
            'supplier_name'      => 'nullable|string|max:255',
            'supplier_lead_days' => 'nullable|integer|min:1',
            'unit'               => 'nullable|in:pcs,liter,kg',
        ];
    }
}
