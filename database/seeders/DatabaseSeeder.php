<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        DB::table('membership_types')->insert([
            'type' => 'SILVER'
        ]);

        DB::table('membership_types')->insert([
            'type' => 'GOLD'
        ]);

        DB::table('membership_types')->insert([
            'type' => 'PLATINUM'
        ]);

        DB::table('membership_types')->insert([
            'type' => 'BLACK'
        ]);

        DB::table('membership_types')->insert([
            'type' => 'VIP'
        ]);

        DB::table('membership_types')->insert([
            'type' => 'VVIP'
        ]);
    }
}
