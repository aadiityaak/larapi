<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Post;

class PostController extends Controller
{
  public function index()
  {
    $posts = Post::latest()->paginate(10);
    $posts->load('category');
    return response()->json($posts);
  }

  public function show(Post $post)
  {
    return response()->json($post);
  }

  public function store(Request $request)
  {
    // Validasi input
    $validated = $request->validate(
      [
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'category_id' => 'required|exists:categories,id',
        'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi file gambar
      ],
      [
        'title.required' => 'Judul harus diisi.',
        'content.required' => 'Isi konten harus diisi.',
        'category_id.required' => 'Kategori harus dipilih.',
        'category_id.exists' => 'Kategori tidak ditemukan.',
        'featured_image.image' => 'File harus berupa gambar.',
        'featured_image.mimes' => 'Format file harus JPEG, PNG, JPG, atau GIF.',
        'featured_image.max' => 'Ukuran file maksimal 2MB.',
      ]
    );

    // Handle Featured Image
    if ($request->hasFile('featured_image')) {
      $validated['featured_image'] = $request->file('featured_image')->store('images', 'public');
      $validated['featured_image'] = asset('storage/' . $validated['featured_image']);
    } else {
      unset($validated['featured_image']); // Jika tidak ada file, hapus key ini
    }

    // Tambahkan slug dan user_id
    $slug = str_replace(' ', '-', strtolower($validated['title']));
    $validated['slug'] = $slug;
    $validated['user_id'] = $request->user()->id;

    // Simpan data ke database
    $post = Post::create($validated);

    // Return respons JSON
    return response()->json($post, 201);
  }

  public function update(Request $request, Post $post)
  {
    // Validasi input
    $validated = $request->validate(
      [
        'title' => 'sometimes|string|max:255',
        'content' => 'sometimes|string',
        'category_id' => 'sometimes|exists:categories,id',
        'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi file gambar
      ],
      [
        'title.required' => 'Judul harus diisi.',
        'content.required' => 'Isi konten harus diisi.',
        'category_id.required' => 'Kategori harus dipilih.',
        'category_id.exists' => 'Kategori tidak ditemukan.',
        'featured_image.image' => 'File harus berupa gambar.',
        'featured_image.mimes' => 'Format file harus JPEG, PNG, JPG, atau GIF.',
        'featured_image.max' => 'Ukuran file maksimal 2MB.',
      ]
    );

    // Handle Featured Image
    if ($request->hasFile('featured_image')) {
      // Hapus gambar lama jika ada
      if ($post->featured_image) {
        Storage::disk('public')->delete($post->featured_image);
      }
      // Simpan gambar baru
      $validated['featured_image'] = $request->file('featured_image')->store('images', 'public');
      $validated['featured_image'] = asset('storage/' . $validated['featured_image']);
    } elseif (is_string($request->featured_image)) {
      unset($validated['featured_image']); // Jika featured_image adalah string, abaikan
    }

    // Update slug jika title berubah
    if (isset($validated['title'])) {
      $slug = str_replace(' ', '-', strtolower($validated['title']));
      $validated['slug'] = $slug;
    }

    // Update data post
    $post->update($validated);

    // Return respons JSON
    return response()->json($post);
  }

  public function destroy(Post $post)
  {
    // Hapus gambar dari storage jika ada
    if ($post->featured_image) {
      Storage::disk('public')->delete($post->featured_image);
    }

    // Hapus data post
    $post->delete();

    // Return respons JSON
    return response()->json(['message' => 'Post deleted successfully']);
  }
}
