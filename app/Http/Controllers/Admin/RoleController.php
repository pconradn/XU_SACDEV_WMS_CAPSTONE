<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->latest()->get();

        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all();

        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|unique:roles,name',
            'label' => 'required|string',
            'permissions' => 'nullable|array',
        ]);

        $role = Role::create([
            'name' => $data['name'],
            'label' => $data['label'],
        ]);

        $role->permissions()->sync($data['permissions'] ?? []);

        Log::info('Role created', [
            'performed_by' => auth()->id(),
            'role_id' => $role->id,
        ]);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role created.');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        $role->load('permissions');

        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $data = $request->validate([
            'name' => "required|unique:roles,name,{$role->id}",
            'label' => 'required|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        if ($request->has('is_default')) {

            // remove existing default
            Role::where('is_default', true)->update([
                'is_default' => false
            ]);

            $role->is_default = true;

        } else {

            // prevent removing default if this role is currently default
            if ($role->is_default) {
                return back()->with('error', 'There must always be a default role.');
            }

            $role->is_default = false;
        }

        $role->name = $data['name'];
        $role->label = $data['label'];
        $role->save();
        $role->permissions()->sync($data['permissions'] ?? []);

        Log::info('Role updated', [
            'performed_by' => auth()->id(),
            'role_id' => $role->id,
            'role_name' => $role->name,
        ]);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated.');
    }

    public function destroy(Role $role)
    {
        $defaultRole = Role::where('is_default', true)->firstOrFail();

        if ($role->id === $defaultRole->id) {
            return back()->with('error', 'Default role cannot be deleted.');
        }

        User::where('role_id', $role->id)->update([
            'role_id' => $defaultRole->id,
            'system_role' => 'sacdev_admin',
        ]);

        $role->delete();

        Log::warning('Role deleted', [
            'performed_by' => auth()->id(),
            'deleted_role_id' => $role->id,
        ]);

        return back()->with('success', 'Role deleted.');
    }
}