<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\{Shipment,User};
use Illuminate\Database\Eloquent\Attributes\Guarded;

#[Guarded(['id'])]
class ShipmentStatusHistory extends Model
{
      public function shipments() {
        return $this->belongsTo(Shipment::class);
    }
      public function users() {
        return $this->belongsTo(User::class,'changed_by');
    }
}
