<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Bill;

class Payment extends Model
{
    // Payment
public function bill() {
    return $this->belongsTo(Bill::class);
}

}
