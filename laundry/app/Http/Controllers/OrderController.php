<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\LaundryOrder;
use App\Models\LaundryService;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
         $perpage = $request->query('per_page', 10);
        $ord = LaundryOrder::when(filled('status'),fn($q) => $q->where('status' , 'like' , "%{$request->status}%"))->simplePaginate($perpage);

        return response()->json([
            'success' => true,
            'message' => 'Orders retrieved successfully.',
            'data' => $ord
        ],200);
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
            'laundry_service_id' => 'required|exists:laundry_services,id',
            'quantity' => 'required|integer',
            'requires_delivery' => 'required|boolean',
            'delivery_address' => 'required|string',
            'notes' => 'required|string',
        ]);

        $uniq = Str::padleft(rand(1,9999),5,'0');
        $ser = LaundryService::where('is_active', true)->findOrFail($val['laundry_service_id']);
        $price = (int) $ser->price_unit;

        $ord = LaundryOrder::create([
                ...$val,
                'unit_price' => $price,
                'service_subtotal' => (int) $val['quantity'] * $price,
                'user_id' => $request->user()->id,
                'staff_id' => auth()->user()->id,
                'order_number' => "LDY" . '-' . now()->format('Y') . '-' .  $uniq,
                'delivery_fee' => 0,
                'final_total' => 0,
                'status' => 'pending',
        ]);

        
        return response()->json([
            'success' => true,
            'message' => 'Order created and awaiting staff confirmation.',
            'data' => $ord
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
