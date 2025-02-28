<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
  public function index()
  {
    $posts = Post::latest()->paginate(10);
    return response()->json($posts);
  }

  public function show(Post $post)
  {
    return response()->json($post);
  }

  public function store(Request $request)
  {
    $user_id = $request->user()->id;
    $request->validate([
      'title' => 'required|string|max:255',
      'content' => 'required',
      'category_id' => 'required|exists:categories,id',
      'featured_image' => 'nullable|string',
    ]);
    $slug = str_replace(' ', '-', strtolower($request->title));
    $request->merge(['slug' => $slug]);
    $request->merge(['user_id' => $user_id]);
    $post = Post::create($request->all());
    return response()->json($post, 201);
  }

  public function update(Request $request, Post $post)
  {
    $user_id = $request->user()->id;
    $request->validate([
      'title' => 'sometimes|string|max:255',
      'content' => 'sometimes',
      'category_id' => 'sometimes|exists:categories,id',
      'featured_image' => 'nullable|string',
    ]);
    $slug = str_replace(' ', '-', strtolower($request->title));
    $request->merge(['slug' => $slug]);
    $request->merge(['user_id' => $user_id]);
    $post->update($request->all());
    return response()->json($post);
  }

  public function destroy(Post $post)
  {
    $post->delete();
    return response()->json(['message' => 'Post deleted successfully']);
  }
}
