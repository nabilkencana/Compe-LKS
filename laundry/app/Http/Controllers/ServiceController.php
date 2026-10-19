<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaundryService;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perpage = $request->query('per_page', 10);

        if ($perpage < 1 ||$perpage > 100) {
            return response()->json([
            'success' => false,
            'message' => 'The given data was invalid.',
            'errors' => [
                'fields' => 'per_page',
                'message' => 'Must be between 1 and 100.',
            ],
            ],422);
        }

        $ser = LaundryService::where('is_active', true)->when(filled('search'), fn ($q) => $q->where('service_name', 'like' , "%{$request->search}%"))->simplePaginate($perpage);

        return response()->json([
            'success' => true,
            'message' => 'Services retrieved successfully.',
            'data' => $ser
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
            'description' => 'required|string',
            'price' => 'required',
            'price_unit' => 'required|string',
            'is_active' => 'required|boolean'
        ]);

        $ser = LaundryService::create($val);

        return response()->json([
            'success' => true,
            'message' => 'Laundry service created successfully.',
            'data' => $ser
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ser = LaundryService::where('is_active' , true)->find($id);

         return response()->json([
            'success' => true,
            'message' => 'Service retrieved successfully.',
            'data' => $ser
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
        $ser = LaundryService::findOrFail($id);

        $val = $request->validate([
            'service_name' => 'nullable|string',
            'description' => 'nullable|string',
            'price' => 'nullable',
            'price_unit' => 'nullable|string',
            'is_active' => 'nullable|boolean'
        ]);

        $ser->update($val);

        return response()->json([
            'success' => true,
            'message' => 'Laundry service updated successfully.',
            'data' => $ser
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
