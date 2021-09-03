<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class AddressSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for ($x = 0; $x <= 10; $x++) {
            DB::table('address')->insert([
                'user_id' => $x + 1,
                "location" => $faker->address,
                "favourite_location" => $faker->address
            ]);
        }
    }
}
