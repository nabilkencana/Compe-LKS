<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $category = Category::all();

        return response()->json([
            'message' => 'Categories retrieved successfully.',
            'data' => $category
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
    public function store(StoreCategoryRequest $request)
    {
        $val = $request->validate([
            'name' => 'required|string',
        ]);

        $menu = Category::create($val);

        return response()->json([
            'message' => 'Category created successfully.',
            'data' => $menu
        ] , 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return response()->json([
            'message' => 'Category retrieved successfully.',
            'data' => $category
        ] , 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
          $val = $request->validate([
            'name' => 'nullable|string',
        ]);

        $category->update($val);

        return response()->json([
            'message' => 'Category updated successfully.',
            'data' => $category
        ] , 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->noContent();
    }
}
