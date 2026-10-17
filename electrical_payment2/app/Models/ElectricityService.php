<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


use App\Models\ElectricityAccount;
use Illuminate\Database\Eloquent\Attributes\Guarded;

#[Guarded(['id'])]
class ElectricityService extends Model
{
    // ElectricityService
public function accounts() {
    return $this->hasMany(ElectricityAccount::class, 'electricity_service_id');
}

}
