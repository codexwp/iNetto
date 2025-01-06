<?php

namespace App\Traits;

use App\Models\Admin;
use App\Models\Config;
use App\Models\Seller;
use App\Models\Tariff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

trait Install
{
    public static function doInstall(Request $request)
    {
        try{
            $step = session()->get('step') ?? 1;
            $message = '';

            if ($step === 1) {
                $license_key = $request->input('license_key');
                if(empty($license_key)) {
                    throw new \Exception("License key is required");
                }

                //Connect & Install Database Tables
                $credentials = $request->only(['host','port', 'dbname', 'username', 'password']);
                if(empty($credentials['host']) || empty($credentials['port']) || empty($credentials['dbname']) || empty($credentials['username']) || empty($credentials['password'])) {
                    throw new \Exception("Some inputs are missing");
                }

                config([
                    'database.connections.dynamic' => [
                        'driver' => 'mysql',
                        'host' => $credentials['host'],
                        'port' => $credentials['port'],
                        'database' => $credentials['dbname'],
                        'username' => $credentials['username'],
                        'password' => $credentials['password'],
                        'charset' => 'utf8mb4',
                        'collation' => 'utf8mb4_unicode_ci',
                        'prefix' => '',
                        'strict' => true,
                        'engine' => null,
                    ]
                ]);

                DB::purge('dynamic');
                DB::connection('dynamic')->getPdo();
                $connection = DB::connection('dynamic');

                $tables = $connection->select('SHOW TABLES');
                if(count($tables) > 0) {
                    throw new \Exception('Database is not empty');
                }

                session()->put('license_key', $license_key);

                //API request to get SQL of database
                $url = 'http://127.0.0.1:8001/api/v1/inetto/evanto-verify';
                if(env('APP_ENV') == "production") {
                    $url = 'http://gateway.codexwp.com/api/v1/inetto/evanto-verify';
                }

                $response = Http::get("$url?code=$license_key");
                $data = $response->json();

                if($data['success'] === false) {
                    throw new \Exception($data['message']);
                }

                $sql_statements = explode(';', $data['data']) ;
                foreach ($sql_statements as $query) {
                    if($query) {
                        $connection->statement($query . ';');
                    }
                }

                self::setEnvVariable('DB_HOST', $credentials['host']);
                self::setEnvVariable('DB_PORT', $credentials['port']);
                self::setEnvVariable('DB_DATABASE', $credentials['dbname']);
                self::setEnvVariable('DB_USERNAME', $credentials['username']);
                self::setEnvVariable('DB_PASSWORD', $credentials['password']);

                session()->put('step', 2);
                $message = 'Database is successfully installed';
            }

            elseif ($step === 2) {
                //Admin Configuration
                $admin = $request->only(['name','email', 'mobile', 'password']);
                if(empty($admin['name']) || empty($admin['email']) || empty($admin['mobile']) || empty($admin['password'])) {
                    throw new \Exception("Some inputs are missing");
                }

                Tariff::truncate();
                Admin::truncate();
                Seller::truncate();

                Tariff::create(['name' => "Tariff 1"]);
                Admin::create([
                    'name' => $admin['name'],
                    'email'    => $admin['email'],
                    'mobile'   => $admin['mobile'],
                    'password' => $admin['admin_password'],
                ]);
                Seller::create([
                    'name'  => 'System',
                    'email' => $admin['email'],
                    'mobile'   => $admin['mobile'],
                    'password' => $admin['admin_password'],
                    'tariff_id'=> 1,
                    'is_active_user_sms' => 1
                ]);

                $license_key = session()->get('license_key') ?? '';
                Config::set('license_key', $license_key);

                session()->put('step', 3);
                $message = 'Installation is completed.';
            }

            return ['status' => 'success', 'message' => $message];

        } catch (\PDOException $e) {
            return [
                'status' => 'error',
                'message' => 'Connection failed: ' . $e->getMessage()
            ];
        }
        catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }

    }

    public static function checkInstall()
    {
        try{
            $tables = DB::select('SHOW TABLES');
            if(count($tables) !== 23) {
                throw new \Exception("Database tables are not exist");
            }

            $license = Config::get('license_key');
            if(empty($license)) {
                throw new \Exception("License key is not found");
            }
            return [ 'status' => true ];
        }
        catch (\Exception $e) {
            return [ 'status' => false, 'message' => $e->getMessage() ];
        }

    }


    private static function setEnvVariable($key, $value)
    {
        $envPath = base_path('.env');
        if (File::exists($envPath)) {
            $envContent = File::get($envPath);
            $pattern = "/^{$key}=.*/m";
            if (preg_match($pattern, $envContent)) {
                $envContent = preg_replace($pattern, "{$key}={$value}", $envContent);
            } else {
                $envContent .= PHP_EOL . "{$key}={$value}";
            }
            File::put($envPath, $envContent);
            return true;
        }

        return false;
    }
}
