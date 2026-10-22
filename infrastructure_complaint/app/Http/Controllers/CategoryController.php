<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ComplaintCategory;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $cate = ComplaintCategory::where('is_active' , true)->get();

         return response()->json([
                'success' => true,
                'message' => 'Complaint Category Retrivied Successfully',
                'data' => $cate
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
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $room = ComplaintCategory::create([...$val,'is_active' => true]);

        return response()->json([
            'message' => 'Complaint Category Created successfully.',
            'data'    => $room,
        ], 201);
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
