<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complaint;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $r)
    {
        $r->validate(['status' => 'nullable|in:submitted,accepted,rejected']);

        $list = Complaint::with('room', 'category')
            ->when($r->filled('status'), fn($q) => $q->where('status', $r->status))
            ->latest()
            ->get();

        return response()->json([
            'message' => 'Complaints retrieved successfully.',
            'data'    => $list,
        ], 200);
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
    public function store(Request $r)
    {
        $d = $r->validate([
            'room_id'        => 'required|exists:rooms,id,is_active,1',
            'category_id'    => 'required|exists:complaint_categories,id,is_active,1',
            'title'          => 'required|string|max:150',
            'description'    => 'required|string',
            'evidence_image' => 'nullable|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $d['evidence_image'] = $r->file('evidence_image')?->store('complaints', 'public');
        $d['status'] = 'submitted';

        $c = $r->user()->complaints()->create($d);

        return response()->json([
            'message' => 'Complaint submitted successfully.',
            'data'    => $c->only(['id', 'title', 'status', 'evidence_image']),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $r, Complaint $complaint)
    {
        if ($r->user()->role !== 'admin' && $complaint->user_id === $r->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden'
            ], 403);
        }

        return response()->json([
            'message' => 'Complaint retrieved successfully.',
            'data'    => $complaint->load('room', 'category', 'ticket'),
        ], 200);
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
    public function update(Request $r, string $id , Complaint $c)
    {
        $d = $r->validate([
            'room_id'        => 'sometimes|exists:rooms,id,is_active,1',
            'category_id'    => 'sometimes|exists:complaint_categories,id,is_active,1',
            'title'          => 'sometimes|string|max:150',
            'description'    => 'sometimes|string',
            'evidence_image' => 'nullable|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $d['evidence_image'] = $r->file('evidence_image')?->store('complaints', 'public');

        $c->update();

        return response()->json([
            'message' => 'Complaint submitted successfully.',
            'data'    => $c,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Complaint $complaint)
    {
        $complaint->delete();

        return response()->noContent();
    }

    public function mine(Request $r)
    {
        $r->validate(['status' => 'nullable|in:submitted,accepted,rejected']);

        $list = Complaint::with('room', 'category')
            ->when($r->filled('status'), fn($q) => $q->where('status', $r->status))
            ->latest()
            ->get();

        return response()->json([
            'message' => 'Complaints retrieved successfully.',
            'data'    => $list,
        ], 200);
    }
}
