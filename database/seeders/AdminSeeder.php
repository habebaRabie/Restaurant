<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        
        for ($x = 0; $x <= 10; $x++)
        {
        $value =mt_rand(0 , 1);
        if($value == 1)
        {
            $result = true;
        }
        else{
            $result = false;
        }
        DB::table('admins')->insert([
            'username' => $faker->email,
           
            'password' => Hash::make("12345678"),
            'superadmin' => $result


        ]);
    }
    }
}
