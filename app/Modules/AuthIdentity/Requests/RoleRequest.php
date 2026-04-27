<?php

/**
 * @file: RoleRequest.php
 * @description: التحقق من بيانات الأدوار - Auth & Identity Service
 * @module: AuthIdentity
 * @author: Team Leader (Khalid)
 */

namespace App\Modules\AuthIdentity\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $roleId = $this->route('id') ?? null;

        return [
            'name'           => 'required|string|max:255|unique:roles,name,' . $roleId . ',role_id',
            'slug'           => 'required|string|max:255|unique:roles,slug,' . $roleId . ',role_id',
            'description'    => 'nullable|string|max:1000',
            'is_system_role' => 'required|boolean',
            'permissions'    => 'nullable|array',
            'permissions.*'  => 'integer|exists:permissions,permission_id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'        => 'اسم الدور مطلوب',
            'name.unique'          => 'اسم الدور مستخدم بالفعل',
            'slug.required'        => 'Slug الدور مطلوب',
            'slug.unique'          => 'Slug الدور مستخدم بالفعل',
            'is_system_role.required' => 'حقل is_system_role مطلوب',
            'permissions.*.exists' => 'الصلاحية المحددة غير موجودة',
        ];
    }
}
