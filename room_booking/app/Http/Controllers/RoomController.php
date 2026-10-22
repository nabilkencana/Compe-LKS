<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $room = Room::all();

         return response()->json([
            'status'=>'success',
            'message' => 'Rooms retrieved successfully',
            'data' => $room
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
            'branch_slug' => 'required|exists:branches,slug',
            'base_price' => 'numeric|min:1000|max:1000000',
            'additional_friday_price' => 'required|numeric|max:1000000',
            'additional_saturday_price' => 'required|numeric|max:1000000',
            'additional_monday_price' => 'required|numeric|max:1000000',
        ]);

        $br = Room::create($val);

        return response()->json([
            'status'=>'success',
            'message' => 'Room successfully added',
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
    public function update(Request $request, Room $room)
    {
          $val = $request->validate([
            'name' => 'sometimes|string',
            'branch_slug' => 'sometimes|exists:branches,slug',
            'base_price' => 'sometimes|numeric|min:1000|max:1000000',
            'additional_friday_price' => 'sometimes|numeric|max:1000000',
            'additional_saturday_price' => 'sometimes|numeric|max:1000000',
            'additional_monday_price' => 'sometimes|numeric|max:1000000',
        ]);

        $room->update($val);

        return response()->json([
            'status'=>'success',
            'message' => 'Room successfully updated.',
            'data' => $room
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( Room $room)
    {
         $room->delete();

        return response()->noContent();
    }
}
