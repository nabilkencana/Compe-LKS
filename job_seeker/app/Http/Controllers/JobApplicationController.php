<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job_Application;
use App\Models\Job_Vacancy;

class JobApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $job = Job_Application::paginate(10);

        return response()->json([
                'success' => 'true',
                'message' => 'Job Application retrieved successfully.',
                'data' => $job
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
    public function store(Request $request , String $id)
    {
        $job = Job_Vacancy::find($id);

        if (!$job ) {
             return response()->json([
                'success' => 'false',
                'message' => 'Vacancy does not exist'
            ],404);
        }

        $validated = $request->validate([
            'resume_id' => 'required|integer',
            'cover_letter' => 'required|string',
        ]);

        $job = Job_Application::create($validated);

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
         $job = Job_Application::find($id);

         if (!$job ) {
             return response()->json([
                'success' => 'false',
                'message' => 'Application belongs to another user'
            ],404);
        }

        return response()->json([
                'success' => 'true',
                'message' => 'Applications retrieved successfully..',
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
        $application = Job_Application::find($id);

        $validated = $request->validate([
            'status' => 'required'
        ]);

        $application->update($validated);

         return response()->json([
                'success' => 'true',
                'message' => 'Applications update successfully..',
                'data' => $application
            ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         $job = Job_Application::find($id);

         if (!$job ) {
             return response()->json([
                'success' => 'false',
                'message' => 'Application belongs to another user'
            ],404);
        }

        $job->delete();

        return response()->noContent();
    }
}
