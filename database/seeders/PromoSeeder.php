<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
class PromoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('promo')->insert([
            'code' => Str::random(10),
            'value' => 20.0,
            'end_date'=>date("2022/12/12"),
            'created_at'=>date("y/m/d H:i:s")

        ]);
           
    }
}
