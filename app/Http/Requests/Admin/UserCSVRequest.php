<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use App\Traits\CsvTransit;
use Illuminate\Foundation\Http\FormRequest;

class UserCSVRequest extends FormRequest
{

    use CsvTransit;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [];

        foreach ($this->records as $index => $record) {
            if($record['action'] == '1') {
                $rules['records.' . $index . '.name'] = ['nullable', 'string', 'max:255'];
                $rules['records.' . $index . '.email'] = ['nullable', 'email', 'max:255', 'unique:users,email'];
                $rules['records.' . $index . '.mobile'] = ['nullable', 'numeric', 'unique:users,mobile'];
                $rules['records.' . $index . '.username'] = ['required', 'string', 'max:255', 'unique:users,username'];
                $rules['records.' . $index . '.password'] = ['required', 'string', 'min:4'];
                $rules['records.' . $index . '.package_id'] = ['required', 'integer', 'exists:packages,id'];
                $rules['records.' . $index . '.seller_id'] = ['required', 'integer', 'exists:sellers,id'];
                $rules['records.' . $index . '.is_active'] = ['nullable', 'integer'];
                $rules['records.' . $index . '.is_active_client'] = ['nullable', 'integer'];
                $rules['records.' . $index . '.expire_at'] = ['nullable', 'date_format:Y-m-d'];
                $rules['records.' . $index . '.govt_id'] = ['nullable', 'string', 'max:255'];
                $rules['records.' . $index . '.country'] = ['nullable', 'string', 'max:255'];
                $rules['records.' . $index . '.state'] = ['nullable', 'string', 'max:255'];
                $rules['records.' . $index . '.city'] = ['nullable', 'string', 'max:255'];
                $rules['records.' . $index . '.town'] = ['nullable', 'string', 'max:255'];
                $rules['records.' . $index . '.street'] = ['nullable', 'string', 'max:255'];
                $rules['records.' . $index . '.zip_code'] = ['nullable', 'string', 'max:255'];
            }
            else if($record['action'] == '2') {
                $user = User::where('username', $record['username'])->first();
                if(!$user){
                    $line = $index +2;
                    throw \Illuminate\Validation\ValidationException::withMessages(['csv_file' => "Line - {$line} : Username is invalid. You should not change username while update"]);
                }

                $rules['records.' . $index . '.name'] = ['nullable', 'string', 'max:255'];
                $rules['records.' . $index . '.email'] = ['nullable', 'email', 'max:255', 'unique:users,email,'.$user->id];
                $rules['records.' . $index . '.mobile'] = ['nullable', 'numeric', 'unique:users,mobile,'.$user->id];
                $rules['records.' . $index . '.username'] = ['required', 'string', 'max:255', 'unique:users,username,'.$user->id];
                $rules['records.' . $index . '.password'] = ['nullable', 'string', 'min:4'];
                $rules['records.' . $index . '.package_id'] = ['required', 'integer', 'exists:packages,id'];
                $rules['records.' . $index . '.seller_id'] = ['required', 'integer', 'exists:sellers,id'];
                $rules['records.' . $index . '.is_active'] = ['nullable', 'integer'];
                $rules['records.' . $index . '.is_active_client'] = ['nullable', 'integer'];
                $rules['records.' . $index . '.expire_at'] = ['nullable', 'date_format:Y-m-d'];
                $rules['records.' . $index . '.govt_id'] = ['nullable', 'string', 'max:255'];
                $rules['records.' . $index . '.country'] = ['nullable', 'string', 'max:255'];
                $rules['records.' . $index . '.state'] = ['nullable', 'string', 'max:255'];
                $rules['records.' . $index . '.city'] = ['nullable', 'string', 'max:255'];
                $rules['records.' . $index . '.town'] = ['nullable', 'string', 'max:255'];
                $rules['records.' . $index . '.street'] = ['nullable', 'string', 'max:255'];
                $rules['records.' . $index . '.zip_code'] = ['nullable', 'string', 'max:255'];
            }
        }

