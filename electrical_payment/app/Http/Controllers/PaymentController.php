<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('view' , Payment::class);
        
        // $data = $request->whereAny('status');
    //    $account = ElectricityAccount::simplePaginate($data);

    $status = $request->query('status');
    $account = Payment::where("status", $status)->simplePaginate();

        return response()->json([
                'success' => true,
                'message' => 'account retrived successful.',
                'data' => $account
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
        Gate::authorized('create' , Payment::class);

        $validated = $request->validate([
            'billing_period' => 'required|date',
            'usage_kwh' => 'required|integer',
            'due_date' => 'required|date',
        ]);

        $validated = $request->user()->service();

        $service = ElectricityAccount::create([$validated['electricity_account_id']->$request->user()->id(),$validated]);
        
        return response()->json([
                'success' => true,
                'message' => 'Service successful Created.',
                'data' => $service
            ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
         Gate::authorized('update' , Payment::class);

        $payment = Payment::findOrFail($id);

         return response()->json([
                'success' => true,
                'message' => 'payment Retrieved successful.',
                'data' => $payment
            ],201);
    }

    public function me(Request $req)
    {
         $pay = $req->user()->id;

         return response()->json([
                'success' => true,
                'message' => 'payment Retrieved successful.',
                'data' => $pay
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
        Gate::authorized('update' , ElectricityService::class);

        $service = ElectricityService::findOrFail($id);

        $validated = $request->validate([
            'service_name' => 'nullable|string',
            'price_per_kwh' => 'nullable|integer',
            'description' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        $service->update($validated);
        
        return response()->json([
                'success' => true,
                'message' => 'Service successful updated.',
                'data' => $service
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
