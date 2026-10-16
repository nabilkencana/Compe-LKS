<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seeker_Profile;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
          $seeker = Seeker_Profile::all();

        return response()->json([
                'success' => 'true',
                'message' => 'Seeker profile retrieved successfully.',
                'data' => $seeker
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $user = $request->user();

        $profile = $request->user()->profile;

        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Not found'
            ],404);
        }

        return response()->json([
                'success' => 'true',
                'message' => 'Seeker profile retrieved successfully.',
                'data' => [
                    'user_id' => $user->id,
                    'phone' => $profile->phone,
                    'address' => $profile->address,
                    'summary' => $profile->summary,
                    'profil_photo_url' => $profile->profil_photo_url
                ]
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
    public function update(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'cover_letter' => 'nullable|string',
            'summary' => 'nullable|string',
            'profile_photo_url' => 'nullable|string|mimes:png',
        ]);

        $profile = $request->user()->profile()->updateOrCreate(
            ['user_id' => $request->user()->profile],
            $validated,
        );

        return response()->json([
                'success' => 'true',
                'message' => 'Profile updated successfully.',
                'data' => [
                    'user_id' => $request->user()->id,
                     'phone' => $profile->phone,
                    'address' => $profile->address,
                    'summary' => $profile->summary,
                    'profil_photo_url' => $profile->profil_photo_url
                ]
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
