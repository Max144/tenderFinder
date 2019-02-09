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
        if(!\App\User::where('name', 'admin')->count()) {
            DB::table('users')->insert([
                [
                    'name' => 'admin',
                    'email' => 'iiejibmenb@gmail.com',
                    'password' => bcrypt('8EJJZSZM6E')
                ],
            ]);
        }

        if(!\App\User::where('name', 'Lev')->count()) {
            DB::table('users')->insert([
                [
                    'name' => 'Lev',
                    'email' => 'lev@gmail.com',
                    'password' => bcrypt('4;<kU;ymF8')
                ],
            ]);
        }
    }
}
