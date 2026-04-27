<?php

/**
 * @file: NotificationPreferenceRequest.php
 * @description: التحقق من بيانات تفضيلات الإشعارات - Notification Service (NF-02)
 * @module: Notification
 * @author: Team Leader (Khalid)
 */

namespace App\Modules\Notification\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NotificationPreferenceRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'push_enabled'       => 'nullable|boolean',
            'sms_enabled'        => 'nullable|boolean',
            'email_enabled'      => 'nullable|boolean',
            'quiet_hours_start'  => 'nullable|date_format:H:i',
            'quiet_hours_end'    => 'nullable|date_format:H:i|after:quiet_hours_start',
            'preferred_language' => 'nullable|in:ar,en',
            'fcm_token'          => 'nullable|string|max:512',
        ];
    }

    public function messages(): array
    {
        return [
            'quiet_hours_start.date_format' => 'وقت البدء يجب أن يكون بالصيغة HH:MM',
            'quiet_hours_end.date_format'   => 'وقت الانتهاء يجب أن يكون بالصيغة HH:MM',
            'quiet_hours_end.after'         => 'وقت الانتهاء يجب أن يكون بعد وقت البدء',
            'preferred_language.in'         => 'اللغة يجب أن تكون ar أو en',
        ];
    }
}
