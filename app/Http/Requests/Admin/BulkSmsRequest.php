<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BulkSmsRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'method' => ['required', 'string', 'in:manual,dynamic'],
            'receiver' => ['nullable', 'required_if:method,dynamic', 'string', 'in:user,seller'],
            'seller_id' => ['nullable', 'required_if:seller_type,single', 'integer', 'exists:sellers,id'],
            'user_id' => ['nullable', 'required_if:user_type,single', 'integer', 'exists:users,id'],
            'condition' => ['nullable', 'required_if:type,user', 'string', 'in:none,enabled,disabled,expired,not_expired'],
            'mobile' => ['nullable', 'required_if:method,manual', 'string'],
            'message'   => ['required', 'string']
        ];
    }
}
