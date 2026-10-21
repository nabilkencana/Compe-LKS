<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Payment;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page');

        $pay = Payment::where('laundry_order_id' , $request->user()->id)->simplePaginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Payments retrieved successfully.',
            'data' => $pay
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request,string $id)
    {
        $pay = Payment::where('laundry_order_id' , $request->user()->id)->findOrFail($id);

        $img = $pay->payment_proof;
        return response()->file(Storage::disk('public')->path($img));
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
        $pay = Payment::findOrFail($id);

        $val = $request->validate([
            'status' => 'required|in:completed,rejected',
            'notes' => 'required|string',
        ]);

         if ($pay->status === 'completed') {
             return response()->json([
            'success' => false,
            'message' => 'Payment status cannot be changed from its current state.',
            ],409);
        }

        $pay->update([
            ...$val,
            'verified_by' => auth()->user()->id,
            'verified_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment status updated successfully.',
            'data' => $pay
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
