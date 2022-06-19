<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Helpers\Constant;
use App\Models\Model_has_role;
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

        $users = User::all();

        foreach ($users as $usr_no => $usr) {
            $model_has_roles = Model_has_role::where('model_id',$usr->id)->get();
            if (count($model_has_roles) == 0) {
                $default_roles = array(Constant::ROLE_ADMIN,Constant::ROLE_USER);
                $random_key = array_rand($default_roles,1);
                $usr->assignRole($default_roles[$random_key]);
            }
        }
    }
}