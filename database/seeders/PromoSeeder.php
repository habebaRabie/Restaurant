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
            'code' => 'A123',
            'type'=> 'precent_off',
            'precent_off'=> 30,
            
            'end_date' =>'2022-8-16',
            'active'=>1
          ]);
          PromoCode::create([
            'code' => 'B123',
            'type'=> 'precent_off',
            'precent_off'=> 30,
            'no_users' => 10,
            'end_date' =>'2022-9-27',
            'active'=>1
          ]);
      }
           
    }

