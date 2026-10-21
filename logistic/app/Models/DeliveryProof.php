<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\{Shipment,User};
use Illuminate\Database\Eloquent\Attributes\Guarded;

#[Guarded(['id'])]
class DeliveryProof extends Model
{
      public function users() {
        return $this->belongsTo(User::class,'courier_id');
    }    
      public function shipments() {
        return $this->belongsTo(Shipment::class);
    }    


}
