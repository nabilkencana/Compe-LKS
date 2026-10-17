<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\{User,ElectricityService,Bill};
use Illuminate\Database\Eloquent\Attributes\Guarded;

#[Guarded(['id'])]
class ElectricityAccounts extends Model
{
    // ElectricityAccount
public function user() {
    return $this->belongsTo(User::class);
}
public function service() {
    return $this->belongsTo(ElectricityService::class, 'electricity_service_id');
}
public function bills() {
    return $this->hasMany(Bill::class);
}

}
