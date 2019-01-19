<?php

use Illuminate\Database\Seeder;
use App\User;

class UserFactoryTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)->states('user')->create();
        factory(User::class)->states('user')->create();
        factory(User::class)->states('manager')->create();
        factory(User::class)->states('admin')->create();
    }
}
