<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'id' => (string) Str::uuid(),
            'username' => 'admin247',
            'name' => 'Admin Mesa 24/7',
            'email' => 'admin_mesa247l@dev.com',
            'password' => Hash::make('password123'), 
        ]);
    }
}
