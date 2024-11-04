<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\ProfileUpdateRequest;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Pest\Plugins\Profile;

class ProfileController extends Controller
{

    public function index()
    {
        $user = User::all();
        return response()->json($user);
    }
    public function update(ProfileUpdateRequest $request)
    {
        $user = $request->user();

        $validated = $request->validated();
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $avatarPath;
        }
        $user->update($validated);
        $user = $user->refresh();

        $message['user'] = $user;
        $message['success'] = true;

        return response()->json($message, 200);
    }
}
