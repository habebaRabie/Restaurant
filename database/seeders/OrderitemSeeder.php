<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OrderitemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('order_item')->insert([

            
            'order_id' => 1,
            'item_id' =>1,
            

        ]);
    }
}
