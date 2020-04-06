<?php

use Illuminate\Database\Seeder;
use \Carbon\Carbon;

class ServersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('servers')->insert([
            'name' => 'Server1',
            'location' => 'Montreal',
            'status' => 'Up',
            'ipv4' => '111.111.111.111',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
