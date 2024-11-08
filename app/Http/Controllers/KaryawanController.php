<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class KaryawanController extends Controller
{
    use AuthorizesRequests;

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

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);

        // Validasi manual
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'phone' => 'required|string',
            'address' => 'required|string',
            'position' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
            'avatar' => 'nullable|image|max:2048|mimes:jpeg,png,jpg',
        ]);

        // Menangani avatar
        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');

            // Hapus avatar lama jika ada
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
        } else {
            // Jika tidak ada file avatar, gunakan avatar yang ada
            $validated['avatar'] = $user->avatar;
        }

        // Hanya update password jika diisi
        if (isset($validated['password']) && !empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);
        return response()->json([
            'user' => $user,
            'success' => true
        ], 200);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('delete', $user);
        $user->delete();
        return response()->json($user);
    }
}
