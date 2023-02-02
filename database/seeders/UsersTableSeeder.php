<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $insert = DB::table('users')->insertGetId([
            'username' => 'john_doe',
            'email' => 'johndoe@example.com',
            'password' => bcrypt('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('profiles')->insert([
            'user_id' => $insert,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'username' => 'john_doe',
            'birth_date' => '2000-5-11',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $insert = DB::table('users')->insertGetId([
            'username' => 'jane_doe',
            'email' => 'janedoe@example.com',
            'password' => bcrypt('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('profiles')->insert([
            'user_id' => $insert,
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'username' => 'jane_doe',
            'birth_date' => '2000-5-11',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
