<?php

namespace App\Http\Requests\Admin;

use App\Models\Package;
use App\Models\ServerProfile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PackageRequest extends FormRequest
{

    private $profiles;
    private $server_id = 1;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rule =  [
            'name' => ['required', 'string', 'max:255', Rule::unique('packages', 'name')->where('server_id', $this->server_id)],
            'server_id' => ['required', 'integer', 'exists:servers,id'],
            'profile' => ['required', 'string', 'max:255', 'in:' . implode(',', $this->profiles)],
            'validity' => ['required', 'integer'],
            'validity_unit' => ['required', 'string', 'in:' . implode(',', Package::V_UNIT_LIST)],
            'price' => ['required', 'integer'],
            'description' => ['nullable', 'string', 'max:255'],
            'is_home_display' => ['nullable', 'integer'],
        ];

        if($package = $this->route('package'))
        {
            $rule['name'] = ['required', 'string', 'max:255', Rule::unique('packages', 'name')->where('server_id', $this->server_id)->ignore($package->id)];
        }
        return $rule;
    }

    public function prepareForValidation()
    {
        $this->merge(['server_id' => $this->server_id]);
        if(empty($this->is_home_display)) {
            $this->merge(['is_home_display' => 0]);
        }
        $this->profiles = ServerProfile::getAll()->pluck('name')->toArray();
    }
}
