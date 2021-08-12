<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('item')->insert([

            'item_name' => "margreta pizza",
            'order_id' => 1,
            'category_id'=>1,
            'rating'=>5,
            'price'=>20.2,
            'offer'=>10,
            'offer_end_date'=>date('2021/8/11')

        ]);
    }
}
