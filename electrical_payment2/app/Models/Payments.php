<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\{Bill};
use Illuminate\Database\Eloquent\Attributes\Guarded;

#[Guarded(['id'])]

class Payments extends Model
{
    // Payment
public function bill() {
    return $this->belongsTo(Bill::class);
}

}
