<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TariffPackage extends Model
{
    use HasFactory;

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
