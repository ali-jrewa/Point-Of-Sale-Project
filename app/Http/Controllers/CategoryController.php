<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {
        return view('category.list');
    }

    public function getCategories(){
        $categories = Category::all();

        return response()->json($categories);
    }

    public function store(CreateCategoryRequest $request)
    {
        $category = Category::create($request->validated());


        return response()->json(['success' => 'Category created successfully.'], 201);
    }

    public function edit(string $id)
    {
        $category = Category::findOrFail($id);

        return response()->json($category);
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
    $category->update($request->validated());

    return response()->json([
        'success' => 'Category updated successfully.'
    ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function destroy(Category $category)
{
    $category->delete();

    return response()->json([
        'success' => 'Category deleted successfully.'
    ]);
}
    }

