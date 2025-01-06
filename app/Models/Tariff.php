<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Tariff extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public static function getAll()
    {
        return self::all();
    }

    public function sellers()
    {
        return $this->hasMany(Seller::class, 'tariff_id', 'id');
    }

    public function tariffPackage(int $package_id)
    {
        return $this->tariffPackages()->where('package_id', $package_id)->first();
    }

    public function tariffPackages()
    {
        return $this->hasMany(TariffPackage::class, 'tariff_id', 'id');
    }

    public static function updateTariffPackageCommissions(array $tpc)
    {
        DB::beginTransaction();
        try{
            foreach ($tpc as $tariff_id => $name_packages)
            {
                $tariff = self::find($tariff_id);
                $tariff->name = $name_packages['name'];
                $tariff->save();
                $packages = Package::getAll(1);
                foreach ($packages as $package)
                {
                    $tariff_packages = $name_packages['packages'] ?? [];
                    $tariff_package = TariffPackage::where('tariff_id', $tariff_id)->where('package_id', $package->id)->firstOrNew();
                    $tariff_package->tariff_id = $tariff_id;
                    $tariff_package->package_id = $package->id;
                    $tariff_package->cost = isset($tariff_packages[$package->id]) ?  $tariff_packages[$package->id]['cost']?? null : null;
                    $tariff_package->is_active = isset($tariff_packages[$package->id]) ?  $tariff_packages[$package->id]['is_active']?? 0 : 0;
                    $tariff_package->save();
                }
            }
            DB::commit();
            return true;
        }
        catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

}
