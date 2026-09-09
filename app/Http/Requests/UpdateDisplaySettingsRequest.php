<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDisplaySettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'display_duration_seconds' => 'required|integer|min:5|max:60',
            'show_employee_photo'      => 'nullable|boolean',
        ];
    }
}
