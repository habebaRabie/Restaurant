<?php

namespace Database\Seeders;
use App\Models\PromoCode;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PromoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PromoCode::create([
            'code' => 'ABC123',
            'type'=> 'fixed',
            'value'=> 30,
            'start_date' =>'2021-8-16',
            'end_date' =>'2021-8-16',
          ]);
          PromoCode::create([
            'code' => 'BC123',
            'type'=> 'precent_off',
            'precent_off'=> 30,
            'start_date' =>'2021-8-16',
            'end_date' =>'2021-9-27',
          ]);
      }
           
    }

