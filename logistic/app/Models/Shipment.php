<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\{User,ShippingServcie,DeliveryProof,ShipmentStatusHistory,Payment};
use Illuminate\Database\Eloquent\Attributes\Guarded;

#[Guarded(['id'])]
class Shipment extends Model
{
    public function users() {
        return $this->belongsTo(User::class,'customer_id','courier_id');
    }
    public function shippings() {
        return $this->belongsTo(ShippingService::class);
    }
    public function payments() {
        return $this->hasMany(Payment::class);
    }
    public function histories() {
        return $this->hasMany(ShipmentStatusHistory::class);
    }
    public function proofs() {
        return $this->hasMany(DeliveryProof::class);
    }
}
