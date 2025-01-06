<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ServerRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'ip'   => ['required', 'string'],
            'port'   => ['required', 'string', 'max:255'],
            'username'   => ['required', 'string', 'max:255'],
            'password'   => ['required', 'string', 'max:255'],
            'ssl'   => ['nullable', 'integer'],
            'description'   => ['nullable', 'string', 'max:255'],
            'is_active'   => ['required', 'integer'],
        ];
    }
}
