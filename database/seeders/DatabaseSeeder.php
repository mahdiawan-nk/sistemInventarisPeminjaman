<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserRole;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roleUser = [
            'Administrator',
            'Staff IT',
            'User'
        ];

        foreach($roleUser as $role){
            UserRole::create([
                'name'=>$role,
                'guard_name'=>'web'
            ]);
        }
    }
}
