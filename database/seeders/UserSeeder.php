<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Admin ZVRS Kitchen',
            'role' => 'admin',
            'email' => 'kitchenzvrs@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('Adminzvrskitchen'),
            'user_whatsapp' => '081934131038',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
