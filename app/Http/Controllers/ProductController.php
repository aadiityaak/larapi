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
      $products = $query->get()->map(function ($data) {
        $metaProducts = $data->metaProducts
          ->pluck('meta')
          ->filter()
          ->values()
          ->map(function ($meta) {
            return [
              'id' => $meta->id,
              'name' => $meta->name,
              'type' => $meta->type,
            ];
          });
        return [
          'id' => $data->id,
          'name' => $data->name,
          'description' => $data->description,
          'category' => $data->category,
          'meta' => $metaProducts->pluck('id'),
          'meta_products' => $metaProducts,
          'order_count' => $data->orders->count(),
        ];
      });
    } else {
      $products = $query->paginate(25);
      $products->getCollection()->transform(function ($data) {
        $metaProducts = $data->metaProducts
          ->pluck('meta')
          ->filter()
          ->values()
          ->map(function ($meta) {
            return [
              'id' => $meta->id,
              'name' => $meta->name,
              'type' => $meta->type,
            ];
          });
        return [
          'id' => $data->id,
          'name' => $data->name,
          'description' => $data->description,
          'category' => $data->category,
          'meta' => $metaProducts->pluck('id'),
          'meta_products' => $metaProducts,
          'order_count' => $data->orders->count(),
        ];
      });
    }

    return response()->json($products, 200);
  }

  /**
   * Store a newly created product in storage.
   */
  public function store(Request $request)
  {
    $validatedData = $request->validate([
      'name' => 'string|max:255|nullable',
      'category' => 'string',
      'description' => 'string|nullable',
      'meta_products' => 'array|nullable',
    ]);

    $product = Product::create($validatedData);

    if ($request->has('meta_products')) {
      foreach ($request->input('meta_products') as $metaId) {
        $product->metaProducts()->create(['meta_id' => $metaId]);
      }
    }
    $response = [
      'id' => $product->id,
      'name' => $product->name,
      'description' => $product->description,
      'category' => $product->category,
      'meta' => $product->metaProducts->pluck('meta')->pluck('id'),
      'meta_products' => $product->metaProducts
        ->pluck('meta')
        ->filter()
        ->values()
        ->map(function ($meta) {
          return [
            'id' => $meta->id,
            'name' => $meta->name,
            'type' => $meta->type,
          ];
        }),
      'order_count' => $product->orders->count(),
    ];
    return response()->json($response, 201);
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
    $validatedData = $request->validate([
      'name' => 'string|max:255|nullable',
      'category' => 'string',
      'description' => 'string|nullable',
      'meta_products' => 'array|nullable',
      'meta_id' => 'array|nullable',
    ]);

    $product = Product::with('metaProducts.meta', 'orders')->findOrFail($id);

    $product->update($validatedData);

    // Update meta_id
    if ($request->has('meta_products')) {
      $product->metaProducts()->delete();
      foreach ($request->input('meta_products') as $metaId) {
        $product->metaProducts()->create(['meta_id' => $metaId]);
      }
    }
    $product->load('metaProducts.meta', 'orders');

    $response = [
      'id' => $product->id,
      'name' => $product->name,
      'description' => $product->description,
      'category' => $product->category,
      'meta' => $product->metaProducts->pluck('meta')->pluck('id'),
      'meta_products' => $product->metaProducts
        ->pluck('meta')
        ->filter()
        ->values()
        ->map(function ($meta) {
          return [
            'id' => $meta->id,
            'name' => $meta->name,
            'type' => $meta->type,
          ];
        }),
      'order_count' => $product->orders->count(),
    ];
    return response()->json($response, 200);
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
