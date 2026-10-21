<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\ShippingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;

class ShipmentController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Shipment::with(['service:id,service_name', 'courier:id,name']);

        if ($user->role === 'customer') {
            $query->where('customer_id', $user->id);
        } elseif ($user->role === 'courier') {
            $query->where('courier_id', $user->id);
        } elseif (!in_array($user->role, ['admin', 'staff'])) {
            abort(403);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $perPage = min(max($request->integer('per_page', 10), 1), 100);

        return response()->json([
            'success' => true,
            'message' => 'Shipments retrieved successfully.',
            'data' => $query->latest()->simplePaginate($perPage),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'shipping_service_id' => 'required|integer|exists:shipping_services,id',
            'sender_name' => 'required|string|max:255',
            'sender_phone' => 'required|string|max:30',
            'sender_address' => 'required|string',
            'recipient_name' => 'required|string|max:255',
            'recipient_phone' => 'required|string|max:30',
            'recipient_address' => 'required|string',
            'package_description' => 'required|string',
            'weight' => 'required|numeric|gt:0',
            'notes' => 'nullable|string',
        ]);

        $service = ShippingService::whereKey($data['shipping_service_id'])
            ->where('is_active', true)
            ->first();

        if (!$service) {
            return response()->json([
                'success' => false,
                'message' => 'Shipping service unavailable.',
            ], 404);
        }

        $shipment = DB::transaction(function () use ($data, $request) {
            $shipment = Shipment::create($data + [
                'customer_id' => $request->user()->id,
                'tracking_number' => 'TMP-' . Str::uuid(),
                'status' => 'pending',
                'shipping_fee' => null,
            ]);

            $trackingNumber = 'LOG-' . now()->format('Y') . '-'
                . str_pad((string) $shipment->id, 8, '0', STR_PAD_LEFT);

            $shipment->update(['tracking_number' => $trackingNumber]);
            $shipment->histories()->create([
                'changed_by' => $request->user()->id,
                'old_status' => null,
                'new_status' => 'pending',
            ]);

            return $shipment->fresh(['service']);
        });

        return response()->json([
            'success' => true,
            'message' => 'Shipment created successfully.',
            'data' => $shipment,
        ], 201);
    }

    public function me(Request $request)
    {
        $shipments = Shipment::where('customer_id', $request->user()->id)
            ->with('service')
            ->latest()
            ->simplePaginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Your shipments retrieved successfully.',
            'data' => $shipments,
        ]);
    }

    public function show(Shipment $shipment)
    {
        Gate::authorize('view', $shipment);

        return response()->json([
            'success' => true,
            'message' => 'Shipment retrieved successfully.',
            'data' => $shipment->load([
                'service',
                'customer:id,name,email',
                'courier:id,name',
                'payments',
                'histories',
                'proofs',
            ]),
        ]);
    }

    public function cancel(Request $request, Shipment $shipment)
    {
        Gate::authorize('cancel', $shipment);

        if (!in_array($shipment->status, ['pending', 'confirmed'])) {
            return response()->json([
                'success' => false,
                'message' => 'Shipment cannot be cancelled in its current status.',
            ], 409);
        }

        DB::transaction(function () use ($shipment, $request) {
            $oldStatus = $shipment->status;
            $shipment->update(['status' => 'cancelled']);
            $shipment->histories()->create([
                'changed_by' => $request->user()->id,
                'old_status' => $oldStatus,
                'new_status' => 'cancelled',
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Shipment cancelled successfully.',
            'data' => $shipment->fresh(),
        ]);
    }
}
