<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use \Carbon\Carbon;

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
            'firstname' => 'Test',
            'lastname' => 'User',
            'email' => 'test.user@fake.ca',
            'password' => Hash::make('Secret123!'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
