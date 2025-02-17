<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        return response()->json($permissions);
    }

    public function update(Request $request, User $user)
    {
        $permissions = $request->input('permissions');
        $user->syncPermissions($permissions); // Menggunakan Spatie
        return response()->json(['message' => 'Permissions updated']);
    }
}
