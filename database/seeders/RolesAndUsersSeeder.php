<?php

namespace Database\Seeders;

use App\Enums\UserStatus;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RolesAndUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run(): void
    {
        // 1. Create Default POS Permissions
        $viewSales = Permission::create(['name' => 'view-sales', 'display_name' => 'View Sales Terminal']);
        $editSale  = Permission::create(['name' => 'edit-sale', 'display_name' => 'edit Transaction']);
        $settings  = Permission::create(['name' => 'crud-users', 'display_name' => 'Manage System Settings']);

        // 2. Create Default Roles
        $adminRole   = Role::create(['name' => 'admin', 'display_name' => 'Administrator']);
        $managerRole = Role::create(['name' => 'manager', 'display_name' => 'Store Manager']);
        $cashierRole = Role::create(['name' => 'cashier', 'display_name' => 'Cashier']);

        // 3. Assign Permissions to Roles
        // Admin gets everything
        $adminRole->permissions()->attach([$viewSales->id, $editSale->id, $settings->id]);

        // Manager gets sales access and refund capabilities
        $managerRole->permissions()->attach([$viewSales->id, $editSale->id]);

        // Cashier only gets checkout screen access
        $cashierRole->permissions()->attach([$viewSales->id]);

        // 4. Create Sample System Users
        User::create([
            'role_id'  => $adminRole->id,
            'name'     => 'Ali',
            'email'    => 'ali@ali.com',
            'password' => Hash::make('123123'),
            'status'   => UserStatus::Active,
        ]);

        User::create([
            'role_id'  => $cashierRole->id,
            'name'     => 'John Cashier',
            'email'    => 'cashier@pos.com',
            'password' => Hash::make('123123'),
            'status'   => UserStatus::Active,
        ]);

        User::create([
            'role_id'  => $managerRole->id,
            'name'     => 'manager 1',
            'email'    => 'manager@pos.com',
            'password' => Hash::make('123123'),
            'status'   => UserStatus::Active,
        ]);
    }
}
