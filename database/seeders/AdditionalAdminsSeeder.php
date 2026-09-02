<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdditionalAdminsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admins = [
            [
                'email' => 'reiacglobal@gmail.com',
                'name' => 'REIAC Global Admin',
                'password' => 'REIAC@1234',
                'is_active' => true,
            ],
            [
                'email' => 'reiacglobalinfo@gmail.com',
                'name' => 'REIAC Global Info Admin',
                'password' => 'REIAC@1234',
                'is_active' => true,
            ],
        ];

        foreach ($admins as $adminData) {
            Admin::firstOrCreate(
                ['email' => $adminData['email']],
                [
                    'name' => $adminData['name'],
                    'password' => $adminData['password'],
                    'is_active' => $adminData['is_active'],
                ]
            );
        }
    }
}
