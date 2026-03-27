<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | ROLES
        |--------------------------------------------------------------------------
        */

        $superAdmin = Role::firstOrCreate([
            'name' => 'super_admin'
        ], [
            'label' => 'Super Administrator',
            'description' => 'Full system access'
        ]);

        $sacdev = Role::firstOrCreate([
            'name' => 'sacdev'
        ], [
            'label' => 'SACDEV Staff',
            'description' => 'Handles project approvals'
        ]);

        $osa = Role::firstOrCreate([
            'name' => 'osa_head'
        ], [
            'label' => 'OSA Head',
            'description' => 'Approves specific forms'
        ]);

        /*
        |--------------------------------------------------------------------------
        | PERMISSIONS (keep this minimal for now)
        |--------------------------------------------------------------------------
        */

        $permissions = [
            ['code' => 'projects.view', 'label' => 'View Projects'],
            ['code' => 'projects.approve', 'label' => 'Approve Projects'],
            ['code' => 'projects.return', 'label' => 'Return Projects'],
            ['code' => 'documents.manage', 'label' => 'Manage Documents'],
            ['code' => 'users.manage', 'label' => 'Manage Users'],
            ['code' => 'roles.manage', 'label' => 'Manage Roles'],
            ['code' => 'context.manage', 'label' => 'Manage Context Options'],
        ];

        $permissionModels = [];

        foreach ($permissions as $perm) {
            $permissionModels[$perm['code']] = Permission::firstOrCreate(
                ['code' => $perm['code']],
                [
                    'label' => $perm['label'],
                    'description' => null,
                ]
            );
        }



   
        $superAdmin->permissions()->sync(
            Permission::pluck('id')->toArray()
        );

    
        $sacdev->permissions()->sync([
            $permissionModels['projects.view']->id,
            $permissionModels['projects.approve']->id,
            $permissionModels['projects.return']->id,
            $permissionModels['documents.manage']->id,
        ]);

   
        $osa->permissions()->sync([
            $permissionModels['projects.view']->id,
            $permissionModels['projects.approve']->id,
        ]);



        // r current admin behavior

        $adminUsers = User::where('system_role', 'sacdev_admin')->get();

        foreach ($adminUsers as $user) {
           
            $user->role_id = $superAdmin->id;
            $user->save();
        }
    }
}