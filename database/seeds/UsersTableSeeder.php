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
            [
                'name' => 'admin',
                'email' => 'iiejibmenb@gmail.com',
                'password' => bcrypt('8EJJZSZM6E')
            ],
        ]);
    }
}
