<?php

/**
 * @file: PasswordResetRequest.php
 * @description: التحقق من طلبات إعادة تعيين/تغيير كلمة المرور - Auth & Identity Service
 * @module: AuthIdentity
 * @author: Team Leader (Khalid)
 */

namespace App\Modules\AuthIdentity\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasswordResetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $action = $this->route()->getActionMethod();

        return match ($action) {
            'forgotPassword' => [
                'email' => 'required|email|exists:users,email',
            ],
            'resetPassword' => [
                'token'                 => 'required|string',
                'email'                 => 'required|email|exists:users,email',
                'password'              => 'required|string|min:8|confirmed',
                'password_confirmation' => 'required|string',
            ],
            'changePassword' => [
                'current_password'      => 'required|string',
                'password'              => 'required|string|min:8|confirmed|different:current_password',
                'password_confirmation' => 'required|string',
            ],
            default => [],
        };
    }

    public function messages(): array
    {
        return [
            'email.required'           => 'البريد الإلكتروني مطلوب',
            'email.exists'             => 'لا يوجد حساب بهذا البريد الإلكتروني',
            'token.required'           => 'رمز إعادة التعيين مطلوب',
            'password.required'        => 'كلمة المرور الجديدة مطلوبة',
            'password.min'             => 'كلمة المرور يجب ألا تقل عن 8 أحرف',
            'password.confirmed'       => 'تأكيد كلمة المرور غير متطابق',
            'password.different'       => 'كلمة المرور الجديدة يجب أن تختلف عن القديمة',
            'current_password.required' => 'كلمة المرور الحالية مطلوبة',
        ];
    }
}
