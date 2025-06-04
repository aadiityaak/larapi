<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Jobdesk;

class KaryawanController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $paginate = $request->boolean('paginate', true);
        $name = $request->query('name');

        $query = User::with(['roles', 'jobdesk']);

        // Filter nama jika lebih dari 2 karakter
        if ($name && strlen($name) > 2) {
            $query->where('name', 'like', '%' . $name . '%');
        }

        $query->orderByDesc('created_at');

        $transformUser = function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_admin' => strval($user->is_admin),
                'avatar' => $user->avatar,
                'phone' => $user->phone,
                'address' => $user->address,
                'role' => $user->roles->pluck('name'),
                'total_jobdesk' => $user->jobdesk->count(),
                'jobdesk_on_progress' => $user->jobdesk->where('status', 'Progress')->count(),
                'jobdesk_selesai' => $user->jobdesk->where('status', 'Selesai')->count(),
            ];
        };

        if (!$paginate) {
            $users = $query->get()->map($transformUser);
            return response()->json([
                'data' => $users,
                'total' => $users->count(),
            ]);
        }

        // Paginate with transform
        $paginated = $query->paginate(25);
        $paginated->getCollection()->transform($transformUser);

        return response()->json($paginated);
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
            'role' => $user->roles->pluck('name'),
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

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = asset('storage/' . $validated['avatar']);
        } else {
            unset($validated['avatar']);
        }

        // Handle password hashing
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Handle role separately, cek dulu
        $roleName = $validated['role'] ?? null;
        unset($validated['role']); // jangan ikut update di table users

        // Update user data
        $updated = $user->update($validated);

        if ($updated && $requested_user->is_admin == 1 && $roleName) {
            $role = Role::where('name', $roleName)->first();
            if (!$role) {
                return response()->json(['message' => 'Role not found'], 404);
            }
            $user->syncRoles([$role->name]);
        }

        if ($updated) {
            $response = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_admin' => strval($user->is_admin),
                'avatar' => $user->avatar,
                'phone' => $user->phone,
                'address' => $user->address,
                'role' => $user->roles->pluck('name'),
                'total_jobdesk' => $user->jobdesk->count(),
                'jobdesk_on_progress' => $user->jobdesk->where('status', 'Progress')->count(),
                'jobdesk_selesai' => $user->jobdesk->where('status', 'Selesai')->count(),
            ];
            return response()->json($response, 200);
        }

        return response()->json(['message' => 'Update failed'], 400);
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
            'avatar' => 'nullable|image|max:2048', // disarankan validasi image
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = asset('storage/' . $path);
        } else {
            unset($validated['avatar']);
        }

        // Hash password
        $validated['password'] = Hash::make($validated['password']);

        // Tangani role secara terpisah
        $roleName = $validated['role'] ?? null;
        unset($validated['role']); // jangan masuk ke User::create

        // Create user
        $user = User::create($validated);

        // Assign role jika tersedia
        if ($roleName) {
            $role = Role::where('name', $roleName)->first();
            if (!$role) {
                return response()->json(['message' => 'Role not found'], 404);
            }
            $user->assignRole($role->name);
        }

        // Buat response
        $response = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'is_admin' => strval($user->is_admin),
            'avatar' => $user->avatar,
            'phone' => $user->phone,
            'address' => $user->address,
            'role' => $user->roles->pluck('name'),
            'total_jobdesk' => $user->jobdesk->count(),
            'jobdesk_on_progress' => $user->jobdesk->where('status', 'Progress')->count(),
            'jobdesk_selesai' => $user->jobdesk->where('status', 'Selesai')->count(),
        ];

        return response()->json($response, 201);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('delete', $user);
        $user->delete();
        return response()->json($user);
    }
}
