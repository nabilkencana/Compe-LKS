<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tk = Task::where('user_id' , $request->user()->id)->get();

        return response()->json([
            'success' => true,
            'message' => 'Task Retrievied Successfully.',
            'data' => $tk,
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
            'title' => 'required|max:255|string',
            'description' => 'nullable|string',
            'status' => 'nullable|in:pending,completed'
        ]);

        $tk = Task::create([...$val,'user_id' => $request->user()->id]);

        return response()->json([
            'success' => true,
            'message' => 'Task Created Successfully.',
            'data' => $tk,
        ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id , Request $request)
    {
        $tk = Task::where('user_id' , $request->user()->id)->findOrFail($id);

        // if (!$tk) {
        //     return response()->json([
        //     'success' => false,
        //     'message' => 'Task Not found',
        // ],404);
        // }

        return response()->json([
            'success' => true,
            'message' => 'Task Retrievied Successfully.',
            'data' => $tk,
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
         $tk = Task::where('user_id' , $request->user()->id)->find($id);

        if (!$tk) {
            return response()->json([
            'success' => false,
            'message' => 'Task Not found',
        ],404);
        }

        $val = $request->validate([
            'title' => 'nullable|max:255|string',
            'description' => 'nullable|string',
            'status' => 'nullable|in:pending,completed'
        ]);

        $tk->update($val);


        return response()->json([
            'success' => true,
            'message' => 'Task Retrievied Successfully.',
            'data' => $tk,
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request)
    {
          $tk = Task::where('user_id' , $request->user()->id)->find($id);

        if (!$tk) {
            return response()->json([
            'success' => false,
            'message' => 'Task Not found',
        ],404);
        }

        return response()->noContent();
    }
}
