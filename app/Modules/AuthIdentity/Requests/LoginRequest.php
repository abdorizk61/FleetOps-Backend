<?php

/**
 * @file: LoginRequest.php
 * @description: التحقق من بيانات تسجيل الدخول - Auth & Identity Service
 * @module: AuthIdentity
 * @author: Team Leader (Khalid)
 */

namespace App\Modules\AuthIdentity\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'    => 'required|email|max:255',
            'password' => 'required|string|min:8|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required'    => 'البريد الإلكتروني مطلوب',
            'email.email'       => 'البريد الإلكتروني غير صحيح',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min'      => 'كلمة المرور يجب ألا تقل عن 8 أحرف',
        ];
    }
}
