<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Roles;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rolesData = Roles::where('name', 'admin')->first();


        User::create([
            'name' => 'admin',
            'email' => 'admin@mail.com',
            'password' => Hash::make('12345678'),
            'role_id' => $rolesData->id
        ]);   
    }
}
