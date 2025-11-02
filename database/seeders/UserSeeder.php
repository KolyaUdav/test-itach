<?php

namespace Database\Seeders;

use App\Enums\Roles\Entities;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::create([
            'name' => 'Valerka',
            'email' => 'valerka@example.com',
            'password' => Hash::make('ValErka1_'),
            'role_id' => Entities::Admin->value,
        ]);
    }
}
