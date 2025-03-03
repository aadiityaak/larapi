<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\ProfileUpdateRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{

    public function index()
    {
        $user = User::paginate(25);
        return response()->json($user);
    }

    public function show($id)
    {
        $user = User::find($id);
        return response()->json($user);
    }
    public function update(ProfileUpdateRequest $request)
    {
        $user = $request->user();
        $validated = $request->validated();

        // Menangani avatar
        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = asset('storage/' . $validated['avatar']);
        } else {
            // Jika tidak ada file avatar, gunakan avatar yang ada
            $validated['avatar'] = $user->avatar;
        }

        // Hanya update password jika diisi
        if (isset($validated['password']) && !empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            // Hapus password dari array validasi agar tidak diupdate
            unset($validated['password']);
        }

        //abaikan position jika diisi selain oleh is_admin
        if (isset($validated['position']) && $user->is_admin !== 1) {
            unset($validated['position']);
        }

        // Update profil pengguna
        $user->update($validated);
        $user = $user->refresh();

        return response()->json([
            'user' => $user,
            'success' => true,
        ], 200);
    }
}
