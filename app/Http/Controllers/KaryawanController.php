<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Jobdesk;


class KaryawanController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $paginate = $request->query('paginate');
        $name = $request->query('name');
        $position = $request->query('position');

        // Query dasar semua user dengan relasi jobdesk
        $query = User::with('jobdesk');

        // Filter berdasarkan name jika ada
        if ($name && strlen($name) > 2) {
            $query->where('name', 'like', '%' . $name . '%');
        }

        // Filter berdasarkan position jika ada
        if ($position) {
            $query->where('position', $position);
        }

        // Sorting descending berdasarkan created_at
        $query->orderBy('created_at', 'desc');

        // Check if pagination should be disabled
        if ($paginate === 'false') {
            // Get all records without pagination
            $users = $query->get()->map(function ($data) {
                return [
                    'id' => $data->id,
                    'name' => $data->name,
                    'email' => $data->email,
                    'is_admin' => strval($data->is_admin),
                    'avatar' => $data->avatar,
                    'phone' => $data->phone,
                    'address' => $data->address,
                    'position' => $data->position,
                    'total_jobdesk' => $data->jobdesk->count(),
                    'jobdesk_on_progress' => $data->jobdesk->where('status', 'Progress')->count(),
                    'jobdesk_selesai' => $data->jobdesk->where('status', 'Selesai')->count(),
                ];
            });
        } else {
            // Paginate results
            $users = $query->paginate(25);
            $users->getCollection()->transform(function ($data) {
                return [
                    'id' => $data->id,
                    'name' => $data->name,
                    'email' => $data->email,
                    'is_admin' => strval($data->is_admin),
                    'avatar' => $data->avatar,
                    'phone' => $data->phone,
                    'address' => $data->address,
                    'position' => $data->position,
                    'total_jobdesk' => $data->jobdesk->count(),
                    'jobdesk_on_progress' => $data->jobdesk->where('status', 'Progress')->count(),
                    'jobdesk_selesai' => $data->jobdesk->where('status', 'Selesai')->count(),
                ];
            });
        }

        return response()->json($users);
    }

    public function show($id)
    {
        $user = User::find($id);
        // load jobdesk
        $user->load('jobdesk');
        $response = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'is_admin' => strval($user->is_admin),
            'avatar' => $user->avatar,
            'phone' => $user->phone,
            'address' => $user->address,
            'position' => $user->position,
            'total_jobdesk' => $user->jobdesk->count(),
            'jobdesk_on_progress' => $user->jobdesk->where('status', 'Progress')->count(),
            'jobdesk_selesai' => $user->jobdesk->where('status', 'Selesai')->count(),
        ];
        return response()->json($response);
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

        // Handle Avatar
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = asset('storage/' . $validated['avatar']);
        } else {
            unset($validated['avatar']);
        }

        // Handle Password
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Check for position update
        if ($requested_user->is_admin !== 1 && isset($validated['position'])) {
            $validated['position'] = $user->position;
        }

        // Update user
        $updated = $user->update($validated);

        // Check if the update was successful
        if ($updated) {
            // Retrieve the updated user data
            $data = User::find($id);
            return response()->json($data, 200);
        } else {
            return response()->json(['message' => 'Update failed'], 400);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'required|string',
            'address' => 'required|string',
            'position' => 'nullable|string',
            'password' => 'required|string|min:8|confirmed',
            'avatar' => 'nullable',
        ]);

        // Abaikan 'avatar' jika merupakan string, hanya lanjutkan jika file
        if (is_string($request->avatar)) {
            unset($validated['avatar']);
        } elseif ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = asset('storage/' . $validated['avatar']);
        }

        $validated['password'] = bcrypt($validated['password']);
        $data = User::create($validated);

        $response = [
            'id' => $data->id,
            'name' => $data->name,
            'email' => $data->email,
            'is_admin' => strval($data->is_admin),
            'avatar' => $data->avatar,
            'phone' => $data->phone,
            'address' => $data->address,
            'position' => $data->position,
            'total_jobdesk' => $data->jobdesk->count(),
            'jobdesk_on_progress' => $data->jobdesk->where('status', 'Progress')->count(),
            'jobdesk_selesai' => $data->jobdesk->where('status', 'Selesai')->count(),
        ];

        return response()->json($response, 200);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('delete', $user);
        $user->delete();
        return response()->json($user);
    }
}
