<?php
namespace App\Modules\StartFromHere\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HelloRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:2|max:50,'
        ];

    }
    public function messages(): array
    {
        return [
            'name.required' => 'حقل الاسم فارغ',
            'name.string' => 'يجب ان يكون نصا ',
            'name.min' => 'يجب ان يكون اكثر من حرفين',
            'name.max' => 'يجب ان يكون اقل من 50 حرف'
        ];
    }
}

?>