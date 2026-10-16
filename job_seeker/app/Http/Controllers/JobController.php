<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job_Vacancy;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $job = Job_Vacancy::paginate(10);

        return response()->json([
                'success' => 'true',
                'message' => 'Job retrieved successfully.',
                'data' => $job
            ],200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $validated = $request->validate([
            'title' => 'required|string',
            'comapany_name' => 'required|string',
            'location' => 'required|string',
            'employment_type' => 'required|string',
            'string' => 'required|string',
            'status' => 'required',
            'deadline' => 'required|date',
        ]);

        $job = Job_Vacancy::create($validated);

         return response()->json([
                'success' => 'true',
                'message' => 'Apply Submitted',
                'data' => $seeker
            ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $job = Job_Vacancy::find($id);

        if (!$job ) {
             return response()->json([
                'success' => 'false',
                'message' => 'Vacancy does not exist'
            ],404);
        }
        if (!$job->status === 'unpublished') {
             return response()->json([
                'success' => 'false',
                'message' => 'Vacancy is draft/unpublished'
            ],404);
        }

        return response()->json([
                'success' => 'true',
                'message' => 'Job retrieved successfully.',
                'data' => $job
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
        $validated = $request->validate([
            'title' => 'nullable|string',
            'company_name' => 'nullable|string',
            'employment_type' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'nullable|string|in:published,unpublished',
        ]);

        $seeker->update($validated);

        return response()->json([
                'success' => 'true',
                'message' => 'Job vacan updated successfully.',
                'data' => $seeker
            ],200);
    }

    /**
     * Remove the specified reso'urce from storage.
     */
    public function destroy(string $id)
    {
         $job = Job_Vacancy::find($id);

         if (!$job) {
             return response()->json([
                'success' => 'false',
                'message' => 'Application belongs to another user'
            ],404);
        }

        $job->delete();

        return response()->json([
                'success' => 'true',
                'message' => 'Seeker profile retrieved successfully.',
                'data' => $job
            ],200);
    }
}
