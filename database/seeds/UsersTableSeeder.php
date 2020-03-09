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
//            'name' => Str::random(10),
          	'name' => 'Simiyu',
            'email' => 'kevin.simiyu@kahakiafrica.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
    	    'remember_token' => Str::random(10),
        ]);
    }
}
