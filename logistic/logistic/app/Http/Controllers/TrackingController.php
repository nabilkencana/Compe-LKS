<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Support\Facades\Gate;

class TrackingController extends Controller
{
    public function show(string $trackingNumber)
    {
        $shipment = Shipment::with([
            'histories.changedBy:id,name',
            'service:id,service_name',
        ])->where('tracking_number', $trackingNumber)->firstOrFail();

        Gate::authorize('view', $shipment);

        $history = $shipment->histories->map(fn ($item) => [
            'old_status' => $item->old_status,
            'new_status' => $item->new_status,
            'location' => $item->location,
            'updated_by' => $item->changedBy?->name,
            'created_at' => $item->created_at,
        ])->values();

        return response()->json([
            'success' => true,
            'message' => 'Tracking retrieved successfully.',
            'data' => [
                'tracking_number' => $shipment->tracking_number,
                'current_status' => $shipment->status,
                'service' => $shipment->service?->service_name,
                'history' => $history,
            ],
        ]);
    }

    public function byId(Shipment $shipment)
    {
        Gate::authorize('view', $shipment);

        return response()->json([
            'success' => true,
            'message' => 'Tracking retrieved successfully.',
            'data' => $shipment->load('histories'),
        ]);
    }
}
