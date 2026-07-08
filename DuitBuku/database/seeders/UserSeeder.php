<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed the single business-owner account (this app has no registration flow).
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'owner@duitbuku.test'],
            [
                'name'     => 'Anna Adame',
                'role'     => 'Founder',
                'password' => Hash::make('password'),
            ]
        );
    }
}
