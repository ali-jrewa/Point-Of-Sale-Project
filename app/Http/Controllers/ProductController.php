<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\StoreProductRequest;
use App\Models\Product;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Category;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function __construct(protected ProductService $productService) {}



    public function index()
    {
        $categories = Category::all()->pluck('name', 'id');
        return view('product.list', compact('categories'));
    }


    public function getProducts(Request $request){
          $products = $this->productService
            ->search($request->search);

        return response()->json($products);
    }

    public function store(StoreProductRequest $request)
    {
        $this->productService->store($request->validated());

        return response()->json([
            'success' => 'Product created successfully.'
        ]);
    }

    public function edit(Product $product)
    {
        $product->load('category');

        return response()->json($product);
    }

    public function update(UpdateProductRequest $request,Product $product)
    {
        $this->productService->update($product,$request->validated());

        return response()->json([
            'success' => 'Product updated successfully.'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

   public function destroy(Product $product)
{
    $this->productService->delete($product);

    return response()->json([
        'success' => 'Product deleted successfully.'
    ]);
}
}
