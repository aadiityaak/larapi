<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // mengembalikan data user berdasarkan $request / user yang sedang login
        $user = $request->user();
        $data = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'email_verified_at' => $user->email_verified_at,
            'is_admin' => strval($user->is_admin),
            'avatar' => $user->avatar,
            'phone' => $user->phone,
            'address' => $user->address,
            'role' => $user->roles->pluck('name'),
            'capabilities' => $user->getAllPermissions()->pluck('name'),
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at
        ];
        return response()->json($data);
    }
}