        return $rules;
    }

    protected function prepareForValidation()
    {
        if($this->file('csv_file')->getClientOriginalExtension()  != 'csv')
            throw \Illuminate\Validation\ValidationException::withMessages(['csv_file' => 'The file format is different than the format (.csv).']);

        $csv = self::createSplFileObject($this->file('csv_file'));

        $records = [];
        $emails = [];
        $mobiles = [];
        $usernames = [];
        $empty_flag = true;

        foreach ($csv as $index => $record) {
            if ($index === 0) {
                $empty_flag = false;
                $header = implode(',', $record);
                $bom = pack('CCC', 0xEF, 0xBB, 0xBF);
                $header = preg_replace("/^$bom/", '', $header);
                $header = str_replace('"', '', $header);

                if ($header === implode(',', self::csvHeaderForUsers()))
                    continue;
                else
                    throw \Illuminate\Validation\ValidationException::withMessages(['csv_file' => 'CSV file headers are invalid']);
            }

            if (count($record) !== count(self::csvHeaderForUsers())) {
                throw \Illuminate\Validation\ValidationException::withMessages(['csv_file' => 'Line - '. $index + 1 . ' ：The number of items does not match the header.']);
            }

            $records[] = [
                'action' => $record[0],
                'name' => $record[1],
                'email' => $record[2],
                'mobile' => $record[3],
                'username' => $record[4],
                'password' => $record[5],
                'package_id' => $record[6],
                'seller_id' => $record[7],
                'is_active' => $record[8],
                'is_active_client' => $record[9],
                'expire_at' => $record[10],
                'govt_id' => $record[11],
                'country' => $record[12],
                'state' => $record[13],
                'city' => $record[14],
                'town' => $record[15],
                'street' => $record[16],
                'zip_code' => $record[17],
            ];

            $emails[] = $record[2];
            $mobiles[] = $record[3];
            $usernames[] = $record[4];
        }

        if ($empty_flag) {
            throw \Illuminate\Validation\ValidationException::withMessages(['csv_file' => 'CSV file is empty']);
        }

        $unique_emails_count = array_count_values($emails);
        unset($unique_emails_count['']);

        if (!empty($unique_emails_count) && max($unique_emails_count) >= 2)
            throw \Illuminate\Validation\ValidationException::withMessages(['csv_file' => 'Duplicate email address are found.']);

        $unique_mobile_count = array_count_values($mobiles);
        unset($unique_mobile_count['']);

        if (!empty($unique_mobile_count) && max($unique_mobile_count) >= 2) {
            $duplicates = array_keys(array_filter($unique_mobile_count, function ($count) {
                return $count >= 2;
            }));
            throw \Illuminate\Validation\ValidationException::withMessages(['csv_file' => 'Duplicate mobiles are found ('.implode(',', $duplicates).').']);
        }

        $unique_username_count = array_count_values($usernames);
        unset($unique_username_count['']);

        if (!empty($unique_username_count) && max($unique_username_count) >= 2)
            throw \Illuminate\Validation\ValidationException::withMessages(['csv_file' => 'Duplicate usernames are found.']);

        $this->merge([
            'records' => $records,
        ]);
    }

    public function attributes()
    {
        $attributes = [];

        foreach ($this->records as $index => $record) {

            $lineNumber = $index + 2;

            $attributes = array_merge(
                $attributes,
                [
                    'records.' . $index . '.name' => 'Line-' . $lineNumber . ': Name ',
                    'records.' . $index . '.email' => 'Line-' . $lineNumber . ': Email address ',
                    'records.' . $index . '.mobile' => 'Line-' . $lineNumber . ': Mobile ',
                    'records.' . $index . '.username' => 'Line-' . $lineNumber . ': Username ',
                    'records.' . $index . '.password' => 'Line-' . $lineNumber . ': Password ',
                    'records.' . $index . '.package_id' => 'Line-' . $lineNumber . ': Package ID ',
                    'records.' . $index . '.seller_id' => 'Line-' . $lineNumber . ': Seller ID',
                    'records.' . $index . '.is_active' => 'Line-' . $lineNumber . ': Can Login? ',
                    'records.' . $index . '.is_active_client' => 'Line-' . $lineNumber . ': Enable PPPoe? ',
                    'records.' . $index . '.expire_at' => 'Line-' . $lineNumber . ': Expire Date ',
                    'records.' . $index . '.govt_id' => 'Line-' . $lineNumber . ': Govt. ID ',
                    'records.' . $index . '.country' => 'Line-' . $lineNumber . ': Country ',
                    'records.' . $index . '.state' => 'Line-' . $lineNumber . ': State ',
                    'records.' . $index . '.city' => 'Line-' . $lineNumber . ': City ',
                    'records.' . $index . '.town' => 'Line-' . $lineNumber . ': Town ',
                    'records.' . $index . '.street' => 'Line-' . $lineNumber . ': Street ',
                    'records.' . $index . '.zip_code' => 'Line-' . $lineNumber . ': Zip code ',
                ]
            );
        }

        return $attributes;
    }
}
