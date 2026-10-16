<?php

namespace App\Http\Controllers;


use App\Models\ElectricityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       $data = $request->query("per_page",10);

        $service = ElectricityService::simplePaginate($data);

        return response()->json([
                'success' => true,
                'message' => 'Service retrived successful.',
                'data' => $service->items()
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
        // $this->authorize('create' , ElectricityService::class);

        $validated = $request->validate([
            'service_name' => 'required|string',
            'price_per_kwh' => 'required|integer',
            'description' => 'required|string',
            'status' => 'required|string',
        ]);

        
        $service = ElectricityService::create($validated);
        
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
