<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\ProfileUpdateRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Pest\Plugins\Profile;

class ProfileController extends Controller
{
    public function update(ProfileUpdateRequest $request)
    {
        $user = $request->user();

        $validated = $request->validated();

        $user->update($validated);
        $user = $user->refresh();

        $message['user'] = $user;
        $message['success'] = true;

        return response()->json($message, 200);
    }
}
