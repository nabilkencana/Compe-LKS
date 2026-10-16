<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use App\Models\User;
use App\Models\ElectricityService;
use App\Models\Bill;

#[Guarded(['id'])]
class ElectricityAccount extends Model
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
