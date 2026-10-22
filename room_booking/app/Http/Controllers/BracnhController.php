<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;

class BracnhController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $br = Branch::all();

         return response()->json([
            'status'=>'success',
            'message' => 'Branch retrieved successfully',
            'data' => $br
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
            'name' => 'required|string',
            'slug' => 'required|unique:branches,slug',
        ]);

        $br = Branch::create($val);

        return response()->json([
            'status'=>'success',
            'message' => 'Branch successfully added',
            'data' => $br
        ]);
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
    public function update(Request $request,Branch $branch )
    {
         $val = $request->validate([
            'name' => 'required|string'
        ]);

       $branch->update([
        'name' => $val['name'],
        ]);

        return response()->json([
            'status'=>'success',
            'message' => 'branch successfully updated.',
            'data' => $branch->fresh()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch)
    {
        $branch->delete();

        return response()->noContent();
    }
}
