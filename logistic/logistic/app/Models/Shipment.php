<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shipment extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'weight' => 'decimal:2',
        'shipping_fee' => 'integer',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function courier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'courier_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(ShippingService::class, 'shipping_service_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(ShipmentStatusHistory::class)->latest();
    }

    public function proofs(): HasMany
    {
        return $this->hasMany(DeliveryProof::class);
    }
}
