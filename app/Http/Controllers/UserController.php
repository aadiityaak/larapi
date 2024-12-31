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
        // {
        //     "id": 1,
        //     "name": "Test Admin",
        //     "email": "test@larapi.test",
        //     "email_verified_at": "2024-12-23T03:36:38.000000Z",
        //     "is_admin": 1,
        //     "avatar": "avatars\/6uC5HtnW5Y441nSkj4nueilglVcbmYYNRBlcaJLA.jpg",
        //     "phone": "08123456789",
        //     "address": "Jl. Kebon Jeruk No. 1",
        //     "position": "Manager",
        //     "created_at": "2024-12-23T03:36:38.000000Z",
        //     "updated_at": "2024-12-26T03:47:16.000000Z"
        // }
        $data = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'email_verified_at' => $user->email_verified_at,
            'is_admin' => strval($user->is_admin),
            'avatar' => $user->avatar,
            'phone' => $user->phone,
            'address' => $user->address,
            'position' => $user->position,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at
        ];
        return response()->json($data);
    }
}
