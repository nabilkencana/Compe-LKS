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
        $rooms = Room::where('is_active', true)
            ->orderBy('building')
            ->orderBy('room_number')
            ->get(['id', 'building', 'room_number', 'name' ,'is_active']);

        return response()->json(['message' => 'Rooms retrieved successfully.', 'data' => $rooms], 200);
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
            'building' => 'required|string|unique:rooms,building',
            'room_number' => 'required|integer|min:1|unique:rooms,room_number',
            'name' => 'required|string',
            'is_active' => 'in:true,false'
        ]);

        $room = Room::create([...$val,'is_active' => true]);

        return response()->json([
            'message' => 'Room Created successfully.',
            'data'    => $room,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $room = Room::findOrFail($id);

        return response()->json([
            'succes' => true,
            'message' => 'Rooms retrieved successfully.',
            'data' => $room
        ]);
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
        $room = Room::findOrFail($id);

        $val = $request->validate([
            'building' => 'sometimes|string|unique:rooms,building',
            'room_number' => 'sometimes|integer|min:1|unique:rooms,room_number',
            'name' => 'sometimes|string',
            'is_active' => 'in:true,false'
        ]);

        $room->update($val);

        return response()->json([
            'message' => 'Room Updated successfully.',
            'data'    => $room,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $room = Room::findOrFail($id);
        $room->delete();
        return response()->noContent();
    }
}
