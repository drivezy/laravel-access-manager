<?php

namespace Drivezy\LaravelAccessManager\Database\Seeds;

use Drivezy\LaravelAccessManager\Models\Role;
use Drivezy\LaravelAccessManager\Models\RoleAssignment;
use Drivezy\LaravelUtility\Database\Seeds\BaseSeeder;
use Drivezy\LaravelUtility\LaravelUtility;

/**
 * Class RoleSeeder
 * @package Drivezy\LaravelAccessManager\Database\Seeds
 */
class RoleSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run ()
    {
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

        //add super admin role to the first user of the system
        RoleAssignment::create([
            'source_type' => md5(LaravelUtility::getUserModelFullQualifiedName()),
            'source_id'   => 1,
            'role_id'     => 1,
        ]);
    }
}
