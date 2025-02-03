<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WebUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define roles and their corresponding user details
        $rolesWithUsers = [
            'admin' => [
                'name' => 'Admin User',
                'email' => 'admin@admin.com',
                'password' => 'admin123',
                'status' => '1',
            ],
            'manager' => [
                'name' => 'Manager User',
                'email' => 'manager@admin.com',
                'password' => 'manager123',
                'status' => '1',
            ],
            'deo' => [
                'name' => 'Data Entry Operator',
                'email' => 'deo@admin.com',
                'password' => 'deo123',
                'status' => '1',
            ],
            'reviewer' => [
                'name' => 'Reviewer User',
                'email' => 'reviewer@admin.com',
                'password' => 'reviewer123',
                'status' => '1',
            ],
            'transport' => [
                'name' => 'Transport User',
                'email' => 'transport@admin.com',
                'password' => 'transport123',
                'status' => '1',
            ],
        ];

        foreach ($rolesWithUsers as $roleName => $userData) {
            // Create or fetch the role
            $role = Role::firstOrCreate(
                ['name' => $roleName, 'guard_name' => 'web']
            );

            // Create or fetch the user
            $user = User::firstOrCreate(
                ['email' => $userData['email']], // Ensure unique user by email
                [
                    'name' => $userData['name'],
                    'password' => Hash::make($userData['password']),
                    'status' => $userData['status'],
                ]
            );

            // Assign the role to the user
            $user->assignRole($role);
        }
    }
}
