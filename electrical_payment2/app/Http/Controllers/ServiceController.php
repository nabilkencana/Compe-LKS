<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ElectricityService;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $req)
    {
          $pp= $req->query('per_page',20);

        $bill = ElectricityService::simplePaginate($pp);

        return response()->json([
                'message' => 'Bill Success',
                'data' => $bill->items()
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
            'service_name' => 'required|string',
            'price_per_kwh' => 'required|integer',
            'description' => 'required|string',
            'status' => 'required|string',
        ]);

        $service = ElectricityService::create($val);

        return response()->json([
                'message' => 'Service created Success',
                'data' => $service
            ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
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
        $service = ElectricityService::find($id);

         $val = $request->validate([
            'service_name' => 'nullable|string',
            'price_per_kwh' => 'nullable|integer',
            'description' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        $service->update();

        return response()->json([
                'message' => 'Service created Success',
                'data' => $service
            ],201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
