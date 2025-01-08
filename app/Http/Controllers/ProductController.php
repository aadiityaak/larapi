<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
  /**
   * Display a listing of the products.
   */
  public function index(Request $request)
  {
    // set paginate
    $products = Product::paginate(25);

    return response()->json($products);
  }

  /**
   * Store a newly created product in storage.
   */
  public function store(Request $request)
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'price' => 'required|string|max:255',
      'description' => 'required|string',
      'data' => 'required|string',
    ]);

    $product = Product::create($request->all());
    return response()->json($product, 201);
  }

  /**
   * Display the specified product.
   */
  public function show($id)
  {
    $product = Product::findOrFail($id);
    return response()->json($product);
  }

  /**
   * Update the specified product in storage.
   */
  public function update(Request $request, $id)
  {
    $request->validate([
      'name' => 'string|max:255',
      'price' => 'string|max:255',
      'description' => 'string',
      'data' => 'string',
    ]);

    $product = Product::findOrFail($id);
    $product->update($request->all());
    return response()->json($product);
  }

  /**
   * Remove the specified product from storage.
   */
  public function destroy($id)
  {
    $product = Product::findOrFail($id);
    $product->delete();
    return response()->json(null, 204);
  }
}
