<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $menu = Menu::with('category')
        ->when(filled('search') , fn($q) => $q->where('name' , 'LIKE' , "%{$request->search}%"))
        ->when(filled('category_id') , fn($q) => $q->where('category_id' , $request->category_id))
        ->when($request->has('available'), fn($q) => $q->where('is_available', $request->available))
        ->simplePaginate();

          return response()->json([
            'message' => 'Menus retrieved successfully.',
            'data' => $menu->items()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $val = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string',
            'price' => 'required|integer|min:1',
            'stock' => 'required|integer|min:1',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photo' , 'public');
        }

        $menu = Menu::create([...$val , 'is_available' => true , 'photo' => $path]);

        return response()->json([
            'message' => 'Menus created successfully.',
            'data' => $menu,
        ] , 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
          return response()->json([
            'message' => 'Menus retrieved successfully.',
            'data' => $menu,
        ] , 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        $val = $request->validate([
            'category_id' => 'sometimes|exists:categories,id',
            'name' => 'sometimes|string',
            'price' => 'sometimes|integer|min:1',
            'stock' => 'sometimes|integer|min:1',
        ]);

        if ($request->hasFile('photo')) {
            $request->file('photo')->delete();
            $path = $request->file('photo')->store('photo' , 'public');
        }

        $menu->update($val);

        return response()->json([
            'message' => 'Menus created successfully.',
            'data' => $menu,
        ] , 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();
        return response()->noContent();
    }
}
