<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Bill;

class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorized('view' , Bill::class);

        $bill = Bill::all();

          return response()->json([
                'success' => true,
                'message' => 'Bill Retrieved successful.',
                'data' => $bill
            ],201);
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
         Gate::authorized('create' , ElectricityAccount::class);

        $validated = $request->validate([
            'billing_period' => 'required|date',
            'usage_kwh' => 'required|integer',
            'due_date' => 'required|date',
            'electricity_account_id' => 'required|integer|exist:users,id',
        ]);

        $service = ElectricityAccount::create($validated);
        
        return response()->json([
                'success' => true,
                'message' => 'Service successful Created.',
                'data' => [
                    'user_id' => $request->auth()->id(),
                    'customer_number' => "CUST" .  $service->id,
                    'meter_number' => "MTR".$service->meter_number,
                    'status' => $service->status,
                ]
            ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        Gate::authorized('view' , Bill::class);

        $bill = Bill::findOrFail($id);

         return response()->json([
                'success' => true,
                'message' => 'Bill Retrieved successful.',
                'data' => $bill
            ],201);
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
