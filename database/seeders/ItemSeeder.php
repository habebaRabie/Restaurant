<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $faker->addProvider(new \FakerRestaurant\Provider\en_US\Restaurant($faker));
        for ($x = 0; $x <= 10; $x++)
        {
        DB::table('item')->insert([

            'item_name' => $faker->foodName(),
            'order_id' => null,
            'category_id'=>1,
            'rating'=>rand(0 , 5),
            'price'=>rand(0 , 200),
            'offer'=>null,
            'offer_end_date'=>null

        ]);
    }
    }
}
