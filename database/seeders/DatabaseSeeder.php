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
        // User::factory(10)->create();

        $roleDev = Role::create(['name' => 'dev']);
        $roleAdmin = Role::create(['name' => 'admin']);
        $rolePegawai = Role::create(['name' => 'pegawai']);

        $user = User::factory()->create([
            'name' => 'dev',
            'email' => 'dev@mail.com',
            'password' => bcrypt('a')
        ]);
        $user->assignRole($roleDev);
    }
}
