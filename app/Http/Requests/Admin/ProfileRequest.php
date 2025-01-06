<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProfileRequest extends FormRequest
{

    private $route_name;
    private $auth_user;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rule = [];
        if($this->route_name == 'admin.profile.update') {
            $rule['name'] = ['required', 'string', 'max:255'];
            $rule['email'] = ['required', 'email', 'unique:admins,email,'.$this->auth_user->id];
            $rule['mobile'] = ['required', 'numeric', 'unique:admins,mobile,'.$this->auth_user->id];
        } else if($this->route_name == 'admin.profile.change_password') {
            $rule['password'] = ['required', 'min:6', 'confirmed'];
        }
        return $rule;
    }

    public function prepareForValidation()
    {
        $this->auth_user = auth('admin')->user();

        if ($this->route_name == 'admin.profile.change_password' && !Hash::check($this->current_password, $this->auth_user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['The current password does not match our records.'],
            ]);
        }

        $this->route_name = request()->route()->getName();
    }
}
