<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::create(['name' => 'super admin']);

        $user = User::create([
            'name' => 'Muhammad Faizal Fazri',
            'email' => 'faizal.fazri@raharja.info',
            'password' => '123123'
        ]);

        $user->assignRole('super admin');
    }
}
