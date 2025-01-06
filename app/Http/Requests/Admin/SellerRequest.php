<?php

namespace App\Http\Requests\Admin;

use App\Models\Package;
use App\Models\ServerProfile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SellerRequest extends FormRequest
{

    private $seller;
    private $action;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rule =  [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'unique:sellers,email'],
            'mobile' => ['required', 'numeric'],
            'password' => ['required', 'min:4', 'confirmed'],
            'tariff_id' => ['required', 'integer', 'exists:tariffs,id'],
            'country' => ['nullable', 'string', 'max:255'],
            'govt_id' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'town' => ['nullable', 'string', 'max:255'],
            'street' => ['nullable', 'string', 'max:255'],
            'zip_code' => ['nullable', 'string', 'digits:4'],
        ];

        switch ($this->action) {
            case 'personal-info':
                $rule['email'] = ['nullable', 'email', Rule::unique('sellers', 'email')->ignore($this->seller->id)];
                $rule['mobile'] = ['required', 'numeric', Rule::unique('sellers', 'mobile')->ignore($this->seller->id)];
                $rule['is_active'] = ['required', 'integer'];
                $rule['is_active_user_sms'] = ['required', 'integer'];
                unset($rule['password']);
                break;
            case 'seller-password':
                $rule = [
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
        $this->seller = $this->route('seller');
        $this->action = $this->route('action');
    }

}
