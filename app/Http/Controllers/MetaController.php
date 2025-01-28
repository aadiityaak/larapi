<?php

namespace App\Http\Controllers;

use App\Models\Meta;
use Illuminate\Http\Request;

class MetaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $paginate = $request->query('paginate');

        if ($paginate === 'false') {
            $meta = Meta::orderByDesc('id')->get();
        } else {
            $meta = Meta::orderByDesc('id')->paginate(25);
        }

        return response()->json($meta);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'meta_key' => 'string|max:255|nullable',
            'meta_value' => 'string|nullable',
        ]);

        $meta = Meta::create($request->all());

        return response()->json($meta, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Meta $meta)
    {
        return response()->json($meta);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Meta $meta)
    {
        $request->validate([
            'meta_key' => 'string|max:255|nullable',
            'meta_value' => 'string|nullable',
        ]);

        $meta->update($request->all());

        return response()->json($meta);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Meta $meta)
    {
        $meta->delete();
        return response()->json(null, 204);
    }
}
