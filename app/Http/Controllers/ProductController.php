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
    $paginate = $request->query('paginate');
    $name = $request->query('name');

    // Query dasar semua products
    $query = Product::with('metaProducts.meta', 'orders');

    // Filter berdasarkan name jika ada
    if ($name && strlen($name) > 2) {
      $query->where('name', 'like', '%' . $name . '%');
    }

    // Shorting descending
    $query->orderBy('created_at', 'desc');

    // Paginate the results
    if ($paginate === 'false') {
      $products = $query->get();
    } else {
      $products = $query->paginate(25);
    }

    // Transformasi data untuk response
    $products->getCollection()->transform(function ($data) {
      return [
        'id' => $data->id,
        'name' => $data->name,
        'price' => $data->price,
        'description' => $data->description,
        'category' => $data->category,
        'meta' => $data->metaProducts->pluck('meta'),
        'order_count' => $data->orders->count(),
      ];
    });

    return response()->json($products);
  }

  /**
   * Store a newly created product in storage.
   */
  public function store(Request $request)
  {
    $request->validate([
      'name' => 'string|max:255|nullable',
      'price' => 'numeric|nullable',
      'description' => 'string|nullable',
      'meta_products' => 'array|nullable',
    ]);

    $product = Product::create($request->only('name', 'price', 'description'));

    if ($request->has('meta_products')) {
      foreach ($request->input('meta_products') as $metaId) {
        $product->metaProducts()->create(['meta_id' => $metaId]);
      }
    }

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
      'name' => 'string|max:255|nullable',
      'price' => 'numeric|nullable',
      'description' => 'string|nullable',
      'meta_id' => 'array|nullable',
    ]);

    $product = Product::with('metaProducts.meta', 'orders')->findOrFail($id);

    $product->update($request->only('name', 'price', 'description'));

    // Update meta_id
    if ($request->has('meta_products')) {
      $product->metaProducts()->delete();
      foreach ($request->input('meta_products') as $metaId) {
        $product->metaProducts()->create(['meta_id' => $metaId]);
      }
    }

    return response()->json($product);
  }

  /**
   * Remove the specified product from storage.
   */
  public function destroy($id)
  {
    $product = Product::findOrFail($id);
    $product->delete();

    // hapus meta_id
    $product->metaProducts()->delete();

    return response()->json(null, 204);
  }
}
