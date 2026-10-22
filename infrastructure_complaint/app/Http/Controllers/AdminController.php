<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complaint;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       
       $comp = Complaint::all();

       return response()->json([
            'success' => true, 
            'message' => 'Complaints retrieved successfully.',
            'data' => $comp
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
        //
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

    public function accept(Complaint $complaint)
    {
        if ($complaint->status !== 'submitted') {
             return response()->json([
            'success' => false, 
            'message' => 'Only submitted complaints can be reviewed.'
       ],409);
        }
 
        $complaint->update(['status' => 'accepted']);
 
        return response()->json([
            'message' => 'Complaint accepted successfully.',
            'data'    => $complaint->only(['id', 'status']),
        ], 200);
    }

    public function reject(Request $r, Complaint $complaint)
    {
        $d = $r->validate(['rejection_reason' => 'required|string']);       

        if ($complaint->status !== 'submitted') {
             return response()->json([
            'success' => false, 
            'message' => 'Only submitted complaints can be reviewed.'
       ],409);
        }
 
        $complaint->update([
            'status'           => 'rejected',
            'rejection_reason' => $d['rejection_reason'],
        ]);
 
        return response()->json([
            'message' => 'Complaint rejected successfully.',
            'data'    => $complaint->only(['id', 'status', 'rejection_reason']),
        ], 200);
    }

    public function  assign() {
        
    }

}
