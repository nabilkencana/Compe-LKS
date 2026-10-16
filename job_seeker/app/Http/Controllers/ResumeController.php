<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resume;

class ResumeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $resume = Resume::all();

        $user = auth()->id();

        return response()->json([
                'success' => 'true',
                'message' => 'Seeker profile retrieved successfully.',
                // 'data' => $seeker
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
         $request->validate(['resume' => 'required|file|mimes:pdf|max:5120']);

    $resume = Resume::create([
        'user_id' => $request->user()->id,
        'original_filename' => $request->file('resume')->getClientOriginalName(),
        'storage_path' => $request->file('resume')->store('resumes'),
        'mime_type' => $request->file('resume')->getClientMimeType(),
        'size' => $request->file('resume')->getSize(),
        'is_current' => true,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Resume uploaded successfully.',
        'data' => $resume->only(['id', 'original_filename', 'mime_type', 'size', 'is_current'])
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $resume = Resume::find($id);

        if (!$resume ) {
             return response()->json([
                'success' => 'false',
                'message' => 'resume does not exist'
            ],404);
        }

        $resume->delete();

        return response()->noContent();
    }

     public function download(string $id)  {
        $resume = Resume::find($id);
        
        if (!$resume) {
             return response()->json([
                'success' => 'false',
                'message' => 'resume does not exist'
            ],404);
        }

        return response()->download(public_path('resumes/{$resume->file_name}'));
    }
}
