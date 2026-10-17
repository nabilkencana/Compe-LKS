<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\{Payment,ElectricityAccount};
use Illuminate\Database\Eloquent\Attributes\Guarded;

#[Guarded(['id'])]

class Bills extends Model
{
    // Bill
public function account() {
    return $this->belongsTo(ElectricityAccount::class, 'electricity_account_id');
}
public function payments() {
    return $this->hasMany(Payment::class);
}

}
