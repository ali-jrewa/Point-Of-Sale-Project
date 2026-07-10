<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;

class CategoryController extends Controller
{
    public function __construct(protected CategoryService $categoryService) {}


    public function index()
    {
        return view('category.list');
    }

    public function getCategories(){
        $categories = Category::all();

        return response()->json($categories);
    }

    public function store(StoreCategoryRequest $request)
    {
        $this->categoryService->store($request->validated());


        return response()->json(['success' => 'Category created successfully.'], 201);
    }

    public function edit(Category $category)
    {

        return response()->json($category);
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
    $this->categoryService->update($category, $request->validated());

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
        $this->categoryService->delete($category);

        return response()->json([
            'success' => 'Category deleted successfully.'
        ]);
    }
}

