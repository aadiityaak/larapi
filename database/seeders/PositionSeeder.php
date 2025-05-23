<?php
use Spatie\Permission\Models\Role;

public function run(): void
{
  Role::create(['name' => 'admin']);
  Role::create(['name' => 'user']);
  Role::create(['name' => 'owner']);
  Role::create(['name' => 'manager']);
  Role::create(['name' => 'keuangan']);
  Role::create(['name' => 'staff']);
}