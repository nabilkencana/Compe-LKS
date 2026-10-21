<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class, 'customer_id');
    }

    public function courierShipments(): HasMany
    {
        return $this->hasMany(Shipment::class, 'courier_id');
    }

    public function paymentsVerified(): HasMany
    {
        return $this->hasMany(Payment::class, 'verified_by');
    }
}
