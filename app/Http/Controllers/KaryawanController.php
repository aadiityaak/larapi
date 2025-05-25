<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Jobdesk;

class KaryawanController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $paginate = $request->query('paginate');
        $name = $request->query('name');
        $role = $request->query('role');

        $query = User::with('jobdesk');

        if ($name && strlen($name) > 2) {
            $query->where('name', 'like', '%' . $name . '%');
        }

        if ($role) {
            $query->where('role', $role);
        }

        $query->orderBy('created_at', 'desc');

        if ($paginate === 'false') {
            $users = $query->get()->map(function ($data) {
                return [
                    'id' => $data->id,
                    'name' => $data->name,
                    'email' => $data->email,
                    'is_admin' => strval($data->is_admin),
                    'avatar' => $data->avatar,
                    'phone' => $data->phone,
                    'address' => $data->address,
                    'role' => $data->role,
                    'total_jobdesk' => $data->jobdesk->count(),
                    'jobdesk_on_progress' => $data->jobdesk->where('status', 'Progress')->count(),
                    'jobdesk_selesai' => $data->jobdesk->where('status', 'Selesai')->count(),
                ];
            });
        } else {
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
                    'role' => $data->role,
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
        $user->load('jobdesk');

        $response = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'is_admin' => strval($user->is_admin),
            'avatar' => $user->avatar,
            'phone' => $user->phone,
            'address' => $user->address,
            'role' => $user->role,
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

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'phone' => 'required|string',
            'address' => 'required|string',
            'role' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
            'avatar' => 'nullable',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = asset('storage/' . $validated['avatar']);
        } else {
            unset($validated['avatar']);
        }

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        if ($requested_user->is_admin !== 1 && isset($validated['role'])) {
            $validated['role'] = $user->role;
        }

        $updated = $user->update($validated);

        if ($updated) {
            if ($requested_user->is_admin === 1 && isset($validated['role'])) {
                $user->syncRoles([$validated['role']]);
            }
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
            'role' => 'nullable|string',
            'password' => 'required|string|min:8|confirmed',
            'avatar' => 'nullable',
        ]);

        if (is_string($request->avatar)) {
            unset($validated['avatar']);
        } elseif ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = asset('storage/' . $validated['avatar']);
        }

        $validated['password'] = bcrypt($validated['password']);
        $data = User::create($validated);

        if (!empty($validated['role'])) {
            $data->assignRole($validated['role']);
        }

        $response = [
            'id' => $data->id,
            'name' => $data->name,
            'email' => $data->email,
            'is_admin' => strval($data->is_admin),
            'avatar' => $data->avatar,
            'phone' => $data->phone,
            'address' => $data->address,
            'role' => $data->role,
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
