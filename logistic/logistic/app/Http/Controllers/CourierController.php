<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourierController extends Controller
{
    public function index(Request $request)
    {
        $shipments = Shipment::where('courier_id', $request->user()->id)
            ->with('service')
            ->latest()
            ->simplePaginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Assigned shipments retrieved successfully.',
            'data' => $shipments,
        ]);
    }

    public function updateStatus(Request $request, Shipment $shipment)
    {
        if ($shipment->courier_id !== $request->user()->id) {
            abort(403);
        }

        $transitions = [
            'assigned' => ['picked_up'],
            'picked_up' => ['in_transit'],
            'in_transit' => ['out_for_delivery'],
            'out_for_delivery' => ['delivered', 'failed_delivery'],
            'failed_delivery' => ['out_for_delivery'],
        ];

        $data = $request->validate([
            'status' => 'required|in:picked_up,in_transit,out_for_delivery,failed_delivery,delivered',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        if (!in_array($data['status'], $transitions[$shipment->status] ?? [])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid shipment status transition.',
            ], 409);
        }

        if ($data['status'] === 'delivered'
            && !$shipment->proofs()->where('delivery_result', 'delivered')->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Delivery proof is required before marking delivered.',
            ], 409);
        }

        DB::transaction(function () use ($shipment, $request, $data) {
            $oldStatus = $shipment->status;

            $shipment->update([
                'status' => $data['status'],
                'notes' => $data['notes'] ?? $shipment->notes,
            ]);

            $shipment->histories()->create([
                'changed_by' => $request->user()->id,
                'old_status' => $oldStatus,
                'new_status' => $data['status'],
                'location' => $data['location'] ?? null,
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Shipment status updated successfully.',
            'data' => $shipment->fresh(),
        ]);
    }

    public function proof(Request $request, Shipment $shipment)
    {
        if ($shipment->courier_id !== $request->user()->id) {
            abort(403);
        }

        if (!in_array($shipment->status, ['out_for_delivery', 'failed_delivery'])) {
            return response()->json([
                'success' => false,
                'message' => 'Shipment is not ready for delivery proof.',
            ], 409);
        }

        $data = $request->validate([
            'delivery_result' => 'required|in:delivered,failed',
            'proof_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'delivery_notes' => 'nullable|string|max:1000',
            'recipient_name' => 'nullable|string|max:255',
        ]);

        $proof = $shipment->proofs()->create([
            'courier_id' => $request->user()->id,
            'delivery_result' => $data['delivery_result'],
            'proof_file' => $request->file('proof_file')?->store('delivery-proofs', 'local'),
            'delivery_notes' => $data['delivery_notes'] ?? null,
            'recipient_name' => $data['recipient_name'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Delivery proof uploaded successfully.',
            'data' => $proof,
        ], 201);
    }
}
