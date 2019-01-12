<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'role' => 'user',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('roles')->insert([
            'role' => 'manager',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('roles')->insert([
            'role' => 'admin',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
