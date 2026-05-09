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
          ->filter(fn ($mp) => $mp->meta)
          ->values()
          ->map(function ($mp) {
            $meta = $mp->meta;
            return [
              'id' => $meta->id,
              'name' => $meta->name,
              'type' => $meta->type,
              'show_in_print' => (bool) $mp->show_in_print,
            ];
          });
        return [
          'id' => $data->id,
          'name' => $data->name,
          'description' => $data->description,
          'category' => $data->category,
          'meta' => $data->metaProducts->pluck('meta_id'),
          'meta_print_ids' => $data->metaProducts->where('show_in_print', true)->pluck('meta_id')->values(),
          'meta_products' => $metaProducts,
          'order_count' => $data->orders->count(),
        ];
      });
    } else {
      $products = $query->paginate(25);
      $products->getCollection()->transform(function ($data) {
        $metaProducts = $data->metaProducts
          ->filter(fn ($mp) => $mp->meta)
          ->values()
          ->map(function ($mp) {
            $meta = $mp->meta;
            return [
              'id' => $meta->id,
              'name' => $meta->name,
              'type' => $meta->type,
              'show_in_print' => (bool) $mp->show_in_print,
            ];
          });
        return [
          'id' => $data->id,
          'name' => $data->name,
          'description' => $data->description,
          'category' => $data->category,
          'meta' => $data->metaProducts->pluck('meta_id'),
          'meta_print_ids' => $data->metaProducts->where('show_in_print', true)->pluck('meta_id')->values(),
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
      'meta_print_ids' => 'array|nullable',
    ]);

    $product = Product::create($validatedData);

    if ($request->has('meta_products')) {
      $printIds = collect($request->input('meta_print_ids', []))
        ->map(fn ($v) => (int) $v)
        ->filter()
        ->values()
        ->all();
      foreach ($request->input('meta_products') as $metaId) {
        $metaId = (int) $metaId;
        $product->metaProducts()->create([
          'meta_id' => $metaId,
          'show_in_print' => in_array($metaId, $printIds, true),
        ]);
      }
    }
    $product->load('metaProducts.meta', 'orders');
    $response = [
      'id' => $product->id,
      'name' => $product->name,
      'description' => $product->description,
      'category' => $product->category,
      'meta' => $product->metaProducts->pluck('meta_id'),
      'meta_print_ids' => $product->metaProducts->where('show_in_print', true)->pluck('meta_id')->values(),
      'meta_products' => $product->metaProducts
        ->filter(fn ($mp) => $mp->meta)
        ->values()
        ->map(function ($mp) {
          $meta = $mp->meta;
          return [
            'id' => $meta->id,
            'name' => $meta->name,
            'type' => $meta->type,
            'show_in_print' => (bool) $mp->show_in_print,
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
      'meta_print_ids' => 'array|nullable',
    ]);

    $product = Product::with('metaProducts.meta', 'orders')->findOrFail($id);

    $product->update($validatedData);

    // Update meta_id
    if ($request->has('meta_products')) {
      $printIds = collect($request->input('meta_print_ids', []))
        ->map(fn ($v) => (int) $v)
        ->filter()
        ->values()
        ->all();
      $product->metaProducts()->delete();
      foreach ($request->input('meta_products') as $metaId) {
        $metaId = (int) $metaId;
        $product->metaProducts()->create([
          'meta_id' => $metaId,
          'show_in_print' => in_array($metaId, $printIds, true),
        ]);
      }
    }
    $product->load('metaProducts.meta', 'orders');

    $response = [
      'id' => $product->id,
      'name' => $product->name,
      'description' => $product->description,
      'category' => $product->category,
      'meta' => $product->metaProducts->pluck('meta_id'),
      'meta_print_ids' => $product->metaProducts->where('show_in_print', true)->pluck('meta_id')->values(),
      'meta_products' => $product->metaProducts
        ->filter(fn ($mp) => $mp->meta)
        ->values()
        ->map(function ($mp) {
          $meta = $mp->meta;
          return [
            'id' => $meta->id,
            'name' => $meta->name,
            'type' => $meta->type,
            'show_in_print' => (bool) $mp->show_in_print,
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
