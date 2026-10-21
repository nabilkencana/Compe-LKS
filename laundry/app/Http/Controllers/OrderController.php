<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\LaundryOrder;
use App\Models\LaundryService;
use App\Models\Payment;

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

    public function admin(Request $request)
    {
        $perpage = $request->query('per_page', 10);
        $ord = LaundryOrder::when(filled('status'),fn($q) => $q->where('status' , 'like' , "%{$request->status}%"))
        ->when(filled('search'),fn($q) => $q->where('order_number' , 'like' , "%{$request->search}%"))
        ->simplePaginate($perpage);
                

        return response()->json([
            'success' => true,
            'message' => 'Orders retrieved successfully.',
            'data' => $ord
        ],200);
    }

    public function staff(Request $request)
    {
        $ord = LaundryOrder::when(filled('status'),fn($q) => $q->where('status' , 'like' , "%{$request->status}%"))->simplePaginate();

        return response()->json([
            'success' => true,
            'message' => 'Assigned orders retrieved successfully.',
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
        $price = (int) $ser->price;

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
    public function show(Request $request,string $id)
    {
        $ord = LaundryOrder::with(['payments','histories'])->findOrFail($id);

        if ($request->user()->id === $ord->id) {
             return response()->json([
            'success' => false,
            'message' => 'Order is not own',
            ],404);
        }

         return response()->json([
            'success' => true,
            'message' => 'Order retrieved successfully.',
            'data' => $ord
        ],200);
    }

    public function cancel(Request $request,string $id)
    {
        $ord = LaundryOrder::where('user_id' , $request->user()->id)->findOrFail($id);

        if (!$ord) {
             return response()->json([
            'success' => false,
            'message' => 'Order not found.',
            ],404);
        }
        if ($ord->status !== 'pending') {
             return response()->json([
            'success' => false,
            'message' => 'This order can no longer be cancelled.',
            ],409);
        }

        $ord->update([
            'status' => 'cancelled'
        ]);


         return response()->json([
            'success' => true,
            'message' => 'Order retrieved successfully.',
            'data' => $ord
        ],200);
    }
    
    public function confirm(Request $request,string $id)
    {
        $ord = LaundryOrder::findOrFail($id);

        if ($ord->status !== 'pending') {
             return response()->json([
            'success' => false,
            'message' => 'Only pending orders can be confirmed.',
            ],409);
        }

        $val = $request->validate([
            'delivery_fee' => 'required|min:1',
            'notes' => 'required|string'
        ]);

        $price = $ord->service_subtotal;

        $ord->update([
            ...$val,
            'final_total' => $val['delivery_fee'] + $price,
            'status' => 'confirmed'

        ]);


         return response()->json([
            'success' => true,
            'message' => 'Order retrieved successfully.',
            'data' => $ord
        ],200);
    }

    public function status(Request $request,string $id)
    {
        $ord = LaundryOrder::findOrFail($id);

        if ($ord->status !== 'confirmed') {
             return response()->json([
            'success' => false,
            'message' => 'Only pending orders can be confirmed.',
            ],409);
        }

        $val = $request->validate([
            'status' => 'required|in:washing,drying,ironing,ready_for_pickup,completed',
            'notes' => 'required|string'
        ]);

        $ord->update($val);

         return response()->json([
            'success' => true,
            'message' => 'Order retrieved successfully.',
            'data' => $ord
        ],200);
    }

    public function assign(Request $request,string $id)
    {
        $ord = LaundryOrder::findOrFail($id);

        $val = $request->validate([
            'staff_id' => 'required|exists:users,id'
        ]);

        $ord->update($val);

         return response()->json([
            'success' => true,
            'message' => 'Staff assigned successfully.',
            'data' => $ord
        ],200);
    }

    public function payment(Request $request,string $id)
    {
        $ord = LaundryOrder::with('payments')->findOrFail($id);

        if ($ord->status !== 'confirmed') {
             return response()->json([
            'success' => false,
            'message' => 'Payment cannot be submitted for this order at the current state.',
            ],409);
        }

        $val = $request->validate([
            'amount' => 'required',
            'payment_method' => 'required|string',
            'payment_proof' => 'required|mimes:png,jpg,pdf|max:5120',
        ]);

        if ($request->hasFile('payment_proof')) {
            # code...
            $path = $request->file('payment_proof')->store('payment_proof', 'public');
        }

        $pay = Payment::create([
            ...$val,
            'laundry_order_id' => $ord->id,
            'verified_by' => $request->user()->id,
            'payment_proof' => $path,
            'status' => 'pending',
            'verified_at' => date('yymmdd'),
            'paid_at' => date('yymmdd'),
        ]);

          return response()->json([
            'success' => true,
            'message' => 'Payment submitted and awaiting verification.',
            'data' => $pay
        ],200);
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
