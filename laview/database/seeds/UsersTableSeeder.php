<?php

use Illuminate\Database\Seeder;
use \App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create a user.
        User::truncate();
        $user = User::create([
            'name'           => 'Damon Snow',
            'avatar'         => '',
            'email'          => 'hqfdotcom@gmail.com',
            'job_number'     => '00000',
            'phone'          => '12345678901',
            'active'         => 1,
            'password'       => bcrypt('123456'),
            'remember_token' => str_random(16),
        ]);

        // create a role.
        $role = \Spatie\Permission\Models\Role::create([
            'name'       => 'super_admin',
            'guard_name' => 'api',
            'comment'    => 'Super admin',
        ]);

        //create a permission
        $permission = \Spatie\Permission\Models\Permission::create([
            'name'       => 'super_admin',
            'guard_name' => 'api',
            'comment'    => 'Super admin',
        ]);

        // assign role and permission.
        $role->givePermissionTo($permission);
        $user->assignRole($role);

    }
}
