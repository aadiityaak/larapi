<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
  /**
   * Display a listing of the categories.
   */
  public function index()
  {
    return response()->json(Category::all());
  }

  /**
   * Store a newly created category in storage.
   */
  public function store(Request $request)
  {
    $request->validate([
      'name' => 'required|string|max:255|unique:categories,name',
    ]);

    $slug = str_replace(' ', '-', strtolower($request->name));
    $category = Category::create([
      'name' => $request->name,
      'slug' => $slug
    ]);

    return response()->json($category, Response::HTTP_CREATED);
  }

  /**
   * Display the specified category.
   */
  public function show(Category $category)
  {
    return response()->json($category);
  }

  /**
   * Update the specified category in storage.
   */
  public function update(Request $request, Category $category)
  {
    $request->validate([
      'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
    ]);
    $slug = str_replace(' ', '-', strtolower($request->name));
    $category->update([
      'name' => $request->name,
      'slug' => $slug
    ]);

    return response()->json($category);
  }

  /**
   * Remove the specified category from storage.
   */
  public function destroy(Category $category)
  {
    $category->delete();

    return response()->json(null, Response::HTTP_NO_CONTENT);
  }
}
