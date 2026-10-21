<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffShipmentController extends Controller
{
    public function confirm(Request $request, Shipment $shipment)
    {
        if ($shipment->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending shipments can be confirmed.',
            ], 409);
        }

        $data = $request->validate([
            'shipping_fee' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($shipment, $request, $data) {
            $shipment->update([
                'shipping_fee' => $data['shipping_fee'],
                'status' => 'confirmed',
            ]);

            $shipment->histories()->create([
                'changed_by' => $request->user()->id,
                'old_status' => 'pending',
                'new_status' => 'confirmed',
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Shipment confirmed successfully.',
            'data' => $shipment->fresh(),
        ]);
    }
}
