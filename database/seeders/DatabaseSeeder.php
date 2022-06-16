<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Helpers\Constant;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $guard_name = 'web';

        Role::factory()->create([
            'name' => Constant::ROLE_ADMIN,
            'guard_name' => $guard_name,
        ]);

        Role::factory()->create([
            'name' => Constant::ROLE_USER,
            'guard_name' => $guard_name,
        ]);

        User::factory(5)->create();
    }
}