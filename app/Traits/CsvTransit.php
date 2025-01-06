<?php

namespace App\Traits;

use App\Models\Age;
use App\Models\EntryAndExitTime;
use App\Models\Package;
use App\Services\FormBuilderService;
use App\Services\QrService;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\StreamedResponse;

trait CsvTransit
{
    /**
     * ダウンロード処理
     * @param array $csv_data
     * @param array $csv_header
     * @param string $filename
     * @return StreamedResponse
     */
    public static function export(array $csv_data, array $csv_header, string $filename)
    {
        $response = new StreamedResponse (function () use ($csv_data, $csv_header) {
            $stream = fopen('php://output', 'w');
            fwrite($stream, "\xEF\xBB\xBF");
            fputcsv($stream, $csv_header);
            foreach ($csv_data as $value) {
                fputcsv($stream, $value);
            }
            fclose($stream);
        });
        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set('Content-Disposition', "attachment; filename={$filename}");

        return $response;
    }

    /**
     * ファイルオブジェクト作成
     * @param UploadedFile $file
     * @return \SplFileObject
     */
    public static function createSplFileObject(UploadedFile $file): \SplFileObject
    {
        $csv = new \SplFileObject($file);
        $csv->setFlags(
            \SplFileObject::DROP_NEW_LINE |
            \SplFileObject::READ_AHEAD |
            \SplFileObject::SKIP_EMPTY |
            \SplFileObject::READ_CSV
        );

        return $csv;
    }

    public static function csvHeaderForUsers()
    {
        $headers = [
            'Action(Empty=NA, 1=Create, 2=Update)',
            'Name',
            'Email',
            'Mobile(without country code)',
            'Username * (do not change while update)',
            'Password *',
        ];

        $packages = Package::getAll(1, 'asc');
        $package_data = [];
        foreach ($packages as $package) {
            $package_data[] = "{$package->id}={$package->name}";
        }
        $package_str = implode(',', $package_data);
        $headers[] = "Package ID *($package_str)";
        $headers[] = "Seller ID *(1=admin, else=others)";
        $headers[] = "Active/Inactive(1=active,0=inactive)";
        $headers[] = "Enable/Disable(1=enabled,0=disabled)";
        $headers[] = "Expire Date(YYYY-MM-DD)";
        $headers[] = "Govt.ID";
        $headers[] = "Country";
        $headers[] = "State";
        $headers[] = "City";
        $headers[] = "Town";
        $headers[] = "Street";
        $headers[] = "ZipCode";
        return $headers;
    }

    public static function csvDataForUsers($users)
    {
        $csv_data = [];
        foreach ($users as $user) {
            $csv_data[] = [
                '',
                $user->name,
                $user->email,
                $user->mobile,
                $user->username,
                '',
                $user->package_id,
                $user->seller_id,
                $user->is_active,
                $user->is_active_client,
                $user->expire_at,
                $user->govt_id,
                $user->country,
                $user->state,
                $user->city,
                $user->town,
                $user->street,
                $user->zip_code
            ];
        }
        return $csv_data;
    }

 }
