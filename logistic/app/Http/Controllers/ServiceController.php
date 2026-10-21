<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShippingService;
class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page' , 10);
        $shi = ShippingService::simplePaginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Shipping services retrieved successfully.',
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
            'service_name' => 'required|string',
            'description' => 'required|string',
            'is_active' => 'required|boolean',
        ]);

        $shi = ShippingService::create($val);

        return response()->json([
            'success' => true,
            'message' => 'Shipping service created successfully.',
            'data' => $shi
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $shi = ShippingService::find($id);

        if (!$shi) {
              return response()->json([
            'success' => false,
            'message' => 'Shipping service not found.',
        ],404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Service retrieved successfully.',
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
        $shi = ShippingService::find($id);
        
        if (!$shi) {
              return response()->json([
            'success' => false,
            'message' => 'Shipping service not found.',
        ],404);
        }

        $val = $request->validate([
            'service_name' => 'nullable|string',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $shi->update($val);

        return response()->json([
            'success' => true,
            'message' => 'Shipping service created successfully.',
            'data' => $shi
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
