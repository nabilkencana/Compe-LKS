<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminShipmentController extends Controller
{
    public function assignCourier(Request $request, Shipment $shipment)
    {
        $hasVerifiedPayment = $shipment->payments()
            ->where('status', 'completed')
            ->exists();

        if ($shipment->status !== 'confirmed' || !$hasVerifiedPayment) {
            return response()->json([
                'success' => false,
                'message' => 'Shipment must have a verified payment before assignment.',
            ], 409);
        }

        $data = $request->validate([
            'courier_id' => 'required|integer|exists:users,id',
        ]);

        $isCourier = User::whereKey($data['courier_id'])
            ->where('role', 'courier')
            ->exists();

        if (!$isCourier) {
            return response()->json([
                'success' => false,
                'message' => 'Selected user is not a courier.',
            ], 422);
        }

        DB::transaction(function () use ($shipment, $request, $data) {
            $shipment->update([
                'courier_id' => $data['courier_id'],
                'status' => 'assigned',
            ]);

            $shipment->histories()->create([
                'changed_by' => $request->user()->id,
                'old_status' => 'confirmed',
                'new_status' => 'assigned',
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Courier assigned successfully.',
            'data' => $shipment->fresh(['courier']),
        ]);
    }
}
