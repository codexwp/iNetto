<?php

namespace App\Http\Requests\Admin;

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
            'seller_id' => ['required', 'integer', 'exists:sellers,id'],
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
            case 'status-expire':
                $rule = [
                    'is_active' => ['required', 'integer', 'in:0,1'],
                    'is_active_client' => ['required', 'integer', 'in:0,1'],
                    'expire_at' => ['nullable', 'date'],
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
        $this->action = $this->route('action');

        if($this->action == 'status-expire' && $this->is_active_client) {
            if(\Carbon\Carbon::createFromTimeString($this->expire_at . ' 11:59:59')->lessThan(now())) {
                throw \Illuminate\Validation\ValidationException::withMessages(['expire_at' => "To enable PPPoe must have a valid expire date"]);
            }
        }
    }
}
