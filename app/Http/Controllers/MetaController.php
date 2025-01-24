<?php

namespace App\Http\Controllers;

use App\Models\Meta;
use Illuminate\Http\Request;

class DataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $paginate = $request->query('paginate');

        if ($paginate === 'false') {
            $data = Meta::all();
        } else {
            $data = Meta::paginate(25);
        }
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Meta $data)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Meta $data)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Meta $data)
    {
        //
    }
}
