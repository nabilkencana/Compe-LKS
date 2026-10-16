<?php

namespace App\Http\Controllers;

use App\Models\ElectricityAccount;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Gaauthorize('view',ElectricityAccount::class);

        $user = $request->user()->id;
        $account = ElectricityAccount::with('electricity_services')->where('user_id', $user)->get();

        return response()->json([
                'success' => true,
                'message' => 'account retrived successful.',
                'data' => $account
            ],200);
    }

    public function all(Request $request)
    {

       $account = ElectricityAccount::all();

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
        Gate::authorize('create' , ElectricityAccount::class);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'electricity_service_id' => 'required|exists:electricity_services,id',
            'customer_number' => 'required|integer',
            'meter_number' => 'required|integer',
            'status' => 'required|in:active,non_active',
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
        Gate::authorized('owner' , ElectricityAccount::class);

        $account = ElectricityAccount::findOrFail($id);

         return response()->json([
                'success' => true,
                'message' => 'Service successful updated.',
                'data' => $service
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
        Gate::authorized('update' , ElectricityAccount::class);

        $account = ElectricityAccount::findOrFail($id);

        $validated = $request->validate([
            'service_name' => 'nullable|string',
            'price_per_kwh' => 'nullable|integer',
            'description' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        $account->update($validated);
        
        return response()->json([
                'success' => true,
                'message' => 'account successful updated.',
                'data' => $account
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
