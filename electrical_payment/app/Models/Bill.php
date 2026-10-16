<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;
use App\Models\ElectricityAccount;
use App\Models\Payment;

#[Guarded(['id'])]
class Bill extends Model
{
    // Bill
public function account() {
    return $this->belongsTo(ElectricityAccount::class, 'electricity_account_id');
}
public function payments() {
    return $this->hasMany(Payment::class);
}

}
