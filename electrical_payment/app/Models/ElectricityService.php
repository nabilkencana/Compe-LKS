<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;
use App\Models\ElectricityAccount;

#[Guarded(['id'])]
class ElectricityService extends Model
{
    // ElectricityService
public function accounts() {
    return $this->hasMany(ElectricityAccount::class, 'electricity_service_id');
}


}
