<?php

namespace App\Models;

use App\Services\Pear2Service;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ServerProfile extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public static function getAll(int $server_id = null, string $order_by = 'desc')
    {
        $query = self::query();
        if($server_id)
            $query->where('server_id', $server_id);

        return $query->orderBy('created_at', $order_by)->get();
    }

    public static function updateProfiles(int $server_id, array|null $profile_names)
    {
        try{
            ServerProfile::truncate();
            foreach ($profile_names as $profile) {
                $profile['server_id'] = $server_id;
                ServerProfile::updateOrCreate(
                    [
                        'name' => $profile['name'],
                    ],
                    $profile
                );
            }
            return true;
        }
        catch (\Exception $e) {
            return false;
        }
    }

    public static function importFromMikrotik(int $server_id)
    {
        try {
            $p2s = new Pear2Service($server_id);
            if ($p2s->client == null)
                throw new \Exception('Connection error/Server is inactive.');

            $profiles = $p2s->getAllProfiles();
            $names = [];
            foreach ($profiles as $profile) {
                $names[] = $profile['name'];
                $db_profile = ServerProfile::where('server_id', $server_id)->where('name', $profile['name'])->first();
                if ($db_profile == null) {
                    ServerProfile::create([
                        'server_id' => $server_id,
                        'name' => $profile['name'],
                        'is_active' => 1
                    ]);
                } else {
                    $db_profile->is_active = 1;
                    $db_profile->save();
                }
            }
            ServerProfile::where('server_id', $server_id)->whereNotIn('name', $names)->update(['is_active' => 0]);

            return true;
        }
        catch (\Exception $e) {
            return false;
        }
    }

    public function packages()
    {
        return $this->hasMany(Package::class, 'profile', 'name');
    }
}
