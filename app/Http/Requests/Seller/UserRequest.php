<?php

namespace App\Http\Requests\Seller;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    private $user;
    private $action;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rule =  [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'unique:users,email'],
            'username' => ['required', 'string', 'max:255',  'unique:users,username'],
            'mobile' => ['nullable', 'numeric', 'unique:users,mobile'],
            'password' => ['required', 'min:4', 'confirmed'],
            'package_id' => ['required', 'integer', 'exists:packages,id'],
            'govt_id' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'town' => ['nullable', 'string', 'max:255'],
            'street' => ['nullable', 'string', 'max:255'],
            'zip_code' => ['nullable', 'string', 'digits:4'],
        ];

        switch ($this->action) {
            case 'personal-info':
                $rule['email'] = ['nullable', 'email', Rule::unique('users', 'email')->ignore($this->user->id)];
                $rule['mobile'] = ['nullable', 'numeric', Rule::unique('users', 'mobile')->ignore($this->user->id)];
                unset($rule['username'], $rule['password'], $rule['package_id']);
                break;
            case 'user-password':
                $rule = [
                    'username' => ['required', 'string', Rule::unique('users', 'username')->ignore($this->user->id)],
                    'password' => ['nullable', 'min:4', 'confirmed']
                ];
                break;
            default:
                //code block
        }
        return $rule;
    }

    public function prepareForValidation()
    {
        $this->user = $this->route('user');
        abort_unless($this->user->seller_id == auth('seller')->id(), 403);
        $this->action = $this->route('action');
    }
}
