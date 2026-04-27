<?php

/**
 * @file: BulkImportRequest.php
 * @description: التحقق من ملف استيراد الطلبات (CSV/XML) - Order Management Service (OM-01 / fn39)
 * @module: OrderManagement
 * @author: Team Leader (Khalid)
 */

namespace App\Modules\OrderManagement\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BulkImportRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'file'   => 'required|file|mimes:csv,txt,xml|max:10240', // 10MB max
            'format' => 'required|in:csv,xml',
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'ملف الاستيراد مطلوب',
            'file.mimes'    => 'يجب أن يكون الملف بصيغة CSV أو XML',
            'file.max'      => 'حجم الملف لا يتجاوز 10MB',
            'format.in'     => 'صيغة الملف يجب أن تكون csv أو xml',
        ];
    }
}
