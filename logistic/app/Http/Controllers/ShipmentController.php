<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shipment;
use App\Models\ShippingService;

class ShipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {


        $shi = Shipment::when(filled('status'),fn($q) => $q->where('status' , 'like' , "%{$request->status}%"))->simplePaginate();
        $this->authorize('view', $shi);
        
        return response()->json([
            'success' => true,
            'message' => 'Shipments retrieved successfully.',
            'data' => $shi->items()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $val = $request->validate([
            'shipping_service_id' => 'required|exists:shipping_services,id',
            'sender_name' => 'required|string',
            'sender_phone' => 'required|integer',
            'sender_address' => 'required|string',
            'package_description' => 'required|string',
            'weight' => 'required|numeric|gt:0',
            'notes' => 'nullable|string',
        ]);

        $ser = ShippingService::where('is_active', true)->findOrFail($val['shipping_service_id']);

        if (!$ser) {
            return response()->json([
            'success' => false,
            'message' => 'Shipping service unavailable.',
            'errors' => [[
                'field' => 'shipping_service_id',
                'message' => 'Select an active service.',
            ]]
        ],404);
        }

        $shi = Shipment::create([
            ...$val,
            'user_id' => $request->user()->id,
            'tracking_number' => 'LOG' .  '-' . now()->format('Y') . mt_rand(1 , 99999),
            'status' => 'pending',
            'shipping_fee' => null
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Shipment created successfully.',
            'data' => $shi
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $shi = Shipment::findOrFail($id);

        $this->authorize('viewAny', $shi);

        return response()->json([
            'success' => true,
            'message' => 'Shipment retrieved successfully.',
            'data' => $shi
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
