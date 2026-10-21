<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\{Shipment};
use Illuminate\Database\Eloquent\Attributes\Guarded;

#[Guarded(['id'])]
class ShippingService extends Model
{
    public function shipments() {
        return $this->hasMany(Shipment::class);
    }
}
