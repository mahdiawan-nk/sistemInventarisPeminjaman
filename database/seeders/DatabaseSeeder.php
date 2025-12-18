<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserRole;
use Illuminate\Support\Facades\Hash;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $roleUser = [
        //     'Administrator',
        //     'Staff IT',
        //     'User'
        // ];

        // foreach($roleUser as $role){
        //     UserRole::create([
        //         'name'=>$role,
        //         'guard_name'=>'web'
        //     ]);
        // }

        $user =User::create([
            'name'=>'Administrator',
            'email'=>'admin@example.com',
            'password'=> Hash::make('12345678')
        ]);

        $user->assignRole('Administrator');
    }
}
