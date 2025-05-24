<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
  public function index()
  {
    $roles = Role::with('permissions')->get();
    return $roles;
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|unique:roles,name',
      'guard_name' => 'nullable|string',
      'capabilities' => 'nullable|array', // tambahkan validasi capabilities
      'capabilities.*' => 'string|exists:permissions,name', // pastikan permission ada
    ]);

    $role = Role::create([
      'name' => $validated['name'],
      'guard_name' => $validated['guard_name'] ?? 'web',
    ]);

    // Sinkronkan capabilities (permissions) jika tersedia
    if (isset($validated['capabilities'])) {
      $permissions = Permission::whereIn('name', $validated['capabilities'])->get();
      $role->syncPermissions($permissions);
    }

    return $role;
  }

  public function show(Role $role)
  {
    $role->load('permissions'); // <= tambahkan ini juga
    return $role;
  }

  public function update(Request $request, Role $role)
  {
    // Validasi nama role
    $validated = $request->validate([
      'name' => 'required|string|unique:roles,name,' . $role->id,
      'capabilities' => 'nullable|array', // tambahkan validasi capabilities
      'capabilities.*' => 'string|exists:permissions,name', // pastikan permission ada
    ]);

    // Update nama role
    $role->update([
      'name' => $validated['name'],
      'guard_name' => $validated['guard_name'] ?? 'web',
    ]);

    // Sinkronkan capabilities (permissions) jika tersedia
    if (isset($validated['capabilities'])) {
      $permissions = Permission::whereIn('name', $validated['capabilities'])->get();
      $role->syncPermissions($permissions);
    }
    // Sinkronkan capabilities (permissions) jika tersedia
    $role->syncPermissions($permissions);
    return $role;
  }

  public function destroy(Role $role)
  {
    $role->delete();
    return response()->noContent();
  }
}
