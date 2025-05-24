<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
  public function index()
  {
    return Role::all(); // bisa ditambahkan filter/pagination
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|unique:roles,name',
      'guard_name' => 'nullable|string',
    ]);

    return Role::create([
      'name' => $validated['name'],
      'guard_name' => $validated['guard_name'] ?? 'web',
    ]);
  }

  public function show(Role $role)
  {
    return $role;
  }

  public function update(Request $request, Role $role)
  {
    $validated = $request->validate([
      'name' => 'required|string|unique:roles,name,' . $role->id,
    ]);

    $role->update($validated);

    return $role;
  }

  public function destroy(Role $role)
  {
    $role->delete();
    return response()->noContent();
  }
}
