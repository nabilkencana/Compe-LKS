<?php

namespace App\Http\Controllers;

use App\Models\ShippingService;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = ShippingService::query();

        if ($request->user()?->role === 'customer') {
            $query->where('is_active', true);
        }

        $perPage = $request->integer('per_page', 10);
        $services = $query->orderBy('service_name')->simplePaginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Shipping services retrieved successfully.',
            'data' => $services,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'service_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        $service = ShippingService::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Shipping service created successfully.',
            'data' => $service,
        ], 201);
    }

    public function show(Request $request, ShippingService $shippingService)
    {
        if (!$shippingService->is_active && $request->user()->role === 'customer') {
            abort(404);
        }

        if (!$shippingService) {       
        return response()->json([
            'success' => false,
            'message' => 'Shipping service not found.',
            'errors' => [],
        ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Shipping service retrieved successfully.',
            'data' => $shippingService,
        ]);
    }

    public function update(Request $request , string $id)
    {
        $shippingService = ShippingService::findOrFail($id);

        $data = $request->validate([
            'service_name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        $shippingService->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Shipping service updated successfully.',
            'data' => $shippingService->fresh(),
        ]);
    }

    public function destroy(ShippingService $shippingService)
    {
        if ($shippingService->shipments()->exists()) {
            $shippingService->update(['is_active' => false]);
        } else {
            $shippingService->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Shipping service deactivated successfully.',
        ]);
    }
}
