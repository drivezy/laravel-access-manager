<?php

use Drivezy\LaravelAccessManager\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run () {
        Role::create([
            'name'        => 'Super Admin',
            'identifier'  => 'super-admin',
            'description' => 'The one user who has right for everything in the system',
        ]);

        Role::create([
            'name'        => 'Public',
            'identifier'  => 'public',
            'description' => 'When assigned this right, means the user should be able to access it',
        ]);
    }
}