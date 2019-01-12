<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'OpenBlog User',
            'email' => 'user@openblog.com',
            'role_id' => 1,
            'password' => bcrypt('password'),
            'email_verified_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('users')->insert([
            'name' => 'OpenBlog Manager',
            'email' => 'manager@openblog.com',
            'role_id' => 2,
            'password' => bcrypt('password'),
            'email_verified_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('users')->insert([
            'name' => 'OpenBlog Admin',
            'email' => 'admin@openblog.com',
            'role_id' => 3,
            'password' => bcrypt('password'),
            'email_verified_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
