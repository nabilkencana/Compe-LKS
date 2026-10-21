<?php

namespace App\Policies;

use App\Models\Shipment;
use App\Models\User;

class ShipmentPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    public function view(User $user, Shipment $shipment): bool
    {
        return in_array($user->role, ['admin', 'staff'])
            || $shipment->customer_id === $user->id
            || ($user->role === 'courier' && $shipment->courier_id === $user->id);
    }

    public function cancel(User $user, Shipment $shipment): bool
    {
        return $user->role === 'customer'
            && $shipment->customer_id === $user->id;
    }
}
