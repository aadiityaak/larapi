<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware as ControllersMiddleware;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [
            new ControllersMiddleware('auth:sanctum', except: ['index', 'show'])
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Post::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $fdields = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required',
        ]);

        $post = $request->user()->posts()->create($fdields);

        return $post;
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return $post;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        Gate::authorize('modify', $post);

        $fdields = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required',
        ]);

        $post->update($fdields);

        return $post;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        Gate::authorize('modify', $post);
        $post->delete();

        return ['message' => 'Post Deleted'];
    }
}
