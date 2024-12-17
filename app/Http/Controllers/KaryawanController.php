<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Jobdesk;
use App\Notifications\PendingJobdesk;


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
        $requested_user = $request->user();
        $this->authorize('update', $user);

        // Validasi manual
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'phone' => 'required|string',
            'address' => 'required|string',
            'position' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
            'avatar' => 'nullable',
        ]);

        // Abaikan 'avatar' jika merupakan string, hanya lanjutkan jika file
        if (is_string($request->avatar)) {
            unset($validated['avatar']);
        } elseif ($request->hasFile('avatar')) {
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

        // Cek jika user bukan admin dan coba mengupdate position, maka abaikan perubahan position
        if ($requested_user->is_admin !== 1 && isset($validated['position'])) {
            $validated['position'] = $user->position;
            $message = 'Data tersimpan, position tidak bisa diupdate';
        } else {
            $message = 'Data tersimpan';
        }

        // Update user dengan data yang tervalidasi
        $user->update($validated);

        return response()->json([
            'user' => $user,
            'message' => $message,
            'success' => true
        ], 200);
    }

    public function sendJobdeskReminder(Request $request)
    {
        $request->validate([
            'jobdesk_id' => 'required|integer|exists:jobdesks,id',
            'title' => 'required|string|max:255',
        ]);

        $user = User::find($request->user()->id);
        $jobdesk = [
            'id' => $request->jobdesk_id,
            'title' => $request->title,
        ];

        // Kirim notifikasi
        $user->notify(new PendingJobdesk($jobdesk));

        return response()->json(['message' => 'Notifikasi jobdesk telah dikirim!']);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('delete', $user);
        $user->delete();
        return response()->json($user);
    }
}
