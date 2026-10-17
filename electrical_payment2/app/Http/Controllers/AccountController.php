<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ElectricityAccounts;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $acc = ElectricityAccounts::with('service')->where('user_id', $request->user()->id)->get();

          return response()->json([
                'message' => 'Data Account Success',
                'data' => $acc
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
            'user_id' => 'required|exists:users,id',
            'electricity_service_id' => 'required|exists:electricity_services,id',
            'customer_number' => 'required|unique:electricity_accounts,customer_number',
            'meter_number' => 'required|unique:electricity_accounts,meter_number',
            'status' => 'required|in:active,non_active'
        ]);

        $acc = ElectricityAccounts::create($val);

        return response()->json([
                'message' => 'ElectricityAccounts created successful.',
                'data' => $acc
            ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
         $acc = ElectricityAccounts::with('service')->find($id);

        if (!$acc) {
              return response()->json([
                'message' => 'account is missing or belongs to another customer',
            ],404);
        }

          return response()->json([
                'message' => 'Data Account Success',
                'data' => $acc
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
