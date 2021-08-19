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
        for ($x = 0; $x <= 3; $x++)
        {
        DB::table('items')->insert([

            'item_name' => $faker->foodName(),
            'category_id'=>1,
            'rating'=>rand(0 , 5),
            'price'=>rand(100 , 200),
            'offer'=>rand(50,99),
            'offer_end_date'=>date("2022/1/1"),
            

        ]);
    }
    }
}
