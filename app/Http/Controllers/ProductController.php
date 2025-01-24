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
    $query = Product::with('dataProducts.data', 'orders');

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
        'data_products' => $data->dataProducts->pluck('data'),
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
      'data_products' => 'array|nullable',
    ]);

    $product = Product::create($request->only('name', 'price', 'description'));

    // Simpan data_products
    if ($request->has('data_products')) {
      foreach ($request->input('data_products') as $dataId) {
        $product->dataProducts()->create(['data_id' => $dataId]);
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
      'data_products' => 'array|nullable',
    ]);

    $product = Product::with('dataProducts.data', 'orders')->findOrFail($id);

    $product->update($request->only('name', 'price', 'description'));

    // Update data_products
    if ($request->has('data_products')) {
      $product->dataProducts()->delete();
      foreach ($request->input('data_products') as $dataId) {
        $product->dataProducts()->create(['data_id' => $dataId]);
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

    // hapus data_products
    $product->dataProducts()->delete();

    return response()->json(null, 204);
  }
}
