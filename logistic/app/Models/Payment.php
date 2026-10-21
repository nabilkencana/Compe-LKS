<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\{Shipment,User};
use Illuminate\Database\Eloquent\Attributes\Guarded;

#[Guarded(['id'])]
class Payment extends Model
{
      public function shipments() {
        return $this->belongsTo(Shipment::class);
    }
    
      public function users() {
        return $this->belongsTo(User::class,'verified_by');
    }



}
