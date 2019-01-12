<?php

use Illuminate\Database\Seeder;

class RolePermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // USER
        DB::table('permission_role')->insert([
            'role_id' => 1,
            'permission_id' => 1,
        ]);

        DB::table('permission_role')->insert([
            'role_id' => 1,
            'permission_id' => 2,
        ]);

        DB::table('permission_role')->insert([
            'role_id' => 1,
            'permission_id' => 3,
        ]);

        DB::table('permission_role')->insert([
            'role_id' => 1,
            'permission_id' => 4,
        ]);

        // MANAGER
        DB::table('permission_role')->insert([
            'role_id' => 2,
            'permission_id' => 1,
        ]);

        DB::table('permission_role')->insert([
            'role_id' => 2,
            'permission_id' => 2,
        ]);

        DB::table('permission_role')->insert([
            'role_id' => 2,
            'permission_id' => 3,
        ]);

        DB::table('permission_role')->insert([
            'role_id' => 2,
            'permission_id' => 4,
        ]);

        // ADMIN
        DB::table('permission_role')->insert([
            'role_id' => 3,
            'permission_id' => 1,
        ]);

        DB::table('permission_role')->insert([
            'role_id' => 3,
            'permission_id' => 2,
        ]);

        DB::table('permission_role')->insert([
            'role_id' => 3,
            'permission_id' => 3,
        ]);

        DB::table('permission_role')->insert([
            'role_id' => 3,
            'permission_id' => 4,
        ]);

        DB::table('permission_role')->insert([
            'role_id' => 3,
            'permission_id' => 5,
        ]);

        DB::table('permission_role')->insert([
            'role_id' => 3,
            'permission_id' => 6,
        ]);

        DB::table('permission_role')->insert([
            'role_id' => 3,
            'permission_id' => 7,
        ]);

        DB::table('permission_role')->insert([
            'role_id' => 3,
            'permission_id' => 8,
        ]);
    }
}
