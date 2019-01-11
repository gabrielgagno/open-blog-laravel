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
            'is_admin' => true,
            'email' => 'user@openblog.com',
            'password' => bcrypt('password'),
            'email_verified_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
