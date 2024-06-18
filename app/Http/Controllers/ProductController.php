<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Http\Controllers\Log;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();
        return view('index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'product_name' => 'required|string|max:255',
            'quantity_in_stock' => 'required|integer|min:0',
            'price_per_item' => 'required|numeric|min:0',
        ]);

        try {
            $product = Product::create($validatedData);
            return response()->json(['message' => 'Product saved successfully', 'product' => $product], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error saving product. Please try again.'], 500);
        }
    }    

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'product_name' => 'required|string|max:255',
            'quantity_in_stock' => 'required|integer|min:0',
            'price_per_item' => 'required|numeric|min:0',
        ]);

        try {
            $product = Product::findOrFail($id);
            $product->update($validatedData);
            return response()->json(['message' => 'Product updated successfully', 'product' => $product], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating product. Please try again.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();
            return response()->json(['message' => 'Product deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting product. Please try again.'], 500);
        }
    }
}
