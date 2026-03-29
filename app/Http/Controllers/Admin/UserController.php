<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Cluster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['role', 'clusters'])
            ->where('system_role', 'sacdev_admin')
            ->latest()
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        $clusters = Cluster::all();

        return view('admin.users.create', compact('roles', 'clusters'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role_id' => 'required|exists:roles,id',
            'clusters' => 'nullable|array',
            'clusters.*' => 'exists:clusters,id',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $data['role_id'],
            'system_role' => 'sacdev_admin', // IMPORTANT
        ]);

        if (!empty($data['clusters'])) {
            $user->clusters()->sync($data['clusters']);
        }

        Log::info('Admin user created', [
            'performed_by' => auth()->id(),
            'created_user_id' => $user->id,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $clusters = Cluster::all();

        $user->load('clusters');

        return view('admin.users.edit', compact('user', 'roles', 'clusters'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$user->id}",
            'role_id' => 'required|exists:roles,id',
            'clusters' => 'nullable|array',
            'clusters.*' => 'exists:clusters,id',
        ]);

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'role_id' => $data['role_id'],
            'system_role' => 'sacdev_admin',
        ]);

        $user->clusters()->sync($data['clusters'] ?? []);

        Log::info('Admin user updated', [
            'performed_by' => auth()->id(),
            'updated_user_id' => $user->id,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated.');
    }

    public function destroy(User $user)
    {
        Log::warning('Admin user deleted', [
            'performed_by' => auth()->id(),
            'deleted_user_id' => $user->id,
        ]);

        $user->delete();

        return back()->with('success', 'User deleted.');
    }
}